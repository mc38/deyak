<?php
include "../../db/command.php";
include "../../plugin/authentication.php";
include "../../plugin/data_transfer.php";
include "../../plugin/rcrypt.php";
include "../../plugin/bill.php";

$dt = new data_transfer();
if($dt->data_receive()){
	$send_data = "";
	
	if($a = authenticate()){
	
		$data = json_decode(base64_decode($_POST['d']));
		
		$str 	= $data[0];
		$t 		= $data[1];
		$tot 	= $data[2];
		
		if(! file_exists("../../../devtest")){
			mkdir("../../../devtest");
		}

		$head = array();
		$head[] = "Consumer No";
		$head[] = "Previous Bill Date";
		$head[] = "Previous Reading";
		$head[] = "Current Bill Date";
		$head[] = "Current Reading";
		$head[] = "Consumed Unit";
		$head[] = "Power Factor";
		$head[] = "New Consumed Unit";
		$head[] = "MF";
		$head[] = "Billed Unit";
		$head[] = "Consumption Day";
		$head[] = "Due Date";
		$head[] = "Energy Breakup";
		$head[] = "Energy Charge";
		$head[] = "Subsidy";
		$head[] = "Total Energy Charge";
		$head[] = "Fixed Charge";
		$head[] = "Meter Rent";
		$head[] = "Electricity Duty";
		$head[] = "FPPPA";
		$head[] = "Current Demand";
		$head[] = "Principal Arrear";
		$head[] = "Arrear Surcharge";
		$head[] = "Current Surcharge";
		$head[] = "Total Arrear";
		$head[] = "Adjustment";
		$head[] = "Net Bill Amount";
		$head[] = "Net Bill Amount After Due Date";

		$head_str = implode(",", $head);
		if($t ==0){
			$str = $head_str . PHP_EOL . $str;
		}
		
		$filename = "../../../devtest/appveribilldata.csvtemp";
		if($t==0 && file_exists($filename)){
			unlink($filename);
		}
		$out = fopen($filename, "a");
		if ( $out ) {
			fwrite($out, $str . PHP_EOL);
			fclose($out);
		}
		
		if($t == $tot -1){
			rename($filename, "../../../devtest/appveribilldata_". date('Y-m-d_h-i-s-a', $datetime) .".csv");
		}

		$send_data = 1;
	}else{
		$send_data = 0;
	}
	
	$dt->data_send($send_data);
}
?>