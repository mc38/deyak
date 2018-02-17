<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$s = $data[0];
	$sd= $data[1];
	$tr= $data[2];
	$bk= $data[3];
	$ci= $data[4];
	$yr= $data[5];
	$ad= $data[6];
	
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
			
			if($ci !=""){
				$where .= " and cid like '%".$ci."'";
			}
			
			if($yr !=""){
				$where .= " and premeter_read_date like '%".$yr."'";
			}
			
			$total_billdata_q = mysql_query("select id from p_billdata where status='0'");
			$total_billdata = mysql_num_rows($total_billdata_q);
			
			$query ="select * from p_billdata where status='' and subdiv_id='".$s."' and mydate='".strtotime($sd)."'".$where;
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
				$consumer_data = array();
				
				
				
				while($d = mysql_fetch_object($q)){
					
					$twhere = "";
					if($tr !=""){
						$twhere .= " and tariff_id like '".$tr."%'";
					}
					
					if($ad !=""){
						$twhere .= " and lower(consumer_address) like '%". strtolower($ad) ."%'";
					}
					
					$cquery ="select cid,consumer_name,consumer_address,category_name,tariff_id from p_consumerdata where id='".$d->link."' and subdiv_id='".$s."' and mydate='".strtotime($sd)."'". $twhere;
					$cq = mysql_query($cquery);
					
					if(mysql_num_rows($cq) ==1){
						$cd= mysql_fetch_object($cq);
						
						$consumer_list[$d->bookno][]=$d;
						$consumer_data[$d->bookno][]=$cd;
						if(! in_array($d->bookno,$cbook)){
							$cbook[]=$d->bookno;
						}
					}
				}
				
				echo '<table border="1" style="border:1px solid #ddd; border-spacing:0px;">';
				echo '	<tr><th>Slno</th>	<th>Book no</th>	<th>Consumer ID</th>		<th>Name</th>	<th>Perv. Reading</th>	<th>Expected Data</th>	<th>Action</th>		<th>Meter Pic</th></tr>';
				
				$j =1;
				for($i=0;$i<sizeof($cbook);$i++){
					
					
					for($ii=0;$ii<sizeof($consumer_list[$cbook[$i]]);$ii++){
						$cdata  = $consumer_data[$cbook[$i]][$ii];
						$data = $consumer_list[$cbook[$i]][$ii];
						$link = $data->link;
							echo '<tr style="border-top:1px solid #000;">';
							echo '<td>'.$j.'</td>';
							echo '<td valign="top">'.$cbook[$i].'</td>';
							
							
							echo '<td>'. $cdata->cid .'</td>';
							echo '<td>';
							echo '<b>'. $cdata->consumer_name .'</b><br/>'. $cdata->consumer_address .'<hr/><b style="text-transform:capitalize;">'. $cdata->category_name .'</b> - '. $cdata->tariff_id ;
							echo '</td>';
							
							$s_read = $data->premeter_read + $data->ppunit;
							echo '<td align="center"><b>'. $data->premeter_read .'</b><br/><br/>'. DateTime::createFromFormat("d-M-Y", substr($data->premeter_read_date,0,11))->format("d-m-Y") .'<hr/><b>PPU : </b>'. $data->ppunit .'<hr/><b>'. $s_read .'</b></td>';
							
							$tmd = date('01-m-Y',strtotime('-20days',strtotime($sd)));
							$exq = mysql_query("select status,premeter_read,postmeter_read,ppunit,link from p_billdata where cid='". $data->cid ."' and mydate='". strtotime($tmd) ."'");
							
							if(mysql_num_rows($exq) >0){
								$exd = mysql_fetch_object($exq);
								
								$exq_m = mysql_query("select status,premeter_read,postmeter_read,ppunit from p_billdata_multi where link='". $exd->link ."'");
								if(mysql_num_rows($exq_m) >0){
									$exd = mysql_fetch_object($exq_m);
								}
									
									$reading =""; $ex_unit=""; $sh_unit = '';
									if($exd->status == '0'){
										$ex_unit = ($exd->postmeter_read - ($exd->premeter_read  + $exd->ppunit) ) - rand(1,5);
										if($ex_unit<1){
											$ex_unit = 1;
										}
										$reading = ($data->premeter_read + $ex_unit + $data->ppunit );
										$sh_unit = '<hr/>'. $ex_unit .' Unit';
									}else if($exd->status == '1'){
										$reading = "Door Closed";
									}else if($exd->status == '2'){
										$reading = "Meter Tempered";
									}else if($exd->status == '3'){
										$reading = "Meter Stopped";
									}
									
									
									$sh_reading  ="-";
									if($reading != ""){
										$sh_reading = '<b>'. $reading .'</b>'. $sh_unit ;
									}
									echo '<td align="center">'. $sh_reading .'</td>';
									
								
							}else{
								echo '<td align="center">-</td>';
							}
							
							echo '<td align="center">
									<input id="pread_'.$j.'" type="hidden"  value="'. $s_read  .'" />
									<select id="status_'. $j .'" onchange="change_pic('.$j.'); type_show(this,'.$j.');" style="width:80px; margin:3px 10px; font-size:12px;">
										<option value="0">Meter OK</option>
										<option value="1">Door Closed</option>
										<option value="3">Meter Stopped</option>
									</select>
									<br/>
									<input id="read_'. $j .'" type="text" style="width:60px; margin:3px 10px;" onkeydown="check_amount(this,this.value);" onkeyup="check_amount(this,this.value);" onkeypress="check_amount(this,this.value);" />
									<br/>
									<button id="edit_but_'.$j.'" type="button" style="margin:0px; width:60px;" data-i="'.$j.'" value="'.$data->id.'" onclick="bill_edit(this);">Edit</button>
									<br/>
									<div style="padding-top:10px;" id="action_msg_'. $j .'"></div>
								</td>';
							
							$picdata = '';
							if($data->meterpic ==""){
								if( $total_billdata >50){
									$mtrpic_no = rand(0,$total_billdata -1);
									$mtrpic_q = mysql_query("select meterpic from p_billdata where status='0' limit ".$mtrpic_no.",1");
									$mtrpic_d = mysql_fetch_object($mtrpic_q);
									$picdata = $mtrpic_d->meterpic;
								}
							}else{
								$picdata = $data->meterpic;
							}
							
							$meterpic_data = '<td align="center">-</td>';
							if($picdata !=""){
								$meterpic_data = '<td align="center" id="mtrpich_'. $j .'"><img id="mtrpic_'.$j.'" style="width:100px; height:100px;" src="data:image/jpeg;base64,'. $picdata .'" onclick="change_pic('.$j.');" /><input id="mtrpicdata_'.$j.'" type="hidden" value="'.$picdata.'" /><div id="mtrpic_action_'.$j.'"></div></td>';
							}
							
							echo $meterpic_data;
							
							echo '</tr>';
							
							
							$j++;
						
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