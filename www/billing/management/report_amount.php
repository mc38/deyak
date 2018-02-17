<?php
session_start();
include "../db/command.php";
require_once("../plugin/func/authentication.php");
if($u = authenticate()){

include "inner/h.php";

date_default_timezone_set('Asia/Kolkata');
$datetime=date($_SERVER['REQUEST_TIME']);

$msg = "";
$html_data = "";

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
		$where = "";
		
		$q = mysql_query("select * from m_data where c_subdiv_id='". $s ."' and c_import_status=1 and c_pass_status<2 and c_mydate='". $mydate ."' and in_status<>''". $where);
		if(mysql_num_rows($q) >0){
			$cd = 0;
			$pa = 0;
			$as = 0;
			$cs = 0;
			$ta = 0;
			$amount = 0;

			while($d = mysql_fetch_object($q)){
				
				$cd = $cd + $d->in_current_demand;
				$pa = $pa + $d->out_principal_arrear;
				$as = $as + $d->out_arrear_surcharge;
				$cs = $cs + $d->in_current_surcharge;


			}

			$ta = $pa + $as + $cs;
			$amount = $cd + $ta;

			$total_bill = mysql_num_rows($q);

			$avg_cd = number_format($cd / $total_bill, 2, ".", "");
			$avg_pa = number_format($pa / $total_bill, 2, ".", "");
			$avg_as = number_format($as / $total_bill, 2, ".", "");
			$avg_cs = number_format($cs / $total_bill, 2, ".", "");
			$avg_ta = number_format($ta / $total_bill, 2, ".", "");
			$avg_amount = number_format($amount / $total_bill, 2, ".", "");

			$html_data = '
			<div class="form_container">

				<div class="ip_box">
					<span>Total No of Bill :</span>
					<input type="text" readonly="readonly" value="'. $total_bill .'" />
				</div>

				<div class="ip_box">
					<span>Total Current Demand :</span>
					<div style="height:30px;">
						<input type="text" style="width:50%; float:left;" readonly="readonly" value="'. $cd .'" />
						<input type="text" style="width:50%; float:left;" readonly="readonly" value="'. $avg_cd .'" />
					</div>
				</div>

				<div class="ip_box">
					<span>Total Principal Arrear :</span>
					<div style="height:30px;">
						<input type="text" style="width:50%; float:left;" readonly="readonly" value="'. $pa .'" />
						<input type="text" style="width:50%; float:left;" readonly="readonly" value="'. $avg_pa .'" />
					</div>
				</div>

				<div class="ip_box">
					<span>Total Arrear Surcharge :</span>
					<div style="height:30px;">
						<input type="text" style="width:50%; float:left;" readonly="readonly" value="'. $as .'" />
						<input type="text" style="width:50%; float:left;" readonly="readonly" value="'. $avg_as .'" />
					</div>
				</div>

				<div class="ip_box">
					<span>Total Current Surcharge :</span>
					<div style="height:30px;">
						<input type="text" style="width:50%; float:left;" readonly="readonly" value="'. $cs .'" />
						<input type="text" style="width:50%; float:left;" readonly="readonly" value="'. $avg_cs .'" />
					</div>
				</div>

				<div class="ip_box">
					<span>Total Arrear :</span>
					<div style="height:30px;">
						<input type="text" style="width:50%; float:left;" readonly="readonly" value="'. $ta .'" />
						<input type="text" style="width:50%; float:left;" readonly="readonly" value="'. $avg_ta .'" />
					</div>
				</div>

				<div class="ip_box">
					<span>Total Amount :</span>
					<div style="height:30px;">
						<input type="text" style="width:50%; float:left;" readonly="readonly" value="'. $amount .'" />
						<input type="text" style="width:50%; float:left;" readonly="readonly" value="'. $avg_amount .'" />
					</div>
				</div>

			</div>
			';
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
				<span class="head_text">Amount Details section</span>
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
			'. $html_data .'
		</body>
		</html>
	';

}else{
	echo '<script src="java/jquery.min.js"></script><script>$(function(){window.location.href="index.php";})</script>';
}


?>