<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	if(
		(isset($_SESSION['pay']) && $_SESSION['pay']!="")
		||
		(isset($_GET['p']) && $_GET['p']!="")
	){
		$pid = "";
		if(isset($_SESSION['pay'])){
			$pid = $_SESSION['pay'];
		}else if(isset($_GET['p'])){
			$pid = $_GET['p'];
		}


		$q = mysql_query("select * from bill_payment where id='". $pid ."'");
		if(mysql_num_rows($q) >0){
			$d = mysql_fetch_object($q);
			
			echo '
			<table border="1" style="border-spacing:0px; width:600px">
				<tr>
					<td colspan="2" align="center"><h2>Assam Power Distribution Corporation Limited</h2></td>
				</tr>
				<tr>
					<td colspan="2"><h5 style="padding:0; margin:0;">Payment Receipt Energy Bill</h5></td>
				</tr>
				<tr>
					<td colspan="2">Save this slip for future.</td>
				</tr>
				<tr>
					<td colspan="2" valign="top">
						<b>Receipt no: </b>'. $d->dataid .'<br />
					</td>
				</tr>
				<tr>
					<td colspan="2" valign="top">
						<b>Name : </b>'.strtoupper($d->conname).'<br />
						<hr/>
						<b>Paid Amount in Rupees: </b>'. number_format($d->amount,2) .'<br/>
						<b>Date Time: </b>'. date('d-m-Y h:i:s a',$d->datetime) .'<br/>
					</td>
				</tr>
				<tr style="height:100px">
					<td valign="bottom" align="center" width="50%">
						<b>Consumer Signature</b><br/>
					</td>
					<td valign="bottom" align="center" width="50%">
						<b>Authority Signature</b><br/>
					</td>
				</tr>
				<tr>
					<td colspan="2"><p>N.B. Donot lose this document.</p></td>
				</tr>
			</table>
		';
		}
		else{
			echo 'No data available';
		}
		
	}
	else{
		echo 'No data available';
	}
}
else{
	echo "Unauthorized user";
}
?>