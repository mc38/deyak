<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	$query = "select * from settings_holidays order by datetime desc";
	$q = mysql_query($query);
	
	$j =1;
	if(mysql_num_rows($q) >0){
		while($d = mysql_fetch_object($q)){
			//<button type="button" style="width:50px;" onclick="edit_data('. $d->id .')">Edit</button>
			echo '
				<tr>
					<th class="cus_sln"><span>'.$j.'</span></th>
					<td class="cus_det" valign="top">
						<b>'.$d->name.'</b> -> '. date('d-m-Y',$d->datetime) .'
					</td>
					<td class="cus_act" valign="top">
						<button type="button" value="'. $d->id .'" onclick="del_h(this);">Delete</button>
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