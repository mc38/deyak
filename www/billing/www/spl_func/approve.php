<?php
function billapprove($d,$u,$datetime){
	$dir = dirname(__FILE__);
	include str_replace("\\","/",$dir) . "/../db/command.php";

	$id = $d->id;

	//get ids
	$billd_q = mysql_query("select * from bill_details where id='". $d->c_bid ."'");
	$billd_d = mysql_fetch_object($billd_q);
	
	$conid		= $billd_d->conid;	
	$readid 	= $billd_d->readid;
	$baid		= $billd_d->baid;
	
	/*-------------------------------------------------------------------------*/
	//survey_update
	
	$survey_datetime 	= $d->in_reading_date;
	$agent 				= $d->in_aid;
	$meterslno 			= $d->in_survey_meterslno;
	$metertype 			= $d->in_survey_metertype;
	$consumertype 		= $d->in_survey_consumertype;
	$gps_lati 			= $d->in_survey_gps_lati;
	$gps_longi 			= $d->in_survey_gps_longi;
	$gps_alti 			= $d->in_survey_gps_alti;
	$nwsignal 			= $d->in_survey_nwsignal;
	$meterheight 		= $d->in_survey_meterheight;
	$mobno 				= $d->in_survey_mobno;
	$mdid 				= $d->id;
	
	$surcol = array();					$surval = array();
	$surcol[] = "conid";				$surval[] = $conid;
	$surcol[] = "survey_datetime";		$surval[] = $survey_datetime;
	$surcol[] = "update_datetime";		$surval[] = $datetime;
	$surcol[] = "agent";				$surval[] = $agent;
	$surcol[] = "meterslno";			$surval[] = $meterslno;
	$surcol[] = "metertype";			$surval[] = $metertype;
	$surcol[] = "consumertype";			$surval[] = $consumertype;
	$surcol[] = "gps_lati";				$surval[] = $gps_lati;
	$surcol[] = "gps_longi";			$surval[] = $gps_longi;
	$surcol[] = "gps_alti";				$surval[] = $gps_alti;
	$surcol[] = "nwsignal";				$surval[] = $nwsignal;
	$surcol[] = "meterheight";			$surval[] = $meterheight;
	$surcol[] = "mobileno";				$surval[] = $mobno;
	$surcol[] = "mdid";					$surval[] = $mdid;
	
	$surcol_str = implode(',',$surcol);	$surval_str = implode("','",$surval);
	mysql_query("insert into consumer_survey(". $surcol_str .") values('". $surval_str ."')");
	$survey_id = mysql_insert_id();
	
	$survey = 0;
	if(($mobno !="") && ($meterslno !="") && ($metertype >0) && ($consumertype >0) && ($meterheight >0)){
		$survey = 1;
	}
	mysql_query("update consumer_details set survey_id='". $survey_id ."', gps_lati='". $gps_lati ."', gps_longi='". $gps_longi ."', gps_alti='". $gps_alti ."', mobileno='". $mobno ."', survey='". $survey ."' where id='". $survey_id ."'");
	
	
	
	$mstatus = $d->in_status;
	$conday  = $d->in_consumption_day;
	/*-------------------------------------------------------------------------*/
	//bill_reading
	$meterno 			= $d->out_meter_no;
	$post_reading 		= $d->in_postmeter_read;
	$post_reading_date	= $d->in_reading_date;
	$unit_consumed		= $d->in_unit_consumed;
	$power_factor		= $d->in_pf;
	$unit_pf			= $d->in_unit_pf;
	$mfactor			= $d->out_mfactor;
	$unit_billed		= $d->in_unit_billed;
	$mdid				= $d->id;
	
	$rstr = array();
	$rstr[] = "meterno='". $meterno ."'";
	$rstr[] = "post_read_date='". $post_reading_date ."'";
	$rstr[] = "post_read='". $post_reading ."'";
	$rstr[] = "unit_consumed='". $unit_consumed ."'";
	$rstr[] = "power_factor='". $power_factor ."'";
	$rstr[] = "unit_pf='". $unit_pf ."'";
	$rstr[] = "m_factor='". $mfactor ."'";
	$rstr[] = "unit_billed='". $unit_billed ."'";
	$rstr[] = "mdid='". $mdid ."'";
	$rstr[] = "update_datetime='". $datetime ."'";
	
	$rstr_str = implode(",",$rstr);
	mysql_query("update bill_reading set ". $rstr_str ." where id='". $readid ."'");
	/*
	$avarage_unit = 0;
	$rq = mysql_query("select unit_consumed, avarage_unit from bill_reading where conid='". $conid ."' order by id desc");
	$rd = mysql_fetch_object($rq);
	if($mstatus ==0 || $mstatus ==3 || $mstatus ==4){
		$avarage_unit = round((($rd->avarage_unit + ($rd->unit_consumed / $conday))/2), 2);
	}else{
		$avarage_unit = $rd->avarage_unit;
	}
	$new_mydate = strtotime("1-".date("m-Y",strtotime("+40days",$d->c_mydate)));
	mysql_query("insert into bill_reading(mydate, conid, prev_read_date, prev_read, avarage_unit) values('". $new_mydate ."','". $conid ."','". $post_reading_date ."','". $post_reading ."','". $avarage_unit ."')");
	$readid_n = mysql_insert_id();
	*/

	/*-------------------------------------------------------------------------*/
	//bill_amount
	$pre_pa = 0;
	$pre_as = 0;
	$pre_ad = 0;
	$pre_cr	= 0;
	$baq = mysql_query("select pa,asr,adjustment,credit from bill_amount where conid='". $conid ."' order by id desc limit 0,1");
	if(mysql_num_rows($baq) >0){
		$bad = mysql_fetch_object($baq);
		if($bad->pa !=""){	$pre_pa		= $bad->pa;}
		if($bad->asr !=""){	$pre_as		= $bad->asr;}
		if($bad->adjustment !=""){	$pre_ad		= $bad->adjustment;}
		if($bad->credit !=""){	$pre_cr	= $bad->credit;}
	}

	//stage_1 billing_information entry
	$ba_mydate 		= $d->c_mydate;
	$ba_type 		= "1";
	$ba_adjustment 	= round($pre_ad, 2);
	$ba_pa 			= round($pre_pa, 2);
	$ba_as 			= round($pre_as, 2);
	$ba_cs 			= round($d->in_current_surcharge, 2);
	$ba_cd 			= round($d->in_current_demand, 0);
	$ba_nba 		= round((($ba_pa + $ba_as + $ba_cs + $ba_cd) -$ba_adjustment), 2);
	$ba_i 			= 0;
	$ba_nbai 		= round($ba_nba, 2);
	$ba_due_dtime	= $d->in_due_date;
	$mdid 			= $d->id;
	$ba_cr 			= round($pre_cr, 2);
	$ba_cs_pa 		= round(($ba_pa + $ba_cd),0);

	$bacol = array();			$baval = array();
	$bacol[] = "mydate";		$baval[] = $ba_mydate;
	$bacol[] = "conid";			$baval[] = $conid;
	$bacol[] = "datetime";		$baval[] = $datetime;
	$bacol[] = "type";			$baval[] = $ba_type;
	$bacol[] = "pa";			$baval[] = $ba_pa;
	$bacol[] = "asr";			$baval[] = $ba_as;
	$bacol[] = "cs";			$baval[] = $ba_cs;
	$bacol[] = "cd";			$baval[] = $ba_cd;
	$bacol[] = "nba";			$baval[] = $ba_nba;
	$bacol[] = "i";				$baval[] = $ba_i;
	$bacol[] = "nbai";			$baval[] = $ba_nbai;
	$bacol[] = "cs_pa";			$baval[] = $ba_cs_pa;
	$bacol[] = "adjustment";	$baval[] = $ba_adjustment;
	$bacol[] = "mdid";			$baval[] = $mdid;
	$bacol[] = "due_datetime";	$baval[] = $ba_due_dtime;
	$bacol[] = "credit";		$baval[] = $ba_cr;

	$bacol_str = implode(',',$bacol);	$baval_str = implode("','",$baval);
	mysql_query("insert into bill_amount(". $bacol_str .") values('". $baval_str ."')");
	if($ba_cr >0){
	//stage_2 billing_information entry
		$pre_cd = 0;
		$pre_pa = 0;
		$pre_as = 0;
		$pre_cs = 0;
		$pre_ad = 0;
		$pre_cr	= 0;

		$baaq = mysql_query("select cd,pa,asr,cs,adjustment,credit,due_datetime from bill_amount where conid='". $conid ."' order by id desc limit 0,1");
		if(mysql_num_rows($baaq) >0){
			$baad = mysql_fetch_object($baaq);

			if($baad->cd !=""){			$pre_cd 		= $baad->cd;}
			if($baad->pa !=""){			$pre_pa 		= $baad->pa;}
			if($baad->asr !=""){		$pre_as 		= $baad->asr;}
			if($baad->cs !=""){			$pre_cs			= $baad->cs;}
			if($baad->adjustment !=""){	$pre_ad			= $baad->adjustment;}
			if($baad->credit !=""){		$pre_cr			= $baad->credit;}
		}

		$n_mydate 		= $d->c_mydate;
		$n_type 		= "2";
		$n_pa 			= round((($pre_pa + $pre_cd) - $pre_ad), 2);
		$n_as 			= round(($pre_as + $pre_cs), 2);
		$n_due_dtime	= $baad->due_datetime;
		$n_cr 			= $pre_cr;
		$n_ad 			= 0;

		$payment_arr = array($n_as,$n_pa);
		$i =0;
		while($i<sizeof($payment_arr)){
			if($payment_arr[$i] > $n_cr){
				$payment_arr[$i] = $payment_arr[$i] - $n_cr;
				$n_cr = 0;
				break;
			}else{
				$n_cr = $n_cr - $payment_arr[$i];
				$payment_arr[$i] = 0;
			}
			$i++;
		}
		$n_as = $payment_arr[0];
		$n_pa = $payment_arr[1];
		$n_ad = 0;
		if($n_cr <10){$n_ad = $n_cr; $n_cr =0;}
		$n_cs_pa = $n_pa;

		$bacol = array();			$baval = array();
		$bacol[] = "mydate";		$baval[] = $n_mydate;
		$bacol[] = "conid";			$baval[] = $conid;
		$bacol[] = "datetime";		$baval[] = $datetime;
		$bacol[] = "type";			$baval[] = $n_type;
		$bacol[] = "pa";			$baval[] = $n_pa;
		$bacol[] = "asr";			$baval[] = $n_as;
		$bacol[] = "cs_pa";			$baval[] = $n_cs_pa;
		$bacol[] = "adjustment";	$baval[] = $n_ad;
		$bacol[] = "due_datetime";	$baval[] = $n_due_dtime;
		$bacol[] = "credit";		$baval[] = $n_cr;

		$bacol_str = implode(',',$bacol);	$baval_str = implode("','",$baval);
		mysql_query("insert into bill_amount(". $bacol_str .") values('". $baval_str ."')");
	}


	/*-------------------------------------------------------------------------*/
	//bill_details
	mysql_query("update bill_details set done=1 where id='". $d->c_bid ."'");

	/*
	$bdcol = array();			$bdval = array();
	$bdcol[] = "mydate";		$bdval[] = $new_mydate;
	$bdcol[] = "subdiv_id";		$bdval[] = $d->c_subdiv_id;
	$bdcol[] = "conid";			$bdval[] = $conid;
	$bdcol[] = "readid";		$bdval[] = $readid_n;
	$bdcol[] = "baid";			$bdval[] = $baid_n;

	$bdcol_str = implode(',',$bdcol);	$bdval_str = implode("','",$bdval);
	mysql_query("insert into bill_details(". $bdcol_str .") values('". $bdval_str ."')");
	*/

	$okdone = mysql_query("update m_data set c_done='1',c_pass_status='1',c_pass_datetime='". $datetime ."',c_pass_user='". $u ."' where id='". $id ."'");
	if($okdone){
		return true;
	}else{
		return false;
	}
}

?>