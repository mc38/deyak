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
		
		$q = mysql_query("select out_oldcid,out_cid,out_consumer_name,in_status,in_postmeter_read,out_subdivision from m_data where c_subdiv_id='". $s ."' and c_mydate='". $mydate ."' and in_status<>'' and out_consumer_category='AGRICULTURE'");

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
				<span class="head_text">Agriculture Consumer List</span>
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

		echo '
		<h1>Total - '. mysql_num_rows($q) .'</h1>
		<table border="1">
			<tr>
				<th>Slno</th>
				<th>Subdiv</th>
				<th>Consumer ID</th>
				<th>DEYAK ID</th>
				<th>Name</th>
				<th>Status</th>
				<th>Reading</th>
				<th>Average Unit (per day)</th>
			</tr>
		';


		$j =1;
		while($d = mysql_fetch_object($q)){
			$qq = mysql_query("select avg_unit from in_data_queue where consumer_no='". $d->out_oldcid ."'");
			$avg_unit = "";
			if(mysql_num_rows($qq)>0){
				$qd = mysql_fetch_object($qq);
				$avg_unit = $qd->avg_unit;
			}

			echo '
				<tr>
					<td>'. $j .'</td>
					<td>'. $d->out_subdivision .'</td>
					<td>'. $d->out_oldcid .'</td>
					<td>'. $d->out_cid .'</td>
					<td>'. $d->out_consumer_name .'</td>
					<td>'. $meter_status[$d->in_status] .'</td>
					<td>'. $d->in_postmeter_read .'</td>
					<td>'. $avg_unit .'</td>
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