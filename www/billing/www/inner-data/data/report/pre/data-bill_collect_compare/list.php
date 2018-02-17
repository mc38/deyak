<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");
require_once("../../../../plugin/func/num2str.php");

if(authenticate()){
	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$s = $data[0];
	$sd= $data[1];
	
	$subdq = mysql_query("select id,name from subdiv_data where sid='".$s."'");
	if(mysql_num_rows($subdq)==1){
		$subdd = mysql_fetch_object($subdq);
		
		
		$bopen = false;
		$bamount = 0;
		$bq = mysql_query("select net_charge from out_bill_xml where mydate='". strtotime($sd) ."' and subdivision_id='". $s ."'");
		if(mysql_num_rows($bq) >0){
			$bopen = true;
			while($bd = mysql_fetch_object($bq)){
				$bamount += $bd->net_charge;
			}
		}
		
		$copen = false;
		$cdate = date('M-Y',strtotime($sd));
		$camount = 0;
		$cq = mysql_query("select recep from in_reading_xml where subdiv_id='".$s."' and recep like '%".$cdate."%'");
		if(mysql_num_rows($cq) >0){
			$copen = true;
			while($cd = mysql_fetch_object($cq)){
				$data = $cd->recep;
				$temp_arr = explode(' ',$data);
				$a_pos = array_search("Amount",$temp_arr) +1;
				$amount_d = $temp_arr[$a_pos];
				$am = str_replace(':','',$amount_d);
				
				$camount += $am;
			}
		}
		
		
		
		
		$bshow = 'Data not Found';
		if($bopen){
			if($bamount>0){
				$bshow ='<h3 style="font-weight:normal;">Rs '. number_format($bamount,2) .'/-</h3><p style="text-transform:capitalize;">'. rupee_2_str($bamount) .'</p>';
			}else{
				$bshow ='Zero Amount';
			}
		}
		
		$cshow = 'Data not Found';
		if($copen){
			if($camount>0){
				$cshow ='<h3 style="font-weight:normal;">Rs '. number_format($camount,2) .'/-</h3><p style="text-transform:capitalize;">'. rupee_2_str($camount) .'</p>';
			}else{
				$cshow ='Zero Amount';
			}
		}
		
		
		echo '
			<b>Sub Division : </b>'. $s.', <span style="text-transform:capitalize;">'.$subdd->name.'</span>
			<hr/>
			<b>Month : </b>'. date('F, Y',strtotime($sd)) .'
			<hr/>
			<h2>Comparison of Billing Amount and Collection Amount</h2>
			<table border="1" style="border:1px solid #000; border-spacing:0px">
				<tr>
					<th>Total Billing Net-Amount</th>
					<th>Total Collected Amount</th>
				</tr>
				
				<tr>
					<td>'. $bshow .'</td>
					<td>'. $cshow .'</td>
				</tr>
			</table>
			
			<p>NB: Collected Amount data as per the xml provided from NIC EBS system</p>
		';
		
	}
	else{
		echo '<center><h3 style="color:red;">Invalid subdivision</h3></center>';
	}
}
else{
	echo "Unauthorized user";
}
?>