<?php
	
	if(! file_exists("temp/")){
		mkdir("temp");
	}
	
	date_default_timezone_set('Asia/Kolkata');
	$datetime=date($_SERVER['REQUEST_TIME']);
	
	
	$tdate = date('Y-m-d',$datetime);
	if(! file_exists("temp/".$tdate)){
		mkdir("temp/".$tdate);
	}
	///////////////////
	
	
	$destination = "temp/".$tdate."/".$_GET['file']."";
	
	$tmp_name = $_FILES['file_upload']['tmp_name'];
	$part =(string)$_GET['num'];
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
	

?>