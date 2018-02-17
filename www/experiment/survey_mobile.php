<?php
error_reporting(0);
include "connection.php";

echo '
<h2>Survey Mobile no list</h2>
	<table border="1">
		<tr>
			<th>Slno</th>
			<th>Consumer No</th>
			<th>Consumer Name</th>
			<th>DTR no</th>
			<th>Category</th>
			<th>Mobile no</th>
		</tr>
';

$j =1;
$q = mysql_query("select out_oldcid,out_consumer_name, out_dtrno,out_consumer_category,in_survey_mobno from m_data where c_mydate=1496255400 and in_survey_mobno<>''");
while($d = mysql_fetch_object($q)){
	echo '
		<tr>
			<td>'. $j .'</td>
			<td>'. $d->out_oldcid .'</td>
			<td>'. $d->out_consumer_name .'</td>
			<td>'. $d->out_dtrno .'</td>
			<td>'. $d->out_consumer_category .'</td>
			<td>'. $d->in_survey_mobno .'</td>
		</tr>
	';
	$j++;
}

echo '</table>';

?>