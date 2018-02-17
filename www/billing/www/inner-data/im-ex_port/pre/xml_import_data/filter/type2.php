<?php

$templete_check[0]="conid";
$templete_check[1]="book_no";
$templete_check[2]="conname";
$templete_check[3]="prevreading";
$templete_check[4]="Prev_read_date";
$templete_check[5]="ppunit";
$templete_check[6]="recep";
$templete_check[7]="unpaidbill";
$templete_check[8]="credit";
$templete_check[9]="AVGUNITCONS";


$templete[0]=$templete_check[0];
$templete[1]=$templete_check[1];
$templete[2]=$templete_check[2];
$templete[3]=$templete_check[3];
$templete[4]=$templete_check[5];
$templete[5]=$templete_check[6];
$templete[6]=$templete_check[7];
$templete[7]=$templete_check[8];
$templete[8]=$templete_check[4];
$templete[9]=$templete_check[9];


$total_data = sizeof($data);
$accepted_data =0;

$aborted_data=array();

$consumer_list=array();		$temp_con = array();
$conid_list = array();
$subdiv_list = array();

$consumer_error = array();

for($i=0;$i<sizeof($data);$i++){
	$d = $data->Table1;
	$table = $d[$i];
	
	$keys = array_keys((array)$table);
	///////////////////////////////////check xml format///////////////////////////////////////////
	if((sizeof($keys) == sizeof($templete_check)) && ($keys === $templete_check)){
		$accepted_data ++;
		
		///////////consumer id//////////////
		$conid 		= $table->$templete[0];
		
		$mon = (int) date('m',$datetime);
		$day = (int) date('d',$datetime);
		$year = (int) date('Y',$datetime);
		
		if($day>15){
			$mon ++;
			if($mon>12){
				$mon =1;
				$year++;
			}
		}
		
		$idate = strtotime("01-".$mon."-".$year);
		
		$cq = mysql_query("select id,SUB_DIV_ID from in_consumer_xml where CON_ID = '". $conid ."' and mydate='".$idate."'");
		if(mysql_num_rows($cq) ==1){
			
			$cd = mysql_fetch_object($cq);
			
			$rq = mysql_query("select id from in_reading_xml where conid='".$conid."' and mydate ='".$idate."'");
			if(mysql_num_rows($rq) == 0){
				$consumer_list[]=$i;
				$conid_list[$i]=$cd->id;
				$subdiv_list[$i]=$cd->SUB_DIV_ID;
			}
			
		}
		else{
			$consumer_error[]=$i;
		}
	}
	else{
		$aborted_data[]=$i;
	}
}


?>