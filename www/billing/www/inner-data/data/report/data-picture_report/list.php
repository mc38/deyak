<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");
require_once("../../../../plugin/libs/phpqrcode/qrlib.php");
include "../../../../../config/config.php";	
	
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
		<h2>List of Reading Picture data </h2>
		<table id="listload" border="1" style="border-spacing:0px; font-size:12px;">
			
			<tr>
				<th style="width:50px;">Sl no</th>
				<th>DEYAK ID</th>
				<th>Consumer Name</th>
				<th>Reading</th>
				<th>Original Meter Pic</th>
				<th>Binary Meter Pic</th>
			</tr>
	';
}

function firsttime_show_end(){
	echo '</table>';
}

function loop_show($d,$j){

	$meterpic = ''; $binarypic ='';
	global $snapshot_link_snapshot_report;
	
	$pic_m_name = $snapshot_link_snapshot_report .'?t=image&i='. $d->in_meterpic;
	$pic_m_data = file_get_contents($pic_m_name);
	
	if($pic_m_data !=""){
		$meterpic ='
			<input id="imgn_'. $d->id .'" type="hidden" value="'. $pic_m_name .'" />
			<div onclick="show_large_image(0,'. $d->id .');">
				<img src="'. $pic_m_name .'" style="width:100px; height:auto;" />
			</div>
		';
	}else{
		$meterpic ='Not uploaded yet';
	}

	$binarypic = '
		<input id="imgb_'. $d->id .'" type="hidden" value="data:image/jpeg;base64,'. base64_decode($d->in_meterpic_binary) .'" />
		<div onclick="show_large_image(1,'. $d->id .');">
			<img src="data:image/jpeg;base64,'. base64_decode($d->in_meterpic_binary) .'" style="width:100px; height:auto;" />
		</div>
	';


	echo '
		<tr>
			<td style="width:50px;">'. $j .'</td>
			<td>'. $d->out_cid .'</td>
			<td>'. $d->out_consumer_name .'</td>
			<td align="center">'. $d->in_postmeter_read .'</td>
			<td align="center">'. $meterpic .'</td>
			<td align="center">'. $binarypic .'</td>
		</tr>
	';
}





$total = 0;


if($u = authenticate()){
	
	$sdata = json_decode(base64_decode($_GET['s']));
	
	$subdiv = $sdata[0];
	$mydate = strtotime($sdata[1]);
	
		
		$where ="";
		$where =" where c_subdiv_id='".$subdiv."' and c_mydate='". $mydate ."' and in_meterpic<>'0' and c_import_status=1 order by id";
		
		$query = "select id,out_cid,out_consumer_name,in_postmeter_read,in_meterpic,in_meterpic_binary from m_data".$where;
		//echo $query;
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
			echo 'Empty List';
		}
}
else{
	echo "Unauthorized user";
}




if($_GET['pos'] == 0){echo '<input id="listtotal" type="hidden" value="'. $total .'" />';}
?>