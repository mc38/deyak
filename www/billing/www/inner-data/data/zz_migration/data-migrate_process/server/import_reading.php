<?php
		
	$prevread		= $d->previous_reading;
	$prebilldate	= $d->previous_bill_datetime;
	$avg_unit		= $d->avg_unit;
	$premstatus		= $d->pre_meterstatus;
	
	$ecq = mysql_query("select * from settings_estimated_consumption where cate='". $category ."'");
	if(mysql_num_rows($ecq) ==1){
		$ecd = mysql_fetch_object($ecq);
		if($connload >0.5){
			$loadeff = round($connload,0);
		}else{
			$loadeff = 0.5;
		}
		
		if($avg_unit == 0){
			$avg_unit = (int)(($loadeff * $ecd->consump)/30);
		}
		
		$col 		= array();						$coldata		= array();
		
		$col[0] 	= "mydate";						$coldata[0]		= strtotime($mydate);
		$col[1] 	= "conid";						$coldata[1]		= $conid;
		$col[2] 	= "prev_read_date";				$coldata[2]		= $prebilldate;
		$col[3] 	= "prev_read";					$coldata[3]		= $prevread;
		$col[4] 	= "avarage_unit";				$coldata[4]		= $avg_unit;
		$col[5] 	= "pre_meterstatus";			$coldata[5]		= $premstatus;
		
		$colstr		= implode(',',$col);			$coldatastr		= implode("','",$coldata);
		
		mysql_query("insert into bill_reading(". $colstr .") values('". $coldatastr ."')");
		$readid = mysql_insert_id();
		$logdata .= "|->reading data imported<br/>";
		
		$billdo = true;
	}else{
		$logdata .= "|->estimated consumption is not set<br/>";
	}
?>