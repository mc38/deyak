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
		
		$id = $data[0];
		$q = mysql_query("select id,status from zzuserdata where id='".$id."'");
		if(mysql_num_rows($q) == 1){
			
			mysql_query("delete from zzuserdata where id='".$id."'");
			echo $_POST['c'];
		}
		else{
			echo 1;
		}
	}
}
else{
	echo 0;
}
?>