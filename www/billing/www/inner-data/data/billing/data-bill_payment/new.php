<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");
require_once("../../../../plugin/func/index.php");

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
			
			$cq = mysql_query("select cid,oldcid,consumer_name,consumer_address,subdiv_id from consumer_details where id='". $d->conid ."'");
			$cd = mysql_fetch_object($cq);

			$sq = mysql_query("select name from settings_subdiv_data where sid='". $cd->subdiv_id ."'");
			$sd = mysql_fetch_object($sq);

			$p_recv = "";
			if($d->insert_from == 0){
				$aq = mysql_query("select fname,lname from zzuserdata where id='". $d->user ."'");
				if(mysql_num_rows($aq)>0){
					$ad = mysql_fetch_object($aq);
					$p_recv = strtoupper($ad->fname) .' '. strtoupper($ad->lname) .' (Portal)';
				}else{
					$p_recv = 'User data not available (Portal)';
				}
				
			}else{
				$aq = mysql_query("select name from agent_info where id='". $d->user ."'");
				if(mysql_num_rows($aq)>0){
					$ad = mysql_fetch_object($aq);

					$p_recv = strtoupper($ad->name) .' (Agent App)';
				}else{
					$p_recv = 'Agent data not available (Agent App)';
				}
			}

			
			echo '
			<div style="border:1px solid #182746;">
				<table border="1" style="border-spacing:0px; width:600px">
					<tr>
						<td colspan="2" align="center"><h2>Assam Power Distribution Corporation Limited</h2></td>
					</tr>
					<tr>
						<td colspan="2"><h3 style="padding:0; margin:0;">Payment Receipt Energy Bill</h3></td>
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
							<b>Sub-Division Name: </b>'. strtoupper($sd->name) .'
							<hr/>
							<b>Consumer No : </b>'.strtoupper($cd->oldcid).'<br />
							<b>DEYAK Id : </b>'.strtoupper($cd->cid).'<br />
							<b>Name : </b>'.strtoupper($cd->consumer_name).'<br />
							<b>Address : </b>'.strtoupper($cd->consumer_address).'<br />
							<hr/>
							<b>Received an amount </b>'. number_format($d->amount,2) .' ('. rupee_2_str($d->amount) .')<br/>
							<b>Date Time: </b>'. date('d-m-Y h:i:s a',$d->datetime) .'<br/>
						</td>
					</tr>
					<tr style="height:100px">
						<td valign="bottom" align="center" width="50%">
							<b>Consumer Signature</b><br/>
						</td>
						<td valign="bottom" align="center" width="50%">
							'. $p_recv .'
							<hr/>
							<b>Received By</b><br/>
						</td>
					</tr>
					<tr>
						<td colspan="2"><p>N.B. Donot lose this document.</p></td>
					</tr>
				</table>
			</div>
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