<?php
require_once("../../../../../db/command.php");
require_once("../../../../../plugin/func/authentication.php");

if($u = authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		
		$data = json_decode(base64_decode($_POST['d']));
		
		$id 			= $data[0];
		$oldcon 		= $data[1];						//03
		$conname 		= strtoupper($data[2]);			//05
		$conaddress 	= strtoupper($data[3]);			//06
		$meterno 		= strtoupper($data[4]);			//07
		$connload 		= $data[5];						//08
		$mfactor 		= $data[6];						//09
		$category 		= $data[7];						//10
		$metertype		= $data[8];						//11

		$prevreading 	= $data[9];						//12
		$prevbilldate 	= $data[10];					//13

		$parrear 		= $data[11];					//15
		$arrsurchrg 	= $data[12];					//16
		$adjust			= $data[13];					//18

		$avgunit		= $data[14];					//19
		$duedate 		= $data[15]; 					//20
		
		$col 	= array();						$coldata 	= array();
		$col[] 	= "old_consumer_no";			$coldata[]	= $oldcon;									//03
		$col[] 	= "consumer_name";				$coldata[]	= $conname;									//05
		$col[] 	= "consumer_address";			$coldata[]	= $conaddress;								//06
		$col[] 	= "meter_no";					$coldata[]	= $meterno;									//07
		$col[] 	= "connected_load";				$coldata[]	= $connload;								//08
		$col[]	= "multiplying_factor";			$coldata[]	= $mfactor;									//09
		$col[] 	= "consumer_category_code";		$coldata[]	= $category;								//10
		$col[]	= "meter_type";					$coldata[]	= $metertype;								//11

		$col[] 	= "previous_reading";			$coldata[]	= $prevreading;								//12
		$col[] 	= "previous_bill_date";			$coldata[]	= $prevbilldate;							//13_0
		$col[] 	= "previous_bill_datetime";		$coldata[]	= strtotime($prevbilldate);					//13_1

		$col[] 	= "principle_arrear";			$coldata[]	= $parrear;									//15
		$col[] 	= "arrear_surcharge";			$coldata[]	= $arrsurchrg;								//16
		$col[] 	= "adjustment";					$coldata[]	= $adjust;									//18

		$col[] 	= "avg_unit";					$coldata[]	= $avgunit;									//19

		$col[] 	= "due_date"; 					$coldata[] 	= date('d-M-y',strtotime($duedate));		//20_1
		$col[] 	= "due_datetime"; 				$coldata[] 	= strtotime($duedate);						//20_2
		
		$colstr = array();
		for($i=0;$i<sizeof($col);$i++){
			$colstr[$i]= $col[$i] ."='". $coldata[$i] ."'";
		}
		$cols = implode(',',$colstr);
		
		$q = mysql_query("select id from in_data_queue where id='".$id."' and status='0'");
		if(mysql_num_rows($q)<1){
			echo 1;
		}
		else{
			mysql_query("update in_data_queue set ". $cols .",byuser='". $u ."',importtype='1' where id='". $id ."'");
			echo $_POST['c'];
		}
	}
}
else{
	echo 0;
}
?>