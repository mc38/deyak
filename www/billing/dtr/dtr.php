<table border="1">
	<tr>
		<th>Consumer no</th>
		<th>Consumer Name</th>
		<th>Col_15</th>
		<th>Col_17</th>
		<th>Status</th>
		<th>Bill done</th>
	</tr>
<?php
include "../www/db/command.php";

$total = 0;
$ok = 0;
$not_ok = 0;

$b_total = 0;
$b_ok = 0;
$b_not_ok = 0;

$all_ok = 0;


$dir = "dtr";
$files = scandir($dir);
for($i=2;$i<sizeof($files);$i++){
	$fdata = file($dir ."/". $files[$i]);
	for($j=0;$j<sizeof($fdata);$j++){
		$d = explode('$',$fdata[$j]);
		$total++;

		$status = "Not same";
		if($d[15] == $d[17]){
			$status = "Same";
			$ok++;
		}

		$bill = "Not Done";
		$q = mysql_query("select id from m_data where out_oldcid='". $d[1] ."' and in_status<>''");
		if(mysql_num_rows($q)>0){
			$bill = "Done";
			if($d[15] == $d[17]){
				$b_ok++;
			}
			$b_total++;
		}

		$qq = mysql_query("select id from in_data_queue where consumer_no='". $d[1] ."' and principle_arrear='". $d[15] ."' and cs_pa='". $d[17] ."'");
		if(mysql_num_rows($qq) >0){
			$all_ok++;
		}

		echo '
			<tr>
				<td>'. $d[1] .'</td>
				<td>'. strtoupper($d[2]) .'</td>
				<td>'. $d[15] .'</td>
				<td>'. $d[17] .'</td>
				<td>'. $status .'</td>
				<td>'. $bill .'</td>
			</tr>
		';
	}
}
$not_ok = $total - $ok;
$b_not_ok = $b_total - $b_ok;
?>
</table>
<?php
$ok_p = ($ok * 100) / $total;
$notok_p = ($not_ok * 100)/ $total;

echo 'Total -> '. $total .'<br/>';
echo 'Same -> '. $ok .' ( '. round($ok_p,2) .'% )<br/>';
echo 'Not Same -> '. $not_ok .' ( '. round($notok_p,2) .'% )<br/>';
echo '<hr/>';
$b_ok_p = ($b_ok * 100) / $b_total;
$b_notok_p = ($b_not_ok * 100)/ $b_total;
echo 'Bill Total -> '. $b_total .'<br/>';
echo 'Bill Same -> '. $b_ok .' ( '. round($b_ok_p,2) .'% )<br/>';
echo 'Bill Not Same -> '. $b_not_ok .' ( '. round($b_notok_p,2) .'% )<br/>';
echo '<hr/>';
echo 'All ok -> '. $all_ok .'<br/>';
?>