<?php
require_once("../filter/type1.php");
////////////////////////////////////////////////////////////////////////////////////////

if(sizeof($nconsumer_list)>0){
	for($i=0;$i<sizeof($nconsumer_list);$i++){
		$table = $d[$nconsumer_list[$i]];
		
		$conid = $table->$templete_check[0];
		
		$col = array();						$val = array();
		
		$col[0]="CON_ID";					$val[0]=$table->$templete_check[0];
		$col[1]="NAME";						$val[1]=$table->$templete_check[1];
		$col[2]="BOOK_NO";					$val[2]=$table->$templete_check[2];
		$col[3]="RURAL_URBAN";				$val[3]=$table->$templete_check[3];
		$col[4]="METER_NO";					$val[4]=$table->$templete_check[4];
		$col[5]="CAPACITY";					$val[5]=$table->$templete_check[5];
		$col[6]="SUB_DIV_ID";				$val[6]=$table->$templete_check[6];
		$col[7]="SUBDIVISIONNAME";			$val[7]=$table->$templete_check[7];
		$col[8]="ADDRESS";					$val[8]=$table->$templete_check[8];
		$col[9]="MULTI_FACT";				$val[9]=$table->$templete_check[9];
		$col[10]="PHASE";					$val[10]=$table->$templete_check[10];
		$col[11]="KW_HP_KV";				$val[11]=$table->$templete_check[11];
		$col[12]="CONC_LOAD";				$val[12]=$table->$templete_check[12];
		$col[13]="MET_OWNER";				$val[13]=$table->$templete_check[13];
		$col[14]="METERTYPE";				$val[14]=$table->$templete_check[14];
		$col[15]="METERSEALNO";				$val[15]=$table->$templete_check[15];
		$col[16]="TARIFF_ID";				$val[16]=$table->$templete_check[16];
		
		$col_str = implode(',',$col);		$val_str = implode("','",$val);
		
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
		
		$q = mysql_query("select id from in_consumer_xml where CON_ID='". $conid ."'");
		if(mysql_num_rows($q)==0){
			mysql_query("insert into in_consumer_xml(".$col_str.") values('".$val_str."')");
		}
		
		if($i == sizeof($nconsumer_list)-1){
			echo $_POST['c'];
		}
		
	}
}
else{
	echo 2;
}


?>