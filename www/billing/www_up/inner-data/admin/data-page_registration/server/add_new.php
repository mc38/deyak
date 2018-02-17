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
		
		$t = $data[0];
		$n = $data[1];
		$l = $data[2];
		
		$q = mysql_query("select id from zzpagetag where id='". $t ."'");
		if(mysql_num_rows($q)>0){
			
			$q = mysql_query("select id from zzpage where lower(name)='". strtolower($n) ."' and link='".$t."'");
			if(mysql_num_rows($q)>0){
				echo 2;
			}else{
				
				$l_arr = explode('.',$l);
				$dir = dirname(__FILE__);
				$lm = str_replace("\\","/",$dir) . "/../../../../".$l;
				if(file_exists($lm) && sizeof($l_arr)==2 && strtolower($l_arr[1])=="php" ){
					
					$dq = mysql_query("insert into zzpage(name,location,link) values('".$n."','".$l."','".$t."')");
					if($dq){
						$iid = mysql_insert_id();
						if($iid %100 == 0){
							$iid ++;
						}
						
						$ddq = mysql_query("update zzpage set id='".$iid."', srl='".$iid."' where id='".mysql_insert_id()."'");
						if($ddq){
							echo $_POST['c'];
						}
					}
				}else{
					echo 3;
				}
			}
		}else{
			echo 1;
		}
	}
}
else{
	echo 0;
}
?>