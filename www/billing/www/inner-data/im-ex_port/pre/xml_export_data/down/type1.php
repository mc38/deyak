<?php

$templete[0] 	= "consumer_id";
$templete[1]	= "subdivision_id";
$templete[2] 	= "book_no";
$templete[3] 	= "bill_from_datetime";
$templete[4] 	= "bill_to_datetime";
$templete[5] 	= "previous_reading";
$templete[6] 	= "current_reading";
$templete[7] 	= "unit_consumed";
$templete[8] 	= "reading_date";
$templete[9] 	= "remarks";
$templete[10] 	= "multiplying_factor";
$templete[11] 	= "ppunit";


$q = mysql_query("select * from out_reading_xml where subdivision_id='". $s ."' and mydate='". strtotime($b) ."' and down='0'");
if(mysql_num_rows($q)>0){
	
	$fname = "read_". date('d-m-Y_h-i-s_a',$datetime);
	if(! file_exists("temp/")){
		mkdir("temp");
	}
	
	$dir = "temp/".$fname;
	if(! file_exists($dir)){
		mkdir($dir);
	}
	
	$buff = 5;
	
	$j =1;
	while($datad = mysql_fetch_object($q)){
		
		$pos = $j-1;
		if(($pos % $buff) == 0){
			$xml = new DOMDocument("1.0");
			$root = $xml->createElement("data");
			$xml->appendChild($root);
		}
		/////////////////////////////////////////////
			$table = $xml->createElement("table");
			for($k=0; $k <sizeof($templete);$k++){
				$tdoc = $xml->createElement($templete[$k]);
				$data = $datad->$templete[$k];
				if($k==3 || $k ==4 || $k==8){
					$data = date('d-M-Y',strtotime($data));
				}else if($k == 11){
					if($data<0){
						$data = 0;
					}
				}
				$d = $xml->createTextNode($data);
				$tdoc->appendChild($d);
				$table->appendChild($tdoc);
			}
			$root->appendChild($table);
		///////////////////////////////////////////////
		if(($pos % $buff) == $buff-1){
			$xml->formatOutput = true;
			$fno = $j/$buff;
			$ffrm = (($fno -1) * $buff) +1;
			$xml->save($dir ."/read_". $fno ."-". $ffrm ."_to_". $j .".xml");
		}
		
		$j++;
				
	}
	if(($pos % $buff < $buff-1) && ($pos % $buff >=0)){
		$xml->formatOutput = true;
		$j = $j-1;
		$fno = (int)($j/$buff)+1;
		$ffrm = (($fno-1) * $buff) +1;
		$xml->save($dir ."/read_". $fno ."-". $ffrm ."_to_". $j .".xml");
	}
		
	//////////////////////////zip file///////////////////////////////////////////////////		
	// Get real path for our folder
	$rootPath = realpath($dir);
	
	// Initialize archive object
	$zip = new ZipArchive();
	$zip->open($dir.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
	
	// Create recursive directory iterator
	/** @var SplFileInfo[] $files */
	$files = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($rootPath),
		RecursiveIteratorIterator::LEAVES_ONLY
	);
	
	foreach ($files as $name => $file)
	{
		// Skip directories (they would be added automatically)
		if (!$file->isDir())
		{
			// Get real and relative path for current file
			$filePath = $file->getRealPath();
			$relativePath = substr($filePath, strlen($rootPath) + 1);
	
			// Add current file to archive
			$zip->addFile($filePath, $relativePath);
		}
	}
	
	// Zip archive will be created only after closing object
	$zip->close();
	/////////////////////////////////////////////////////////////////////////////////////////
	deleteDir($dir);
	
	$fhandle = fopen("temp/".$fname.".php",'w');
	$xdir = dirname(__FILE__);
	$xmlcode = file_get_contents($xdir."/xmlcode.txt");
	file_put_contents("temp/". $fname .".php",$xmlcode);
	fclose($fhandle);
	
	echo '<script>function ddown(){window.location.href = "temp/'.$fname.'.php?fname='.$fname.'";}</script><body onload="ddown();"></body>';			
	
}
else{
	echo '<h1>Error :: No data available for download</h1>';
}
?>