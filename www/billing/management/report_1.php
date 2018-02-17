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
		isset($_POST['h']) && ($_POST['h']!="")
		&&
		isset($_POST['daf']) && ($_POST['daf']!="")
		&&
		isset($_POST['daf']) && ($_POST['dat']!="")
	){
		$s = $_POST['s'];
		$m = $_POST['m'];
		$y = $_POST['y'];
		$nf = $_POST['nf'];
		$nt = $_POST['nt'];
		$h = $_POST['h'];
		$daf = $_POST['daf'];
		$dat = $_POST['dat'];

		$nm = $m;
		if($m<10){
			$nm = "0". $m;
		}

		$mydate = strtotime($y ."-". $nm ."-01");

		$where = " and c_pass_status=1 and c_import_status=1";
		//$where ="";
		
		$da_datetime_f = strtotime($daf);
		$da_datetime_t = strtotime("+1day",strtotime($dat));
		
		$q = mysql_query("select * from m_data where in_reading_date>=". $da_datetime_f ." and in_reading_date<". $da_datetime_t ." and c_subdiv_id='". $s ."' and c_mydate='". $mydate ."' and in_status<>'' and in_unit_consumed>=". $nf ." and in_unit_consumed<=". $nt ."". $where);

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
				<span class="head_text">Management Report 1</span>
			</div>
			<br/><br/>

			<form method="post" action="">
			<div class="form_container">
				<div class="ip_box">
					<span>Date :</span>
					<input name="daf" type="date" style="width:49%; float:left;" autocomplete="off" spellcheck="false" value="'. $daf .'" />
					<input name="dat" type="date" style="width:49%; float:right;" autocomplete="off" spellcheck="false" value="'. $dat .'" />
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
					<span>Per day consumption Period in hour :</span>
					<input name="h" type="text" autocomplete="off" spellcheck="false" value="'. $h .'" />
				</div>

				<div class="ip_box">
					<span>Reading Threshold :</span>
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
		<div>Deviation =  (consumption - expected)unit</div>
		<table border="1">
			<tr>
				<th>Slno</th>
				<th>DTR</th>
				<th>Category</th>
				<th>Consumer no</th>
				<th>Consumer Name</th>
				<th>Consumeed Unit</th>
				<th>Load</th>
				<th>Consumption Day</th>
				<th>Expected Consumption</th>
				<th>Deviation</th>
			</tr>
		';

		$total_neg_devi = 0;
		$total_zer_devi = 0;
		$total_pos_devi = 0;

		$j =1;
		while($d = mysql_fetch_object($q)){
			$load_arr = explode(" ", $d->out_connection_load);
			$load = $load_arr[0];
			if($load<0.5){$load = 0.5;}

			$exp_consumption = $load * $h * $d->in_consumption_day;
			$exp_con_str = $load ."KW X ". $h ."Hour X ". $d->in_consumption_day ."Days = ". $exp_consumption ."Unit";

			$devi = $d->in_unit_consumed - $exp_consumption;

			if($devi<0){$total_neg_devi++;}
			else if($devi==0){$total_zer_devi++;}
			else if($devi>0){$total_pos_devi++;}

			echo '
			<tr>
				<td>'. $j .'</td>
				<td>'. $d->out_dtrno .'</td>
				<td>'. $d->out_consumer_category .'</td>
				<td>'. $d->out_oldcid .'</td>
				<td>'. $d->out_consumer_name .'</td>
				<td>'. $d->in_unit_consumed .'</td>
				<td>'. $load .' KW</td>
				<td>'. $d->in_consumption_day .'</td>
				<td>'. $exp_con_str .'</td>
				<td>'. $devi .'</td>
			</tr>

			';
			$j++;
		}

		echo '
		</table>
		<h3>Total Negative Deviation - '. $total_neg_devi .'</h3>
		<h3>Total Zero Deviation - '. $total_zer_devi .'</h3>
		<h3>Total Positive Deviation - '. $total_pos_devi .'</h3>
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