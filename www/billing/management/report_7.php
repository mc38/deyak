<?php
session_start();
include "../db/command.php";
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

			$total_bill = mysql_num_rows($q);

			$total_load = 0;
			$total_unit_consp = 0 ;
			$total_unit_billed = 0;

			while($d = mysql_fetch_object($q)){
				$kw = explode(" ", $d->out_connection_load);
				if(strtoupper($kw[1]) == "KW"){$total_load += $kw[0];}
				else if(strtoupper($kw[1]) == "KVA"){$total_load += ($kw[0] * $d->in_pf /100);}

				$total_unit_consp += $d->in_unit_consumed;
				$total_unit_billed += $d->in_unit_billed;
			}

			$avg_kw = round($total_load / $total_bill,2);
			$avg_uc = round($total_unit_consp / $total_bill,2);
			$avg_ub = round($total_unit_billed / $total_bill,2);

			$html_data = '
			<div class="form_container">

				<div class="ip_box">
					<span>Total No of Bill :</span>
					<input type="text" readonly="readonly" value="'. $total_bill .'" />
				</div>

				<div class="ip_box">
					<span>Load in KW (Total / Avg) :</span>
					<div style="height:30px;">
						<input type="text" style="width:50%; float:left;" readonly="readonly" value="'. $total_load .'" />
						<input type="text" style="width:50%; float:left;" readonly="readonly" value="'. $avg_kw .'" />
					</div>
				</div>

				<div class="ip_box">
					<span>Unit Consumed (Total / Avg) :</span>
					<div style="height:30px;">
						<input type="text" style="width:50%; float:left;" readonly="readonly" value="'. $total_unit_consp .'" />
						<input type="text" style="width:50%; float:left;" readonly="readonly" value="'. $avg_uc .'" />
					</div>
				</div>

				<div class="ip_box">
					<span>Unit Billed (Total / Avg) :</span>
					<div style="height:30px;">
						<input type="text" style="width:50%; float:left;" readonly="readonly" value="'. $total_unit_billed .'" />
						<input type="text" style="width:50%; float:left;" readonly="readonly" value="'. $avg_ub .'" />
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
				<span class="head_text">Management Report 7 - (Consumption Summary)</span>
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

		echo $html_data;
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