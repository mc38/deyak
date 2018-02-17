<?php
session_start();
include "../db/command.php";
include "../../config/config.php";
require_once("../plugin/func/authentication.php");
if($u = authenticate()){

include "inner/h.php";

date_default_timezone_set('Asia/Kolkata');
$datetime=date($_SERVER['REQUEST_TIME']);

$category = array(); $cateid = array();
$cate_q = mysql_query("select id,name from settings_consumer_cate");
while($cate_d = mysql_fetch_object($cate_q)){
	$category[] = $cate_d;
	$cateid[$cate_d->id] = $cate_d->name;
}


$msg = "";

$ok = false;

	$s = "";
	$m = "";
	$y = "";
	$h = "";
	$d = "";

	if(
		isset($_POST['s']) && ($_POST['s']!="")
		&&
		isset($_POST['m']) && ($_POST['m']!="")
		&&
		isset($_POST['y']) && ($_POST['y']!="")
		&&
		isset($_POST['h'])
		&&
		isset($_POST['d'])
	){
		$s = $_POST['s'];
		$m = $_POST['m'];
		$y = $_POST['y'];
		$h = $_POST['h'];
		$d = $_POST['d'];

		$nm = $m;
		if($m<10){
			$nm = "0". $m;
		}

		$mydate = strtotime($y ."-". $nm ."-01");

		$where = " and c_pass_status=1 and c_import_status=1";
		//$where ="";
		$hwhere = "";
		if($h !="" && isset($cateid[$h])){
			$hwhere = " and out_consumer_category='". $cateid[$h] ."'";
		}

		$dwhere = "";
		if($d !="" ){
			$dwhere = " and out_dtrno='". $d ."'";
		}
		
		$q = mysql_query("select * from m_data where c_subdiv_id='". $s ."' and c_mydate='". $mydate ."' and in_status<>''". $hwhere . $dwhere ."". $where);

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
				<span class="head_text">Management Ledger Report (MRI)</span>
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
					<span>Category :</span>
					<select name="h" style="width:100%;">
                    	<option value="">Select Category</option>
	';
						for($i=0;$i<sizeof($category);$i++){
							$hselected = "";
							if($h == $category[$i]->id){$hselected='selected="selected"';}
							echo '<option value="'. $category[$i]->id .'" '. $hselected .'>'. $category[$i]->name .'</option>';
						}
	echo '
					</select>
				</div>

				<div class="ip_box">
					<span>DTR :</span>
					<input name="d" type="text" autocomplete="off" spellcheck="false" value="'. $d .'" />
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
		<table border="1" style="font-size:12px;">
			<tr>
				<th>Slno</th>
				<th>Consumer No</th>
				<th>Name</th>
				<th>Address</th>
				<th style="width:80px;">Category</th>
				<th>DTR no</th>
				<th>Connected Load</th>
				<th>Meter no</th>
				<th>MF</th>
				<th>Billno</th>
				<th style="width:60px;">Bill Date</th>
				<th style="width:60px;">Due Date</th>

				<th>Present Reading</th>
				<th style="width:60px;">Present Reading Date</th>
				<th>Previous Reading</th>
				<th style="width:60px;">Previous Reading Date</th>
				<th>Unit Consumed</th>
				<th>PF</th>
				<th>Unit Billed</th>
				<th>No of Days</th>

				<th>Energy Break up</th>
				<th>Energy Charge</th>
				<th>Subsidy</th>
				<th>Fixed Charge</th>
				<th>Meter Rent</th>
				<th>Electricity Duty</th>
				<th>FPPA Charge</th>
				<th>Current Demand</th>
				<th>Principal Arrear</th>
				<th>Arrear Surcharge</th>
				<th>Current Surcharge</th>
				<th>Total Arrear</th>
				<th>Adjustment</th>
				<th>Net Bill Amount</th>
			</tr>
		';

		$total_consumed_unit = 0;
		$total_billed_unit = 0;

		$total_amount_energy_chrg 	= 0;
		$total_amount_subsidy 		= 0;
		$total_amount_fixed_chrg 	= 0;
		$total_amount_meter_rent 	= 0;
		$total_amount_eduty 		= 0;
		$total_amount_pffa 			= 0;
		$total_amount_cd 			= 0;
		$total_amount_pa 			= 0;
		$total_amount_as 			= 0;
		$total_amount_cs 			= 0;
		$total_amount_ta 			= 0;
		$total_amount_adj 			= 0;
		$total_amount_nba 			= 0;


		$j =1;
		while($d = mysql_fetch_object($q)){

			if($d->in_status >0){
				$present_reading = $meter_status[$d->in_status];
			}else{
				$present_reading = $d->in_postmeter_read;
			}

			echo '
			<tr>
				<td align="center">'. $j .'</td>
				<td align="center">'. $d->out_oldcid .'</td>
				<td>'. $d->out_consumer_name .'</td>
				<td>'. $d->out_consumer_address .'</td>
				<td align="center">'. $d->out_consumer_category .'</td>
				<td align="center">'. $d->out_dtrno .'</td>
				<td align="center">'. $d->out_connection_load .'</td>
				<td align="center">'. $d->out_meter_no .'</td>
				<td align="center">'. $d->out_mfactor .'</td>
				<td align="center">'. $d->in_apdcl_billno .'</td>

				<td align="center">'. date('d-m-Y', $d->in_reading_date) .'</td>
				<td align="center">'. date('d-m-Y', $d->in_due_date) .'</td>
				<td align="center">'. $present_reading .'</td>
				<td align="center">'. date('d-m-Y', $d->in_reading_date) .'</td>
				<td align="center">'. $d->out_premeter_read .'</td>
				<td align="center">'. date('d-m-Y', $d->out_premeter_read_date) .'</td>
				<td align="center">'. $d->in_unit_consumed .'</td>
				<td align="center">'. $d->in_pf .'</td>
				<td align="center">'. $d->in_unit_billed .'</td>
				<td align="center">'. $d->in_consumption_day .'</td>

				<td>'. base64_decode($d->in_energy_brkup) .'</td>
				<td align="right">'. number_format($d->in_energy_amount ,2) .'</td>
				<td align="right">'. number_format($d->in_subsidy ,2) .'</td>
				<td align="right">'. number_format($d->in_fixed_charge ,2) .'</td>
				<td align="right">'. number_format($d->in_meter_rent ,2) .'</td>
				<td align="right">'. number_format($d->in_electricity_duty ,2) .'</td>
				<td align="right">'. number_format($d->in_fppa_charge ,2) .'</td>
				<td align="right">'. number_format($d->in_current_demand ,2) .'</td>
				<td align="right">'. number_format($d->out_principal_arrear ,2) .'</td>
				<td align="right">'. number_format($d->out_arrear_surcharge ,2) .'</td>
				<td align="right">'. number_format($d->in_current_surcharge ,2) .'</td>
				<td align="right">'. number_format($d->in_total_arrear ,2) .'</td>
				<td align="right">'. number_format($d->out_adjustment ,2) .'</td>
				<td align="right">'. number_format($d->in_net_bill_amount ,2) .'</td>
			</tr>
			';
			$j++;

			$total_consumed_unit += $d->in_unit_consumed;
			$total_billed_unit += $d->in_unit_billed;

			$total_amount_energy_chrg += $d->in_energy_amount;
			$total_amount_subsidy += $d->in_subsidy;
			$total_amount_fixed_chrg += $d->in_fixed_charge;
			$total_amount_meter_rent += $d->in_meter_rent;
			$total_amount_eduty += $d->in_electricity_duty;
			$total_amount_pffa += $d->in_fppa_charge;
			$total_amount_cd += $d->in_current_demand;
			$total_amount_pa += $d->out_principal_arrear;
			$total_amount_as += $d->out_arrear_surcharge;
			$total_amount_cs += $d->in_current_surcharge;
			$total_amount_ta += $d->in_total_arrear;
			$total_amount_adj += $d->out_adjustment;
			$total_amount_nba += $d->in_net_bill_amount;

		}

		echo '
		<tr>
			<th colspan="16">Total</th>

			<th>'. $total_consumed_unit .'</th>
			<th>-</th>
			<th>'. $total_billed_unit .'</th>
			<th>-</th>
			<th>-</th>

			<th align="right">'. number_format($total_amount_energy_chrg ,2) .'</th>
			<th align="right">'. number_format($total_amount_subsidy ,2) .'</th>
			<th align="right">'. number_format($total_amount_fixed_chrg ,2) .'</th>
			<th align="right">'. number_format($total_amount_meter_rent ,2) .'</th>
			<th align="right">'. number_format($total_amount_eduty ,2) .'</th>
			<th align="right">'. number_format($total_amount_pffa ,2) .'</th>
			<th align="right">'. number_format($total_amount_cd ,2) .'</th>
			<th align="right">'. number_format($total_amount_pa ,2) .'</th>
			<th align="right">'. number_format($total_amount_as ,2) .'</th>
			<th align="right">'. number_format($total_amount_cs ,2) .'</th>
			<th align="right">'. number_format($total_amount_ta ,2) .'</th>
			<th align="right">'. number_format($total_amount_adj ,2) .'</th>
			<th align="right">'. number_format($total_amount_nba ,2) .'</th>
		</tr>
		</table
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