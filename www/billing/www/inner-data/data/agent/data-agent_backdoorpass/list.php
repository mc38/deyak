<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if($u = authenticate()){
	
	$sdata = json_decode(base64_decode($_GET['s']));
	
	$subdiv = $sdata[0];
	$s = $sdata[1];
	
	
		
		$where ="";
		if($subdiv > 0){
			$where =" where subdiv='".$subdiv."' and backdoor=1 and backdoor_datetime>". $datetime ." and name like '".$s."%'";
		}else{
			$where .= "";
		}
		
		$query = "select * from agent_info".$where;
		$q = mysql_query($query);
		
		if(mysql_num_rows($q) >0){
			$j=1;
			while($d = mysql_fetch_object($q)){
				
				$name = $d->name;
				
				echo '
					<tr>
						<th class="cus_sln"><span>'.$j.'</span></th>
						<td class="cus_date" valign="top">
						'.date('d-m-Y',$d->backdoor_datetime).'<br />'.date('h:i:s A',$d->backdoor_datetime).'
						</td>
						<td class="cus_det" valign="top">'. strtoupper($name) .'</td>
						<td class="cus_act" valign="top">'. $d->backdoor_pass .'</td>
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