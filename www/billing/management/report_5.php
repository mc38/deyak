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

		//$where = " and c_pass_status=1 and c_import_status=1";
		$where ="";
		
		$q = mysql_query("select * from m_data where c_subdiv_id='". $s ."' and c_mydate='". $mydate ."'". $where);

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
				<span class="head_text">Management Report 5 - (DTR wise bill breakup)</span>
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
		$dtr_list = array();
		$dtr_amount = array();

		while($d = mysql_fetch_object($q)){
			if(! in_array($d->out_dtrno, $dtr_list)){
				$dtr_list[] = (int)$d->out_dtrno;
				$dtr_amount[$d->out_dtrno] = array(0,0,0,0,0,0);
 			}
		}
		$total = array(0,0,0,0);

		mysql_data_seek($q, 0);
		while($d = mysql_fetch_object($q)){
			$dtr = (int)$d->out_dtrno;

			$dtr_amount[$dtr][0] = $dtr_amount[$dtr][0] +1;
			if($d->c_import_status == 1){
				$dtr_amount[$dtr][2] = $dtr_amount[$dtr][2] +1;

				if($d->c_pass_status == 0){
					$dtr_amount[$dtr][3] = $dtr_amount[$dtr][3] +1;
				}else if($d->c_pass_status == 1){
					$dtr_amount[$dtr][4] = $dtr_amount[$dtr][4] +1;
				}else if($d->c_pass_status == 2){
					$dtr_amount[$dtr][5] = $dtr_amount[$dtr][5] +1;
				}

			}else{
				$dtr_amount[$dtr][1] = $dtr_amount[$dtr][1] +1;
			}
		}
		sort($dtr_list);

		echo '
		<h1>Total No of Bills - '. mysql_num_rows($q) .'</h1>
		<table border="1">
			<tr>
				<th>Slno</th>
				<th>DTR</th>
				<th>Bill Quantity</th>
				<th>Bill Remaining</th>
				<th>Bill Imported</th>
				<th>Bill Queued</th>
				<th>Bill Approved</th>
				<th>Bill Rejected</th>
			</tr>
		';


		$j =1;
		for($i=0;$i<sizeof($dtr_list);$i++){
			$color ='style="background:#ccc;"';
			if($dtr_amount[$dtr_list[$i]][1] >0){
				$color = 'style="background:#fff;"';
			}

			echo '
			<tr '. $color .'>
				<td align="center">'. $j .'</td>
				<td align="center">'. $dtr_list[$i] .'</td>
				<td align="right">'. $dtr_amount[$dtr_list[$i]][0] .'</td>
				<th align="right" style="text-align:right;">'. $dtr_amount[$dtr_list[$i]][1] .'</th>
				<td align="right">'. $dtr_amount[$dtr_list[$i]][2] .'</td>
				<td align="right">'. $dtr_amount[$dtr_list[$i]][3] .'</td>
				<td align="right">'. $dtr_amount[$dtr_list[$i]][4] .'</td>
				<td align="right">'. $dtr_amount[$dtr_list[$i]][5] .'</td>
			</tr>
			';
			$j++;

			$total[0] = $total[0] + $dtr_amount[$dtr_list[$i]][0];
			$total[1] = $total[1] + $dtr_amount[$dtr_list[$i]][1];
			$total[2] = $total[2] + $dtr_amount[$dtr_list[$i]][2];
			$total[3] = $total[3] + $dtr_amount[$dtr_list[$i]][3];
			$total[4] = $total[4] + $dtr_amount[$dtr_list[$i]][4];
			$total[5] = $total[5] + $dtr_amount[$dtr_list[$i]][5];
		}

		echo '
			<tr>
				<th colspan="2">Total</th>
				<td align="right">'. $total[0] .'</td>
				<td align="right">'. $total[1] .'</td>
				<td align="right">'. $total[2] .'</td>
				<td align="right">'. $total[3] .'</td>
				<td align="right">'. $total[4] .'</td>
				<td align="right">'. $total[5] .'</td>
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