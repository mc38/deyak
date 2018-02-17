<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	if(! file_exists("temp/")){
		mkdir("temp");
	}
	
	date_default_timezone_set('Asia/Kolkata');
	$datetime=date($_SERVER['REQUEST_TIME']);
	
	//////////////////////
	$fname = $_FILES['file']['name'];
	
	$destination = "../../../../../file/image/data/".$fname ;
	//$destination = "temp/".$fname ;
	
	if(! file_exists($destination)){
		$q = mysql_query("select id from m_data where in_meterpic='". $fname ."'");
		if(mysql_num_rows($q) ==1){
			$tmp_name = $_FILES['file']['tmp_name'];
			$target_file = $destination;
			// Open temp file
			$out = fopen($target_file, "a");
			
			if ( $out ) {
				// Read binary input stream and append it to temp file
				$in = fopen($tmp_name, "rb");
				if ( $in ) {
					while ( $buff = fread( $in, 1048576 ) ) {
						fwrite($out, $buff);
					}   
				}
				fclose($in);
				fclose($out);
			}

			//print_r($_SERVER);

			echo 1;
		}else{
			echo 3;
		}
	}else{
		echo 2;
	}
}else{
	echo 0;
}	
?>