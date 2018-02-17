<?php
require_once("../filter/type.php");
////////////////////////////////////////////////////////////////////////////////////////
if(mysql_num_rows($caq) >0){
	
	$key = $r->getrkey($cad->imei);
	if(sizeof($ob_d)>0){
		for($i=0;$i<sizeof($ob_d);$i++){

			$id =  $r->rdecode($key,$ob_d[$i]->id);

			$col = array();										$val = array();
			$col[] = "in_aid";									$val[] = $r->rdecode($key,$ob_d[$i]->aid);
			$col[] = "c_import_status";							$val[] = "1";
			$col[] = "c_import_datetime";						$val[] = $datetime;
			$col[] = "c_pass_status";							$val[] = "0";

			$col[] = "in_billno";								$val[] = $r->rdecode($key,$ob_d[$i]->n_billno);
			$col[] = "in_status";								$val[] = $r->rdecode($key,$ob_d[$i]->n_status);
			$col[] = "in_reading_date";							$val[] = $r->rdecode($key,$ob_d[$i]->n_reading_date);
			$col[] = "in_postmeter_read";						$val[] = $r->rdecode($key,$ob_d[$i]->n_postmeter_read);
			$col[] = "in_meterpic";								$val[] = $r->rdecode($key,$ob_d[$i]->n_meterpic);
			$col[] = "in_meterpic_binary";						$val[] = $r->rdecode($key,$ob_d[$i]->n_meterpic_binary);
			$col[] = "in_unit_consumed";						$val[] = $r->rdecode($key,$ob_d[$i]->n_unit_consumed);
			$col[] = "in_unit_billed";							$val[] = $r->rdecode($key,$ob_d[$i]->n_unit_billed);
			$col[] = "in_consumption_day";						$val[] = $r->rdecode($key,$ob_d[$i]->n_consumption_day);
			$col[] = "in_due_date";								$val[] = $r->rdecode($key,$ob_d[$i]->n_due_date);
			$col[] = "in_energy_brkup";							$val[] = base64_encode($r->rdecode($key,$ob_d[$i]->n_energy_brkup));
			$col[] = "in_energy_amount";						$val[] = $r->rdecode($key,$ob_d[$i]->n_energy_amount);
			$col[] = "in_subsidy";								$val[] = $r->rdecode($key,$ob_d[$i]->n_subsidy);
			$col[] = "in_total_energy_charge";					$val[] = $r->rdecode($key,$ob_d[$i]->n_total_energy_charge);
			$col[] = "in_fixed_charge";							$val[] = $r->rdecode($key,$ob_d[$i]->n_fixed_charge);
			$col[] = "in_electricity_duty";						$val[] = $r->rdecode($key,$ob_d[$i]->n_electricity_duty);
			$col[] = "in_fppa_charge";							$val[] = $r->rdecode($key,$ob_d[$i]->n_fppa_charge);
			$col[] = "in_current_demand";						$val[] = $r->rdecode($key,$ob_d[$i]->n_current_demand);
			$col[] = "in_total_arrear";							$val[] = $r->rdecode($key,$ob_d[$i]->n_total_arrear);
			$col[] = "in_net_bill_amount";						$val[] = $r->rdecode($key,$ob_d[$i]->n_net_bill_amount);
			$col[] = "in_net_bill_amount_after_duedate";		$val[] = $r->rdecode($key,$ob_d[$i]->n_net_bill_amount_after_duedate);
			$col[] = "in_gps_verification";						$val[] = $r->rdecode($key,$ob_d[$i]->n_gps_verification);
			$col[] = "in_ocr_analysis";							$val[] = $r->rdecode($key,$ob_d[$i]->n_ocr_analysis);
			$col[] = "in_pf";									$val[] = $r->rdecode($key,$ob_d[$i]->n_pf);
			$col[] = "in_current_surcharge";					$val[] = $r->rdecode($key,$ob_d[$i]->n_current_surcharge);

			$col[] = "in_survey_gps_lati";						$val[] = $r->rdecode($key,$ob_d[$i]->n_survey_gps_lati);
			$col[] = "in_survey_gps_longi";						$val[] = $r->rdecode($key,$ob_d[$i]->n_survey_gps_longi);
			$col[] = "in_survey_gps_alti";						$val[] = $r->rdecode($key,$ob_d[$i]->n_survey_gps_alti);
			$col[] = "in_survey_meterheight";					$val[] = $r->rdecode($key,$ob_d[$i]->n_survey_meterheight);
			$col[] = "in_survey_mobno";							$val[] = $r->rdecode($key,$ob_d[$i]->n_survey_mobno);
			$col[] = "in_survey_meterslno";						$val[] = $r->rdecode($key,$ob_d[$i]->n_survey_meterslno);
			$col[] = "in_survey_metertype";						$val[] = $r->rdecode($key,$ob_d[$i]->n_survey_metertype);
			$col[] = "in_survey_consumertype";					$val[] = $r->rdecode($key,$ob_d[$i]->n_survey_consumertype);
			$col[] = "in_survey_nwsignal";						$val[] = $r->rdecode($key,$ob_d[$i]->n_survey_nwsignal);
					
			$col[] = "in_meter_rent";							$val[] = $r->rdecode($key,$ob_d[$i]->n_meter_rent);
			$col[] = "in_unit_pf";								$val[] = $r->rdecode($key,$ob_d[$i]->n_unit_pf);
			$col[] = "in_apdcl_billno";							$val[] = $r->rdecode($key,$ob_d[$i]->n_apdcl_billno);
			$col[] = "in_curr_reading";							$val[] = $r->rdecode($key,$ob_d[$i]->n_curr_reading);

			$strarr = array();
			for($i=0; $i<sizeof($col); $i++){
				$strarr[] = $col[$i] ."='". $val[$i] ."'";
			}
			$str = implode(",",$strarr);
			
			$query = "update m_data set ". $str ." where id='". $id ."' and c_import_status=0 and c_done=0";
			//echo $query;
			$q_done = mysql_query($query);
		}
			
		echo $_POST['c'];
	}else{
		echo 2;
	}
}
else{
	echo 2;
}

?>