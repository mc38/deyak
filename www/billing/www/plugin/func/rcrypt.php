<?php
class rcrypt{

	var $key_pub;
	var $key_pvt;
	
	
	
	function createkey($length){
		if($length >0){
			$key = $this->random_code($length);
			return $key;
		}
		else{
			return false;
		}
	}
	
	function getrkey($key){
		if($key !=""){
			$ktemp = str_split($key);
			for($i=0;$i<sizeof($ktemp);$i++){
				$ktemp[$i] = ~ $ktemp[$i];
			}
			
			return base64_encode(utf8_encode(implode("",$ktemp)));
		}
		else{
			return false;
		}
	}
	
	function rencode($key,$data){
		if($key !="" && $data !=""){
			
			$ktemp = str_split($key);
			$dtemp = str_split($data);
			
			$klen = sizeof($ktemp);
			$dlen = sizeof($dtemp);
			
			$t = (int)($dlen/$klen);
			$r = (int) ($dlen % $klen);
			
			for($i=0;$i<$t;$i++){
				for($j=0;$j<$klen;$j++){
					$dtemp[($i*$klen)+$j] = ~($ktemp[$j] ^ $dtemp[($i*$klen)+$j]);
				}
			}
			
			for($i=0;$i<$r;$i++){
				$dtemp[($t*$klen)+$i] = ~($ktemp[$i] ^ $dtemp[($t*$klen)+$i]);
			}
			return base64_encode(utf8_encode(implode("",$dtemp)));
		}
		else{
			return false;
		}
	}
	
	function rdecode($key,$data){
		if($key !="" && $data !=""){
			
			$ktemp = str_split(utf8_decode(base64_decode($key)));
			$dtemp = str_split(utf8_decode(base64_decode($data)));
			
			$klen = sizeof($ktemp);
			$dlen = sizeof($dtemp);
			
			$t = (int)($dlen/$klen);
			$r = (int) ($dlen % $klen);
			
			for($i=0;$i<$t;$i++){
				for($j=0;$j<$klen;$j++){
					$dtemp[($i*$klen)+$j] = ($ktemp[$j] ^ $dtemp[($i*$klen)+$j]);
				}
			}
			
			for($i=0;$i<$r;$i++){
				$dtemp[($t*$klen)+$i] = ($ktemp[$i] ^ $dtemp[($t*$klen)+$i]);
			}
			
			return implode("",$dtemp);
		}
		else{
			return false;
		}
	}
	
	//Supporting funcitons
	private function random_code($l){
		$data = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$out="";
		for($i=0;$i<$l;$i++){
			$out = $out . substr($data,rand(0,strlen($data)-1),1);
		}
		return $out;
	}
	

}
?>