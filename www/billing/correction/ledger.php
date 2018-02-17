<?php
include "db/command.php";
include "../config/config.php";

$mydate = "1501525800";

echo '
<table border="1" style="font-size:12px;">
	<tr>
		<th>Slno</th>
		<th>DEYAK id</th>
		<th>DTR</th>
		<th>Consumer No</th>
		<th>Category</th>
		<th>Consumer Name</th>
		<th>Consumption Day</th>
		<th>Previous Reading</th>
		<th>Current Reading</th>
		<th>Consumed Unit</th>
		<th>PF</th>
		<th>Billed Unit</th>
		<th>Energy Brkup</th>
		<th>Energy Charge</th>
		<th>Subsidy</th>
		<th>Total Energy Charge</th>
		<th>Fixed Charge</th>
		<th>Meter Rent</th>
		<th>Electricity Duty</th>
		<th>FPPA Charge</th>
		<th>Current Demand</th>
		<th>PA</th>
		<th>AS</th>
		<th>CS</th>
		<th>Total Arrear</th>
		<th>Net Bill Amount</th>
	</tr>
';
$q = mysql_query("select * from m_data where c_pass_status=1 and c_mydate='". $mydate ."'");
$i =0;
while($d = mysql_fetch_object($q)){
	$en = $d->in_energy_brkup;
	$en_arr = json_decode(base64_decode($en));
	//echo base64_decode($en);
	if(sizeof($en_arr) >0){
		$j = $i +1;

		$creading = $d->in_unit_consumed;
		$cur_read = $d->in_postmeter_read;
		if($cur_read == "-1"){
			$cur_read = $meter_status[$d->in_status];
			$creading = $creading ." (Avg)";
		}

		echo '
		<tr>
			<td>'. $j .'</td>
			<td>'. $d->out_cid .'</td>
			<td>'. $d->out_dtrno .'</td>
			<td>'. $d->out_oldcid .'</td>
			<td>'. $d->out_consumer_category .'</td>
			<td>'. $d->out_consumer_name .'</td>
			<td>'. $d->in_consumption_day .'</td>
			<td>'. $d->out_premeter_read .'</td>
			<td>'. $cur_read .'</td>
			<td>'. $creading .'</td>
			<td>'. $d->in_pf .'</td>
			<td>'. $d->in_unit_billed .'</td>
			<td style="width:100px;">'. base64_decode($en) .'</td>
			<td>'. $d->in_energy_amount .'</td>
			<td>'. $d->in_subsidy .'</td>
			<td>'. $d->in_total_energy_charge .'</td>
			<td>'. $d->in_fixed_charge .'</td>
			<td>'. $d->in_meter_rent .'</td>
			<td>'. $d->in_electricity_duty .'</td>
			<td>'. $d->in_fppa_charge .'</td>
			<td>'. $d->in_current_demand .'</td>
			<td>'. $d->out_principal_arrear .'</td>
			<td>'. $d->out_arrear_surcharge .'</td>
			<td>'. $d->in_current_surcharge .'</td>
			<td>'. $d->in_total_arrear .'</td>
			<td>'. $d->in_net_bill_amount .'</td>
		</tr>
		';
		$i++;
	}
}
echo '
</table>
';
?>