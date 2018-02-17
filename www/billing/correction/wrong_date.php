<?php
include "www/db/command.php";

$q = mysql_query("select * from bill_details where mydate='1509474600' and status=0");
echo "Total - ". mysql_num_rows($q) ."<br/>";
while($d = mysql_fetch_object($q)){
	$id = $d->id;
	$baid = $d->baid;
	$readid = $d->readid;

	$q_a = mysql_query("update bill_details set mydate='1512066600' where id='". $id ."'");
	$q_b = mysql_query("update bill_amount set mydate='1512066600' where id='". $baid ."'");
	$q_c = mysql_query("update bill_reading set mydate='1512066600' where id='". $readid ."'");

	echo $id ." -> ";
	if($q_a){echo "done ->";}else{echo "     ->";}
	if($q_b){echo "done ->";}else{echo "     ->";}
	if($q_c){echo "done ->";}else{echo "     ->";}
	echo "<br/>";
}
?>