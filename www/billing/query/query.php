<?php
include "www/db/command.php";

$con_get_row = file("pic.txt");
for($i=0;$i<sizeof($con_get_row);$i++){
	$con_get_row[$i] = trim($con_get_row[$i]);
}
$con_get_str = implode("','", $con_get_row);
echo $con_get_str .'<br/>';

$sql_str = "";
$query = "update m_data set c_done=0,c_pass_status=0,c_pass_datetime=0,c_pass_user=0,c_import_status=1,c_import_datetime=1,c_import_user=1 where out_cid in ('". $con_get_str ."')";
mysql_query($query);

?>
