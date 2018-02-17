<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
if(isset($_GET['d']) && $_GET['d']!=""){	
	
	$data = json_decode(base64_decode($_GET['d']));
	$s = $data[0];
	$a = $data[1];
	$dtr = $data[2];
	
	if($s !=""){
	
		$where = ""; $awhere ="";
		if($a !=""){
			$where = " and aid='". $a ."'";
			$awhere = " and id='". $a ."'";
		}
		$adarr = array(); $add = array();
		$query = "select * from agent_dtr where subdiv='". $s ."'". $where ." order by dtr";
		$q = mysql_query($query);
		if(mysql_num_rows($q) >0){
			while($d= mysql_fetch_object($q)){
				if(! isset($adarr[$d->aid])){
					$adarr[$d->aid] = array();
				}
				$adarr[$d->aid][] = $d->id;
				$add[$d->id] = $d;
			}
		
		
			$aquery = "select id,name from agent_info where subdiv='".$s."'". $awhere ." and status=0 order by name";
			$aq = mysql_query($aquery);
			$j =1;
			if(mysql_num_rows($aq) >0){
				while($ad = mysql_fetch_object($aq)){
					if(isset($adarr[$ad->id])){
						$dtrlist = $adarr[$ad->id];
						
						for($i=0;$i<sizeof($dtrlist);$i++){
							
							$act = '
								<button type="button" style="width:50px;" value="'. base64_encode(json_encode($add[$dtrlist[$i]])) .'" onclick="edit_data(this)">Edit</button>
								<button type="button" style="width:50px;" value="'. $add[$dtrlist[$i]]->id .'" onclick="del_data(this)">Del</button>
								<div id="action_msg_'. $add[$dtrlist[$i]]->id .'"></div>
							';
							
							echo '
								<tr>
							';
							if($i==0){
								echo '
									<th class="cus_sln" valign="top" rowspan="'. sizeof($dtrlist) .'"><span>'.$j.'</span></th>
									<td class="cus_det" valign="top" rowspan="'. sizeof($dtrlist) .'">'. $ad->name .'</td>	
								';
							}
							echo '
									<td class="cus_det" valign="middle">'. $add[$dtrlist[$i]]->dtr .'</td>
									<td class="cus_act" valign="middle">'. $act .'</td>
								</tr>
							';
						}
						$j++;
					}
				}
			}
			else{
				echo '<tr><td>Empty List</td></tr>';
			}
		}
		else{
			echo '<tr><td>Empty List</td></tr>';
		}
	
	}else{
		echo '<tr><td>Data problem</td></tr>';
	}
}
else{
	echo '<tr><td>Data problem</td></tr>';
}
}
else{
	echo "Unauthorized user";
}
?>