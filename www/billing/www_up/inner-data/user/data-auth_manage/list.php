<?php
require_once("../../../db/command.php");
require_once("../../../plugin/func/authentication.php");

if(authenticate()){
	
	$where ="";
	if(isset($_GET['s']) && $_GET['s'] !=""){
		$where =" where name like '".base64_decode($_GET['s'])."%'";
	}
	$query = "select * from zzauth".$where;
	$q = mysql_query($query);
	
	$j =1;
	
	if(mysql_num_rows($q) >0){
		while($d = mysql_fetch_object($q)){
			//<button type="button" style="width:50px;" onclick="edit_data('. $d->id .')">Edit</button>
			echo '
				<tr>
					<th class="cus_sln"><span>'.$j.'</span></th>
					<td class="cus_det" valign="top">
						'.$d->name.'<br/>
					</td>
					<td class="cus_act" valign="top">
						-
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