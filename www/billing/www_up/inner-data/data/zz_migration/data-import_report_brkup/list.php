<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$df = strtotime($data[0]);
	$dt= strtotime('+1day',strtotime($data[1]));
	if($df<$dt){
		$den_tot = 0;
		$df_tot = 0;
		
		$operator = array();
		$operator_data = array();
		
		$q = mysql_query("select id,byuser,importtype from in_data_queue where datetime>=". $df ." and datetime<". $dt);
		while($d = mysql_fetch_object($q)){
			if($d->importtype == 0){
				$df_tot ++;
			}else if($d->importtype == 1){
				$den_tot ++;
				
				if(! in_array($d->byuser,$operator) && $d->byuser>0){
					$operator[] = $d->byuser;
					$operator_data[$d->byuser] = 0;
				}
				if($d->byuser>0){
					$operator_data[$d->byuser] ++;
				}
				
			}
		}
		
		echo '
			<div>
				<table border="1" style="width:300px;">
					<tr>
						<td colspan="2"><h3>Data migrated and updated status</h3></td>
					</tr>
					<tr>
						<th>Period :</th> 
						<td align="right">'. date('d-m-Y',$df) .' to '. date('d-m-Y',strtotime($data[1])) .'</td>
					</tr>
					<tr>
						<th>Updated :</th>
						<td align="right">'. $den_tot .'</td>
					</tr>
					<tr>
						<th>Migrated :</th>
						<td align="right">'. $df_tot .'</td>
					</tr>
				</table>
			</div>
		';
		
		if($den_tot >0){
			if(sizeof($operator)>0){
				echo '
					<hr/>
					
					<div>
						<table border="1" style="width:500px;">
							<tr>
								<td colspan="2"><h3>User Data update break up</h3></td>
							</tr>
							<tr>
								<th align="center">User</th>
								<th align="center">Data</th>
							</tr>
				';
				
				$operator_det = array();
			
				$operator_sep = implode(',',$operator);
				$oq = mysql_query("select id,fname,lname from zzuserdata where id in (". $operator_sep .")");
				
				while($od = mysql_fetch_object($oq)){
					$operator_det[$od->id] = '<span style="text-transform:uppercase;">'. $od->fname .' '. $od->lname .'</span>';
				}
				
				for($i=0;$i<sizeof($operator);$i++){
					
					echo '
						<tr>
							<td>'. $operator_det[$operator[$i]] .'</td>
							<td align="right">'. $operator_data[$operator[$i]] .'</td>
						</tr>
					';
				}
				echo '
						</table>
					</div>
					
				';	
			}
		}
		
		
	}else{
		echo '<div align="center">To date must be greater than From date</div>';
	}
}
else{
	echo '<div align="center">Unauthorized user</div>';
}
?>