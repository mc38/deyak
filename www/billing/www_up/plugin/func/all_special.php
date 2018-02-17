<?php
function numarr_to_strcode($input){
	$output="";
	for($i=0;$i<sizeof($input);$i++){
		$output = $output ."&#".$input[$i].";"; 
	}
	return $output;
}

function numarr_to_str($input){
	$output="";
	for($i=0;$i<sizeof($input);$i++){
		$output = $output ."". chr($input[$i]); 
	}
	return $output;
}

function filebackup($link,$name){
	if(!file_exists($link."backup/")){
		 mkdir($link."backup", 0777);
	}
	if(file_exists($link.$name)){
		$data = file_get_contents($link.$name);
		file_put_contents($link."backup/".$name,$data);
		date_default_timezone_set('Asia/Kolkata');
		$fdatetime=date($_SERVER['REQUEST_TIME']);
		file_put_contents($link."backup/".$name.".t",$fdatetime);
	}
	else{
		return false;
	}
}

function randcode($l){
	$data = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$out="";
	for($i=0;$i<$l;$i++){
		$out = $out . substr($data,rand(0,strlen($data)-1),1);
	}
	return $out;
}

/**
 * Copy a file, or recursively copy a folder and its contents
 * @param       string   $source    Source path
 * @param       string   $dest      Destination path
 * @param       string   $permissions New folder creation permissions
 * @return      bool     Returns true on success, false on failure
 */
function xcopy($source, $dest, $permissions = 0755)
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest, $permissions);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        xcopy("$source/$entry", "$dest/$entry");
    }

    // Clean up
    $dir->close();
    return true;
}
 

  function deleteDir($dir) {
  if (is_dir($dir)) {
    $objects = scandir($dir);
    foreach ($objects as $object) {
      if ($object != "." && $object != "..") {
        if (filetype($dir."/".$object) == "dir") 
           deleteDir($dir."/".$object); 
        else unlink   ($dir."/".$object);
      }
    }
    reset($objects);
    rmdir($dir);
  }
 }

function currency_round($in){
	$d = ceil($in * 100);
	return $d/100;
}


?>