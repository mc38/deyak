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
		
		$id = $data[0];
		$d = $data[1];
		
		$q = mysql_query("select id from payment_data where id='".$id."'");
		if(mysql_num_rows($q)==1){
			
			$query ="update payment_data set amount='".$d."' where id ='".$id."'";
			mysql_query($query);
			echo $_POST['c'];
		}
	}
}
else{
	echo 0;
}
?>