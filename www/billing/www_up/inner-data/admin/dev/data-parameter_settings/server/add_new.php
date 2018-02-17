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
		
		$i = $data[0];
		$p = strtoupper($data[1]);
		$v = $data[2];
		
		$q = mysql_query("select id from zzdev where id='".$i."'");
		if(mysql_num_rows($q)==1){
			mysql_query("update zzdev set value='".$v."' where id='".$i."'");
		}
		else{
			mysql_query("insert into zzdev(parameter,value) values('".$p."','".$v."')");
		}
		echo $_POST['c'];
	}
}
else{
	echo 0;
}
?>