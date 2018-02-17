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
		
		$cate = $data[0];
		
		$out = "";
		$q = mysql_query("select consump from settings_estimated_consumption where cate='".$cate."'");
		if(mysql_num_rows($q)>0){
			$d = mysql_fetch_object($q);
			$out = $d->consump;
		}
		
		$dd = base64_encode(json_encode(array($_POST['c'],$out)));
		echo $dd;
	}
	else{
		echo 1;
	}
}
else{
	echo 0;
}
?>