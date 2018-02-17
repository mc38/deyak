<?php
require_once("filter/type.php");

//////////////////////////////////////////////////////////////////////////////////////////////
echo '
	<div>
		<table border="1" style="width:300px;">
			<tr>
				<td colspan="2"><h3>Data import status</h3></td>
			</tr>
			<tr>
				<th>Total :</th> 
				<td align="right">'. $total_data .'</td>
			</tr>
			<tr>
				<th>Data rejected :</th>
				<td align="right">'. $data_not_ok_import .'</td>
			</tr>
			<tr>
				<th>Data OK :</th>
				<td align="right">'. $data_ok_import .'</td>
			</tr>
		</table>
	</div>
	<hr/>
';


//---------------------------------------------------------------------------------------------
// data no ok
if(sizeof($data_not_ok)>0){
	echo '
		<h2>List of Rejected data </h2>
		<table id="listload" border="1" style="border-spacing:0px; font-size:12px;">
			<tr>
				<th rowspan="2" style="width:50px;">Sl no</th>
				<th rowspan="2">Sub div</th>
				<th rowspan="2">DTR no</th>
				<th rowspan="2" style="width:150px;">Consumer no</th>
				<th rowspan="2" style="width:250px;">Consumer</th>
				<th rowspan="2">Meter Slno</th>
				<th rowspan="2">Meter Type</th>

				<th rowspan="2">Load</th>
				<th rowspan="2">Category</th>
				<th rowspan="2">MF</th>
				<th rowspan="2">Avg Unit</th>
				
				<th colspan="3" align="center">Previous</th>
				<th rowspan="2">Prin Arr</th>
				<th rowspan="2">Arr Surchrg</th>
				<th rowspan="2">CS Calc</th>
				<th rowspan="2">Adj</th>
				<th rowspan="2">Status</th>
			</tr>
			<tr>
				<th>Reading</th>
				<th>Bill Date</th>
				<th>Due Date</th>
			</tr>
	';
	
	for($i=0; $i<sizeof($data_not_ok); $i++){
		$j = $i +1;
		$d = explode('$',$data[$data_not_ok[$i]]);
		echo '
			<tr class="content-list">
				<td>'. $j .'</td>
				<td>'. $d[0] .'</td>
				<td>'. $d[8] .'</td>
				<td><b>Old-</b> '. $d[21] .'<hr/><b>New-</b> '. $d[1] .'</td>
				<td><b>'. $d[2] .'</b><br/>'. $d[3] .'</td>
				<td>'. $d[12] .'</td>
				<td>'. $d[7] .' (Rent - Rs '. number_format((float)$d[19],2) .')</td>

				<td>'. (float)$d[4] .' KW</td>
				<td>'. $d[5] .'</td>
				<td>'. (float)$d[13] .'</td>
				<td>'. (float)$d[14] .'</td>

				<td>'. $d[11] .'</td>
				<td>'. $d[10] .'</td>
				<td>'. $d[20] .'</td>

				<td>'. number_format((float)$d[15],2) .'</td> <!-- need to check arrear -->
				<td>'. number_format((float)$d[16],2) .'</td>
				<td>'. number_format((float)$d[17],2) .'</td> <!-- Calc cs -->
				<td>'. number_format((float)$d[18],2) .'</td>

				<td style="color:red;">'. $reason[$data_not_ok_reason[$data_not_ok[$i]]] .'</td>
			</tr>
		';
	}

	echo '
		</table>
	';
}

//---------------------------------------------------------------------------------------------
// data ok
if(sizeof($data_ok)>0){
	echo '
		<h2>List of OK data </h2>
		<table id="listload" border="1" style="border-spacing:0px; font-size:12px;">
			<tr>
				<th rowspan="2" style="width:50px;">Sl no</th>
				<th rowspan="2">Sub div</th>
				<th rowspan="2">DTR no</th>
				<th rowspan="2" style="width:150px;">Consumer no</th>
				<th rowspan="2" style="width:250px;">Consumer</th>
				<th rowspan="2">Meter Slno</th>
				<th rowspan="2">Meter Type</th>

				<th rowspan="2">Load</th>
				<th rowspan="2">Category</th>
				<th rowspan="2">MF</th>
				<th rowspan="2">Avg Unit</th>
				
				<th colspan="3" align="center">Previous</th>
				<th rowspan="2">Prin Arr</th>
				<th rowspan="2">Arr Surchrg</th>
				<th rowspan="2">CS Calc</th>
				<th rowspan="2">Adj</th>
			</tr>
			<tr>
				<th>Reading</th>
				<th>Bill Date</th>
				<th>Due Date</th>
			</tr>
	';
	
	for($i=0; $i<sizeof($data_ok); $i++){
		$j = $i +1;
		$d = explode('$',$data[$data_ok[$i]]);
		echo '
			<tr>
				<td>'. $j .'</td>
				<td>'. $d[0] .'</td>
				<td>'. $d[8] .'</td>
				<td><b>Old-</b> '. $d[21] .'<hr/><b>New-</b> '. $d[1] .'</td>
				<td><b>'. $d[2] .'</b><br/>'. $d[3] .'</td>
				<td>'. $d[12] .'</td>
				<td>'. $d[7] .' (Rent - Rs '. number_format((float)$d[19],2) .')</td>

				<td>'. $d[4] .' KW</td>
				<td>'. $d[5] .'</td>
				<td>'. $d[13] .'</td>
				<td>'. (float)$d[14] .'</td>

				<td>'. $d[11] .'</td>
				<td>'. $d[10] .'</td>
				<td>'. $d[20] .'</td>

				<td>'. number_format((float)$d[15],2) .'</td> <!-- need to check arrear/balance -->
				<td>'. number_format((float)$d[16],2) .'</td>
				<td>'. number_format((float)$d[17],2) .'</td> <!-- Calc cs -->
				<td>'. number_format((float)$d[18],2) .'</td>
			</tr>
		';
	}
	
	echo '
		</table>
	';
}


?>