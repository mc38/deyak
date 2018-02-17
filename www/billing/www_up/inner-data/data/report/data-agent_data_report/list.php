<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	//style
	$border_color = "bbb";
	$back_color = "666";
	$zero_color = "ccc";
	//-------------------------------------------------------------------------------------------
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
		$day = date('t',strtotime($sd));
		$day_array = array();
		for($i=0;$i<$day;$i++){
			$day_array[$i] = 0;
		}
		//-------------------------------------------------------------------------------------------
		$agent_list = array();
		$agent_check = array();
		$agent_bill_brk = array();
		$agent_day_upload = array();
		$agent_day_reading = array();

		$aq = mysql_query("select id,name from agent_info where subdiv='". $subdd->id ."' and status='0'");
		if(mysql_num_rows($aq)>0){
			while($ad = mysql_fetch_object($aq)){
				$agent_list[] = $ad;
				$agent_check[$ad->id] = 0;

				$trr = mysql_query("select id from m_data_reject where in_aid='".$ad->id."' and c_subdiv_id='".$s."' and c_mydate ='". strtotime($sd) ."'");
				$tr_b = mysql_num_rows($trr);

				$agent_bill_brk[$ad->id] = array(0,0,0,0,$tr_b);
				$agent_day_upload[$ad->id] = $day_array;
				$agent_day_reading[$ad->id] = $day_array;
			}
		}

		//-------------------------------------------------------------------------------------------
		$bu_b = 0;




		$bt_q = mysql_query("select id,c_import_status,c_pass_status,in_aid,in_status,in_reading_date,c_import_datetime from m_data where c_subdiv_id='".$s."' and c_mydate ='". strtotime($sd) ."'");
		$bt_b = mysql_num_rows($bt_q);
		if($bt_b >0){
			while($bt_d = mysql_fetch_object($bt_q)){
				//-------------------------------------------------------------------------------------------
				if($bt_d->c_import_status == 1){
					$bu_b++;
				}
				//-------------------------------------------------------------------------------------------
				if(($bt_d->in_status !='') && (isset($agent_bill_brk[$bt_d->in_aid]))){
					if($bt_d->c_import_status == 1){
						$agent_bill_brk[$bt_d->in_aid][3]++;
					}

					if(($bt_d->c_import_status == 1) && ($bt_d->c_pass_status == 0)){
						$agent_bill_brk[$bt_d->in_aid][0]++;
					}

					if(($bt_d->c_import_status == 1) && ($bt_d->c_pass_status == 1)){
						$agent_bill_brk[$bt_d->in_aid][1]++;
					}

					if(($bt_d->c_import_status == 1) && ($bt_d->c_pass_status == 2)){
						$agent_bill_brk[$bt_d->in_aid][2]++;
					}
				}
				//-------------------------------------------------------------------------------------------
				
				if(($bt_d->in_status !='') && ($bt_d->c_import_status==1)){
					$import_day = date('d',$bt_d->c_import_datetime);
					if(isset($agent_day_upload[$bt_d->in_aid][$import_day -1])){
						$agent_day_upload[$bt_d->in_aid][$import_day -1]++;
					}

					$reading_day = date('d',$bt_d->in_reading_date);
					if(isset($agent_day_reading[$bt_d->in_aid][$reading_day -1])){
						$agent_day_reading[$bt_d->in_aid][$reading_day -1]++;
					}
				}
				

			}
		}
		//-------------------------------------------------------------------------------------------

		$br_b = $bt_b - $bu_b;

		//-------------------------------------------------------------------------------------------
		echo '<div><b> Total Bill need to be Uploaded :</b> '. $bt_b .'</div>';
		echo '<div><b> Total Bill uploaded :</b> '. $bu_b .'</div>';
		echo '<hr/>';
		echo '<div><b> Remaining Bill uploaded :</b> '. $br_b .'</div>';
		echo '<hr/>';

		//-------------------------------------------------------------------------------------------
		echo '<h3>Data Upload Summery</h3>';
		echo '<table border="1" style="border:1px solid #'. $border_color .'; border-collapse:collapse;">';
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
		
		
		if(sizeof($agent_list)>0){
			for($i=0; $i<sizeof($agent_list); $i++){
				$aid = $agent_list[$i]->id;
				$check_tot = $agent_bill_brk[$aid][0] + $agent_bill_brk[$aid][1] + $agent_bill_brk[$aid][2] + $agent_bill_brk[$aid][3] + $agent_bill_brk[$aid][4];
				$agent_check[$aid] = $check_tot;
				if($check_tot >0){
					echo '<tr>';
					$name = $agent_list[$i]->name;
					echo '	<td style="text-transform:capitalize;">'. $name .'</td>';

					echo '	<td align="right">'. $agent_bill_brk[$aid][0] .'</td>';
					echo '	<td align="right">'. $agent_bill_brk[$aid][1] .'</td>';
					echo '	<td align="right">'. $agent_bill_brk[$aid][2] .'</td>';
					echo '	<td align="right">'. $agent_bill_brk[$aid][3] .'</td>';
					echo '	<td align="right">'. $agent_bill_brk[$aid][4] .'</td>';
					
					$day_tot[0] = $day_tot[0] + $agent_bill_brk[$aid][0];
					$day_tot[1] = $day_tot[1] + $agent_bill_brk[$aid][1];
					$day_tot[2] = $day_tot[2] + $agent_bill_brk[$aid][2];
					$day_tot[3] = $day_tot[3] + $agent_bill_brk[$aid][3];
					$day_tot[4] = $day_tot[4] + $agent_bill_brk[$aid][4];
					echo '</tr>';
				}
			}
		}
		echo '<tr><th style="background:#'. $back_color .'; color:#fff;">Total</th>';
		for($i=0;$i<sizeof($day_tot);$i++){
			echo '<th align="right" style="background:#'. $back_color .'; color:#fff;">'. $day_tot[$i] .'</th>';
		}
		echo '</tr>';
		echo '</table>';


		
		//-------------------------------------------------------------------------------------------
		
		echo '<h3>Data Upload Report</h3>';
		echo '<table border="1" style="border:1px solid #'. $border_color .'; border-collapse:collapse; font-size:10px;">';
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
		
		if(sizeof($agent_list)>0){
			for($i=0; $i<sizeof($agent_list); $i++){
				$aid = $agent_list[$i]->id;
				if($agent_check[$aid]>0){

					echo '<tr>';
					$name = $agent_list[$i]->name;
					echo '	<td style="text-transform:capitalize;">'. $name .'</td>';

					$tot =0;
					for($j=0;$j<$day;$j++){
						$af_color =''; if($agent_day_upload[$aid][$j] == 0){$af_color='style="color:#'. $zero_color .';"';}
						echo '	<td align="right" '. $af_color .'>'. $agent_day_upload[$aid][$j] .'</td>';
						$day_tot[$j]=$day_tot[$j] + $agent_day_upload[$aid][$j];
						$tot = $tot + $agent_day_upload[$aid][$j];
					}
					echo '	<td align="right">'. $tot .'</td>';
					$day_tot[$day] = $day_tot[$day] + $tot;
					echo '</tr>';
				}
			}
		}
		echo '<tr><th style="background:#'. $back_color .'; color:#fff;">Total</th>';
		for($i=0;$i<sizeof($day_tot);$i++){
			echo '<th align="right" style="background:#'. $back_color .'; color:#fff;">'. $day_tot[$i] .'</th>';
		}
		echo '</tr>';
		echo '</table>';
		
		//-------------------------------------------------------------------------------------------
		
		echo '<h3>Reading Report</h3>';
		echo '<table border="1" style="border:1px solid #'. $border_color .'; border-collapse:collapse; font-size:10px;">';
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
		
		if(sizeof($agent_list)>0){
			for($i=0; $i<sizeof($agent_list); $i++){
				$aid = $agent_list[$i]->id;
				if($agent_check[$aid]>0){

					echo '<tr>';
					$name = $agent_list[$i]->name;
					echo '	<td style="text-transform:capitalize;">'. $name .'</td>';

					$tot =0;
					for($j=0;$j<$day;$j++){
						$af_color =''; if($agent_day_reading[$aid][$j] == 0){$af_color='style="color:#'. $zero_color .';"';}
						echo '	<td align="right" '. $af_color .'>'. $agent_day_reading[$aid][$j] .'</td>';
						$day_tot[$j]=$day_tot[$j] + $agent_day_reading[$aid][$j];
						$tot = $tot + $agent_day_reading[$aid][$j];
					}
					echo '	<td align="right">'. $tot .'</td>';
					$day_tot[$day] = $day_tot[$day] + $tot;
					echo '</tr>';
				}
			}
		}
		echo '<tr><th style="background:#'. $back_color .'; color:#fff;">Total</th>';
		for($i=0;$i<sizeof($day_tot);$i++){
			echo '<th align="right" style="background:#'. $back_color .'; color:#fff;">'. $day_tot[$i] .'</th>';
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