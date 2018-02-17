<?php
function xml_generate($consumerdata,$billdata){
	include "bill.php";
	
	$read_xml = new stdClass();
	$bill_xml = new stdClass();
	
	$status_list[1]="Door Closed";
	$status_list[2]="Meter Tempered";
	$status_list[3]="Meter Stopped";
	
	
	///////////////////Calculation///////////////////////////////
	//////date
	$bill_from_date = new DateTime($billdata->premeter_read_date);
	$bill_to_date	= new DateTime($billdata->reading_date);
	$bill_interval	= $bill_from_date->diff($bill_to_date);
	$bill_diff		= $bill_interval->format('%a');
	
	$bill_from_date = $billdata->premeter_read_date;
	if($bill_diff>34){
		$bill_to_date = date('d-m-Y',strtotime('+34days',strtotime($billdata->premeter_read_date)));
	}else if($bill_diff <26){
		$bill_to_date = date('d-m-Y',strtotime('+26days',strtotime($billdata->premeter_read_date)));
	}
	else{
		$bill_to_date = $billdata->reading_date;
	}
	
	
	//////reading
	$mfactor				= $consumerdata->mfactor;
	
	$previous_reading		= $billdata->premeter_read;
	$current_reading		= $billdata->postmeter_read;
	$current_reading_b		= $billdata->postmeter_read;
	$unit_consumed 			= 1;
	$unit_billed			= 1;
	$ppunit 				= $billdata->ppunit;
	$new_ppunit				= 0;
	$status 				= $billdata->status;
	
	if($status>0){
		$current_reading 	= $status_list[$status];
		$current_reading_b 	= "-1";
		$unit_consumed 		= $billdata->reserve_unit;
		if($unit_consumed <1){
			$unit_consumed = 1;
		}
		$unit_billed		= 1 * $unit_consumed;
		$unit_billed		= round($unit_billed,0);
		
		$new_ppunit 		= $ppunit + $unit_consumed;
	}else{
		$unit_consumed 		= $current_reading - $previous_reading;
		if($unit_consumed <1){
			$unit_consumed =1;
		}
		
		if($unit_consumed > $ppunit){
			$unit_billed 	= $mfactor * ($unit_consumed - $ppunit);
			$unit_billed		= round($unit_billed,0);
		}else{
			$unit_billed 	= $mfactor * (1);
			$unit_billed	= round($unit_billed,0);
			$new_ppunit 	= ($ppunit - $unit_consumed) +1;
		}
	}
	
	///////billing
	$meter_rent 	= $consumerdata->meter_rent;
	$slab			= $consumerdata->slab;
	$slab_amount	= billing_algorithm($unit_billed,$slab,$meter_rent);
	
	$energy_charge	= 0;
	$fixed_charge	= 0;
	$meter_rent		= 0;
	$gross_charge	= 0;
	$rebate_charge	= 0;
	$credit_charge	= 0;
	$net_charge		= 0;
	
	$n_rate = 0;
	$amount_break_up_arr = array();
	for($i=0;$i<sizeof($slab_amount)-2;$i++){
		$slab_temp 	= array();
		$j 			= $i+1;
		$slab_temp[]= "Slab - ".$j;
		$slab_temp[]= $slab_amount[$i][0]."";
		$slab_temp[]= $slab_amount[$i][1]."";
		$slab_temp[]= $slab_amount[$i][2]."";
		
		$amount_break_up_arr[$i]=$slab_temp;
		$n_rate = $slab_amount[$i][1];
		$energy_charge = $energy_charge + $slab_amount[$i][2];
	}
	$amount_break_up = json_encode($amount_break_up_arr);
	
	{
		$fc_old		= $slab_amount[$i][2];
		$cload		= $consumerdata->cload;
		$load_unit 	= $consumerdata->load_unit;
		if($mfactor == 1){
			if($load_unit == "HP"){
				$cload = $cload * 0.746;
			}else if($load_unit == "KVA"){
				$cload = $cload * 0.8;
			}else if($load_unit == "KW"){
				if($cload <1){
					$cload = 1;
				}
			}
		}
		$fixed_charge = $cload * $fc_old;
		$fixed_charge = round($fixed_charge,2,PHP_ROUND_HALF_UP);
		$i++;
	}
	
	{
		$meter_rent	= $slab_amount[$i][2];
	}
	
	{
		$gross_charge = $energy_charge + $fixed_charge + $meter_rent;
	}
	
	{
		$rebate_charge = $energy_charge * $bill_rebate;
		$rebate_charge = round($rebate_charge, 2,PHP_ROUND_HALF_UP);
	}
	
	{
		$credit_charge = $billdata->credit;
	}
	
	{
		$net_charge = $gross_charge - ($rebate_charge + $credit_charge);
	}
	
	///////////////////Reading XML data///////////////////////////////
	$read_xml->mydate					= $billdata->mydate;
	$read_xml->consumer_id				= $billdata->cid;
	$read_xml->subdivision_id			= $billdata->subdiv_id;			
	$read_xml->book_no					= $billdata->bookno;
	$read_xml->bill_from_datetime		= $bill_from_date;
	$read_xml->bill_to_datetime			= $bill_to_date;
	$read_xml->previous_reading			= $billdata->premeter_read;
	$read_xml->current_reading			= $current_reading_b;
	$read_xml->unit_consumed			= $unit_consumed;
	$read_xml->reading_date				= $bill_to_date;
	$read_xml->remarks					= $status;
	$read_xml->multiplying_factor		= $consumerdata->mfactor;
	$read_xml->ppunit					= $new_ppunit;
	$read_xml->link						= $billdata->link;
	
	
	
	///////////////////Billing XML data///////////////////////////////
	
	$due_date =date('t-m-Y',$billdata->mydate);
	if(date('w',strtotime($due_date))<1){
		$due_date = date('d-m-Y',strtotime('-1 day',strtotime($due_date)));
	}
	
	$bill_date = $bill_to_date;
	if(strtotime($bill_to_date)<$billdata->mydate){
		$bill_date = date('d-m-Y',$billdata->mydate);
	}
	
	$bill_xml->mydate					= $billdata->mydate;
	$bill_xml->consumer_id				= $billdata->cid;
	$bill_xml->subdivision_id			= $billdata->subdiv_id;
	$bill_xml->book_no					= $billdata->bookno;
	$bill_xml->tariff_id				= substr($consumerdata->tariff_id,0,5);
	$bill_xml->bill_from_datetime		= $bill_from_date;
	$bill_xml->bill_to_datetime			= $bill_to_date;
	$bill_xml->bill_datetime			= $bill_date;
	$bill_xml->bill_generate_datetime	= $bill_date;
	$bill_xml->bill_due_datetime		= $due_date;
	$bill_xml->previous_reading			= $previous_reading;
	$bill_xml->current_reading			= $current_reading;
	$bill_xml->billed_unit				= $unit_billed;
	$bill_xml->energy_charge			= $energy_charge;
	$bill_xml->fixed_charge				= $fixed_charge;
	$bill_xml->meter_rent				= $meter_rent;
	$bill_xml->other_charge				= 0;
	$bill_xml->diseal_charge			= 0;
	$bill_xml->fuel_charge_rate 		= 0;
	$bill_xml->fuel_charge 				= 0;
	$bill_xml->gross_charge				= $gross_charge;
	$bill_xml->rebate_charge			= $rebate_charge;
	$bill_xml->credit_adjustment		= $credit_charge;
	$bill_xml->net_charge				= $net_charge;
	$bill_xml->old_ec 					= 0;
	$bill_xml->old_uc 					= 0;
	$bill_xml->sundry 					= 0;
	$bill_xml->n_rate					= $n_rate;
	$bill_xml->bill_no					= $billdata->billno;
	$bill_xml->energy_charge_breakup	= $amount_break_up;
	$bill_xml->link						= $billdata->link;
	
	
	//print_r($consumerdata);
	//print_r($billdata);
	//print_r($read_xml);
	//print_r($bill_xml);
	$out = array();
	$out[0]= $read_xml;
	$out[1]= $bill_xml;
	
	return $out;
}
?>