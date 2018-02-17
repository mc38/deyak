<?php
require_once("../filter/type.php");
////////////////////////////////////////////////////////////////////////////////////////

if(sizeof($data_ok)>0){
	for($i=0;$i<sizeof($data_ok);$i++){
		$d = explode('$',$data[$data_ok[$i]]);

		if($d[7] ==""){$d[7] = "0";}

		$subdiv 		= (int) $d[0];
		$dtrno 			= (int) $d[8];
		$oldcon 		= $d[21];
		$conno 			= $d[1];
		$conname 		= strtoupper($d[2]);
		$conaddress 	= strtoupper($d[3]);
		$meterno 		= strtoupper($d[12]);
		$metertype		= $meter[$d[7]];	
		
		$connload 		= (float)$d[4];
		$category 		= $ccate[$d[5]];
		$mfactor		= (float)$d[13];
		$avgunit		= (float)$d[14]; if($avgunit<0){$avgunit = 0;}
		
		$prevreading 	= $d[11];
		$prevbilldate 	= substr($d[10],0,4) ."-". substr($d[10],4,2) ."-". substr($d[10],6,2);

		$parrear 		= (float)$d[15]; // need to check arrear
		$arrsurchrg 	= (float)$d[16];
		$cs_pa			= (float)$d[17]; // Calc CS
		$adjust			= (float)$d[18]; if($adjust <0){$adjust = 0;}

		$due_date		= $d[20];
		$pre_mstatus 	= $d[22];
		

		$col 	= array();						$coldata 		= array();
		$col[] 	= "datetime";					$coldata[]		= $datetime;
		$col[] 	= "subdivision_id";				$coldata[]		= $subdiv;
		$col[] 	= "dtr_no";						$coldata[]		= $dtrno;
		$col[] 	= "old_consumer_no";			$coldata[]		= $oldcon;
		$col[] 	= "consumer_no";				$coldata[]		= $conno;
		$col[] 	= "consumer_name";				$coldata[]		= $conname;
		$col[] 	= "consumer_address";			$coldata[]		= $conaddress;
		$col[] 	= "meter_no";					$coldata[]		= $meterno;
		$col[] 	= "connected_load";				$coldata[]		= $connload;
		$col[] 	= "multiplying_factor";			$coldata[]		= $mfactor;
		$col[] 	= "consumer_category_code";		$coldata[]		= $category;
		$col[]	= "meter_type";					$coldata[]		= $metertype;

		$col[] 	= "previous_reading";			$coldata[]		= $prevreading;
		$col[] 	= "previous_bill_date";			$coldata[]		= $prevbilldate;
		$col[] 	= "previous_bill_datetime";		$coldata[]		= strtotime($prevbilldate);

		$col[] 	= "principle_arrear";			$coldata[]		= $parrear;
		$col[] 	= "arrear_surcharge";			$coldata[]		= $arrsurchrg;
		$col[] 	= "adjustment";					$coldata[]		= $adjust;
		$col[] 	= "avg_unit";					$coldata[]		= $avgunit;

		$col[] 	= "due_date";					$coldata[]		= $due_date;
		$col[] 	= "due_datetime";				$coldata[] 		= strtotime(str_replace('/','-',$due_date));
		$col[] 	= "pre_meterstatus";			$coldata[] 		= $pre_mstatus;
		$col[] 	= "cs_pa";						$coldata[] 		= $cs_pa;
		
		$colstr		= implode(',',$col);		$coldatastr		= implode("','",$coldata);
		
		
		$q = mysql_query("select id from in_data_queue where consumer_no='".$conno."'");
		if(mysql_num_rows($q)==0){
			mysql_query("insert into in_data_queue(". $colstr .") values('". $coldatastr ."')");
			$i_id = mysql_insert_id();
			mysql_query("insert into temp_in_data_queue(qid,". $colstr .") values('". $i_id ."','". $coldatastr ."')");
		}
		
		if($i == sizeof($data_ok)-1){
			echo $_POST['c'];
		}
		
	}
}
else{
	echo 2;
}


?>