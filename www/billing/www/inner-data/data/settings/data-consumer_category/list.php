<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	
	$where ="";
	if(isset($_GET['s']) && $_GET['s'] !=""){
		$where =" where name like '".base64_decode($_GET['s'])."%'";
	}
	$query = "select * from settings_consumer_cate".$where;
	$q = mysql_query($query);
	
	if(mysql_num_rows($q) >0){
		$j=1;
		
		while($d = mysql_fetch_object($q)){

			echo '
				<tr>
					<th class="cus_sln" valign="top"><span>'.$j.'</span></th>
					<td class="cus_det" valign="top">
						<b>Name : </b>'.strtoupper($d->name).'<br />
						<b>Code : </b>'. $d->tariff_id .'<br />
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