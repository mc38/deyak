<?php
include "../../db/command.php";
include "../../plugin/authentication.php";

if($a = authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		
		echo $_POST['d'];
		
	}else{
		echo 1;
	}
}else{
	echo 0;
}
?>