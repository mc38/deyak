<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$s = $data[0];
	$sd= $data[1];
	
	$subdq = mysql_query("select id from subdiv_data where sid='".$s."'");
	if(mysql_num_rows($subdq)==1){
		
		echo '<h3>Agent performance Report</h3>';
		echo '<hr/>';
		echo '<div><b>Report Date :</b> '. date('d-m-Y',$datetime) .'</div>';
		echo '<hr/>';
		
		echo '<div><b>Data Month :</b> '. date('F-Y',strtotime($sd)) .'</div>';
		echo '<hr/>';
		
		echo '<table border="1" style="border:1px solid #000; border-spacing:0px;">';
		echo '<tr><th>Agent Name</th>		<th>Bill Printed Data</th>	<th>Slip Printed Data</th>	<th>Total Right Data</th>	<th>Wrong Data</th></tr>';
		
		$brt =0; $srt =0; $trt =0; $twt =0;
		
		$aq = mysql_query("select id,name from agent_info where subdiv='".$s."' and status='0'");
		if(mysql_num_rows($aq)>0){
			while($ad = mysql_fetch_object($aq)){
				echo '<tr>';
				$name = json_decode(base64_decode($ad->name));
				echo '	<td style="text-transform:capitalize;">'. $name[0] .' '. $name[1] .'</td>';
				
				$tr = mysql_query("select id from p_billdata where aid='".$ad->id."' and subdiv_id='".$s."' and mydate ='". strtotime($sd) ."' and status<>''");
				$br = mysql_query("select id from out_bill_xml where aid='".$ad->id."' and subdivision_id='".$s."' and mydate ='". strtotime($sd) ."'");
				echo '	<td align="right">'. mysql_num_rows($br) .'</td>';
				$sr = mysql_num_rows($tr) - mysql_num_rows($br);
				
				echo '	<td align="right">'. $sr .'</td>';
				echo '	<td align="right">'. mysql_num_rows($tr) .'</td>';
				
				$wr = mysql_query("select id from trash_p_billdata where aid='".$ad->id."' and subdiv_id='".$s."' and mydate ='". strtotime($sd) ."'");
				echo '	<td align="right">'. mysql_num_rows($wr) .'</td>';
				echo '</tr>';
				
				$brt = $brt + mysql_num_rows($br);
				$srt = $srt + $sr;
				$trt = $trt + mysql_num_rows($tr);
				$twt = $twt + mysql_num_rows($wr);
			}
		}
		echo '<tr>
				<td>Total</td>	
				<td align="right">'.$brt.'</td>		
				<td align="right">'. $srt .'</td>		
				<td align="right">'. $trt .'</td>			
				<td align="right">'. $twt .'</td>
			</tr>';
		echo '</table>';
		
		echo '<hr/>';
		
		
		$xq = mysql_query("select id from out_bill_xml where subdivision_id='". $s ."' and mydate='". strtotime($sd) ."'");
		echo '<b>Total No of XML : </b>'. mysql_num_rows($xq) ;
	}
	else{
		echo '<center><h3 style="color:red;">Invalid subdivision</h3></center>';
	}
}
else{
	echo "Unauthorized user";
}
?>