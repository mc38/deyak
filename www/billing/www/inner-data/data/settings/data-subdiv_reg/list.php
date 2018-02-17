 <?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	$where ="";
	if(isset($_GET['s']) && $_GET['s'] !=""){
		$where =" where name like '".base64_decode($_GET['s'])."%' or sid like '".base64_decode($_GET['s'])."%'";
	}
	$query = "select * from settings_subdiv_data".$where;
	$q = mysql_query($query);
	
	$j =1;
	
	if(mysql_num_rows($q) >0){
		while($d = mysql_fetch_object($q)){
			
			$sid = $d->sid;
			$name = $d->name;
			$detail = $d->detail;
			
			
			echo '
				<tr>
					<th class="cus_sln"><span>'.$j.'</span></th>
					<td class="cus_det" valign="top">
						<b>Sub-Division ID : </b>'.$sid.'<br/>
						<b>Name : </b>'.strtoupper($name).'<br />
						<b>Detail : </b><br/>'.$detail.'<br />
					</td>
					<td class="cus_act" valign="top">
					</td>
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