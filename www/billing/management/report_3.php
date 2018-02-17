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
		
		$q = mysql_query("select * from m_data where c_subdiv_id='". $s ."' and c_mydate='". $mydate ."' and in_status<>''". $where);

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
				<span class="head_text">Management Report 3</span>
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
				$dtr_amount[$d->out_dtrno] = array(0,0,0,0,0,0,0,0);
 			}
		}
		$total = array(0,0,0,0,0,0,0);

		mysql_data_seek($q, 0);
		while($d = mysql_fetch_object($q)){
			$dtr = (int)$d->out_dtrno;

			$dtr_amount[$dtr][0] = $dtr_amount[$dtr][0] + $d->in_energy_amount;
			$dtr_amount[$dtr][1] = $dtr_amount[$dtr][1] + $d->in_current_demand;
			$dtr_amount[$dtr][2] = $dtr_amount[$dtr][2] + $d->out_principal_arrear;
			$dtr_amount[$dtr][3] = $dtr_amount[$dtr][3] + $d->out_arrear_surcharge;
			$dtr_amount[$dtr][4] = $dtr_amount[$dtr][4] + $d->in_current_surcharge;
			$dtr_amount[$dtr][5] = $dtr_amount[$dtr][5] + $d->in_total_arrear;
			$dtr_amount[$dtr][6] = $dtr_amount[$dtr][6] + $d->in_net_bill_amount;
			$dtr_amount[$dtr][7]++;
		}
		sort($dtr_list);

		echo '
		<h1>Total No of Bills - '. mysql_num_rows($q) .'</h1>
		<table border="1">
			<tr>
				<th>Slno</th>
				<th>DTR</th>
				<th>Bill Quantity</th>
				<th>Energy Charge</th>
				<th>Current Demand</th>
				<th>CD Avg.</th>
				<th>Principal Arrear</th>
				<th>Arrear Surcharge</th>
				<th>Current Surcharge</th>
				<th>Total Arrear</th>
				<th>Net Bill Amount</th>
			</tr>
		';


		$j =1;
		for($i=0;$i<sizeof($dtr_list);$i++){
			$cdavg = round(($dtr_amount[$dtr_list[$i]][1] / $dtr_amount[$dtr_list[$i]][7]),2);
			echo '
			<tr>
				<td>'. $j .'</td>
				<td align="center">'. $dtr_list[$i] .'</td>
				<td align="center">'. $dtr_amount[$dtr_list[$i]][7] .'</td>
				<td align="right">'. number_format($dtr_amount[$dtr_list[$i]][0] ,2) .'</td>
				<td align="right">'. number_format($dtr_amount[$dtr_list[$i]][1] ,2) .'</td>
				<th align="right">'. number_format($cdavg ,2) .'</th>
				<td align="right">'. number_format($dtr_amount[$dtr_list[$i]][2] ,2) .'</td>
				<td align="right">'. number_format($dtr_amount[$dtr_list[$i]][3] ,2) .'</td>
				<td align="right">'. number_format($dtr_amount[$dtr_list[$i]][4] ,2) .'</td>
				<td align="right">'. number_format($dtr_amount[$dtr_list[$i]][5] ,2) .'</td>
				<td align="right">'. number_format($dtr_amount[$dtr_list[$i]][6] ,2) .'</td>
			</tr>
			';
			$j++;

			$total[0] = $total[0] + $dtr_amount[$dtr_list[$i]][0];
			$total[1] = $total[1] + $dtr_amount[$dtr_list[$i]][1];
			$total[2] = $total[2] + $dtr_amount[$dtr_list[$i]][2];
			$total[3] = $total[3] + $dtr_amount[$dtr_list[$i]][3];
			$total[4] = $total[4] + $dtr_amount[$dtr_list[$i]][4];
			$total[5] = $total[5] + $dtr_amount[$dtr_list[$i]][5];
			$total[6] = $total[6] + $dtr_amount[$dtr_list[$i]][6];
		}

		echo '
			<tr>
				<th colspan="3">Total</th>
				<td align="right">'. number_format($total[0] ,2) .'</td>
				<td align="right">'. number_format($total[1] ,2) .'</td>
				<td align="right">-</td>
				<td align="right">'. number_format($total[2] ,2) .'</td>
				<td align="right">'. number_format($total[3] ,2) .'</td>
				<td align="right">'. number_format($total[4] ,2) .'</td>
				<td align="right">'. number_format($total[5] ,2) .'</td>
				<td align="right">'. number_format($total[6] ,2) .'</td>
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