<?php
include "www/db/command.php";

$table = array();
$table[] = "bill_amount";
$table[] = "bill_details";
$table[] = "bill_payment";
$table[] = "bill_reading";
$table[] = "consumer_details";

$table[] = "in_data_queue";
$table[] = "m_data";
$table[] = "m_data_reject";

$query = "";
for($i=0;$i<sizeof($table);$i++){
	$query = "TRUNCATE ". $table[$i] ."";
	mysql_query($query);
}


var_dump($table);
echo "All table empty now";
?>