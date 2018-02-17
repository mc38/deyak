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
		
		$srl = $data[0];
		$id = $data[1];
		
		$q = mysql_query("select id,srl from zzpage where srl='".$srl."' and link='".$id."'");
		if($q && mysql_num_rows($q) == 1){
			$d = mysql_fetch_object($q);
			
			$nq = mysql_query("SELECT id,srl FROM zzpage WHERE srl < '".$srl."' and link='".$id."' ORDER BY srl DESC LIMIT 1");
			if($nq && mysql_num_rows($nq)>0){
				$nd = mysql_fetch_object($nq);
				
				mysql_query("update zzpage set srl=".$nd->srl." where id='".$d->id."' and link='".$id."'");
				mysql_query("update zzpage set srl=".$d->srl." where id='".$nd->id."' and link='".$id."'");
				echo $_POST['c'];
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