<?php
include "db/command.php";

echo '
<table border="1">
	<tr>
		<td>Slno</td>
		<td>DEYAK id</td>
		<td>DTR</td>
		<td>Consumer No</td>
		<td>Category</td>
		<td>Consumer Name</td>
		<td>Consumption Day</td>
		<td>Billed Unit</td>
		<td>Energy Brkup</td>
		<td>Energy Charge</td>
		td>New Fig</td>
	</tr>
';
$q = mysql_query("select * from m_data");
$i =0;
while($d = mysql_fetch_object($q)){
	$en = $d->in_energy_brkup;
	$en_arr = json_decode(base64_decode($en));
	//echo base64_decode($en);
	if(sizeof($en_arr) ==3){
		$j = $i +1;

		$unit = $d->in_unit_billed;
		$new_s_unit = $en_arr[0][1];
		$unit = $unit - $new_s_unit;
		if($unit>$new_s_unit){
			$en_arr[1][1] = $new_s_unit;
			$en_arr[1][3] = $en_arr[1][1] * $en_arr[1][2];
		}

		$unit = $unit - $new_s_unit;
		if($unit >0){
			$en_arr[2][1] = $unit;
			$en_arr[2][3] = $en_arr[2][1] * $en_arr[2][2];
		}

		$new_energy_charge = 0;
		$cunit = 0;	
		for($ii=0;$ii<sizeof($en_arr);$ii++){
			$cunit = $cunit + $en_arr[$ii][1];
			$new_energy_charge = $new_energy_charge + $en_arr[$ii][3];
		}

		$status = "not ok";
		if($cunit == $d->in_unit_billed){
			$status = "ok";
		}


		$new_total_eng_charege = $new_energy_charge - $d->in_subsidy;
		$new_current_demand = $new_total_eng_charege + $d->in_fixed_charge + $d->in_meter_rent + $d->in_electricity_duty + $d->in_fppa_charge;
		$new_nba = $new_current_demand + $d->in_total_arrear;


		$done = mysql_query("update m_data set in_energy_brkup='". base64_encode(json_encode($en_arr)) ."',in_energy_amount='". $new_energy_charge ."',in_total_energy_charge='". $new_total_eng_charege ."',in_current_demand='". $new_current_demand ."',in_net_bill_amount='". $new_nba ."', in_net_bill_amount_after_duedate='". $new_nba ."' where id='". $d->id ."'");

		$dstat = "Not done";
		if($done){$dstat = "Done";}

		echo '
		<tr>
			<td>'. $j .'</td>
			<td>'. $d->out_cid .'</td>
			<td>'. $d->out_dtrno .'</td>
			<td>'. $d->out_oldcid .'</td>
			<td>'. $d->out_consumer_category .'</td>
			<td>'. $d->out_consumer_name .'</td>
			<td>'. $d->in_consumption_day .'</td>
			<td>'. $d->in_unit_billed .'</td>
			<td>'. base64_decode($en) .'<hr/>'. json_encode($en_arr) .'<hr/>'. $status .' ->'. sizeof($en_arr) .'</td>
			<td>'. $d->in_energy_amount .'<hr/>'. $new_energy_charge .'</td>
			<td>
				 - '. $d->in_subsidy .' = '. $new_total_eng_charege .'
				 <hr/>
				 + '. $d->in_fixed_charge .' + '. $d->in_meter_rent .' + '. $d->in_electricity_duty .' + '. $d->in_fppa_charge .' = '. $new_current_demand .'
				 <hr/>
				 + '. $d->in_total_arrear .' = '. $new_nba .'
				 <hr/>
				 '. $dstat .'
			</td>
		</tr>
		';

		$i++;
	}
}
echo '
</table>
';
?>