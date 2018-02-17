<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");
require_once("../../../../plugin/libs/phpqrcode/qrlib.php");
	
	
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
		<style>
		.qrdata{
			float:left;
			width:auto;
			height:auto;
			padding:0px;
			border:1px solid #000;
		}
		.qrdata img{
			width:180px;
			height:auto;
			margin-bottom:5px;
		}
		.qrdata div{
			width:180px;
			height:14px;
			overflow:hidden;
		}
		</style>
		<div id="listload" style="padding-left:15px; width:calc(100% - 15px); font-size:12px;">
	';
}

function firsttime_show_end(){
	echo '</div>';
}

function loop_show($d,$j){
	if($d->qrcode_pic ==""){
		if(!file_exists("temp")){
			mkdir("temp");
		}
		$filename = "temp/qrtemp". $d->id .".png";
		
		$qrc = md5($d->id ."_". $d->cid);
		$errorCorrectionLevel = 'H';
		$matrixPointSize = 10;
		QRcode::png($qrc, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
		$qrcpic = base64_encode(file_get_contents($filename));
		
		mysql_query("update consumer_details set qrcode_pic='". $qrcpic ."' where id='". $d->id ."'");
		unlink($filename);
	}else{
		$qrcpic = $d->qrcode_pic;
	}
	
	echo '
		<div class="qrdata" align="center">
			<img src="data:image/jpeg;base64,'. $qrcpic .'"/>
			<div>'. $d->cid .'</div>
			<div>'. $d->consumer_name .'</div>
			<div>'. $d->oldcid .' (DTR - '. $d->dtrno .')</div>
			<div>Meter no - '. $d->meterno .'</div>
		</div>
	';
}





$total = 0;


if($u = authenticate()){
	
	$sdata = json_decode(base64_decode($_GET['s']));
	
	$subdiv = $sdata[0];
	$dtr 	= $sdata[1];
		
		$where =" where ";
		if($dtr !=""){
			$where .= "dtrno='". $dtr ."' and ";
		}
		$where .="subdiv_id='".$subdiv."' order by consumer_name";
		
		$query = "select * from consumer_details".$where;
		
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