<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");
	
	
$metertype = array();
$mq = mysql_query("select id,name from settings_meter_cate");
while($md = mysql_fetch_object($mq)){
	$metertype[$md->id]=$md->name;
}
		
$consumertype = array();
$cq = mysql_query("select id,name from settings_consumer_cate");
while($cd = mysql_fetch_object($cq)){
	$consumertype[$cd->id] = $cd->name;
}
	
function firsttime_show_start(){
	
	
	echo '
		<h2>List of Migration data </h2>
		<table id="listload" border="1" style="border-spacing:0px; font-size:10px;">
			
			<tr>
				<th rowspan="2" style="width:50px;">Sl no</th>
				<th rowspan="2">Info</th>
				<th rowspan="2">DTR no</th>
				<th rowspan="2" style="width:150px;">Consumer no</th>
				<th rowspan="2" style="width:250px;">Consumer</th>
				<th rowspan="2">Meter Slno</th>
				<th rowspan="2">Meter Type</th>

				<th rowspan="2">Load</th>
				<th rowspan="2">Category</th>
				<th rowspan="2">M-Factor</th>
				<th rowspan="2">Avg Unit</th>

				<th colspan="2" align="center">Previous</th>
				<th colspan="3" align="center">Amouunt (Rs)</th>
				<th rowspan="2">Status</th>
			</tr>
			
			<tr>
				<th>Reading</th>
				<th>Bill Date</th>

				<th>Prin Arr</th>
				<th>Arr Surchrg</th>
				<th>CS Calc</th>
				<th>Adj</th>
			</tr>
	';
}

function firsttime_show_end(){
	echo '</table>';
}

function loop_show($d,$j){
	//include "../../../../../config/config.php";
	
	$info = date('d-m-Y h:i:s a',$d->datetime);
	if($d->importtype == 0){
		$info = $info . "<hr/>File Import";
	}else{
		$info = $info . "<hr/>Data entered by";
		$uq = mysql_query("select fname,lname from zzuserdata where id='". $d->byuser ."'");
		if(mysql_num_rows($uq)>0){
			$ud = mysql_fetch_object($uq);
			$info = $info . '<hr/><span style="text-transform:uppercase;">'. $ud->fname .' '. $ud->lname .'</span>';
		}
	}
	
	$conid = '<b>Old</b> : '. $d->old_consumer_no .'<br/><b>New</b> : '. $d->consumer_no ;
	
	global $metertype;
	global $consumertype;
	$meter = "Owned by Consumer";
	if($d->meter_type >0){
		$meter = $metertype[$d->meter_type];
	}
	
	$status = '<span style="color:red;">Not Processed</span>';
	if($d->status == 1){
		$status = '<span style="color:green;">Processed</span>';
	}
	
	echo '
		<tr>
			<th align="center" valign="top">'.$j.'</th>
			<td valign="top">'.$info.'</td>
			<td valign="top">'.$d->dtr_no.'</td>
			<td valign="top">'.$conid.'</td>
			<td valign="top"><b>'.$d->consumer_name.'</b><br/>'. $d->consumer_address .'</td>
			<td valign="top">'. $d->meter_no .'</td>
			<td valign="top">'. $meter .'</td>

			<td valign="top">'. $d->connected_load .' kW</td>
			<td valign="top">'. $consumertype[$d->consumer_category_code] .'</td>
			<td valign="top">'. $d->multiplying_factor .'</td>
			<td valign="top">'. $d->avg_unit .'</td>

			<td valign="top">'. $d->previous_reading .'</td>
			<td valign="top">'. $d->previous_bill_date .'</td>

			<td valign="top">'. number_format((float)$d->principle_arrear,2) .'</td>
			<td valign="top">'. number_format((float)$d->arrear_surcharge,2) .'</td>
			<td valign="top">'. number_format((float)$d->cs_pa,2) .'</td>
			<td valign="top">'. number_format((float)$d->adjustment,2) .'</td>
			
			<td valign="top">'. $status .'</td>
		</tr>
	
	';
}





$total = 0;


if($u = authenticate()){
	
	$sdata = json_decode(base64_decode($_GET['s']));
	
	$subdiv = $sdata[0];
	
		
		$where ="";
		$where =" where subdivision_id='".$subdiv."' order by datetime desc";
		
		$query = "select * from in_data_queue".$where;
		$q = mysql_query($query);
		$total = mysql_num_rows($q);
		
		if($total>0){
			
			if($_GET['pos'] == 0){
				firsttime_show_start();
			}
			
			mysql_data_seek($q,(int) $_GET['pos']);
			$i = $_GET['pos'];
			
			while($d = mysql_fetch_object($q)){
				$j = $i +1;
				loop_show($d,$j);
				
				$i++;
				if($i>=($_GET['pos']+$_GET['freq'])){
					break;
				}
			}
			if($_GET['pos'] == 0){
				firsttime_show_end();
			}
			
		}
		else{
			echo '<tr><td>Empty List</td></tr>';
		}
}
else{
	echo "Unauthorized user";
}




if($_GET['pos'] == 0){echo '<input id="listtotal" type="hidden" value="'. $total .'" />';}
?>