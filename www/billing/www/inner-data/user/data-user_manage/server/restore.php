<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");
require_once("../../../../plugin/func/index.php");

if(authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		
		$data = json_decode(base64_decode($_POST['d']));
		
		$id = $data[0];
		$q = mysql_query("select id,username from zzuserdata where id='".$id."'");
		if(mysql_num_rows($q) == 1){
			$d = mysql_fetch_object($q);
			
			$us			= strtolower($d->username);
			$pa			= generate_code(10);
			
			$salt = randcode(5);
			$data = $us.$pa."_".$salt;
			
			$hashalgo = hash_algos();
			$r = rand(0,sizeof($hashalgo)-1);
			
			$h = hash($hashalgo[$r],$data);
			$active[]=$us; $active[]=$pa; $acstr = base64_encode(json_encode($active));
			
			mysql_query("update zzuserdata set hashalgo='".$r."',ushashvalue='".$h."',salt='".$salt."',uactive='".$acstr."' where id='".$id."'");
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


function generate_code($len){
	$data="123456789";
	$out="";
	for($i=0;$i<$len;$i++){
		$out = $out .''. substr($data, rand(0, strlen($data)-1),1);
	}
	return $out;
}
?>