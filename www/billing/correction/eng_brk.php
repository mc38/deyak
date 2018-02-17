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
		<td>Figure</td>
	</tr>
';
$q = mysql_query("select * from m_data where c_pass_status=1");
$i =0;
while($d = mysql_fetch_object($q)){
	$en = $d->in_energy_brkup;
	$en_arr = json_decode(base64_decode($en));
	//echo base64_decode($en);
	if(sizeof($en_arr) ==3){
		$j = $i +1;

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
			<td>'. base64_decode($en) .'</td>
			<td>'. $d->in_energy_amount .'</td>
			<td>
				 - '. $d->in_subsidy .' = '. $d->in_total_energy_charge .'
				 <hr/>
				 + '. $d->in_fixed_charge .' + '. $d->in_meter_rent .' + '. $d->in_electricity_duty .' + '. $d->in_fppa_charge .' = '. $d->in_current_demand .'
				 <hr/>
				 + '. $d->in_total_arrear .' = '. $d->in_net_bill_amount .'
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