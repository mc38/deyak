<?php
require_once("../../../../../db/command.php");
require_once("../../../../../plugin/func/authentication.php");

if($u = authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		
		$data = json_decode(base64_decode($_POST['d']));
		
		$id 		= $data[0];
		
		$q = mysql_query("select * from m_data where id='". $id ."' and c_pass_status=0 and in_status<>''");
		if(mysql_num_rows($q)==1){
			$d = mysql_fetch_object($q);

			$i = 0;
			$col_d = array();									$val_d = array();
			$col_d[$i]	= "datetime";							$val_d[$i] = $datetime;			$i++;
			$col_d[$i]	= "c_mid";								$val_d[$i] = $id;				$i++;

			$col_d[$i]	= "c_bid";								$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "c_subdiv_id";						$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_aid";								$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "c_mydate";							$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "c_import_status";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "c_import_datetime";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "c_import_user";						$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "c_pass_status";						$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "c_pass_datetime";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "c_pass_user";						$val_d[$i] = $d->$col_d[$i];	$i++;

			$col_d[$i]	= "out_equation_category";				$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "c_ocr";								$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "c_survey";							$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_subdivision";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_dtrno";							$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_cid";							$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_oldcid";							$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_qrcode";							$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_gps_lati";						$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_gps_longi";						$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_gps_alti";						$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_consumer_name";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_consumer_address";				$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_consumer_category";				$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_connection_type";				$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_mfactor";						$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_connection_load";				$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_meter_no";						$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_reserve_unit";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_premeter_read_date";				$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_premeter_read";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_slab";							$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_meter_rent";						$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_principal_arrear";				$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_arrear_surcharge";				$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_current_surcharge";				$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_adjustment";						$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_rate_eduty";						$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_rate_surcharge";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_rate_fppa";						$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "out_multibill";						$val_d[$i] = $d->$col_d[$i];	$i++;

			$col_d[$i]	= "in_billno";							$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_status";							$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_reading_date";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_postmeter_read";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_meterpic";						$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_meterpic_binary";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_unit_consumed";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_unit_billed";						$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_consumption_day";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_due_date";						$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_energy_brkup";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_energy_amount";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_subsidy";							$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_total_energy_charge";				$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_fixed_charge";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_electricity_duty";				$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_fppa_charge";						$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_current_demand";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_total_arrear";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_net_bill_amount";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_net_bill_amount_after_duedate";	$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_gps_verification";				$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_ocr_analysis";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_pf";								$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_survey_gps_lati";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_survey_gps_longi";				$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_survey_gps_alti";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_survey_meterheight";				$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_survey_mobno";					$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_survey_meterslno";				$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_survey_metertype";				$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_survey_consumertype";				$val_d[$i] = $d->$col_d[$i];	$i++;
			$col_d[$i]	= "in_survey_nwsignal";					$val_d[$i] = $d->$col_d[$i];	$i++;

			$col_d_str	= implode(",",$col_d);					$val_d_str = implode("','",$val_d);


			$inq = mysql_query("insert into m_data_reject(". $col_d_str .") values('". $val_d_str ."')");
			if($inq){
				mysql_query("update m_data set c_pass_status='2',c_pass_datetime='". $datetime ."',c_pass_user='". $u ."' where id='". $id ."'");
			}
		}
		echo $_POST['c'];
	}
}
else{
	echo 0;
}
?>