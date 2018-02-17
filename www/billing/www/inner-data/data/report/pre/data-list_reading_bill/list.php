<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$s = $data[0];
	$sd= $data[1];
	$bk= $data[2];
	$tr= $data[3];
	$r = $data[4];
	
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
		
		$tr_arr = array();
		if($tr !=""){
			$tr_arr = explode(',',$tr);
			for($i=0;$i<sizeof($tr_arr);$i++){
				$trq = mysql_query("select id from consumer_cate where tariff_id like '%". base64_encode($tr_arr[$i]) ."%'");
				if(mysql_num_rows($trq) >0){
					$noerror = false;
					$msg = "<center>Tariff ID Invalid</center>";
					break;
				}
			}
		}
		
		if($noerror){
			
			$where = "";
			if($bk !=""){
				$where .= " and bookno='".$bk."'";
			}
			if($tr !=""){
				$tr_arr = explode(',',$tr);
				$where_t = array();
				for($i=0;$i<sizeof($tr_arr);$i++){
					$where_t[]= "tariff_id like '".$tr_arr[$i]."%'";
				}
				$where_st = implode(" or ",$where_t);
				$where .= " and (".$where_st.")";
			}
			
			$query ="select id,bookno,cid,consumer_name,category_name,tariff_id from p_consumerdata where subdiv_id='".$s."' and mydate='".strtotime($sd)."'".$where;
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
				
				if($tr !=""){
					echo 'Tariff ID : '. $tr .'';
					echo '<hr/>';
				}
				
				$cbook = array();
				$consumer_list = array();
				while($d = mysql_fetch_object($q)){
					
					$bquery = "select mydate, consumer_id, bill_from_datetime, bill_to_datetime, previous_reading, current_reading, energy_charge, fixed_charge, meter_rent, gross_charge, rebate_charge, credit_adjustment, net_charge from out_bill_xml where link='". $d->id ."' order by id desc";
					$bq = mysql_query($bquery);
					if(mysql_num_rows($bq) >0){
						$bd = mysql_fetch_object($bq);
						
						$consumer_list[$d->bookno][]=array($d,$bd);
						if(! in_array($d->bookno,$cbook)){
							$cbook[]=$d->bookno;
						}
					}
				}
				
				$done =0;
				$undone =0;
				echo '<table border="1" style="border:1px solid #000; border-spacing:0px">';
				echo '	<tr>
							<th>Slno</th>	
							<th>Book no</th>	
							<th>Consumer ID</th>	
							<th>Name</th>	
							<th>Tariff ID</th>	
							<th>Bill from</th>
							<th>Previous Reading</th>
							<th>Bill To</th>
							<th>Current Reading</th>
							<th>Energy Charge</th>
							<th>Fixed Charge</th>
							<th>Meter Rent</th>
							<th>Gross Charge</th>
							<th>Rebate</th>
							<th>Credit</th>
							<th>Net Charge</th>
						</tr>';
				
				
				$j =1;
				for($i=0;$i<sizeof($cbook);$i++){
					
					$bprint = true;	
					for($ii=0;$ii<sizeof($consumer_list[$cbook[$i]]);$ii++){
						$data = $consumer_list[$cbook[$i]][$ii];
						
						$cdata = $data[0];
						$bdata = $data[1];
						
							echo '<tr>';
							echo '	<td>'.$j.'</td>';
							if($bprint){
								echo '<td valign="top" rowspan="'. sizeof($consumer_list[$cbook[$i]]) .'">'.$cbook[$i].'</td>';
								$bprint = false;
							}
							
						
							echo '	<td>'. $cdata->cid .'</td>	
									<td>'. $cdata->consumer_name .'</td>
									<td>'. $cdata->tariff_id .'</td>
									<td>'. $bdata->bill_from_datetime.'</td>
									<td>'. $bdata->previous_reading.'</td>
									<td>'. $bdata->bill_to_datetime.'</td>
									<td>'. $bdata->current_reading.'</td>
									<td>'. $bdata->energy_charge.'</td>
									<td>'. $bdata->fixed_charge.'</td>
									<td>'. $bdata->meter_rent.'</td>
									<td>'. $bdata->gross_charge.'</td>
									<td>'. $bdata->rebate_charge.'</td>
									<td>'. $bdata->credit_adjustment.'</td>
									<td>'. $bdata->net_charge.'</td>
								';
							echo '</tr>';
							
							$j++;
						
						
					}
				
					
				}
				echo '</table>';
				echo '<hr/>';
				
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