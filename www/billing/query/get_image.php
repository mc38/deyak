<?php
include "../www/db/command.php";

if(! file_exists("image/")){
	mkdir("image");
}

$con_get_row = file("pic.txt");
for($i=0;$i<sizeof($con_get_row);$i++){
	$con_get_row[$i] = trim($con_get_row[$i]);
}
$con_get_str = implode("','", $con_get_row);
echo $con_get_str .'<br/>';

$sql_str = "";
$query = "select in_meterpic from m_data where out_cid in ('". $con_get_str ."')";
$q = mysql_query($query);
echo mysql_num_rows($q);
if(mysql_num_rows($q) >0){
	//$d = mysql_fetch_array($q);
	while($d = mysql_fetch_array($q)){
		$src_p = "../file/image/data/".$d[0];
		$des_p = "image/". $d[0];
		if(file_exists($src_p)){
			copy($src_p,$des_p);
		}
	}
}

?>