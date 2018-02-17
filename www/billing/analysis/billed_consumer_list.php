<?php
session_start();
ini_set('memory_limit', '128M');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "www/db/command.php";
include "config/config.php";

date_default_timezone_set('Asia/Kolkata');
$datetime=date($_SERVER['REQUEST_TIME']);

$list_type = array();
$list_type[1] = "Not Billed Consumer";
$list_type[2] = "Billed and Queued Consumer";
$list_type[3] = "Billed and Rejected Consumer";
$list_type[4] = "Billed and Accepted Consumer";

echo '
<form method="post" action="">
<label>Select any date of Month</label><input name="date" type="date"><br/>

<label>Select List Type</label>
<select name="l" style="width:100%;">
	<option value="">Select One</option>
	<option value="1">Not Billed Consumer</option>
	<option value="2">Billed and Queued Consumer</option>
	<option value="3">Billed and Rejected Consumer</option>
	<option value="4">Billed and Accepted Consumer</option>
</select>
<br/>

<button type="submit">Search</button>

</form>
';

if(
	!empty($_POST['date'])
	&&
	!empty($_POST['l'])
){

	$l = $_POST['l'];
	$mydate = strtotime(date('Y-m-01',strtotime($_POST['date'])));
	$qpass = true;

	$import_where = "";
	if($l==1){
		$import_where = " and c_import_status=0 and c_pass_status=0";
	}elseif ($l==2) {
		$import_where = " and c_import_status=1 and c_pass_status=0";
	}elseif ($l==3) {
		$import_where = " and c_import_status=1 and c_pass_status=2";
	}elseif ($l==4) {
		$import_where = " and c_import_status=1 and c_pass_status=1";
	}else{
		$qpass = false;
	}

	if($qpass){
		$mstat_all = array();
		$mq = mysql_query("select id,out_cid,c_mydate,out_premeterstatus,in_status,c_import_status from m_data");
		while($md = mysql_fetch_object($mq)){
			if(!isset($mstat_all[$md->out_cid])){
				$mstat_all[$md->out_cid] = array();
			}
			$mstat_all[$md->out_cid][$md->c_mydate] = $md;
		}

		$q = mysql_query("select id,c_mydate,out_cid,out_oldcid,out_consumer_name,out_consumer_category,out_dtrno,in_status from m_data where c_subdiv_id='22' and c_mydate='". $mydate ."'". $import_where);

		echo '
		<h2>Subdivisiion - Hajo(22)</h2>
		<h2>Month Year - '. date('F, Y',$mydate) .'</h2>
		<h2>List Type - '. $list_type[$l] .'</h2>
		<div>
			<h2>Total - '. mysql_num_rows($q) .'</h2>
		</div>
		';

		$start_month = strtotime("2017-08-01");

		$current_month = $start_month; $mydate_array = array();
		while($current_month<=$mydate){
			$mydate_array[] = $current_month;
			$current_month = strtotime(date('Y-m-01',strtotime("+45day", $current_month)));
		}

		echo '
		<table border="1" style="border-collapse:collapse; font-size:10px">
			<tr>
				<th rowspan="2">Slno</th>
				<th rowspan="2">Deyak ID</th>
				<th rowspan="2">DTR no</th>
				<th rowspan="2">Consumer no</th>
				<th rowspan="2">Consumer Category</th>
				<th rowspan="2">Consumer Name</th>
				<th colspan="'. sizeof($mydate_array) .'">Meter Status</th>
			</tr>
			<tr>
		';

		for($i=0; $i<sizeof($mydate_array); $i++){
			echo '	
			<th>'. date('F, Y', $mydate_array[$i]) .'</th>
			';
		}

		echo '
			</tr>
		';

		$j=1;
		while($d = mysql_fetch_object($q)){
			$mstat_array = array();
			
			for($i=0;$i<sizeof($mydate_array);$i++){
				$cmydate = $mydate_array[$i];
				$mstat = "";
				if(!isset($mstat_all[$d->out_cid][$cmydate])){
					$mstat = "Not found";
				}else{
					$mdata = $mstat_all[$d->out_cid][$cmydate];
					
					$mstat .= $meter_status[(int)$mdata->out_premeterstatus];
					if($mstat !=""){$mstat .= " -> ";}

					if($mdata->c_import_status ==1){
						$mstat .= $meter_status[(int)$mdata->in_status];
					}else{
						$mstat .= "Not available";
					}
				}
				$mstat_array[] = $mstat;
			}


			echo '
				<tr>
					<td align="center">'. $j .'</td>
					<td align="center">'. $d->out_cid .'</td>
					<td align="center">'. $d->out_dtrno .'</td>
					<td align="center">'. $d->out_oldcid .'</td>
					<td align="center">'. $d->out_consumer_category .'</td>
					<td>'. $d->out_consumer_name .'</td>
			';
			for($i=0;$i<sizeof($mstat_array);$i++){
				echo '
				<td align="center">'. $mstat .'</td>
				';
			}
			echo '
				</tr>
			';
			$j++;
		}

		echo '
		</table>
		';
	}
}
?>