<?php
$total_data = 0;			$consumer_list = array();
$data_ok_import = 0;		$data_ok = array();
$data_not_ok_import	= 0;	$data_not_ok = array();	$data_not_ok_reason = array();

$reason = array(0=>"Consumer repeat",1=>"Data length is not 23",2=>"All data is not found",3=>"Subdivision code not exists",4=>"Consumer Category Code not exists",5=>"Meter category not exists",6=>"Date format is not correct",7=>"Consumer already imported");
/*
*not_ok_reason
	0->Consumer repeat
	1->Data length is not 17
	2->All data is not found
	3->Subdivision code not exists
	4->Consumer Category Code not exists
	5->Meter category not exists
	6->Date format is not correct, use(YYYY-MM-DD)
	7->Consumer already imported
*/

$subdiv_code = array();
$subdiv_q = mysql_query("select sid from settings_subdiv_data");
while($subdiv_d = mysql_fetch_object($subdiv_q)){
	$subdiv_code[] = $subdiv_d->sid;
}

$ccate = array();
$ccate_code = array();
$ccate_q = mysql_query("select id,tariff_id from settings_consumer_cate");
while($ccate_d = mysql_fetch_object($ccate_q)){
	$ccate_code[] = $ccate_d->tariff_id;
	$ccate[$ccate_d->tariff_id] = $ccate_d->id;
}

$meter = array();
$meter_code = array();
$meter_q = mysql_query("select id,link from settings_meter_cate");
while($meter_d = mysql_fetch_object($meter_q)){
	$meter_code[] = $meter_d->link;
	$meter[$meter_d->link] = $meter_d->id;
}


for($i=0;$i<sizeof($data);$i++){
	$total_data ++;
	$d = explode('$', $data[$i]);
	$d[21] = trim($d[21]);
	if($d[7] == ""){$d[7]="0";}

	if(! in_array($d[1],$consumer_list)){
		$consumer_list[]=$d[1];

		if(sizeof($d) == 23){
			$d[21] = $d[21] . "a";

			$check_arr = array();
			$check_arr[] = $d[0];
			$check_arr[] = $d[1];
			$check_arr[] = $d[2];
			$check_arr[] = $d[3];
			$check_arr[] = $d[4];
			$check_arr[] = $d[5];
			$check_arr[] = $d[7];
			$check_arr[] = $d[8];
			$check_arr[] = $d[10];
			$check_arr[] = $d[11];
			$check_arr[] = $d[12];
			$check_arr[] = $d[13];
			$check_arr[] = $d[14];
			$check_arr[] = $d[15]; //need to check arrear
			$check_arr[] = $d[16];
			$check_arr[] = $d[17]; // cs calc
			$check_arr[] = $d[18];
			$check_arr[] = $d[20];
			$check_arr[] = $d[21];
			$check_arr[] = $d[22];

			if(! array_search("",$check_arr)){
				$d[21] = str_replace("a", "", $d[21]);

				if(in_array((int)$d[0],$subdiv_code)){

					if(in_array((int)$d[5],$ccate_code)){
						
						if(in_array($d[7],$meter_code)){

							//echo strtoupper(date('d/m/Y',strtotime(str_replace('/','-',$d[20])))) ." ". strtoupper($d[20]); 
							if(
								((strlen($d[20]) == 10) && (strtoupper(date('d/m/Y',strtotime(str_replace('/','-',$d[20])))) == strtoupper($d[20])))
							){

								if(
									((strlen($d[10]) == 8) && (date('Ymd',strtotime(substr($d[10],0,4) ."-". substr($d[10],4,2) ."-". substr($d[10],6,2))) == $d[10]))
								){
									$q = mysql_query("select id from in_data_queue where consumer_no='". $d[1] ."'");
									if(mysql_num_rows($q)<1){
										$data_ok[] = $i;
										$data_ok_import++;
									}else{
										$data_not_ok[] = $i;
										$data_not_ok_reason[$i]=7;
										$data_not_ok_import++;
									}
								}else{
									$data_not_ok[] = $i;
									$data_not_ok_reason[$i]=6;
									$data_not_ok_import++;
								}
							}else{
								$data_not_ok[] = $i;
								$data_not_ok_reason[$i]=6;
								$data_not_ok_import++;
							}
						}else{
							$data_not_ok[] = $i;
							$data_not_ok_reason[$i]=5;
							$data_not_ok_import++;
						}
					}else{
						$data_not_ok[] = $i;
						$data_not_ok_reason[$i]=4;
						$data_not_ok_import++;
					}
				}else{
					$data_not_ok[] = $i;
					$data_not_ok_reason[$i]=3;
					$data_not_ok_import++;
				}
			}else{
				$data_not_ok[] = $i;
				$data_not_ok_reason[$i]=2;
				$data_not_ok_import++;
			}
		}else{
			$data_not_ok[] = $i;
			$data_not_ok_reason[$i]=1;
			$data_not_ok_import++;
		}
	}else{
		$data_not_ok[] = $i;
		$data_not_ok_reason[$i]=0;
		$data_not_ok_import++;
	}
}
?>