<?php
include "www/db/command.php";

$q = mysql_query("select * from m_data where in_status<>''");
while($d = mysql_fetch_object($q)){

	$unit = $d->in_unit_billed;
	$cday = $d->in_consumption_day;

	$slab_choose = array();
	$slab = json_decode(base64_decode($d->out_slab));
	if($unit > $slab[0]){
		$slab_choose = json_decode(base64_decode($slab[2]));
	}else{
		$slab_choose = json_decode(base64_decode($slab[1]));
	}


	/* Energy */
	$fixed_charge_basic = 0;
	$subsidy = 0;
	$energy_charge_list = array();

	$unit_remain = $unit;
	for($i=0;$i<sizeof($slab_choose);$i++){
		$
	}
	

	$energy_brkup = $d->in_energy_brkup;
	$en_b = json_decode(base64_decode($energy_brkup));
	print_r($en_b);
	echo '<hr/>';
	$energy_charge 	= $d->in_energy_charge;
	//$subsidy		= $unit * 

}
?>