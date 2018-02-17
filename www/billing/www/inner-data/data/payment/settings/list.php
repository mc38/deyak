<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	$sq = mysql_query("select sid,name from subdiv_data");
	$sub = array();
	while($sd = mysql_fetch_object($sq)){
		$sub[$sd->sid]=$sd;
	}
	
	
	$query = "select * from payment_setting";
	$q = mysql_query($query);
	
	if(mysql_num_rows($q) >0){
		$j=1;
		while($d = mysql_fetch_object($q)){
			$ct = $d->ctype;
			$cd = $d->cdata;
			
			$comm = "";
			if($ct == 1){
				$comm = "Rs ". $cd ."/- per transaction";
			}else if($ct ==2){
				$comm = $cd ."% of collection money";
			}
			
			
			echo '
				<tr>
					<th class="cus_sln"><span>'.$j.'</span></th>
					<td class="cus_det" valign="top">
						<b>Sub-division : </b>'. $sub[$d->subdiv]->name .' [ '. $d->subdiv .' ]<br/>
						<b>Commission : </b>'.$comm.'<br />
					</td>
					<td class="cus_act" valign="top">-</td>
				</tr>
			
			';
			
			$j++;
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