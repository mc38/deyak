<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		
		$data = json_decode(base64_decode($_POST['d']));
		
		$cate = strtolower($data[0]);
		
		$q = mysql_query("select id from zzauth where name='".$cate."'");
		if(mysql_num_rows($q)>0){
			echo 1;
		}
		else{
			mysql_query("insert into zzauth(name) values('".$cate."')");
			echo $_POST['c'];
		}
	}
}
else{
	echo 0;
}
?>