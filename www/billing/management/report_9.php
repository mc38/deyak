<?php
session_start();
include "../db/command.php";
include "../../config/config.php";
require_once("../plugin/func/authentication.php");
if($u = authenticate()){

include "inner/h.php";

date_default_timezone_set('Asia/Kolkata');
$datetime=date($_SERVER['REQUEST_TIME']);


$msg = "";

$ok = false;
	
	$t = "";
	$s = "";
	$m = "";
	$y = "";

	if(
		isset($_POST['t']) && ($_POST['t']!="")
		&&
		isset($_POST['s']) && ($_POST['s']!="")
		&&
		isset($_POST['m']) && ($_POST['m']!="")
		&&
		isset($_POST['y']) && ($_POST['y']!="")
	){
		$t = $_POST['t'];
		$s = $_POST['s'];
		$m = $_POST['m'];
		$y = $_POST['y'];

		$nm = $m;
		if($m<10){
			$nm = "0". $m;
		}

		$mydate = strtotime($y ."-". $nm ."-01");
		$tt = $t-1;
		$where = " and c_pass_status=". $tt ." and c_import_status=1";
		//$where ="";
		
		$q = mysql_query("select in_aid,in_status,out_premeterstatus from m_data where c_subdiv_id='". $s ."' and c_mydate='". $mydate ."'". $where);

		if(mysql_num_rows($q) >0){
			$ok = true;
		}else{
			$msg = "No data is found";
		}
	}


	$data_type = array();
	$data_type[0] = "";
	$data_type[1] = "Queued Data";
	$data_type[2] = "Acceped Data";


	echo '

	<!DOCTYPE html>
	<html>
	<body>

		<div class="body"  align="center">
';

		include "inner/head.php";

echo '
			<div>
				<span class="head_text">Management Report 9 - (Agent wise Meter Status breakup)</span>
			</div>
			<br/><br/>

			<form method="post" action="">
			<div class="form_container">
				<div class="ip_box">
					<span>Data Type : </span>
					<select name="t" style="width:100%;">
                    	<option value="">Select Data Type</option>
    ';
    					for($i=1;$i<sizeof($data_type);$i++){
							$dtselected = "";
							if($t == $i){$dtselected='selected="selected"';}
							echo '<option value="'. $i .'" '. $dtselected .'>'.$data_type[$i].'</option>';
						}
    echo '
					</select>
				</div>

				<div class="ip_box">
					<span>Subdivision ID :</span>
					<input name="s" type="text" autocomplete="off" spellcheck="false" value="'. $s .'" />
				</div>

				<div class="ip_box">
					<span>Billing period :</span>
					<select class="period" name="m">
						<option value="">Select Month</option>
	';
						$month=array("January","Fabruary","March","April","May","June","July","August","September","October","November","December");
						for($i=0;$i<sizeof($month);$i++){
							$j = $i+1;
							$mselected = "";
							if($m == $j){$mselected='selected="selected"';}
							echo '<option value="'. $j .'" '. $mselected .'>'.$month[$i].'</option>';
						}
	echo '
					</select>
					

					<select class="period"  name="y">
                    	<option value="">Select Year</option>
	';
						$year = date('Y',$datetime)+1;
						for($i=0;$i<3;$i++){
							$yselected = "";
							if($y == $year){$yselected='selected="selected"';}
							echo '<option value="'.$year.'" '. $yselected .'>'.$year.'</option>';
							$year--;
						}

	echo '
					</select>
				</div>

				<div class="ip_box">
					<button type="submit">View</button>
					<span style="color: brown;">'. $msg .'</span>
				</div>
			</div>
			</form>
	';

	if($ok){

	if(mysql_num_rows($q) >0){
		$sq = mysql_query("select id from settings_subdiv_data where sid='". $s ."'");
		$sd = mysql_fetch_object($sq);

		$agent_name = array();
		$aq = mysql_query("select id,name from agent_info where subdiv='". $sd->id ."'");
		while($ad = mysql_fetch_object($aq)){
			$agent_name[$ad->id] = $ad->name;
		}

		$agent_list = array();
		$agent_amount = array();

		$msize = sizeof($meter_status)+5;

		while($d = mysql_fetch_object($q)){
			if(! in_array($d->in_aid, $agent_list)){
				$agent_list[] = (int)$d->in_aid;
				$agent_amount[$d->in_aid] = array_fill(0,$msize,0);
 			}
		}
		$total = array_fill(0,$msize,0);

		mysql_data_seek($q, 0);
		while($d = mysql_fetch_object($q)){
			$agent = (int)$d->in_aid;

			$agent_amount[$agent][$d->in_status] = $agent_amount[$agent][$d->in_status] +1;
			$agent_amount[$agent][$msize -5] = $agent_amount[$agent][$msize -5] +1;
			if($d->in_status == $d->out_premeterstatus){
				$agent_amount[$agent][$msize -4] = $agent_amount[$agent][$msize -4] +1;
			}else{
				$agent_amount[$agent][$msize -3] = $agent_amount[$agent][$msize -3] +1;
				if(($d->in_status ==0) && ($d->out_premeterstatus>0)){
					$agent_amount[$agent][$msize -2] = $agent_amount[$agent][$msize -2] +1;
				}else if(($d->in_status >0) && ($d->out_premeterstatus ==0)){
					$agent_amount[$agent][$msize -1] = $agent_amount[$agent][$msize -1] +1;
				}
			}
		}
		sort($agent_list);

		echo '
		<h1>Total No of Bills - '. mysql_num_rows($q) .'</h1>
		<table border="1">
			<tr>
				<th>Slno</th>
				<th>Agent</th>
		';
			for($i=0;$i<sizeof($meter_status);$i++){
				echo '<th>'. $meter_status[$i] .'</th>';
			}
		echo '
				<th>Total</th>
				<th>Status Same</th>
				<th>Status Changed</th>
				<th>Become Regular</th>
				<th>Become Irregular</th>
			</tr>
		';


		$slno =1;
		for($i=0;$i<sizeof($agent_list);$i++){

			echo '
			<tr>
				<td align="center">'. $slno .'</td>
				<td align="left">'. $agent_name[$agent_list[$i]] .'</td>
			';
			for($j=0; $j<$msize; $j++){
				echo '<td align="right">'. $agent_amount[$agent_list[$i]][$j] .'</td>';
				$total[$j] = $total[$j] + $agent_amount[$agent_list[$i]][$j];
			}
			echo '
			</tr>
			';
			$slno++;
		}

		echo '
			<tr>
				<th colspan="2">Total</th>
		';
			for($j=0; $j<$msize; $j++){
				echo '<td align="right">'. $total[$j] .'</td>';
			}
		echo '
			</tr>
		</table>
		';
	}

	}


	echo '
		</body>
		</html>
	';

}else{
	echo '<script src="java/jquery.min.js"></script><script>$(function(){window.location.href="index.php";})</script>';
}


?>