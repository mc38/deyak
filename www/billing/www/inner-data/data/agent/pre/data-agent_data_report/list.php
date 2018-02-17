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
		$subdd = mysql_fetch_object($subdq);
		
		echo '<h3>Agent Daily Data Report</h3>';
		echo '<hr/>';
		echo '<div><b> Sub Division :</b> '. $s .', '. $subdd->name .'</div>';
		echo '<hr/>';
		echo '<div><b> Report Date :</b> '. date('d-m-Y',$datetime) .'</div>';
		echo '<hr/>';
		echo '<div><b> Month :</b> '. date('F, Y',strtotime($sd)) .'</div>';
		echo '<hr/>';
		
		echo '<table border="1" style="border:1px solid #000; border-spacing:0px;">';
		echo '<tr><th rowspan="2">Agent Name</th>		<th colspan="20" align="center">Day</th>	<th rowspan="2">OK</th>	<th rowspan="2">Problem with<br/>Reading Date</th>	<th rowspan="2">Total</th></tr>';
		
		echo '<tr align="center">';
		$day_tot = array();
		for($i=0;$i<20;$i++){
			$j =$i +1;
			echo '<th>'. $j .'</th>';
			$day_tot[$i]=0;
		}
		$day_tot[20]=0;
		$day_tot[21]=0;
		$day_tot[22]=0;
		echo '</tr>';
		
		
		$aq = mysql_query("select id,name from agent_info where subdiv='".$s."' and status='0'");
		if(mysql_num_rows($aq)>0){
			while($ad = mysql_fetch_object($aq)){
				echo '<tr>';
				$name = json_decode(base64_decode($ad->name));
				echo '	<td style="text-transform:capitalize;">'. $name[0] .' '. $name[1] .'</td>';
				$tot =0;
				for($i=0;$i<20;$i++){
					$tr = mysql_query("select id from p_billdata where reading_date like '". date('d-m-Y',strtotime($i.' day',strtotime($sd))) ."%' and aid='".$ad->id."' and subdiv_id='".$s."' and mydate ='". strtotime($sd) ."' and status<>''");
					echo '	<td align="right">'. mysql_num_rows($tr) .'</td>';
					$day_tot[$i]=$day_tot[$i] + mysql_num_rows($tr);
					$tot = $tot + mysql_num_rows($tr);
				}
				echo '	<td align="right">'. $tot .'</td>';
				$r = mysql_query("select id from p_billdata where aid='".$ad->id."' and subdiv_id='".$s."' and mydate ='". strtotime($sd) ."' and status<>''");
				$prob = mysql_num_rows($r) - $tot;
				echo '	<td align="right">'. $prob .'</td>';
				echo '	<td align="right">'. mysql_num_rows($r) .'</td>';
				
				$day_tot[20] = $day_tot[20] + $tot;
				$day_tot[21] = $day_tot[21] + $prob;
				$day_tot[22] = $day_tot[22] + mysql_num_rows($r);
				echo '</tr>';
			}
		}
		echo '<tr><td>Total</td>';
		for($i=0;$i<sizeof($day_tot);$i++){
			echo '<td align="right">'. $day_tot[$i] .'</td>';
		}
		echo '</tr>';
		echo '</table>';
	}
	else{
		echo '<center><h3 style="color:red;">Invalid subdivision</h3></center>';
	}
}
else{
	echo "Unauthorized user";
}
?>