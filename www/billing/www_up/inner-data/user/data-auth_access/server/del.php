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
		
		$a 		= $data[0];
		$p 		= $data[1];
		
		$aq = mysql_query("select id,access from zzauth where id='".$a."'");
		if(mysql_num_rows($aq)==1){
			
			$pq = mysql_query("select id from zzpage where id='".$p."'");
			if(mysql_num_rows($pq)==1){
				
				$ad = mysql_fetch_object($aq);
				$pd = mysql_fetch_object($pq);
				
				$accesslist = array();
				if($accesslist !=""){
					$accesslist = json_decode(base64_decode($ad->access));
				}
				
				if(in_array($pd->id,$accesslist)){
					
					array_splice($accesslist,array_search($pd->id,$accesslist),1);
					$access_str = base64_encode(json_encode($accesslist));
					
					mysql_query("update zzauth set access='". $access_str ."' where id='". $ad->id ."'");
					
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