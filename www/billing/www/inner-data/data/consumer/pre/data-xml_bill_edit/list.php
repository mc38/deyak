<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$s = $data[0];
	$sd= $data[1];
	$ty= $data[2];
	$ai= $data[3];
	$bk= $data[4];
	$pd= $data[5];
	$ci= $data[6];
	
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
				$where .= " and book_no='".$bk."'";
			}
			
			if($ai !=""){
				$where .= " and aid='".$ai."'";
			}
			
			if($ci !=""){
				$where .= " and consumer_id like '%".$ci."'";
			}
			
			$pprob = true;
			if($pd !="" && $ty >0){
				$pprob = false;
				$pd_f = strtotime($pd);
				$pd_t = strtotime('+1day',strtotime($pd));
				$pq = mysql_query("select datafrom,total from public_xml where datetime>'".$pd_f."' and datetime<'".$pd_t."' and subdiv='".$s."' and mydate='".strtotime($sd)."' order by id desc");
				if(mysql_num_rows($pq)>0){
					$start = 0;	$tot = 0;
					while($pd = mysql_fetch_object($pq)){
						$start = $pd->datafrom;
						$tot = $tot + $pd->total;
					}
					$where .= " order by id limit ".$start.",".$tot;
					$pprob = true;
				}
			}
			
			$query ="select id,gross_charge,link from out_bill_xml where down='".$ty."' and subdivision_id='".$s."' and mydate='".strtotime($sd)."'".$where;
			$bq = mysql_query($query);
			
			if(mysql_num_rows($bq) >0 && $pprob){
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
				
				
				
				while($bd = mysql_fetch_object($bq)){
					
					$query ="select * from p_billdata where status='0' and link='".$bd->link."' and subdiv_id='".$s."' and mydate='".strtotime($sd)."'";
					$q = mysql_query($query);
					
					if(mysql_num_rows($q) ==1){
						$d= mysql_fetch_object($q);
						
						$reading_date_tstamp = strtotime(substr($d->reading_date,0,10));
						$bill_from_date_tstamp = strtotime(substr($d->premeter_read_date,0,11));
						
						$bill_to_date_tstamp = $reading_date_tstamp;
						if($bill_to_date_tstamp<$d->mydate){
							$bill_to_date_tstamp=$d->mydate;
						}
						
						$day_diff = ($bill_to_date_tstamp - $bill_from_date_tstamp)/(3600*24);
						if( $day_diff < 51 ){
							$consumer_list[$d->bookno][]=$d;
							$consumer_data[$d->bookno][]=$bd;
							if(! in_array($d->bookno,$cbook)){
								$cbook[]=$d->bookno;
							}
						}
					}
				}
				
				echo '<table border="1" style="border:1px solid #ddd; border-spacing:0px;">';
				echo '	<tr><th>Slno</th>	<th>Book no</th>	<th>Consumer ID</th>		<th>Name</th>	<th>Perv. Reading</th>	<th>Curr. Reading</th>	<th>Action</th>	<th>Meter Picture</th></tr>';
				
				$j =1;
				for($i=0;$i<sizeof($cbook);$i++){
					
					
					for($ii=0;$ii<sizeof($consumer_list[$cbook[$i]]);$ii++){
						$bdata  = $consumer_data[$cbook[$i]][$ii];
						$data = $consumer_list[$cbook[$i]][$ii];
						$link = $data->link;
							echo '<tr style="border-top:1px solid #000;">';
							echo '<td>'.$j.'</td>';
							echo '<td valign="top">'.$cbook[$i].'</td>';
							
						
							$cq = mysql_query("select cid,consumer_name,category_name,tariff_id,meterno from p_consumerdata where id='".$data->link."'");
							$cd = mysql_fetch_object($cq);
							echo '<td>'. $cd->cid .'</td>';
							echo '<td>';
							echo $cd->consumer_name .'<hr/><b style="text-transform:capitalize;">'. $cd->category_name .'</b> - '. $cd->tariff_id .'<hr/><b>Meter No</b><br/><br/><b>EBS: </b>'. $cd->meterno .', <b>Field: </b>'. $data->fmeterno;
							echo '</td>';
							
							echo '<td align="center"><b>'. $data->premeter_read .'</b><br/><br/>'. DateTime::createFromFormat("d-M-Y", substr($data->premeter_read_date,0,11))->format("d-m-Y") .'<hr/><b>PPU : </b>'. $data->ppunit .'<hr/>Rs '.number_format($bdata->gross_charge,2).'/-</td>';
							
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
							$color = "";
							if($bdata->gross_charge >2000){
								$color = 'style="background:#98261A; color:#fff;"';
							}
							echo '<td align="center" '.$color.'>'.$reading.'<hr/>'. substr($data->reading_date,0,10) .'</td>';
							
							echo '<td align="center">
									<input id="pread_'.$j.'" type="hidden"  value="'. $data->premeter_read  .'" />
									<input id="read_'. $j .'" type="text" style="width:60px; margin:3px 10px;" onkeydown="number_only(this.value,this.id);" onkeyup="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" />
									<br/>
									<button type="button" style="margin:0px; width:60px;" data-i="'.$j.'" value="'.$data->id.'" onclick="bill_edit(this);">Edit</button>
									<br/>
									<div style="padding-top:10px;" id="action_msg_'. $j .'"></div>
								</td>';
							$aname = array("","");	
							if($data->aid >0){
								$aq = mysql_query("select name from agent_info where id='".$data->aid."'");
								$ad = mysql_fetch_object($aq);
								$aname = json_decode(base64_decode($ad->name));
							}
							echo '<td><img style="width:100px; height:100px;" src="data:image/jpeg;base64,'. $data->meterpic .'" /><br/><span style="text-transform:capitalize;">'. $aname[0] .' '. $aname[1] .'</span></td>';
							
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