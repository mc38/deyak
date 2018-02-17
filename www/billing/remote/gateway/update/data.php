<?php
include "../../db/command.php";
include "../../plugin/authentication.php";
include "../../plugin/data_transfer.php";
include "../../plugin/rcrypt.php";

$dt = new data_transfer();
if($dt->data_receive()){
	$send_data = "";
	
	if($a = authenticate()){
	
		$data = json_decode(base64_decode($_POST['d']));
		
		$id 								= $data[0];
		$aid 								= $data[1];
		
		$n_billno							= $data[2];
		$n_status							= $data[3];
		$n_reading_date						= $data[4];
		$n_post_meter_read					= $data[5];
		$n_meterpic							= $data[6];
		$n_unit_consumed					= $data[7];
		$n_unit_billed						= $data[8];
		$n_consumption_day					= $data[9];
		$n_due_date							= $data[10];
		$n_energy_brkup						= $data[11];
		$n_energy_amount					= $data[12];
		$n_subsidy							= $data[13];
		$n_total_energy_charge				= $data[14];
		$n_fixed_charge						= $data[15];
		$n_electricity_duty					= $data[16];
		$n_fppa_charge						= $data[17];
		$n_current_demand					= $data[18];
		$n_total_arrear						= $data[19];
		$n_net_bill_amount					= $data[20];
		$n_net_bill_amount_after_duedate	= $data[21];
		$n_gps_verification					= $data[22];
		$n_ocr_analysis						= $data[23];
		$n_pf								= $data[24];
		$n_current_surcharge				= $data[25];
		$n_meter_rent						= $data[26];
		$n_unit_pf							= $data[27];
		$n_apdcl_billno						= $data[28];

		$n_curr_reading						= $data[29];
		$n_blnk_5							= $data[30];
		$n_blnk_6							= $data[31];
		$n_blnk_7							= $data[32];
		$n_blnk_8							= $data[33];
		$n_blnk_9							= $data[34];

		$n_survey_gps_lati					= $data[35];
		$n_survey_gps_longi					= $data[36];
		$n_survey_alti						= $data[37];
		$n_survey_meterheight				= $data[38];
		$n_survey_mobno						= $data[39];
		$n_survey_meterslno					= $data[40];
		$n_survey_metertype					= $data[41];
		$n_survey_consumertype				= $data[42];
		$n_survey_nwsignal					= $data[43];
		
		
		$r = new rcrypt();
		$key = $r->getrkey($_POST['i']);
		
		if($a == $r->rdecode($key,$aid)){
			$col = array();										$val = array();
			
			$col[] = "in_aid";									$val[] = $r->rdecode($key,$aid);
			$col[] = "c_import_status";							$val[] = "1";
			$col[] = "c_import_datetime";						$val[] = $datetime;
			$col[] = "c_pass_status";							$val[] = "0";

			$col[] = "in_billno";								$val[] = $r->rdecode($key,$n_billno);
			$col[] = "in_status";								$val[] = $r->rdecode($key,$n_status);
			$col[] = "in_reading_date";							$val[] = $r->rdecode($key,$n_reading_date);
			$col[] = "in_postmeter_read";						$val[] = $r->rdecode($key,$n_post_meter_read);
			$col[] = "in_meterpic";								$val[] = $r->rdecode($key,$n_meterpic);
			$col[] = "in_unit_consumed";						$val[] = $r->rdecode($key,$n_unit_consumed);
			$col[] = "in_unit_billed";							$val[] = $r->rdecode($key,$n_unit_billed);
			$col[] = "in_consumption_day";						$val[] = $r->rdecode($key,$n_consumption_day);
			$col[] = "in_due_date";								$val[] = $r->rdecode($key,$n_due_date);
			$col[] = "in_energy_brkup";							$val[] = base64_encode($r->rdecode($key,$n_energy_brkup));
			$col[] = "in_energy_amount";						$val[] = $r->rdecode($key,$n_energy_amount);
			$col[] = "in_subsidy";								$val[] = $r->rdecode($key,$n_subsidy);
			$col[] = "in_total_energy_charge";					$val[] = $r->rdecode($key,$n_total_energy_charge);
			$col[] = "in_fixed_charge";							$val[] = $r->rdecode($key,$n_fixed_charge);
			$col[] = "in_electricity_duty";						$val[] = $r->rdecode($key,$n_electricity_duty);
			$col[] = "in_fppa_charge";							$val[] = $r->rdecode($key,$n_fppa_charge);
			$col[] = "in_current_demand";						$val[] = $r->rdecode($key,$n_current_demand);
			$col[] = "in_total_arrear";							$val[] = $r->rdecode($key,$n_total_arrear);
			$col[] = "in_net_bill_amount";						$val[] = $r->rdecode($key,$n_net_bill_amount);
			$col[] = "in_net_bill_amount_after_duedate";		$val[] = $r->rdecode($key,$n_net_bill_amount_after_duedate);
			$col[] = "in_gps_verification";						$val[] = $r->rdecode($key,$n_gps_verification);
			$col[] = "in_ocr_analysis";							$val[] = $r->rdecode($key,$n_ocr_analysis);
			$col[] = "in_pf";									$val[] = $r->rdecode($key,$n_pf);
			$col[] = "in_current_surcharge";					$val[] = $r->rdecode($key,$n_current_surcharge);

			$col[] = "in_survey_gps_lati";						$val[] = $r->rdecode($key,$n_survey_gps_lati);
			$col[] = "in_survey_gps_longi";						$val[] = $r->rdecode($key,$n_survey_gps_longi);
			$col[] = "in_survey_gps_alti";						$val[] = $r->rdecode($key,$n_survey_alti);
			$col[] = "in_survey_meterheight";					$val[] = $r->rdecode($key,$n_survey_meterheight);
			$col[] = "in_survey_mobno";							$val[] = $r->rdecode($key,$n_survey_mobno);
			$col[] = "in_survey_meterslno";						$val[] = $r->rdecode($key,$n_survey_meterslno);
			$col[] = "in_survey_metertype";						$val[] = $r->rdecode($key,$n_survey_metertype);
			$col[] = "in_survey_consumertype";					$val[] = $r->rdecode($key,$n_survey_consumertype);
			$col[] = "in_survey_nwsignal";						$val[] = $r->rdecode($key,$n_survey_nwsignal);
			
			$col[] = "in_meter_rent";							$val[] = $r->rdecode($key,$n_meter_rent);
			$col[] = "in_unit_pf";								$val[] = $r->rdecode($key,$n_unit_pf);
			$col[] = "in_apdcl_billno";							$val[] = $r->rdecode($key,$n_apdcl_billno);
			$col[] = "in_curr_reading";							$val[] = $r->rdecode($key,$n_curr_reading);
			
			$strarr = array();
			for($i=0; $i<sizeof($col); $i++){
				$strarr[] = $col[$i] ."='". $val[$i] ."'";
			}
			$str = implode(",",$strarr);
			
			$query = "update m_data set ". $str ." where id='". $r->rdecode($key,$id) ."' and c_import_status=0 and c_done=0";
			//echo $query;
			$q_done = mysql_query($query);
			
			if($q_done){
				$send_data = 1;
			}
		}else{
			$send_data = 0;
		}
	
	}else{
		$send_data = 0;
	}
	
	$dt->data_send($send_data);
}
?>