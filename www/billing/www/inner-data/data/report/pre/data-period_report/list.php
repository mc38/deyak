<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$s = $data[0];
	$sd= $data[1];
	
	$subdq = mysql_query("select id,name from subdiv_data where sid='".$s."'");
	if(mysql_num_rows($subdq)==1){
		
		$period 	= array();
		$data		= array();
		$range		=6;
		
		for($i=0;$i<$range;$i++){
			$dt = strtotime('-'.$i.'month',strtotime($sd));
			$period[]	= strtotime(date('25-m-Y',$dt));
			$data[]		= 0;
		}
		
		
		$q = mysql_query("select id,bill_to_datetime from out_bill_xml where subdivision_id='".$s."' and mydate='". strtotime($sd)."'");
		if(mysql_num_rows($q) >0){
			while($d = mysql_fetch_object($q)){
				$bdate = strtotime($d->bill_to_datetime);
				
				$i= $range-1;
				while($bdate>$period[$i] && $i>0){
					$i--;
				}
				$data[$i] = $data[$i] +1;
			}
			
			/////////////////////////
			echo 'Subdivision ID : '. $s .'';
			echo '<hr/>';
			echo 'Month : '. date('F, Y', strtotime($sd)) .'';
			echo '<hr/>';
			if(sizeof($period)>0){
				echo '<h2>Overall Bill XML data generate report</h2>';
				echo '<table border="1" style="border:1px solid #000; border-spacing:0px">';
				echo '	<tr><th>Bill Period</th>	<th>Quantity</th></tr>';
				$tot = 0;
				
				for($i=0;$i<sizeof($period);$i++){
					echo '	<tr><td>'. date('F, Y',strtotime('-1month',$period[$i])) .' <b>to</b> '. date('F, Y',$period[$i]) .'</td>	<td align="right">'. $data[$i] .'</td></tr>';
					$tot= $tot + $data[$i];
				}
					echo '	<tr><th>Total</th>	<td align="right">'. $tot .'</td></tr>';
				
				echo '</table>';
				
			}else{
				echo '<center><h3 style="color:red;">Empty Data</h3></center>';
			}
		}
		else{
			echo '<center><h3 style="color:red;">Empty Data</h3></center>';
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