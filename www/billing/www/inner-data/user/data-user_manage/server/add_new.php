<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");
require_once("../../../../plugin/func/index.php");

if($u = authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		
		$data = json_decode(base64_decode($_POST['d']));
		
		$auth 		= $data[0];
		$fname 		= strtolower($data[1]);
		$lname 		= strtolower($data[2]);
		$contact 	= $data[3];
		$sex 		= $data[4];
		$us			= strtolower($data[5]);
		$sid		= $data[6];
		
		$aq = mysql_query("select * from zzauth where id='".$auth."'");
		if(mysql_num_rows($aq)==1){
			
			$ad = mysql_fetch_object($aq);
			
			$cq = mysql_query("select id from zzuserdata where username='".$us."'");
			if(mysql_num_rows($cq)<1){
			
				$pa = generate_code(10);
				
				$salt = randcode(5);
				$data = $us.$pa."_".$salt;
				
				$hashalgo = hash_algos();
				$r = rand(0,sizeof($hashalgo)-1);
				
				$h = hash($hashalgo[$r],$data);
				$active[]=$us; $active[]=$pa; $acstr = base64_encode(json_encode($active));
				
				mysql_query("insert into zzuserdata(name, username, hashalgo, ushashvalue, salt, uactive, fname, lname, contact ,sex, auth, byuser) values('". $ad->name ."', '". $us ."', '". $r ."', '". $h ."', '". $salt ."', '". $acstr ."', '". $fname ."', '". $lname ."', '". $contact ."', '". $sex ."', '". $auth ."', '". $u ."')");
				mysql_query("insert into zzuser_subdiv(uid,sid) values('". mysql_insert_id() ."','". $sid ."')");
				
				echo $_POST['c'];
			}
			else{
				echo 2;
			}
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