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
	$nf = "";
	$nt = "";
	$h = "";
	$daf = "";
	$dat = "";

	if(
		isset($_POST['s']) && ($_POST['s']!="")
		&&
		isset($_POST['m']) && ($_POST['m']!="")
		&&
		isset($_POST['y']) && ($_POST['y']!="")
		&&
		isset($_POST['nf']) && ($_POST['nf']!="")
		&&
		isset($_POST['nt']) && ($_POST['nt']!="")
		&&
		isset($_POST['h'])
	){
		$s = $_POST['s'];
		$m = $_POST['m'];
		$y = $_POST['y'];
		$nf = $_POST['nf'];
		$nt = $_POST['nt'];
		$h = $_POST['h'];

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
		
		$q = mysql_query("select * from m_data where c_subdiv_id='". $s ."' and c_mydate='". $mydate ."' and in_status<>''". $hwhere ." and in_total_arrear>=". $nf ." and in_total_arrear<=". $nt ."". $where);

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
				<span class="head_text">Management Report 4 (Arrear Report)</span>
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
					<span>Arrear Threshold :</span>
					<input name="nf" type="text" style="width:49%; float:left;" autocomplete="off" spellcheck="false" value="'. $nf .'" />
					<input name="nt" type="text" style="width:49%; float:right;" autocomplete="off" spellcheck="false" value="'. $nt .'" />
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
				<th>DTR</th>
				<th>Category</th>
				<th>Consumer no</th>
				<th>Consumer Name</th>
				<th>Current Demand</th>
				<th>Principal Arrear</th>
				<th>Arrear Surcharge</th>
				<th>Current Surcharge</th>
				<th>Total Arrear</th>
				<th>Net Bill Amount</th>
			</tr>
		';

		$j =1;
		while($d = mysql_fetch_object($q)){

			echo '
			<tr>
				<td>'. $j .'</td>
				<td>'. $d->out_dtrno .'</td>
				<td>'. $d->out_consumer_category .'</td>
				<td>'. $d->out_oldcid .'</td>
				<td>'. $d->out_consumer_name .'</td>
				<td align="right">'. number_format($d->in_current_demand,2) .'</td>
				<td align="right">'. number_format($d->out_principal_arrear,2) .'</td>
				<td align="right">'. number_format($d->out_arrear_surcharge,2) .'</td>
				<td align="right">'. number_format($d->in_current_surcharge,2) .'</td>
				<td align="right">'. number_format($d->in_total_arrear,2) .'</td>
				<td align="right">'. number_format($d->in_net_bill_amount,2) .'</td>
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