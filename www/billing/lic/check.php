<?php
if(
	isset($_POST['d']) && $_POST['d'] !=""
	&&
	isset($_POST['c']) && $_POST['c'] !=""
){
	$data = json_decode(base64_decode($_POST['d']));
	$l = $data[0];
	$ls = file_get_contents("l.lic");
	$data = 0;
	if($l == $ls){
		$data = 1;
	}
	
	$send = array($_POST['c'],$data);
	echo base64_encode(json_encode($send));
}
?>