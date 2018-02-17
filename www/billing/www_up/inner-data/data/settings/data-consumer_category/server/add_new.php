<?php
require_once("../../../../../db/command.php");
require_once("../../../../../plugin/func/authentication.php");
if(authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		
		$data = json_decode(base64_decode($_POST['d']));
		
		$name 		= strtoupper($data[0]);
		$tariff 	= $data[1];
		$slab 		= $data[2];
		$slab_spl 	= $data[3];
		$eduty 		= $data[4];
		$schrg 		= $data[5];
		$fppa		= $data[6];
		
		$slab_spl_t = json_decode(base64_decode($slab_spl));
		$slab_spl_lastu = 0;
		if(sizeof($slab_spl_t)>0){
			$slab_spl_lastu = $slab_spl_t[sizeof($slab_spl_t)-1][1];
		}
		$slab_ultimate = array($slab_spl_lastu,$slab_spl,$slab);
		$slab_ultimate_en = base64_encode(json_encode($slab_ultimate));
		
		mysql_query("insert into settings_consumer_cate(name,slab,tariff_id,electricity_duty,surcharge,fppa) values('".$name."','".$slab_ultimate_en."','".$tariff."','". $eduty ."','". $schrg ."','". $fppa ."')");
		echo $_POST['c'];
		
	}
}
else{
	echo 0;
}
?>