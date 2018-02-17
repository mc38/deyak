<?php

require_once("../filter/type2.php");

/////////////////////////////////////////////////////////////////////////////

if(sizeof($consumer_list) >0){
	
	for($i=0;$i<sizeof($consumer_list);$i++){
		$table = $d[$consumer_list[$i]];
		
		$conid = $table->$templete_check[0];
		$bookno = $table->$templete_check[1];
		
		$col = array();						$val = array();
		
		$col[0]="conid";					$val[0]=$table->$templete_check[0];
		$col[1]="book_no";					$val[1]=$table->$templete_check[1];
		$col[2]="conname";					$val[2]=$table->$templete_check[2];
		$col[3]="prevreading";				$val[3]=$table->$templete_check[3];
		$col[4]="Prev_read_date";			$val[4]=$table->$templete_check[4];
		$col[5]="ppunit";					$val[5]=$table->$templete_check[5];
		$col[6]="recep";					$val[6]=$table->$templete_check[6];
		$col[7]="unpaidbill";				$val[7]=$table->$templete_check[7];
		$col[8]="credit";					$val[8]=$table->$templete_check[8];
		$col[9]="AVGUNITCONS";				$val[9]=$table->$templete_check[9];
		
		$col_str = implode(',',$col);		$val_str = implode("','",$val);
		
		if(isset($subdiv_list[$i])){
			$bq = mysql_query("select id from booklist where book ='". $bookno ."' and subdiv='".$subdiv_list[$i]."'");
			if(mysql_num_rows($bq)<1){
				mysql_query("insert into booklist(book,subdiv) values('". $bookno ."','".$subdiv_list[$i]."')");
			}
		}
		
		
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
		
		$q = mysql_query("select id from in_reading_xml where conid='".$conid."' and mydate ='".$idate."'");
		if(mysql_num_rows($q)<1){
			mysql_query("insert into in_reading_xml(mydate,subdiv_id,".$col_str.",link) values('".$idate."','".$subdiv_list[$consumer_list[$i]]."','".$val_str."','".$conid_list[$consumer_list[$i]]."')");
		}
		
		if($i == sizeof($consumer_list)-1){
			echo $_POST['c'];
		}
	}
}
else{
	echo 2;
}
?>