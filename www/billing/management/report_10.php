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
	$c = "";

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
		&&
		isset($_POST['c'])
	){
		$s = $_POST['s'];
		$m = $_POST['m'];
		$y = $_POST['y'];
		$h = $_POST['h'];
		$d = $_POST['d'];
		$c = $_POST['c'];

		$nm = $m; if($m<10){$nm = "0". $m;}

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

		$cwhere = "";
		if($c !="" ){
			$cwhere = " and out_cid='". $c ."'";
		}
		
		$q = mysql_query("select id,c_mydate,out_cid,out_oldcid,out_consumer_name,out_consumer_address,out_consumer_category,out_dtrno,out_premeterstatus,in_status from m_data where c_subdiv_id='". $s ."' and c_mydate=". $mydate ." and in_status<>''". $hwhere . $dwhere . $cwhere . $where ."");

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
				<span class="head_text">Billing Cycle wise Meter Status Analysis</span>
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
	$consumer_list = array();
	$consumer_meter_status = array();

	if(mysql_num_rows($q) >0){

		echo '
		<h1>Total No of Bills - '. mysql_num_rows($q) .'</h1>
		<style>th{text-align:center !important;}</style>
		<table border="1" style="font-size:12px;">
			<tr>
				<th align="center" rowspan="2">Slno</th>
				<th align="center" rowspan="2">DEYAK Id</th>
				<th align="center" rowspan="2">Consumer No</th>
				<th align="center" rowspan="2">Name</th>
				<th align="center" rowspan="2">Address</th>
				<th align="center" rowspan="2" style="width:80px;">Category</th>
				<th align="center" rowspan="2">DTR no</th>
				<th align="center" colspan="2">Meter Status</th>
			</tr>
			<tr>
				<th align="center">Pre</th>
				<th align="center">Post</th>
			</tr>
		';

		$mstat_sa =0;
		$j = 1;
		while($d=mysql_fetch_object($q)){
			$cid = $d->out_cid;
			
			echo '
			<tr>
				<td align="center">'. $j .'</td>
				<td align="center">'. $d->out_cid .'</td>
				<td align="center">'. $d->out_oldcid .'</td>
				<td>'. $d->out_consumer_name .'</td>
				<td>'. $d->out_consumer_address .'</td>
				<td>'. $d->out_consumer_category .'</td>
				<td align="center">'. $d->out_dtrno .'</td>
			';
			
			$mstat_1 = $d->out_premeterstatus;
			$mstat_2 = $d->in_status;

			$back_style='style="background:#8F0000;color:#fff;"';
			if($mstat_1 == $mstat_2){$back_style=''; $mstat_sa++;}
			echo '
				<td align="center" '. $back_style .'>'. $meter_status[$mstat_1] .'</td>
				<td align="center" '. $back_style .'>'. $meter_status[$mstat_2] .'</td>
			';

			echo '
			</tr>
			';

			$j++;
		}

		$mstat_ch = mysql_num_rows($q) - $mstat_sa;
		echo '
			<tr>
				<td align="right" colspan="8"><b>Total Status Changed</b></td>
				<td align="right">'. $mstat_ch .'</td>
			</tr>
			<tr>
				<td align="right" colspan="8"><b>Total Status Same</b></td>
				<td align="right">'. $mstat_sa .'</td>
			</tr>
		';

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