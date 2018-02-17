<?php
session_start();
include "db/command.php";
require_once("plugin/func/authentication.php");

echo '
	<head>
		<title>APDCL file upload section</title>
	</head>
';

date_default_timezone_set('Asia/Kolkata');
$datetime=date($_SERVER['REQUEST_TIME']);

$domain = $_SERVER['HTTP_HOST'];
$path = $_SERVER['REQUEST_URI'];
$path_arr = explode('/',$path);
$path_login = "http://". $domain;
for($i=0;$i<sizeof($path_arr)-1;$i++){
	$path_login .= $path_arr[$i] . "/";
}

$msg = "";

$txt_path = "temp.txt";
if(file_exists($txt_path)){
	unlink($txt_path);
}

if($u = authenticate()){
	$s = "";
	$m = "";
	$y = "";
	$n = "";
	$v = "";
	$c = "";
	$dt = "";
	$da = "";

	if(
		isset($_POST['s']) && ($_POST['s']!="")
		&&
		isset($_POST['m']) && ($_POST['m']!="")
		&&
		isset($_POST['y']) && ($_POST['y']!="")
		&&
		isset($_POST['n']) && ($_POST['n']!="")
		&&
		isset($_POST['v']) && ($_POST['v']!="")
		&&
		isset($_POST['c']) && ($_POST['c']!="")
		&&
		isset($_POST['dt'])
		&&
		isset($_POST['da']) && ($_POST['da']!="")
	){
		$s = $_POST['s'];
		$m = $_POST['m'];
		$y = $_POST['y'];
		$n = $_POST['n'];
		$v = $_POST['v'];
		$c = $_POST['c'];
		$dt = $_POST['dt'];
		$da = $_POST['da'];

		$nm = $m;
		if($m<10){
			$nm = "0". $m;
		}

		$mydate = strtotime($y ."-". $nm ."-01");

		$where = " and c_pass_status=1 and c_import_status=1";
		//$where ="";
		$dtwhere ="";
		if($dt !=""){
			$dtwhere = " and out_dtrno='". $dt ."'";
		}
		$da_datetime_f = strtotime($da);
		$da_datetime_t = strtotime("+1day",strtotime($da));
		
		$q = mysql_query("select * from m_data where c_pass_datetime>=". $da_datetime_f ." and c_pass_datetime<". $da_datetime_t ." and c_subdiv_id='". $s ."'". $dtwhere ." and c_mydate='". $mydate ."' and in_status<>''". $where);
		if(mysql_num_rows($q) >0){
			while($d = mysql_fetch_object($q)){
				$consumer_no 	= $d->out_oldcid;
				if(strlen($consumer_no)<12){
					$consumer_no = "0".$consumer_no;
				}
				$install_no 	= substr($d->out_oldcid,0,2) . substr($d->out_oldcid,4,strlen($d->out_oldcid)-4);
				if(strlen($install_no)<10){
					$install_no = "0".$install_no;
				}

				$subd = $s;
				if($subd <100){
					$subd = "0".$s;
				}

				$post_meter_read = $d->in_postmeter_read;
				if($post_meter_read <0){
					$post_meter_read = $d->out_premeter_read;
				}

				$dtr = $d->out_dtrno;
				if($dtr <100){
					$dtr = "0".$dtr;
				}
				$pfrp = $d->in_unit_consumed - $d->in_unit_pf; if($pfrp<0){ $pfrp = $pfrp * (-1); }

				$mru 			= "M". $subd . "A" . $dtr;
				$cur_read_date 	= date('dmY',$d->in_reading_date);
				$load_arr 		= explode(" ",$d->out_connection_load);
				$load			= $load_arr[0];
				$bill_no		= $d->in_apdcl_billno;
				$bill_gen_date	= $cur_read_date;
				$meterno		= $d->out_meter_no;
				$mr_note		= 100 + $d->in_status;
				$cur_reading 	= $post_meter_read;
				$unit_billed	= $d->in_unit_billed;
				$extimated_flag = 0;
				$noted_reading	= $cur_reading;
				$valid_state	= "P";
				$dval_1 		= 0;
				$dval_2 		= 0;
				$dval_3 		= "";
				$dval_4 		= 0;
				$dval_5 		= "P";
				$pf				= $d->in_pf;
				$dval_6 		= 0;
				$dval_7 		= 0;
				$dval_8 		= 0;
				$dval_9 		= "P";
				$dval_10 		= 0;
				$dval_11 		= 0;
				$dval_12 		= 0;
				$dval_13 		= 0;
				$dval_14 		= 0;
				$dval_15 		= 0;
				$pfpr 			= $pfrp;
				$dval_17 		= 0;
				$mech_srlno		= $n;
				$due_date		= date('dmY',$d->in_due_date);
				$net_bill		= (int) round($d->in_net_bill_amount, 2);
				$fixed_charge 	= $d->in_fixed_charge;
				$energy_charge  = $d->in_energy_amount;
				$add_demand		= $d->in_meter_rent;
				$elec_duty		= $d->in_electricity_duty;
				$fppa_charge	= $d->in_fppa_charge;
				$gross_adj		= $d->out_adjustment;
				$out_princ 		= $d->out_principal_arrear;
				$out_surch 		= $d->out_arrear_surcharge;
				$dval_18		= 0;
				$govt_subsidy 	= $d->in_subsidy;
				$dval_19		= 0;
				$cur_surcharge	= (int) round($d->in_current_surcharge, 0);
				$dval_20		= 0;
				$soft_ver		= $v;
				$state_code		= $c;


				$txt_array = array();
				$txt_array[0] 	= $consumer_no;
				$txt_array[1] 	= $install_no;
				$txt_array[2] 	= $mru;
				$txt_array[3] 	= $cur_read_date;
				$txt_array[4] 	= $load;
				$txt_array[5] 	= $bill_no;
				$txt_array[6] 	= $bill_gen_date;
				$txt_array[7] 	= $meterno;
				$txt_array[8] 	= $mr_note;
				$txt_array[9] 	= $cur_reading;
				$txt_array[10] 	= $unit_billed;
				$txt_array[11] 	= $extimated_flag;
				$txt_array[12] 	= $noted_reading;
				$txt_array[13] 	= $valid_state;
				$txt_array[14] 	= $dval_1;
				$txt_array[15] 	= $dval_2;
				$txt_array[16] 	= $dval_3;
				$txt_array[17] 	= $dval_4;
				$txt_array[18] 	= $dval_5;
				$txt_array[19] 	= $pf;
				$txt_array[20] 	= $dval_6;
				$txt_array[21] 	= $dval_7;
				$txt_array[22] 	= $dval_8;
				$txt_array[23] 	= $dval_9;
				$txt_array[24] 	= $dval_10;
				$txt_array[25] 	= $dval_11;
				$txt_array[26] 	= $dval_12;
				$txt_array[27] 	= $dval_13;
				$txt_array[28] 	= $dval_14;
				$txt_array[29] 	= $dval_15;
				$txt_array[30] 	= $pfpr;
				$txt_array[31] 	= $dval_17;
				$txt_array[32] 	= $mech_srlno;
				$txt_array[33] 	= $due_date;
				$txt_array[34] 	= $net_bill;
				$txt_array[35] 	= $fixed_charge;
				$txt_array[36] 	= $energy_charge;
				$txt_array[37] 	= $add_demand;
				$txt_array[38] 	= $elec_duty;
				$txt_array[39] 	= $fppa_charge;
				$txt_array[40] 	= $gross_adj;
				$txt_array[41] 	= $out_princ;
				$txt_array[42] 	= $out_surch;
				$txt_array[43] 	= $dval_18;
				$txt_array[44] 	= $govt_subsidy;
				$txt_array[45] 	= $dval_19;
				$txt_array[46] 	= $cur_surcharge;
				$txt_array[47] 	= $dval_20;
				$txt_array[48] 	= $soft_ver;
				$txt_array[49] 	= $state_code;

				//var_dump($txt_array);
				$txt_string = implode('#',$txt_array);

				file_put_contents("temp.txt", $txt_string . PHP_EOL, FILE_APPEND);
			}

			echo '
			<script src="plugin/java/plugins/jquery.min.js"></script>
			<script>
			$(function(){
				window.location.href = "text_file_download.php";
			})
			</script
			';
		}else{
			$msg = "No data is found";
		}
	}




	echo '

	<!DOCTYPE html>
	<html>
	<head>
		<title>DEYAK APDCL upload</title>
	    <link rel="stylesheet" href="apdcl_upload/style/css/style.css" type="text/css">
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
				<span class="head_text">APDCL File Upload Section</span>
			</div>
			<br/><br/>

			<form method="post" action="">
			<div class="form_container">
				<div class="ip_box">
					<span>Date :</span>
					<input name="da" type="date" autocomplete="off" spellcheck="false" value="'. $da .'" />
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
					<span>DTR no :</span>
					<input name="dt" type="text" autocomplete="off" spellcheck="false" value="'. $dt .'" />
				</div>

				<div class="ip_box">
					<span>Machine Serial Number :</span>
					<input name="n" type="text" autocomplete="off" spellcheck="false" value="'. $n .'" />
				</div>

				<div class="ip_box">
					<span>SBM software version :</span>
					<input name="v" type="text" autocomplete="off" spellcheck="false" value="'. $v .'" />
				</div>

				<div class="ip_box">
					<span>State code :</span>
					<input name="c" type="text" autocomplete="off" spellcheck="false" value="'. $c .'" />
				</div>



				<div class="ip_box">
					<button type="submit">Download</button>
					<span style="color: brown;">'. $msg .'</span>
				</div>
			</div>
			</form>
		</body>
		</html>
	';


}else{
	echo '
	<!DOCTYPE html>
	<html>
	<head>
		<title>DEYAK APDCL upload</title>
	    <link rel="stylesheet" href="apdcl_upload/style/css/style.css" type="text/css">
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
				<span class="head_text">APDCL File Upload Section</span>
			</div>
			<br/><br/>
			<div class="form_container">
				<span style="color: red; font-size:36px; ">UNAUTHORIZED ACCESS</span>
				<div class="img"></div>
			</div>
	</body>
	</html>
	';
}




?>