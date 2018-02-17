<?php
include "../www/db/command.php";

$con_get_row = file("pic.txt");
for($i=0;$i<sizeof($con_get_row);$i++){
	$con_get_row[$i] = trim($con_get_row[$i]);
}
$con_get_str = implode("','", $con_get_row);
echo $con_get_str .'<br/>';
$sql_file = "con.sql";
if(file_exists($sql_file)){
	unlink($sql_file);
}

$sql_str = "";
$query = "select * from m_data where out_cid in ('". $con_get_str ."')";
$q = mysql_query($query);
echo mysql_num_rows($q);
if(mysql_num_rows($q) >0){
	//$d = mysql_fetch_array($q);
	while($d = mysql_fetch_array($q)){
		$nd = array();
		for($i=0;$i<sizeof($d)/2;$i++){
			$nd[] = $d[$i];
		}

		$sql_str = "insert into m_data values('". implode("','",$nd) ."');";
		echo $sql_str .'<br/>';
		file_put_contents($sql_file, $sql_str . PHP_EOL, FILE_APPEND);
	}
}

?>