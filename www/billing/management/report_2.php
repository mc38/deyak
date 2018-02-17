<?php
session_start();
include "../db/command.php";
require_once("../plugin/func/authentication.php");
if($u = authenticate()){

include "inner/h.php";

date_default_timezone_set('Asia/Kolkata');
$datetime=date($_SERVER['REQUEST_TIME']);


$msg = "";

$ok = false;

	$s = "";
	$m = "";
	$y = "";

	if(
		isset($_POST['s']) && ($_POST['s']!="")
		&&
		isset($_POST['m']) && ($_POST['m']!="")
		&&
		isset($_POST['y']) && ($_POST['y']!="")
	){
		$s = $_POST['s'];
		$m = $_POST['m'];
		$y = $_POST['y'];

		$nm = $m;
		if($m<10){
			$nm = "0". $m;
		}

		$mydate = strtotime($y ."-". $nm ."-01");

		$where = " and c_pass_status=1 and c_import_status=1";
		//$where ="";
		
		$q = mysql_query("select * from m_data where c_subdiv_id='". $s ."' and c_mydate='". $mydate ."' and in_status<>'' and in_postmeter_read<>'-1' and out_premeter_read=in_postmeter_read". $where);

		if(mysql_num_rows($q) >0){
			$ok = true;
		}else{
			$msg = "No data is found";
		}
	}




	echo '

	<!DOCTYPE html>
	<html>
	<body>

		<div class="body"  align="center">
';

		include "inner/head.php";

echo '
			<div>
				<span class="head_text">Management Report 2</span>
			</div>
			<br/><br/>

			<form method="post" action="">
			<div class="form_container">

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

		$agent_arr = array();
		$aq = mysql_query("select id,name from agent_info where subdiv='". $sd->id ."'");
		while($ad = mysql_fetch_object($aq)){
			$agent_arr[$ad->id] = $ad->name;
		}

		echo '
		<h1>Total - '. mysql_num_rows($q) .'</h1>
		<table border="1">
			<tr>
				<th>Slno</th>
				<th>DTR</th>
				<th>Category</th>
				<th>DEYAK Id</th>
				<th>Consumer no</th>
				<th>Consumer Name</th>
				<th>Previous Reading</th>
				<th>Current Reading</th>
				<th>Deviation</th>
				<th>Energy Charge</th>
				<th>Agent</th>
			</tr>
		';


		$j =1;
		while($d = mysql_fetch_object($q)){
			$curr_reading = $d->in_curr_reading;
			if($curr_reading == ""){
				$curr_reading = $d->in_postmeter_read;
			}
			$devi = $curr_reading - $d->out_premeter_read;

			$agent = "-";
			if(isset($agent_arr[$d->in_aid])){$agent = $agent_arr[$d->in_aid];}

			echo '
			<tr>
				<td>'. $j .'</td>
				<td>'. $d->out_dtrno .'</td>
				<td>'. $d->out_consumer_category .'</td>
				<td>'. $d->out_cid .'</td>
				<td>'. $d->out_oldcid .'</td>
				<td>'. $d->out_consumer_name .'</td>
				<td>'. $d->out_premeter_read .'</td>
				<td>'. $curr_reading .'</td>
				<td>'. $devi .'</td>
				<td>'. number_format($d->in_energy_amount,2) .'</td>
				<td>'. $agent .'</td>
			</tr>

			';
			$j++;
		}

		echo '
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