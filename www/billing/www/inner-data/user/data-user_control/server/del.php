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
		
		$u 		= $data[0];
		$a 		= $data[1];
		
		$uq = mysql_query("select id,access from zzuserdata where id='".$u."'");
		if(mysql_num_rows($uq)==1){
			
			$aq = mysql_query("select id from zzauth where id='".$a."'");
			if(mysql_num_rows($aq)==1){
				
				$ud = mysql_fetch_object($uq);
				$ad = mysql_fetch_object($aq);
				
				$accesslist = array();
				if($accesslist !=""){
					$accesslist = json_decode(base64_decode($ud->access));
				}
				
				if(in_array($ad->id,$accesslist)){
					
					array_splice($accesslist,array_search($ad->id,$accesslist),1);
					$access_str = base64_encode(json_encode($accesslist));
					
					mysql_query("update zzuserdata set access='". $access_str ."' where id='". $ud->id ."'");
					
					echo $_POST['c'];
				}
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
?>