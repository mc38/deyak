<?php
include "../www/db/command.php";

$data = file("no_con_edit.csv");
for($i=0; $i<sizeof($data); $i++){
	$d = explode(",", trim($data[$i]));

	$status = '<span style="color:red;">Not Found</span>';
	$qdone = "";

	$query = "select id from m_data where c_mydate='1504204200' and out_oldcid='0". $d[0] ."'";
	$q = mysql_query($query);
	if(mysql_num_rows($q)>0){
		$qd = mysql_fetch_object($q);

		$status = '<span style="color:green;">Found</span>';
		mysql_query("update m_data set out_dtrno='999' where id='". $qd->id ."'");

		$cq = mysql_query("select id from m_data where out_dtrno='999' and id='". $qd->id ."'");
		if(mysql_num_rows($cq)>0){
			$qdone = "Updated";
		}
	}
	echo $d[0] ." -> ". $d[1] ." -> ". $status ." -> ". $qdone ."<br/>";
}

?>