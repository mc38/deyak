<?php

$templete_check[0]="CON_ID";
$templete_check[1]="NAME";
$templete_check[2]="BOOK_NO";
$templete_check[3]="RURAL_URBAN";
$templete_check[4]="METER_NO";
$templete_check[5]="CAPACITY";
$templete_check[6]="SUB_DIV_ID";
$templete_check[7]="SUBDIVISIONNAME";
$templete_check[8]="ADDRESS";
$templete_check[9]="MULTI_FACT";
$templete_check[10]="PHASE";
$templete_check[11]="KW_HP_KV";
$templete_check[12]="CONC_LOAD";
$templete_check[13]="MET_OWNER";
$templete_check[14]="METERTYPE";
$templete_check[15]="METERSEALNO";
$templete_check[16]="TARIFF_ID";


$templete[0]=$templete_check[0];
$templete[1]=$templete_check[1];
$templete[2]=$templete_check[4];
$templete[3]=$templete_check[5];
$templete[4]=$templete_check[6];
$templete[5]=$templete_check[8];
$templete[6]=$templete_check[9];
$templete[7]=$templete_check[10];
$templete[8]=$templete_check[11];
$templete[9]=$templete_check[12];
$templete[10]=$templete_check[13];
$templete[11]=$templete_check[14];
$templete[12]=$templete_check[15];
$templete[13]=$templete_check[16];
$templete[14]=$templete_check[7];
$templete[15]=$templete_check[3];
$templete[16]=$templete_check[2];


////////////////////////////////////////////////////
$tariff_aream =array("R","U"); $tariff_area_code = array(); $tariff_area=array();
$taq = mysql_query("select id,name from tariff_area");
if(mysql_num_rows($taq)>0){
	$i=0;
	while($tad = mysql_fetch_object($taq)){
		$tariff_area[] = $tariff_aream[$i];
		$tariff_area_code[$tariff_aream[$i]]=$tad->id;
		$tariff_area_name[$tad->id]=$tad->name;
		$i++;
	}
}
////////////////////////////////////////////////////
$mon = (int) date('m',$datetime);
$day = (int) date('d',$datetime);
$year = (int) date('Y',$datetime);

if($day>15){
	$mon ++;
	if($mon>12){
		$mon =1;
		$year++;
	}
}

$idate = strtotime("01-".$mon."-".$year);

////////////////////////////////////////////////////

$total_data = sizeof($data);
$accepted_data =0;

$aborted_data=array();

$nconsumer_list=array();	$temp_con=array();

$nsubdiv_list=array();		$temp_subdiv = array();
$nmetertype_list=array();	$temp_metertype= array();
$ntarrif_id_list=array();	$temp_tarrifid = array();
$ntarrif_area_list=array();	$temp_tarrifarea = array();


for($i=0;$i<sizeof($data);$i++){
	$d = $data->Table;
	$table = $d[$i];
	
	$keys = array_keys((array)$table);
	///////////////////////////////////check xml format///////////////////////////////////////////
	if((sizeof($keys) == sizeof($templete_check)) && ($keys === $templete_check)){
		$accepted_data ++;
		
		$cid = $table->$templete[0];
		$cq = mysql_query("select id from in_consumer_xml where CON_ID='". $cid ."'");
		if(mysql_num_rows($cq) == 0){
			$nconsumer_list[]=$i;
		}
		
		
		//////////////////////////////////////////////////////////////////////////////////
		///////////subdiv/////////////
		$sid = $table->$templete[4];
		if(! in_array($sid."",$nsubdiv_list)){
			$q = mysql_query("select id from subdiv_data where sid='". $sid ."'");
			if(mysql_num_rows($q)<1){
				$nsubdiv_list[]=$sid;
				$temp_subdiv[]=$i;
				$subdiv_name[$sid.""] = $table->$templete[14];
			}
		}
		else{
			$temp_subdiv[]=$i;
		}
		
		///////////meter type/////////////
		$mtype = $table->$templete[11];
		if(! in_array($mtype."",$nmetertype_list)){
			$q = mysql_query("select id from meter_cate where name='". strtoupper($mtype) ."'");
			if(mysql_num_rows($q)<1){
				$nmetertype_list[]=$mtype;
				$temp_metertype[] = $i;
			}
		}
		else{
			$temp_metertype[] = $i;
		}
		
		//////////tariff id/////////////
		$tad = $table->$templete[13]; $tarea = $table->$templete[15];
		if(! in_array($tarea."",$ntarrif_area_list)){
			if(! in_array($tarea,$tariff_area)){
				$ntarrif_area_list[]=$tarea;
				$temp_tarrifarea[]=$i;
			}
			else{
				if(isset($tariff_area_code[$tarea.""])){
					$tariff_a_c = $tariff_area_code[$tarea.""];
					$tid_arr = array($tad,$tariff_a_c);
					$tid = base64_encode(str_replace('}',"",str_replace('{"0":',"",json_encode($tid_arr))));
					
					if(! in_array($tid."",$ntarrif_id_list)){
						$q = mysql_query("select id from consumer_cate where tariff_id='". $tid ."'");
						if(mysql_num_rows($q)<1){
							$ntarrif_id_list[] = $tid;
							$temp_tarrifid[] = $i;
						}
					}
					else{
						$temp_tarrifid[] = $i;
					}
				}
			}
		}
		else{
			$temp_tarrifarea[] = $i;
		}
	}
	else{
		$aborted_data[]=$i;
	}
}


for($i=0;$i<sizeof($temp_subdiv);$i++){
	$sr = array_search($temp_subdiv[$i],$nconsumer_list);
	if($sr >-1){
		array_splice($nconsumer_list,$sr,1);
	}
}

for($i=0;$i<sizeof($temp_metertype);$i++){
	$sr = array_search($temp_metertype[$i],$nconsumer_list);
	if($sr >-1){
		array_splice($nconsumer_list,$sr,1);
	}
}

for($i=0;$i<sizeof($temp_tarrifid);$i++){
	$sr = array_search($temp_tarrifid[$i],$nconsumer_list);
	if($sr >-1){
		array_splice($nconsumer_list,$sr,1);
	}
}

for($i=0;$i<sizeof($temp_tarrifarea);$i++){
	$sr = array_search($temp_tarrifarea[$i],$nconsumer_list);
	if($sr >-1){
		array_splice($nconsumer_list,$sr,1);
	}
}

?>