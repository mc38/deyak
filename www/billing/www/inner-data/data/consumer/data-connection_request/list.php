<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	$db = new dbconnection();
	
	////////////////////////////////////////////////////
	$db->command("select id,name from tariff_area");
	$taq = $db->response;
	for($i=0;$i<sizeof($taq);$i++){
		$tariff_area_name[$taq[$i]->id]=$taq[$i]->name;
	}
	////////////////////////////////////////////////////
	
	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$subdivid = $data[0];
	$s = $data[1];
	
	$slist = true;
	
	$where ="where ";
	
	if($slist){
		
		if(isset($s) && $s !=""){
			$where = $where . "(bookno = '".$s."')";
		}
		$query = "select * from consumer_info ".$where;
		$db->command($query);
		$q = $db->response;
		
		if(sizeof($q) >0){
			for($i=0;$i<sizeof($q);$i++){
				$d = $q[$i];
				
				$db->command("nodeshow (consumer_info,".$d->id.")");
				$noder = $db->response;
				
				$meter=""; $category="";
				for($ii=0;$ii<sizeof($noder);$ii++){
					$noded = $noder[$ii];
					if($noded[0]=="meter_data"){
						$db->command("select meter_no from meter_data where id='". $noded[1] ."'");
						$m = $db->response;
						$meter = $m[0]->meter_no;
					}
					else if($noded[0]=="consumer_cate"){
						$db->command("select name,tariff_id from consumer_cate where id='". $noded[1] ."'");
						$m = $db->response;
						$tid = json_decode(base64_decode($m[0]->tariff_id));
						
						$category = $m[0]->name . " (ID : ".$tid[0].", ". $tariff_area_name[$tid[1]].")";
					}
				}
				$ex = base64_decode($d->extra);
				
				
				$j= $i+1;
				echo '
					<tr>
						<th class="cus_sln" valign="top"><span>'.$j.'</span></th>
						<td class="cus_date" valign="top">'.date('d-m-Y',$d->datetime).'<br />'.date('h:i:s A',$d->datetime).'</td>
						<td class="cus_det" valign="top">
							<b>ID : </b>'.strtoupper($d->consumer_id).'<br />
							<b>Name : </b>'.strtoupper(base64_decode($d->name)).'<br />
							<b>Address : </b>'.strtoupper(base64_decode($d->address)).'<br />
							<b>Contact : </b>'. $d->contact .'<br />
							<b>Multiplying Factor : </b>'. $d->mfactor .'<br />
							<b>Meter no : </b>'. $meter .'<br />
							<b>Category : </b>'. $category .'<br />
							<b>Book no : </b>'. $d->bookno .'<br />
						</td>
						<td class="cus_act" valign="top">-</td>
					</tr>
				
				';
			}
		}
		else{
			echo '<tr><td>Empty List</td></tr>';
		}
	}
	else{
		echo '<tr><td>Empty List</td></tr>';
	}
}
else{
	echo "Unauthorized user";
}
?>