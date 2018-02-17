<?php
if(isset($_GET) && isset($_GET['i']) && $_GET['i']!=""){
	$file = $_GET['i'];
	$f = new SplFileInfo($file);
	$ext = strtolower($f->getExtension());
	switch($ext){
		case "jpg":
			$type = 'image/jpeg';
			break;
		case "jpeg":
			$type = 'image/jpeg';
			break;
		case "png":
			$type = 'image/png';
			break;
		case "gif":
			$type = 'image/gif';
			break;
		default:
			$type = 'image/jpeg';
			break;
	}
	
	$path = "image/data/".$file;
	if(file_exists($path)){
		header('Content-Type:'.$type);
		header('Content-Length: ' . filesize($path));
		readfile($path);
	}
}
?>