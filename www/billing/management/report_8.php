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
	$mt = "";
	$yt = "";
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
		isset($_POST['mt']) && ($_POST['mt']!="")
		&&
		isset($_POST['yt']) && ($_POST['yt']!="")
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
		$mt = $_POST['mt'];
		$yt = $_POST['yt'];
		$h = $_POST['h'];
		$d = $_POST['d'];
		$c = $_POST['c'];

		$nm = $m; if($m<10){$nm = "0". $m;}
		$nmt = $mt; if($mt<10){$nmt = "0". $mt;}

		$mydate = strtotime('-1day',strtotime($y ."-". $nm ."-01"));
		$mydatet = strtotime('+1day',strtotime($yt ."-". $nmt ."-01"));
		$ddiff = round((($mydatet - $mydate)/(3600 * 24)),0);
		if($ddiff<350){
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
			
			$q = mysql_query("select id,c_mydate,out_cid,out_oldcid,out_consumer_name,out_consumer_address,out_consumer_category,out_dtrno,out_premeterstatus,in_status from m_data where c_subdiv_id='". $s ."' and c_mydate>". $mydate ." and c_mydate<". $mydatet ." and in_status<>''". $hwhere . $dwhere . $cwhere . $where ." order by c_mydate");

			if(mysql_num_rows($q) >0){
				$ok = true;
			}else{
				$msg = "No data is found";
			}
		}else{
			$msg = "Maximum 1 year data can be processed.";
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
				<span class="head_text">Management Meter Status Analysis</span>
			</div>
			<br/><br/>

			<form method="post" action="">
			<div class="form_container">

				<div class="ip_box">
					<span>Subdivision ID :</span>
					<input name="s" type="text" autocomplete="off" spellcheck="false" value="'. $s .'" />
				</div>

				<div class="ip_box">
					<span>Billing period from :</span>
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
					<span>Billing period to :</span>
					<select class="period" name="mt">
						<option value="">Select Month</option>
	';
						$month=array("January","Fabruary","March","April","May","June","July","August","September","October","November","December");
						for($i=0;$i<sizeof($month);$i++){
							$j = $i+1;
							$mtselected = "";
							if($mt == $j){$mtselected='selected="selected"';}
							echo '<option value="'. $j .'" '. $mtselected .'>'.$month[$i].'</option>';
						}
	echo '
					</select>
					

					<select class="period"  name="yt">
                    	<option value="">Select Year</option>
	';
						$year = date('Y',$datetime)+1;
						for($i=0;$i<3;$i++){
							$ytselected = "";
							if($yt == $year){$ytselected='selected="selected"';}
							echo '<option value="'.$year.'" '. $ytselected .'>'.$year.'</option>';
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
	$consumer_data = array();
	$consumer_list = array();
	$consumer_meter_status = array();
	$mydate_list = array();
	if(mysql_num_rows($q) >0){
		while($d=mysql_fetch_object($q)){
			if(! in_array($d->c_mydate,$mydate_list)){
				$mydate_list[] = $d->c_mydate;
			}
			/* ----------------------------------------- */
			$cid = $d->out_cid;
			if(! in_array($cid, $consumer_data)){
				$consumer_data[] = $cid;
				$consumer_list[] = $d;
				$consumer_meter_status[$cid] = array();
			}
			$consumer_meter_status[$cid][$d->c_mydate] = array($d->out_premeterstatus,$d->in_status);
		}
	}
	
	if(mysql_num_rows($q) >0){
		$bpcol = sizeof($mydate_list) * 2;
		echo '
		<style>th{text-align:center !important;}</style>
		<table border="1" style="font-size:12px;">
			<tr>
				<th align="center" rowspan="3">Slno</th>
				<th align="center" rowspan="3">DEYAK Id</th>
				<th align="center" rowspan="3">Consumer No</th>
				<th align="center" rowspan="3">Name</th>
				<th align="center" rowspan="3">Address</th>
				<th align="center" rowspan="3" style="width:80px;">Category</th>
				<th align="center" rowspan="3">DTR no</th>
				<th align="center" colspan="'. $bpcol .'">Billing Period</th>
			</tr>
			<tr>
		';
		for($i=0;$i<sizeof($mydate_list);$i++){
			echo'
				<th align="center" colspan="2">'. date('F, Y',$mydate_list[$i]) .'</th>
			'; 
		}
		echo '
			</tr>
			<tr>
		';
		for($i=0;$i<sizeof($mydate_list);$i++){
			echo'
				<th align="center">Pre</th>
				<th align="center">Post</th>
			'; 
		}
		echo '
			</tr>
		';

		for($i=0;$i<sizeof($consumer_list);$i++){
			$data = $consumer_list[$i];
			$j = $i +1;
			echo '
			<tr>
				<td align="center">'. $j .'</td>
				<td align="center">'. $data->out_cid .'</td>
				<td align="center">'. $data->out_oldcid .'</td>
				<td>'. $data->out_consumer_name .'</td>
				<td>'. $data->out_consumer_address .'</td>
				<td>'. $data->out_consumer_category .'</td>
				<td align="center">'. $data->out_dtrno .'</td>
			';
			$pstat_1 = ""; $pstat_2 = "";
			for($ii=0; $ii<sizeof($mydate_list); $ii++){
				if(isset($consumer_meter_status[$data->out_cid][$mydate_list[$ii]])){
					$mstat_1 = $consumer_meter_status[$data->out_cid][$mydate_list[$ii]][0];
					$back_style='style="background:#8F0000;color:#fff;"';
					if(($mstat_1 == $pstat_2) || ($pstat_2 =="")){$back_style='';}
					echo '
					<td align="center" '. $back_style .'>'. $meter_status[$mstat_1] .'</td>
					';
					/*--------------------------------------------------------------------------------------------------*/
					$mstat_2 = $consumer_meter_status[$data->out_cid][$mydate_list[$ii]][1];
					$back_style='style="background:#8F0000;color:#fff;"';
					if(($mstat_2 == $pstat_1) || ($pstat_1 =="")){$back_style='';}
					echo '
					<td align="center" '. $back_style .'>'. $meter_status[$mstat_2] .'</td>
					';
				}else{
					echo '
					<td align="center">-</td>
					<td align="center">-</td>
					';
				}
				$pstat_1 = $consumer_meter_status[$data->out_cid][$mydate_list[$ii]][0];
				$pstat_2 = $consumer_meter_status[$data->out_cid][$mydate_list[$ii]][1];
			}
			echo '
			</tr>
			';

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