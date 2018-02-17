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
		
		$id			= $data[0];
		$th 		= $data[1];
		$slab_low 	= $data[2];
		$slab_hgh 	= $data[3];
		
		$slab_ultimate = array($th,$slab_low,$slab_hgh);
		$slab_ultimate_en = base64_encode(json_encode($slab_ultimate));

		mysql_query("update settings_consumer_cate set pfslab='". $slab_ultimate_en ."' where id='". $id ."'");
		
		echo $_POST['c'];
		
	}
}
else{
	echo 0;
}
?>