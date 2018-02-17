<?php
if(isset($fname) && $fname !="" && isset($fdata) && $fdata !=""  && isset($fpart) && $fpart !="")
{
	$target_file	= $destination['image'] . $fname;
	$in				= $fdata;
	if((int)$fpart == 0){
		if(file_exists($target_file)){
			unlink($target_file);
		}
	}
	
	// Open temp file
	$out = fopen($target_file, "a");
	
	if ( $out ) {
		header('Content-Type: bitmap; charset=utf-8');
		fwrite($out, $in);
		fclose($out);
	}
}
	

?>