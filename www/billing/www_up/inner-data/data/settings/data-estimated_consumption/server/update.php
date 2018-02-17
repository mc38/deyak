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
		$consump = $data[1];
		
		$q = mysql_query("select id from settings_estimated_consumption where cate='".$cate."'");
		if(mysql_num_rows($q)>0){
			$d = mysql_fetch_object($q);
			mysql_query("update settings_estimated_consumption set consump='". $consump ."' where id='". $d->id ."'");
		}
		else{
			mysql_query("insert into settings_estimated_consumption(cate,consump) values('".$cate."','".$consump."')");
		}
		
		echo $_POST['c'];
	}
}
else{
	echo 0;
}
?>