<table border="1">
	<tr>
		<th>Consumer no</th>
		<th>Consumer Name</th>
		<th>Col_15</th>
		<th>Col_17</th>
		<th>Status</th>
	</tr>
<?php
include "../www/db/command.php";

$total = 0;
$ok = 0;
$not_ok = 0;

$b_total = 0;
$b_ok = 0;
$b_not_ok = 0;


$dir = "dtr";
$files = scandir($dir);
for($i=2;$i<sizeof($files);$i++){
	$fdata = file($dir ."/". $files[$i]);
	for($j=0;$j<sizeof($fdata);$j++){
		$d = explode('$',$fdata[$j]);
		$total++;

		mysql_query("update in_data_queue set principle_arrear='". $d[15] ."', cs_pa='". $d[17] ."' where consumer_no='". $d[1] ."'");

		echo '
			<tr>
				<td>'. $d[1] .'</td>
				<td>'. strtoupper($d[2]) .'</td>
				<td>'. $d[15] .'</td>
				<td>'. $d[17] .'</td>
				<td>Done</td>
			</tr>
		';
	}
}
$not_ok = $total - $ok;
$b_not_ok = $b_total - $b_ok;
?>
</table>