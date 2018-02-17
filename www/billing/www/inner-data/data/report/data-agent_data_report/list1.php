<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$s = $data[0];
	$sd= $data[1];
	
	$subdq = mysql_query("select id,name from settings_subdiv_data where sid='".$s."'");
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

		//-------------------------------------------------------------------------------------------

		$bt_q = mysql_query("select id from m_data where c_subdiv_id='".$s."' and c_mydate ='". strtotime($sd) ."'");
		$bt_b = mysql_num_rows($bt_q);

		$bu_q = mysql_query("select id from m_data where c_subdiv_id='".$s."' and c_mydate ='". strtotime($sd) ."' and c_import_status='1'");
		$bu_b = mysql_num_rows($bu_q);

		$br_b = $bt_b - $bu_b;

		echo '<div><b> Total Bill need to be Uploaded :</b> '. $bt_b .'</div>';
		echo '<div><b> Total Bill uploaded :</b> '. $bu_b .'</div>';
		echo '<hr/>';
		echo '<div><b> Remaining Bill uploaded :</b> '. $br_b .'</div>';
		echo '<hr/>';

		//-------------------------------------------------------------------------------------------
		echo '<h3>Data Upload Summery</h3>';
		echo '<table border="1" style="border:1px solid #000; border-spacing:0px;">';
		echo '<tr>
				<th>Agent Name</th>		
				<th>In queue</th>
				<th>Accepted</th>
				<th>Rejected</th>
				<th>Total</th>
				<th>Total Rejection</th>
			</tr>';
		
		echo '<tr align="center">';
		$day_tot = array();
		$day_tot[0]=0;
		$day_tot[1]=0;
		$day_tot[2]=0;
		$day_tot[3]=0;
		$day_tot[4]=0;
		echo '</tr>';
		
		
		$aq = mysql_query("select id,name from agent_info where subdiv='". $subdd->id ."' and status='0'");
		if(mysql_num_rows($aq)>0){
			while($ad = mysql_fetch_object($aq)){
				echo '<tr>';
				$name = $ad->name;
				echo '	<td style="text-transform:capitalize;">'. $name .'</td>';

				$tr = mysql_query("select id from m_data where in_aid='".$ad->id."' and c_subdiv_id='".$s."' and c_mydate ='". strtotime($sd) ."' and in_status<>'' and c_import_status='1'");
				$tot = mysql_num_rows($tr);
				
				$qr = mysql_query("select id from m_data where in_aid='".$ad->id."' and c_subdiv_id='".$s."' and c_mydate ='". strtotime($sd) ."' and in_status<>'' and c_import_status='1' and c_pass_status='0'");
				$q_b = mysql_num_rows($qr);

				$ar = mysql_query("select id from m_data where in_aid='".$ad->id."' and c_subdiv_id='".$s."' and c_mydate ='". strtotime($sd) ."' and in_status<>'' and c_import_status='1' and c_pass_status='1'");
				$a_b = mysql_num_rows($ar);

				$rr = mysql_query("select id from m_data where in_aid='".$ad->id."' and c_subdiv_id='".$s."' and c_mydate ='". strtotime($sd) ."' and in_status<>'' and c_import_status='1' and c_pass_status='2'");
				$r_b = mysql_num_rows($rr);

				$trr = mysql_query("select id from m_data_reject where in_aid='".$ad->id."' and c_subdiv_id='".$s."' and c_mydate ='". strtotime($sd) ."'");
				$tr_b = mysql_num_rows($trr);

				echo '	<td align="right">'. $q_b .'</td>';
				echo '	<td align="right">'. $a_b .'</td>';
				echo '	<td align="right">'. $r_b .'</td>';
				echo '	<td align="right">'. $tot .'</td>';
				echo '	<td align="right">'. $tr_b .'</td>';
				
				$day_tot[0] = $day_tot[0] + $q_b;
				$day_tot[1] = $day_tot[1] + $a_b;
				$day_tot[2] = $day_tot[2] + $r_b;
				$day_tot[3] = $day_tot[3] + $tot;
				$day_tot[4] = $day_tot[4] + $tr_b;
				echo '</tr>';
			}
		}
		echo '<tr><th style="background:#666; color:#fff;">Total</th>';
		for($i=0;$i<sizeof($day_tot);$i++){
			echo '<th align="right" style="background:#666; color:#fff;">'. $day_tot[$i] .'</th>';
		}
		echo '</tr>';
		echo '</table>';


		$day = date('t',strtotime($sd));
		//-------------------------------------------------------------------------------------------
		echo '<h3>Data Upload Report</h3>';
		echo '<table border="1" style="border:1px solid #000; border-spacing:0px;">';
		echo '<tr>
				<th rowspan="2">Agent Name</th>	
				<th colspan="'. $day .'" align="center">Day</th>
				<th rowspan="2">Total</th>
			</tr>';
		
		echo '<tr align="center">';
		$day_tot = array();
		for($i=0;$i<$day;$i++){
			$j =$i +1;
			echo '<th>'. $j .'</th>';
			$day_tot[$i]=0;
		}
		$day_tot[$day]=0;
		echo '</tr>';
		
		
		$aq = mysql_query("select id,name from agent_info where subdiv='". $subdd->id ."' and status='0'");
		if(mysql_num_rows($aq)>0){
			while($ad = mysql_fetch_object($aq)){
				echo '<tr>';
				$name = $ad->name;
				echo '	<td style="text-transform:capitalize;">'. $name .'</td>';
				$tot =0;
				for($i=0;$i<$day;$i++){
					$import_fdate = strtotime($i.' day',strtotime($sd));
					$import_tdate = strtotime("1 day",$import_fdate);
					$tr = mysql_query("select id from m_data where c_import_datetime>". $import_fdate ." and c_import_datetime<". $import_tdate ." and in_aid='".$ad->id."' and c_subdiv_id='".$s."' and c_mydate ='". strtotime($sd) ."' and in_status<>'' and c_import_status='1'");
					echo '	<td align="right">'. mysql_num_rows($tr) .'</td>';
					$day_tot[$i]=$day_tot[$i] + mysql_num_rows($tr);
					$tot = $tot + mysql_num_rows($tr);
				}

				echo '	<td align="right">'. $tot .'</td>';
				
				$day_tot[$day] = $day_tot[$day] + $tot;
				echo '</tr>';
			}
		}
		echo '<tr><th style="background:#666; color:#fff;">Total</th>';
		for($i=0;$i<sizeof($day_tot);$i++){
			echo '<th align="right" style="background:#666; color:#fff;">'. $day_tot[$i] .'</th>';
		}
		echo '</tr>';
		echo '</table>';

		//-------------------------------------------------------------------------------------------
		echo '<h3>Reading Report</h3>';
		echo '<table border="1" style="border:1px solid #000; border-spacing:0px;">';
		echo '<tr>
				<th rowspan="2">Agent Name</th>		
				<th colspan="'. $day .'" align="center">Day</th>	
				<th rowspan="2">Total</th>
			</tr>';
		
		echo '<tr align="center">';
		$day_tot = array();
		for($i=0;$i<$day;$i++){
			$j =$i +1;
			echo '<th>'. $j .'</th>';
			$day_tot[$i]=0;
		}
		$day_tot[$day]=0;
		echo '</tr>';
		
		
		$aq = mysql_query("select id,name from agent_info where subdiv='". $subdd->id ."' and status='0'");
		if(mysql_num_rows($aq)>0){
			while($ad = mysql_fetch_object($aq)){
				echo '<tr>';
				$name = $ad->name;
				echo '	<td style="text-transform:capitalize;">'. $name .'</td>';
				$tot =0;
				for($i=0;$i<$day;$i++){
					$r_fdate = strtotime($i.' day',strtotime($sd));
					$r_tdate = strtotime("1 day",$r_fdate);
					$tr = mysql_query("select id from m_data where in_reading_date>". $r_fdate ." and in_reading_date<". $r_tdate ." and in_aid='".$ad->id."' and c_subdiv_id='".$s."' and c_mydate ='". strtotime($sd) ."' and in_status<>'' and c_import_status='1'");
					echo '	<td align="right">'. mysql_num_rows($tr) .'</td>';
					$day_tot[$i]=$day_tot[$i] + mysql_num_rows($tr);
					$tot = $tot + mysql_num_rows($tr);
				}

				echo '	<td align="right">'. $tot .'</td>';
				$day_tot[$day] = $day_tot[$day] + $tot;
				echo '</tr>';
			}
		}
		echo '<tr><th style="background:#666; color:#fff;">Total</th>';
		for($i=0;$i<sizeof($day_tot);$i++){
			echo '<th align="right" style="background:#666; color:#fff;">'. $day_tot[$i] .'</th>';
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