<?php

class data_transfer{
	
	var $c;
	
	function data_receive(){
		$r = false;
		if(
			(isset($_POST['c']) && $_POST['c'] !="")
			&&
		   	(isset($_POST['d']) && $_POST['d'] !="")
		){
			$this->c = $_POST['c'];
			$r = true;
		}
		return $r;
	}
	
	
	function data_send($data){
		$send = array($this->c,$data);
		echo base64_encode(json_encode($send));
	}
	
	
}

?>