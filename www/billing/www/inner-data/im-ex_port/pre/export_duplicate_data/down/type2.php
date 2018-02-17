<?php

$templete[0] 	= "consumer_id";
$templete[1] 	= "subdivision_id";
$templete[2] 	= "book_no";
$templete[3] 	= "tariff_id";

$templete[4] 	= "bill_from_datetime";
$templete[5] 	= "bill_to_datetime";
$templete[6] 	= "bill_datetime";
$templete[7] 	= "bill_generate_datetime";
$templete[8] 	= "bill_due_datetime";

$templete[9] 	= "previous_reading";
$templete[10] 	= "current_reading";
$templete[11] 	= "billed_unit";

$templete[12] 	= "energy_charge";
$templete[13] 	= "fixed_charge";
$templete[14] 	= "meter_rent";
$templete[15] 	= "other_charge";
$templete[16] 	= "diseal_charge";
$templete[17] 	= "fuel_charge_rate";
$templete[18] 	= "fuel_charge";
$templete[19] 	= "gross_charge";
$templete[20] 	= "rebate_charge";
$templete[21] 	= "credit_adjustment";
$templete[22] 	= "net_charge";
$templete[23] 	= "old_ec";
$templete[24] 	= "old_uc";
$templete[25] 	= "sundry";
$templete[26] 	= "n_rate";

$templete[27] 	= "bill_no";
$templete[28] 	= "energy_charge_breakup";


$q = mysql_query("select * from out_bill_xml where subdivision_id='". $s ."' and mydate='". strtotime($b) ."' and down='1' order by id limit ". $f .",".$t);
if(mysql_num_rows($q)>0){
	
	$fname = "bill_". date('d-m-Y_h-i-s_a',$datetime);
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
				if($k == 10){
					if(ctype_digit($datad->$templete[$k])){
						$d = $xml->createTextNode($datad->$templete[$k]);
					}
					else{
						$d = $xml->createTextNode("-1");
					}
				}
				else if($k==4 || $k ==5 || $k ==6 || $k ==7 || $k ==8){
					$data = $datad->$templete[$k];
					$data = date('d-M-Y',strtotime($data));
					$d = $xml->createTextNode($data);
				}
				else{
					$d = $xml->createTextNode($datad->$templete[$k]);
				}
				$tdoc->appendChild($d);
				$table->appendChild($tdoc);
			}
			$root->appendChild($table);
		///////////////////////////////////////////////
		if(($pos % $buff) == $buff-1){
			$xml->formatOutput = true;
			$fno = $j/$buff;
			$ffrm = (($fno -1) * $buff) +1;
			$xml->save($dir ."/bill_". $fno ."-". $ffrm ."_to_". $j .".xml");
		}
		
		$j++;
				
	}
	if(($pos % $buff < $buff-1) && ($pos % $buff >=0)){
		$xml->formatOutput = true;
		$j = $j-1;
		$fno = (int)($j/$buff)+1;
		$ffrm = (($fno-1) * $buff) +1;
		$xml->save($dir ."/bill_". $fno ."-". $ffrm ."_to_". $j .".xml");
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
	///////////////////////////////////////////////////////////////////////////////////////
	
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