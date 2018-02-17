<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");
include "../../../../../config/config.php";

if(authenticate()){
	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$s 	= $data[0];
	$c 	= $data[1];
	$fd = $data[2];
	$td = $data[3];
	

	$where = "";
	if($c !=""){
		$where .= " and out_cid like '%". $c ."%'";
	}
	
	if($fd !="" && $td !=""){
		$where .= " and c_import_datetime>". strtotime($fd) ." and c_import_datetime<". strtotime('+1day',strtotime($td));
	}
	
	$q = mysql_query("select * from m_data where in_status<>'' and c_import_status=1 and c_pass_status=0 and c_subdiv_id='". $s ."'". $where ." order by c_import_datetime limit 0,30");
	if(mysql_num_rows($q) >0){

		$subdiv_q = mysql_query("select id from settings_subdiv_data where sid='". $s ."'");
		$subdiv_d = mysql_fetch_object($subdiv_q);

		$agent = array();
		$ag_q = mysql_query("select id,name,contact from agent_info where subdiv='". $subdiv_d->id ."'");
		while($ag_d = mysql_fetch_object($ag_q)){
			$agent[$ag_d->id] = $ag_d;
		}

		

		echo '
		<table id="approvelist" border="1">
			<tr>
				<th>Consumer Details</th>
				<th>Meter Reading</th>
				<th>Survey Data</th>
			</tr>
		';
		
		$j = 1;
		while($d = mysql_fetch_object($q)){
			echo '
			<tr id="row_'. $d->id .'">
				<td valign="top">
					<div><b>Slno : </b>'. $j .'</div>
					<div><b>ID : </b>'. $d->out_cid .'</div>
					<div><b>Name : </b>'. $d->out_consumer_name .'</div>
					<div><b>Address : </b>'. $d->out_consumer_address .'</div>
					<hr/>
					<div><b>'. date('d-m-Y',$d->c_import_datetime) .'</b><br/>'. date('h:i:s a',$d->c_import_datetime) .'</div>
					<hr/>
					<div style="padding-top:30px;">
						<button type="button" class="button_green" value="'. $d->id .'" onclick="approve(this);">Accept</button>
						<button type="button" class="button_red" value="'. $d->id .'" onclick="reject(this);">Reject</button>
						<div id="action_msg_'. $d->id .'"></div>
					</div>
				</td>
			';

			$mshow = "";
			if($d->in_status >0){
				$mshow = $meter_status[$d->in_status];
			}else{
				$mshow = $d->in_postmeter_read;
			}

			if($d->in_meterpic == "0"){
				echo '
				<td align="center" valign="top">
					<div>
						<h2>'. $mshow .'</h2>
						<hr/>
						<div>Back Door</div>
					</div>
				</td>
				';
			}else{
				$pic_m_name = $snapshot_link_snapshot_report .'?t=image&i='. $d->in_meterpic;
				$pic_m_data = file_get_contents($pic_m_name);
				if($pic_m_data !=""){
					echo '
					<td align="center" valign="top">
						<input id="img_'. $d->id .'" type="hidden" value="'. $pic_m_name .'" />
						<div onclick="show_large_image('. $d->id .');">
							<img src="'. $pic_m_name .'" style="width:300px; height:auto;" />
							<h2>'. $mshow .'</h2>
						</div>
					</td>
					';
				}else{
					echo '
					<input id="img_'. $d->id .'" type="hidden" value="data:image/jpeg;base64,'. base64_decode($d->in_meterpic_binary) .'" />
					<td align="center" valign="top">
						<div onclick="show_large_image('. $d->id .');">
							<img src="data:image/jpeg;base64,'. base64_decode($d->in_meterpic_binary) .'" style="width:300px; height:auto;" />
							<h2>'. $mshow .'</h2>
						</div>
					</td>
					';
				}
			}
			

				

			echo '
				<td valign="top">
					<h2>Survey</h2>
					<div><b>Mobile no: </b>'. $d->in_survey_mobno .'</div>
					<div><b>Meter Height: </b>'. $d->in_survey_meterheight .'</div>
					<div><b>Meter sl no : </b>'. $d->in_survey_meterslno .'</div>
					<div><b>Meter Type : </b>'. $metertype[$d->in_survey_metertype] .'</div>
					<div><b>Consumer Type : </b>'. $consumertype[$d->in_survey_consumertype] .'</div>
					<hr/>
					<h2>Agent</h2>
					<div><b>Name </b>'. $agent[$d->in_aid]->name .'</div>
					<div><b>Mobile no: </b>'. $agent[$d->in_aid]->contact .'</div>
				</td>
			</tr>
			';
			
			$j++;
		}
	}else{
		echo '<div align="center">Empty list</div>';
	}
}
else{
	echo '<div align="center">Unauthorized user</div>';
}
?>