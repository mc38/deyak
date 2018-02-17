<?php
include "www/db/command.php";

$q = mysql_query("select id,cd from bill_amount where cd<>''");
while($d = mysql_fetch_object($q)){
	if($d->cd !=""){
		$n_cd = round($d->cd, 0);
		echo $n_cd ."<br/>";
		mysql_query("update bill_amount set cd='". $n_cd ."' where id='". $d->id ."'");
	}
}
?>