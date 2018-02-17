<?php
session_start();
include "../db/command.php";
include "../../config/config.php";
include "../spl_func/bill.php";
require_once("../plugin/func/authentication.php");
if($u = authenticate() && isset($_SESSION['pin']) && $_SESSION['pin']==md5("130336")){
	
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
	$t = "";
	$s = "";
	$m = "";
	$y = "";
	$h = "";
	$d = "";
	$c = "";

	if(
		isset($_POST['t']) && ($_POST['t']!="")
		&&
		isset($_POST['s']) && ($_POST['s']!="")
		&&
		isset($_POST['m']) && ($_POST['m']!="")
		&&
		isset($_POST['y']) && ($_POST['y']!="")
		&&
		isset($_POST['h'])
		&&
		isset($_POST['d'])
		&&
		isset($_POST['c'])
	){
		$t = $_POST['t'];
		$s = $_POST['s'];
		$m = $_POST['m'];
		$y = $_POST['y'];
		$h = $_POST['h'];
		$d = $_POST['d'];
		$c = $_POST['c'];

		$nm = $m;
		if($m<10){
			$nm = "0". $m;
		}

		$mydate = strtotime($y ."-". $nm ."-01");
		$tt = $t-1;
		$where = " and c_pass_status=". $tt ." and c_import_status=1";
		//$where ="";
		$hwhere = "";
		if($h !="" && isset($cateid[$h])){
			$hwhere = " and out_consumer_category='". $cateid[$h] ."'";
		}

		$dwhere = "";
		if($d !="" ){
			$dwhere = " and out_dtrno='". $d ."'";
		}

		$cwhere = "";
		if($c !="" ){
			$cwhere = " and out_cid='". $c ."'";
		}
		
		$q = mysql_query("select * from m_data where c_subdiv_id='". $s ."' and c_mydate='". $mydate ."' and in_status<>''". $hwhere . $dwhere . $cwhere . $where);

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
				<span class="head_text">Management Verification Correction</span>
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
					<span>DEYAK ID :</span>
					<input name="c" type="text" autocomplete="off" spellcheck="false" value="'. $c .'" />
				</div>


				<div class="ip_box">
					<button type="submit">View</button>
					<span style="color: brown;">'. $msg .'</span>
				</div>
			</div>
			</form>
	';

	if($ok){
		$cs_count = 0;

	if(mysql_num_rows($q) >0){
		echo '
		<h1>Total - '. mysql_num_rows($q) .'</h1>
		<table border="1" style="font-size:12px;">
			<tr>
				<th>Slno</th>
				<th>DEYAK ID</th>
				<th>Consumer No</th>
				<th>Name</th>
				<th style="width:80px;">Category</th>
				<th>DTR no</th>
				<th>Connected Load</th>
				<th>MF</th>
				<th>CS_PA</th>

				<th>Reading</th>
				<th>Date</th>
				<th>PF</th>
				<th>Unit PF</th>
				<th>Unit Billed</th>

				<th style="width:150px;">Energy Break up</th>
				<th>Energy Charge</th>
				<th>Subsidy</th>
				<th>Total Eng Charge</th>
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

		$error_cday 	= 0;
		$error_cunit	= 0;

		$error_unit_pf 	= 0;
		$error_unit_b	= 0;

		$error_slab 	= 0;
		$error_eng_chrg	= 0;
		$error_subsidy	= 0;
		$error_tec		= 0;
		$error_fix_chrg	= 0;
		$error_mrent	= 0;
		$error_eduty	= 0;
		$error_fppa		= 0;
		$error_cd		= 0;
		$error_cs 		= 0;
		$error_ta		= 0;
		$error_nba		= 0;

		$j =1;
		while($d = mysql_fetch_object($q)){

			/*------------------------------------------------------------------------------------------*/
			$ch_consumption_day = (int)(($d->in_reading_date - $d->out_premeter_read_date) /(3600 * 24));
			if($ch_consumption_day != $d->in_consumption_day){
				$ch_consumption_day = '<span style="color:red;">'. $ch_consumption_day .'</span>';
				$error_cday++;
			}
			/*------------------------------------------------------------------------------------------*/
			$consumed_show = "";
			if($d->in_status >0){
				$ch_read_consumed = $meter_status[$d->in_status];
				$consumed_show = "(AVG)";
			}else{
				$ch_read_consumed = $d->in_postmeter_read;
			}

			if($d->in_status >0){
				$ch_consumed_unit = round(($d->out_reserve_unit * $d->in_consumption_day),0);
			}else{
				$ch_consumed_unit = $d->in_postmeter_read - $d->out_premeter_read;
			}

			if($d->in_unit_consumed != $ch_consumed_unit){
				if($d->in_status >0){
					$ch_consumed_unit = '<span style="color:red;">'. $d->out_reserve_unit .' X '. $d->in_consumption_day .' days = '. $ch_consumed_unit .' '. $consumed_show .'</span>';
				}else{
					$ch_consumed_unit = '<span style="color:red;">'. $ch_consumed_unit .'</span>';
				}
				$error_cunit++;
			}else{
				if($d->in_status >0){
					$ch_consumed_unit = '<span>'. $d->out_reserve_unit .' X '. $d->in_consumption_day .' days = '. $ch_consumed_unit .' '. $consumed_show .'</span>';
				}
			}	
			/*------------------------------------------------------------------------------------------*/
			$cload = (float) substr($d->out_connection_load,0 , strlen($d->out_connection_load)-1);
			$ch_bill_data = billing_process($d->in_unit_consumed, $d->in_pf, $d->in_consumption_day, $d->in_reading_date, $d->out_reserve_unit, $d->out_slab, $cload, $d->out_mfactor, $d->out_principal_arrear, $d->out_arrear_surcharge, $d->out_meter_rent, $d->out_rate_eduty, $d->out_rate_fppa, $d->out_adjustment, $d->out_rate_surcharge, $d->out_prevbillduedate, $d->out_cs_pa);
			/*------------------------------------------------------------------------------------------*/
			$ch_unit_pf = $ch_bill_data[0];
			if($d->in_unit_pf != $ch_unit_pf){
				$ch_unit_pf = '<span style="color:red;">'. $ch_unit_pf .'</span>';
				$error_unit_pf ++;
			}
			/*------------------------------------------------------------------------------------------*/
			$ch_unit_billed = $ch_bill_data[1];
			if($d->in_unit_billed != $ch_unit_billed){
				$ch_unit_billed = '<span style="color:red;">'. $ch_unit_billed .'</span>';
				$error_unit_b ++;
			}
			/*------------------------------------------------------------------------------------------*/
			$main_eng_brk = "";
			$main_eng_brk_arr = json_decode(base64_decode($d->in_energy_brkup));
			for($i=0; $i<sizeof($main_eng_brk_arr); $i++){
				$main_eng_brk .= "[". $main_eng_brk_arr[$i][0] ." -> ". $main_eng_brk_arr[$i][1] ." X ". number_format($main_eng_brk_arr[$i][2],2) ." = ". number_format($main_eng_brk_arr[$i][3],2) ."]<br/>";
			}
			
			$ch_eng_brkup = "";
			$ch_eng_brkup_arr = json_decode(base64_decode($ch_bill_data[2]));
			for($i=0;$i<sizeof($ch_eng_brkup_arr);$i++){
				$ch_eng_brkup .= "[". $ch_eng_brkup_arr[$i][0] ." -> ". $ch_eng_brkup_arr[$i][1] ." X ". number_format($ch_eng_brkup_arr[$i][2],2) ." = ". number_format($ch_eng_brkup_arr[$i][3],2) ."]<br/>";
			}

			if($main_eng_brk != $ch_eng_brkup){
				$ch_eng_brkup = '<span style="color:red;">'. $ch_eng_brkup .'</span>';
				$error_slab ++;
			}else{
				$ch_eng_brkup = '<span>'. $ch_eng_brkup .'</span>';
			}
			/*------------------------------------------------------------------------------------------*/
			$ch_eng_charge = $ch_bill_data[3];
			if(number_format($d->in_energy_amount,2) != number_format($ch_eng_charge,2)){
				$ch_eng_charge = '<span style="color:red;">'. number_format($ch_eng_charge, 2) .'</span>';
				$error_eng_chrg++;
			}else{
				$ch_eng_charge = '<span>'. number_format($ch_eng_charge, 2) .'</span>';
			}
			/*------------------------------------------------------------------------------------------*/
			$ch_subsidy = $ch_bill_data[4];
			if(number_format($d->in_subsidy,2) != number_format($ch_subsidy,2)){
				$ch_subsidy = '<span style="color:red;">'. number_format($ch_subsidy, 2) .'</span>';
				$error_subsidy++;
			}else{
				$ch_subsidy = '<span>'. number_format($ch_subsidy, 2) .'</span>';
			}
			/*------------------------------------------------------------------------------------------*/
			$ch_total_eng_charge = $ch_bill_data[5];
			if(number_format($d->in_total_energy_charge,2) != number_format($ch_total_eng_charge,2)){
				$ch_total_eng_charge = '<span style="color:red;">'. number_format($ch_total_eng_charge, 2) .'</span>';
				$error_tec++;
			}else{
				$ch_total_eng_charge = '<span>'. number_format($ch_total_eng_charge, 2) .'</span>';
			}
			/*------------------------------------------------------------------------------------------*/
			$ch_fixed_charge = $ch_bill_data[6];
			if(number_format($d->in_fixed_charge,2) != number_format($ch_fixed_charge,2)){
				$ch_fixed_charge = '<span style="color:red;">'. number_format($ch_fixed_charge, 2) .'</span>';
				$error_fix_chrg++;
			}else{
				$ch_fixed_charge = '<span>'. number_format($ch_fixed_charge, 2) .'</span>';
			}
			/*------------------------------------------------------------------------------------------*/
			$ch_meter_rent = $ch_bill_data[7];
			if(number_format($d->in_meter_rent,2) != number_format($ch_meter_rent,2)){
				$ch_meter_rent = '<span style="color:red;">'. number_format($ch_meter_rent, 2) .'</span>';
				$error_mrent++;
			}else{
				$ch_meter_rent = '<span>'. number_format($ch_meter_rent, 2) .'</span>';
			}
			/*------------------------------------------------------------------------------------------*/
			$ch_eduty = $ch_bill_data[8];
			if(number_format($d->in_electricity_duty,2) != number_format($ch_eduty,2)){
				$ch_eduty = '<span style="color:red;">'. number_format($ch_eduty, 2) .'</span>';
				$error_eduty++;
			}else{
				$ch_eduty = '<span>'. number_format($ch_eduty, 2) .'</span>';
			}
			/*------------------------------------------------------------------------------------------*/
			$ch_fppa = $ch_bill_data[9];
			if(number_format($d->in_fppa_charge,2) != number_format($ch_fppa,2)){
				$ch_fppa = '<span style="color:red;">'. number_format($ch_fppa, 2) .'</span>';
				$error_fppa++;
			}else{
				$ch_fppa = '<span>'. number_format($ch_fppa, 2) .'</span>';
			}
			/*------------------------------------------------------------------------------------------*/
			$ch_cd = $ch_bill_data[10];
			if(number_format($d->in_current_demand,2) != number_format($ch_cd,2)){
				$ch_cd = '<span style="color:red;">'. number_format($ch_cd, 2) .'</span>';
				$error_cd ++;
			}else{
				$ch_cd = '<span>'. number_format($ch_cd, 2) .'</span>';
			}
			/*------------------------------------------------------------------------------------------*/
			$ch_cs = $ch_bill_data[11];
			if(number_format($d->in_current_surcharge,2) != number_format($ch_cs,2)){
				$ch_cs = '<span style="color:red;">'. number_format($ch_cs, 2) .'</span>';
				$error_cs++;
			}else{
				$ch_cs = '<span>'. number_format($ch_cs, 2) .'</span>';
			}
			/*------------------------------------------------------------------------------------------*/
			$ch_ta = $ch_bill_data[12];
			if(number_format($d->in_total_arrear,2) != number_format($ch_ta,2)){
				$ch_ta = '<span style="color:red;">'. number_format($ch_ta, 2) .'</span>';
				$error_ta++;
			}else{
				$ch_ta = '<span>'. number_format($ch_ta, 2) .'</span>';
			}
			/*------------------------------------------------------------------------------------------*/
			$ch_nba = $ch_bill_data[13];
			if(number_format($d->in_net_bill_amount,2) != number_format($ch_nba,2)){
				$ch_nba = '<span style="color:red;">'. number_format($ch_nba,2) .'</span>';
				$error_nba++;
			}else{
				$ch_nba = '<span>'. number_format($ch_nba,2) .'</span>';
			}
			
			/*
			echo '
			<tr>
				<td align="center" rowspan="2">'. $j .'</td>
				<td align="center" rowspan="2">'. $d->out_cid .'</td>
				<td align="center" rowspan="2">'. $d->out_oldcid .'</td>
				<td rowspan="2">'. $d->out_consumer_name .'</td>
				<td align="center" rowspan="2">'. $d->out_consumer_category .'</td>
				<td align="center" rowspan="2">'. $d->out_dtrno .'</td>
				<td align="center" rowspan="2">'. $d->out_connection_load .'</td>
				<td align="center" rowspan="2">'. $d->out_mfactor .'</td>
				<td align="center" rowspan="2">'. $d->out_cs_pa .'</td>

				<td align="center">DIFF( '. date('d-m-Y', $d->in_reading_date) .' , '.  date('d-m-Y', $d->out_premeter_read_date) .' ) = '. $d->in_consumption_day .' Days</td>
				<td align="center">'. $ch_read_consumed .' - '. $d->out_premeter_read .' = '. $d->in_unit_consumed .' '. $consumed_show .'</td>
				<td align="center">'. $d->in_pf .'</td>
				<td align="center">'. $d->in_unit_pf .'</td>
				<td align="center">'. $d->in_unit_billed .'</td>

				<td>'. $main_eng_brk .'</td>
				<td align="right">'. number_format($d->in_energy_amount ,2) .'</td>
				<td align="right">'. number_format($d->in_subsidy ,2) .'</td>
				<td align="right">'. number_format($d->in_total_energy_charge ,2) .'</td>
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

			<tr style="background:#ddd; border-bottom:2px solid #000;">
				<td align="center">'. $ch_consumption_day .' Days</td>
				<td align="center">'. $ch_consumed_unit .'</td>
				<td align="center">'. $d->in_pf .'</td>
				<td align="center">'. $ch_unit_pf .'</td>
				<td align="center">'. $ch_unit_billed .'</td>

				<td>'. $ch_eng_brkup .'</td>
				<td align="right">'. $ch_eng_charge .'</td>
				<td align="right">'. $ch_subsidy .'</td>
				<td align="right">'. $ch_total_eng_charge .'</td>
				<td align="right">'. $ch_fixed_charge .'</td>
				<td align="right">'. $ch_meter_rent .'</td>
				<td align="right">'. $ch_eduty .'</td>
				<td align="right">'. $ch_fppa .'</td>
				<td align="right">'. $ch_cd .'</td>
				<td align="right">'. number_format($d->out_principal_arrear, 2) .'</td>
				<td align="right">'. number_format($d->out_arrear_surcharge, 2) .'</td>
				<td align="right">'. $ch_cs .'</td>
				<td align="right">'. $ch_ta .'</td>
				<td align="right">'. number_format($d->out_adjustment, 2) .'</td>
				<td align="right">'. $ch_nba .'</td>
			</tr>
			';
			*/
			$j++;

			$che = 5;
			$m_che = round(($d->out_principal_arrear / $d->in_current_demand),0);
			if(($m_che>$che) && ($d->in_current_surcharge==0) && ($ch_bill_data[11]>0)){
				if(isset($_POST['correct']) && $_POST['correct']="ok"){
					
						$updone = mysql_query("update m_data set in_current_surcharge='". $ch_bill_data[11] ."', in_total_arrear='". $ch_bill_data[12] ."', in_net_bill_amount='". $ch_bill_data[13] ."' where id='". $d->id ."'");
						if($updone){
							echo '
							<div>CS -> '. $ch_bill_data[11] .', TA -> '. $ch_bill_data[12] .', NBA -> '. $ch_bill_data[13] .' = Done</div>
							';
						}
					
				}
				$cs_count++;
			}
		}

		echo '
			<tr style="background:#ddd; border-bottom:2px solid #000;">
				<th align="right" colspan="9">Total error</th>

				<td align="center">'. $error_cday.'</td>
				<td align="center">'. $error_cunit .'</td>
				<td align="center">-</td>
				<td align="center">'. $error_unit_pf .'</td>
				<td align="center">'. $error_unit_b .'</td>

				<td>'. $error_slab .'</td>
				<td align="right">'. $error_eng_chrg .'</td>
				<td align="right">'. $error_subsidy .'</td>
				<td align="right">'. $error_tec .'</td>
				<td align="right">'. $error_fix_chrg .'</td>
				<td align="right">'. $error_mrent .'</td>
				<td align="right">'. $error_eduty .'</td>
				<td align="right">'. $error_fppa .'</td>
				<td align="right">'. $error_cd .'</td>
				<td align="right">-</td>
				<td align="right">-</td>
				<td align="right">'. $error_cs .'</td>
				<td align="right">'. $error_ta .'</td>
				<td align="right">-</td>
				<td align="right">'. $error_nba .'</td>
			</tr>
		</table>
		';

		
		echo $cs_count;
		if($cs_count>0){
			echo '
			<form method="post" action="">
				<input type="hidden" name="t" value="'. $t .'" />
				<input type="hidden" name="s" value="'. $s .'" />
				<input type="hidden" name="m" value="'. $m .'" />
				<input type="hidden" name="y" value="'. $y .'" />
				<input type="hidden" name="h" value="'. $h .'" />
				<input type="hidden" name="d" value="'. $d .'" />
				<input type="hidden" name="c" value="'. $c .'" />
				<button name="correct" type="submit" value="ok">Correct</button>
			</form>
			';
		}
	}

	
	}

	echo '
		</body>
		</html>
	';

}else{

$_SESSION['pin']="";
if(isset($_POST['pin']) && ($_POST['pin']!="")){
	$_SESSION['pin'] = md5($_POST['pin']);
	echo '<script src="java/jquery.min.js"></script><script>$(function(){window.location.href="";})</script>';
}

echo '
<!DOCTYPE html>
	<html>
	<head>
		<title>DEYAK APDCL upload</title>
	    <link rel="stylesheet" href="style/style.css" type="text/css">
	</head>
	<body>

		<div class="body"  align="center">
			<div class="heading">
	            <div class="logo_container">
	                <i class="logo"></i>
	                <span class="logo_content">Deyak</span>
	            </div>
			</div>
			<div>
				<span class="head_text">Management Report and Analysis Section - Special Verification</span>
			</div>
			<br/><br/>

			<form target="" action="" method="post">
			<div class="form_container">
				<span style="color: red; font-size:36px; ">Give Pin</span>
				<div class="ip_box" style="float:none;">
					<input name="pin" type="password" value="" style="width:200px;" autocomplete="off" />
				</div>
				<div class="ip_box" style="float:none;">
					<button type="submit">Authenticate</button></form>
				</div>
			</div>
			</form>
	</body>
	</html>
';
}


?>