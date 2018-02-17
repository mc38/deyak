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
		
		$t 			= $data[0];
		if($t == 0){
			$id 		= $data[1];
			$eduty 		= $data[2];
			$schrg 		= $data[3];
			$fppa		= $data[4];
		
			$update = array();
			if($eduty !=""){
				$update[] = "electricity_duty='". $eduty ."'";
			}
			if($schrg !=""){
				$update[] = "surcharge='". $schrg ."'";
			}
			if($fppa !=""){
				$update[] = "fppa='". $fppa ."'";
			}

			if(sizeof($update)>0){
				$update_str = implode(",", $update);
				mysql_query("update settings_consumer_cate set ". $update_str ." where id='". $id ."'");
			}
		}else if($t == 1){
			$id			= $data[1];
			$slab 		= $data[2];
			$slab_spl 	= $data[3];
		
			$slab_spl_t = json_decode(base64_decode($slab_spl));
			$slab_spl_lastu = 0;
			if(sizeof($slab_spl_t)>0){
				$slab_spl_lastu = $slab_spl_t[sizeof($slab_spl_t)-1][1];
			}
			$slab_ultimate = array($slab_spl_lastu,$slab_spl,$slab);
			$slab_ultimate_en = base64_encode(json_encode($slab_ultimate));

			mysql_query("update settings_consumer_cate set slab='". $slab_ultimate_en ."' where id='". $id ."'");
		}
		echo $_POST['c'];
		
	}
}
else{
	echo 0;
}
?>