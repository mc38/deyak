<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

$billday = 32;

if(authenticate()){
	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$s = $data[0];
	$sd= $data[1];
	$ai= $data[2];
	$bk= $data[3];
	
	$subdq = mysql_query("select id from subdiv_data where sid='".$s."'");
	if(mysql_num_rows($subdq)==1){
	
		
		$booklist = array();
		$bkq = mysql_query("select book from booklist where subdiv='".$s."'");
		if(mysql_num_rows($bkq)>0){
			while($bkd = mysql_fetch_object($bkq)){
				$booklist[]=$bkd->book;
			}
		}
		
		$noerror = true; $msg ="";
		if($bk !="" && !in_array($bk,$booklist)){
			$noerror = false;
			$msg = "<center>Book no Invalid</center>";
		}
		
		if($noerror){
			
			$where = "";
			if($bk !=""){
				$where .= " and bookno='".$bk."'";
			}
			
			if($ai !=""){
				$where .= " and aid='".$ai."'";
			}
			
			$query ="select * from p_billdata where status<>'' and subdiv_id='".$s."' and mydate='".strtotime($sd)."'".$where;
			$q = mysql_query($query);
			
			if(mysql_num_rows($q) >0){
				echo 'Subdivision ID : '. $s .'';
				echo '<hr/>';
				echo 'Month : '. date('F, Y', strtotime($sd)) .'';
				echo '<hr/>';
				
				if($bk !=""){
					echo 'Book no : '. $bk .'';
					echo '<hr/>';
				}
				
				$cbook = array();
				$consumer_list = array();
				$consumer_bxno = array();
				
				while($d = mysql_fetch_object($q)){
					$reading_date_tstamp = strtotime(substr($d->reading_date,0,10));
					$bill_from_date_tstamp = strtotime(substr($d->premeter_read_date,0,11));
					
					$bill_to_date_tstamp = $reading_date_tstamp;
					if($bill_to_date_tstamp<$d->mydate){
						$bill_to_date_tstamp=$d->mydate;
					}
					
					$day_diff = ($bill_to_date_tstamp - $bill_from_date_tstamp)/(3600*24);
					if( $day_diff > 50 ){
						
						$nob = round($day_diff/$billday,0);
						
						$xq = mysql_query("select id from out_bill_xml where link='".$d->link."' order by id");
						$xmlno = mysql_num_rows($xq);
						
						if($nob<7 && $xmlno<$nob){
							$consumer_list[$d->bookno][]=$d;
							if(! in_array($d->bookno,$cbook)){
								$cbook[]=$d->bookno;
							}
							$dnx[0]=$day_diff;
							$dnx[1]=$nob;
							$dnx[2]=$xmlno;
							$consumer_bxno[$d->link]=$dnx;
						}
					}
				}
				
				echo '<table border="1" style="border:1px solid #ddd; border-spacing:0px;">';
				echo '	<tr><th>Slno</th>	<th>Book no</th>	<th>Consumer ID</th>		<th>Name</th>	<th>Perv. Reading</th>	<th>Curr. Reading</th>	<th>Meter Picture</th>	<th>No of Bills</th></tr>';
				
				$j =1;
				for($i=0;$i<sizeof($cbook);$i++){
					
					
					for($ii=0;$ii<sizeof($consumer_list[$cbook[$i]]);$ii++){
						$data = $consumer_list[$cbook[$i]][$ii];
						$link = $data->link;
							$gap = $consumer_bxno[$link][1] +1;
							echo '<tr style="border-top:1px solid #000;">';
							echo '<td rowspan="'. $gap .'">'.$j.'</td>';
							echo '<td rowspan="'. $gap .'" valign="top">'.$cbook[$i].'</td>';
							
						
							$cq = mysql_query("select cid,consumer_name,category_name,tariff_id from p_consumerdata where id='".$data->link."'");
							$cd = mysql_fetch_object($cq);
							echo '<td>'. $cd->cid .'</td>';
							echo '<td>';
							echo $cd->consumer_name .'<hr/><b style="text-transform:capitalize;">'. $cd->category_name .'</b> - '. $cd->tariff_id .'';
							echo '</td>';
							
							echo '<td align="center"><b>'. $data->premeter_read .'</b><br/><br/>'. DateTime::createFromFormat("d-M-Y", substr($data->premeter_read_date,0,11))->format("d-m-Y") .'</td>';
						
							$reading ="";
							if($data->status == '0'){
								$reading = '<b>'. $data->postmeter_read ."</b><br/><br/>". date('d-m-Y',$data->mydate);
							}else if($data->status == '1'){
								$reading = "Door Closed";
							}else if($data->status == '2'){
								$reading = "Meter Tempered";
							}else if($data->status == '3'){
								$reading = "Meter Stopped";
							}
							echo '<td align="center">'.$reading.'<hr/>'. substr($data->reading_date,0,10) .'</td>';
							
							$aq = mysql_query("select name from agent_info where id='".$data->aid."'");
							$ad = mysql_fetch_object($aq);
							$aname = json_decode(base64_decode($ad->name));
							echo '<td><img style="width:100px; height:100px;" src="data:image/jpeg;base64,'. $data->meterpic .'" /><br/><span style="text-transform:capitalize;">'. $aname[0] .' '. $aname[1] .'</span></td>';
						
							echo '<td align="center"><h3>'. $consumer_bxno[$link][1] .'</h3><hr/>'. $consumer_bxno[$link][2] .'<hr/>'. $consumer_bxno[$link][0] .' days</td>';
							
							echo '</tr>';
							
							////////////////////all bill/////////////////////////
							$bill_from_date_arr = array(); 
							$from_reading_arr = array();
							
							$xbq = mysql_query("select billed_unit,credit_adjustment from out_bill_xml where link='".$link ."' order by id");
							$xq = mysql_query("select * from out_reading_xml where link='".$link."' order by id");
							$bill_from_date_arr[]=DateTime::createFromFormat("d-M-Y", substr($data->premeter_read_date,0,11))->format("d-m-Y");	$ddiff = $billday;
							$from_reading_arr[]=$data->premeter_read;	$ustatus=$data->status;		$uppunit = $data->ppunit;		$ureserve=$data->reserve_unit;
							if($ustatus<1){$udiff=$data->postmeter_read - $data->premeter_read;}else{$udiff='-1';}
							$credit = $data->credit;
							
							$make_bill_but = true;
							//if($udiff>$uppunit){
							
								$rno = $consumer_bxno[$link][1] - ($consumer_bxno[$link][2]);
								for($x=0;$x < $consumer_bxno[$link][1];$x++){
									
									$xi = $x +1;
									echo '<tr><td colspan="6">';
									echo '<table style="font-size:14px;"><tr>';
									echo '<td style="width:30px;">'.$xi.'.</td>';
									if($x<mysql_num_rows($xq)){
										mysql_data_seek($xq,$x);
										$xd = mysql_fetch_object($xq);
										$xbd = mysql_fetch_object($xbq);
										
										////////bill date
										$bd_from = new DateTime($xd->bill_from_datetime);
										$bd_to = new DateTime($xd->bill_to_datetime);
										$bd_interval = $bd_from->diff($bd_to);
										$ddiff = $bd_interval->format('%a');
										if(! in_array($xd->bill_to_datetime ,$bill_from_date_arr)){
											$bill_from_date_arr[$x]=$xd->bill_to_datetime;
										}
										
										echo '<td style="border-left:1px solid #666; padding-left:10px; width:300px;"><b>Date</b><hr/>'. $xd->bill_from_datetime .' <b>to</b> '. $xd->bill_to_datetime .' [ '. $ddiff .' days ]</td>';
										
										///////reading date
										if($xd->remarks <1){
											if(! in_array($xd->current_reading ,$from_reading_arr)){
												$from_reading_arr[$x]=$xd->current_reading;
											}
											$udiff = $udiff - $xd->unit_consumed; 
										}else{
											if(! in_array($xd->previous_reading ,$from_reading_arr)){
												$from_reading_arr[$x]=$xd->previous_reading;
											}
										}
										
										$uppunit = $xd->ppunit;
										$credit	 = $credit - $xbd->credit_adjustment;
										
										$billunit = $xbd->billed_unit;
										
										echo '<td style="border-left:1px solid #666; padding-left:10px; width:200px;"><b>Reading</b><hr/>'. $xd->previous_reading .' <b>to</b> '. $xd->current_reading .' = '. $xd->unit_consumed .' -> [ <b>'.$billunit.'</b> ] </td>';
										
										///////////////////////
										echo '<td style="border-left:1px solid #666; width:138px;"><b style="color:red; float:right; padding-right:6px;">Bill Created</b></td>';
										
										
									}
									else{
										
										$status_list[1]="Door Closed";
										$status_list[2]="Meter Tempered";
										$status_list[3]="Meter Stopped";
										
										///////bill date
										if($ddiff<$billday){
											$ddiff = (($billday*2)-$ddiff);
										}
										$n_bill_from_datetime = $bill_from_date_arr[sizeof($bill_from_date_arr)-1];
										$n_bill_to_datetime = date('d-m-Y',strtotime('+'.$ddiff.' days',strtotime($n_bill_from_datetime)));
										$bill_from_date_arr[$x]=$n_bill_to_datetime;
										
										
										echo '<td style="border-left:1px solid #666; padding-left:10px; width:300px;"><b>Date</b><hr/>'. $n_bill_from_datetime .' <b>to</b> '. $n_bill_to_datetime .' [ '. $ddiff .' days ]</td>';
										
										$unit_noproblem = true;
										$send_ppunit =$uppunit;
										//////reading date
										$unit_consumed = 1;
										if($ustatus<1){
											$udiff = $udiff - $uppunit;
											if($udiff >$rno-1){
												if($rno>0){
													$unit_consumed = round($udiff/$rno,0);
												}else{
													$unit_consumed = $udiff;
												}
												$udiff = $udiff -$unit_consumed;
											}
											else{
												$unit_noproblem = false;
											}
										}
										else{
											if($ureserve>0){
												$unit_consumed = $ureserve;
											}
										}
										
										$n_previous_reading = $from_reading_arr[sizeof($from_reading_arr)-1];
										if($ustatus<1){
											$n_current_reading = $n_previous_reading + $uppunit + $unit_consumed;
											$from_reading_arr[$x]=$n_current_reading;
										}
										else{
											$n_current_reading = $status_list[$ustatus];
										}
										
										$color ="";
										if($unit_consumed > 200){
											$color="background:#98261A; color:#fff;";
										}
										
										echo '<td style="border-left:1px solid #666; padding-left:10px; width:200px;'.$color.'"><b>Reading</b> [ <b>PPU</b> '. $send_ppunit .' ]<hr/>'. $n_previous_reading .' <b>to</b> '. $n_current_reading .' = '. $unit_consumed .'</td>';
										
										if($credit <0){
											$credit =0;
										}
										
										if($ustatus <1){
											$uppunit = 0;
										}
										
										///////////////////////
										$sending_data[0]=$link;
										$sending_data[1]=$n_bill_from_datetime;
										$sending_data[2]=$n_bill_to_datetime;
										$sending_data[3]=$ustatus;
										$sending_data[4]=$n_previous_reading;
										$sending_data[5]=$n_current_reading;
										$sending_data[6]=$send_ppunit;
										$sending_data[7]=$credit;
										
										if($make_bill_but){
											if($unit_noproblem){
												$sending_data_str = base64_encode(json_encode($sending_data));
												echo '<td style="border-left:1px solid #666; width:135px;">
													<button type="button" style="float:right; padding:0px;" data-send="'.$sending_data_str.'" value="'.$link.'" onclick="make_bill(this);">Make Bill</button><div id="action_msg_'.$link.'" style="float:right;"></div>
												</td>';
											}
											else{
												echo '<td align="center" style="border-left:1px solid #666; width:138px; "><p style="text-align:right; color:red;">Consumed unit lower than ppunit</p></td>';
											}
											$make_bill_but = false;
										}
										else{
											echo '<td align="center" style="border-left:1px solid #666; width:138px; "><p style="text-align:right;">Make Previous Bill First</p></td>';
										}
										
										
										///////////////////
										$ddiff = $billday;
										$rno--;
									}
									echo '</tr></table>';
									echo '</td></tr>';
									
								}
								
								
								$j++;
							
							//}
					}	
				}
				echo '</table>';
			}
			else{
				echo '<center><h3 style="color:red;">Empty List</h3></center>';
			}
		}
		else{
			echo '<center><h3 style="color:red;">'.$msg.'</h3></center>';;
		}
	}
	else{
		echo '<center><h3 style="color:red;">Invalid subdivision</h3></center>';
	}
}
else{
	echo "Unauthorized user";
}
?>