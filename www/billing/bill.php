<?php
function billing_process($unit, $pf, $consumption_day, $billdate, $reserve_unit, $slab, $cload, $mfactor, $pa, $as, $mrent_in, $eduty_rate, $fppa_rate, $adjustment, $surcharge_rate, $prevbilldue, $cs_pa){

	$slabs = json_decode(base64_decode($slab));
	$tariff_slab = $slabs[0];
	$pf_slab = $slabs[1];

	if($cload <0.5){
		$cload = 0.5;
	}else{
		$cload = round($cload,0);
	}

	$cs = round((($cs_pa * $surcharge_rate * ceil(((($billdate - $prevbilldue)/(3600 * 24))-1)/30))/100),0);
	$csdiff = $billdate - $prevbilldue;

	$unit_pf = 0;
	$pf = 0;
	if($pf_slab !=""){
		if($pf ==""){
			$pfd = json_decode(base64_decode($pf_slab));
			$pf = $pfd[0];
		}
		$unit_pf = pf_calculate($unit, $pf, $pf_slab);
	}else{
		$unit_pf = $unit;
	}

	$unit_billed = round(($unit_pf * $mfactor),0);

	//amounts
	$eng_brk = array();
	$charges_arr = billing_algorithm($unit_billed, $tariff_slab, $cload, $consumption_day);
	$slab_len = sizeof($charges_arr) -3;
	for($i=0; $i<$slab_len; $i++){
		$j = $i +1;
		$eng_brk[$i][] = $j ."";
		$eng_brk[$i][] = $charges_arr[$i][0] ."";
		$eng_brk[$i][] = number_format($charges_arr[$i][1], 2, ".","");
		$eng_brk[$i][] = number_format($charges_arr[$i][2], 2, ".","");
	}

	$eng_brkup = base64_encode(json_encode($eng_brk));

	$energy_charge 		= $charges_arr[$i][2]; $i++;
	$fixed_charge 		= $charges_arr[$i][2]; $i++;
	$subsidy 			= $charges_arr[$i][2]; $i++;

	$total_energy_charge 	= $energy_charge - $subsidy;
	$fixed_charge 			= round((($fixed_charge * $consumption_day *12)/365.25),2);
	$meter_rent 			= round((($mrent_in * $consumption_day * 12)/365.25),2);
	$electricity_duty		= round(($unit_billed * $eduty_rate),2);
	$fppa_charge			= round(($unit_billed * $fppa_rate),2);

	$current_demand 		= round(($total_energy_charge + $fixed_charge + $meter_rent + $electricity_duty + $fppa_charge), 2);
	$total_arrear 			= round(($pa + $as + $cs), 2);
	$net_bill_amount 		= round(($current_demand + $total_arrear - $adjustment), 0);

	$out = array();
	$out[] = $unit_pf;
	$out[] = $unit_billed;
	$out[] = $eng_brkup;
	$out[] = $energy_charge;
	$out[] = $subsidy;
	$out[] = $total_energy_charge;
	$out[] = $fixed_charge;
	$out[] = $meter_rent;
	$out[] = $electricity_duty;
	$out[] = $fppa_charge;
	$out[] = $current_demand;
	$out[] = $cs;
	$out[] = $total_arrear;
	$out[] = $net_bill_amount;

	return $out;
}


function billing_algorithm($unit,$slab,$cload,$cday){
	
	$out = false;
	$unitr = $unit;
	
	if($unit >=0 && $slab !=""){
		$slabd = json_decode(base64_decode($slab));
		$unitto = $slabd[0];
		$unitto = round((round(($unitto / 30),0) * $cday),0);

		if($cload<0.5){$cload = 0.5;}
		else{$cload = round($cload,0);}

		if(($unit <= $unitto) && ($unitto >0)){
			$out = bill_make($slabd[1],$unit,$cload,$cday);
		}else{
			$out = bill_make($slabd[2],$unit,$cload,$cday);
		}
	}
	return $out;
}


function pf_calculate($unit,$pf,$slab){
	$sdata = json_decode(base64_decode($slab));
	$cslab =""; $sbrkup ="";
	$chpcen =0;
	if($pf < $sdata[0]){
		$cslab = $sdata[1];
		$slabd = json_decode(base64_decode($cslab));
		for($i=0;$i<sizeof($slabd);$i++){
			$slabdata = $slabd[$i];
			$fd = $slabdata[0];	$td = 0;
			if($slabdata[1] != ""){$td = $slabdata[1];}

			$ch = $slabdata[2]; $am = $slabdata[3];
			$chh = 1;
			if($ch == "-"){$chh = -1;}

			if(($pf<$fd) && ($pf>=$td)){
				$chpcen = $chpcen + (($fd - $pf) * $am * $chh);
				break;
			}else{
				$chpcen = $chpcen + (($fd - $td) * $am * $chh);
			}
		}
	}else if($pf > $sdata[0]){
		$cslab = $sdata[2];
		$slabd = json_decode(base64_decode($cslab));
		for($i=0;$i<sizeof($slabd);$i++){
			$slabdata = $slabd[$i];
			$fd = $slabdata[0];	$td = 200;
			if($slabdata[1] != ""){$td = $slabdata[1];}

			$ch = $slabdata[2]; $am = $slabdata[3];
			$chh = 1;
			if($ch == "-"){$chh = -1;}

			if(($pf>$fd) && ($pf<=$td)){
				$chpcen = ($am * $chh);
			}
		}
	}
	$outunit = $unit + (($unit * $chpcen)/100);
	return round($outunit,0);
}


function bill_make($sarr, $unit, $cload, $consumption_day){
	$out = array();
	$unitr = $unit;
	
	$fixed_charge = 0.0; $brk = false;
	$unit_take = 0;
	$amount = 0.0;
	$eng_chrg = 0.0;
	$subsidy = 0.0;

	$unit_perday = 0;
	
	$slab_arr = json_decode(base64_decode($sarr));
	$stemp = $slab_arr[0];
	$s;

	if(sizeof($stemp) == 5){
		$new_slab_unit_f = 0; $new_slab_unit_t = 0;
		for($i=0; $i<sizeof($slab_arr); $i++){
			$slab_data = $slab_arr[$i];

			$slab_unit_f = 0; $slab_unit_t = 0;
			if($slab_data[1] !=""){
				$slab_unit_f = $slab_data[0];
				$slab_unit_t = $slab_data[1];
			}

			$unit_perday = round(((($slab_unit_t + 1) - $slab_unit_f) /30),0);
			$new_slab_unit_f = $new_slab_unit_t + 1;
			$new_slab_unit_t = $new_slab_unit_f + ($consumption_day * $unit_perday) -1;
			//echo $new_slab_unit_f ." -> ". $new_slab_unit_t ."</br>";
			$diff = 0;
			if($slab_data[1] !=""){
				$diff = ($new_slab_unit_t +1) - $new_slab_unit_f;
			}

			if(($diff>0) && ($unitr > $new_slab_unit_t)){
				$unit_take = $diff;
			}else{
				$unit_take = $unit;
				$fixed_charge = $slab_data[3] * $cload;
				$fixed_charge = round($fixed_charge,2);
				$brk = true;
			}

			$unit = $unit - $unit_take;
			$amount = $unit_take * $slab_data[2];
			$amount = round($amount,2);
			$eng_chrg = round(($amount + $eng_chrg),2);
			$subsidy = $subsidy + ($unit_take * $slab_data[4]);

			//slabs
			$s = sizeof($out);
			$out[$s][] = $unit_take;
			$out[$s][] = (float)$slab_data[2];
			$out[$s][] = $amount;

			if($brk)break;
		}
		
		//energy charge
		$s = sizeof($out);
		$out[$s][] = 0;
		$out[$s][] = 0;
		$out[$s][] = $eng_chrg;

		//fixed charge
		$s = sizeof($out);
		$out[$s][] = 0;
		$out[$s][] = 0;
		$out[$s][] = $fixed_charge;

		//subsidy
		$s = sizeof($out);
		$out[$s][] = 0;
		$out[$s][] = 0;
		$out[$s][] = $subsidy;
	}
	return $out;
}
?>