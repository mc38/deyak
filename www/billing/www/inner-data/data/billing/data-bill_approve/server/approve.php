<?php
require_once("../../../../../db/command.php");
require_once("../../../../../plugin/func/authentication.php");
require_once("../../../../../spl_func/approve.php");

if($u = authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		
		$data = json_decode(base64_decode($_POST['d']));
		
		$id = $data[0];
		
		$q = mysql_query("select * from m_data where id='". $id ."' and c_pass_status=0 and in_status<>''");
		if(mysql_num_rows($q) ==1){
			$d = mysql_fetch_object($q);
			
			$bdone = billapprove($d,$u,$datetime);
			if($bdone){
				echo $_POST['c'];
			}
		}
		
	}
}
else{
	echo 0;
}
?>