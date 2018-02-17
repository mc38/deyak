<?php
include "www/db/command.php";

$con_get_row = file("pic.txt");
for($i=0;$i<sizeof($con_get_row);$i++){
	$con_get_row[$i] = trim($con_get_row[$i]);
}
$con_get_str = implode("','", $con_get_row);
echo $con_get_str .'<br/>';

$sql_str = "";
$query = "delete from m_data where c_subdiv_id='1501525800'";
mysql_query($query);

?>