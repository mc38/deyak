<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if($u= authenticate()){
	
	if(isset($_GET['s']) && $_GET['s'] !=""){
		
		$s = json_decode(base64_decode($_GET['s']));
		
		$cid 	= $s[0];
		
		$query = "select id,consumer_name,consumer_address,cid from consumer_details where cid='". $cid ."'" ;
		$q = mysql_query($query);
		
		if(mysql_num_rows($q) ==1){
			
			$d = mysql_fetch_object($q);
			
			$conid = $d->id;
			
			$pa 	= 0;
			$as 	= 0;
			$cs 	= 0;
			$cd 	= 0;
			$ad 	= 0;
			$nba 	= 0;
			$credit = 0;
			
			$baq = mysql_query("select * from bill_amount where conid='". $conid ."' and datetime<". $datetime ." order by id desc limit 0,11");
			if(mysql_num_rows($baq) >0){
				$bad = mysql_fetch_object($baq);

				$bdd_cp = 0;
				$bdq = mysql_query("select id,status,done from bill_details where conid='". $conid ."' order by id desc limit 0,1");
				if(mysql_num_rows($bdq)>0){
					$bdd = mysql_fetch_object($bdq);
					if(($bdd->status == 1) && ($bdd->done == 0)){
						$bdd_cp = 1;
					}
				}
				
				
				if($bad->credit != ""){	$credit = $bad->credit; }
				if($bad->pa != ""){				$pa 	= $bad->pa; }
				if($bad->asr != ""){			$as		= $bad->asr;}
				if($bad->cs != ""){				$cs 	= $bad->cs; }
				if($bad->cd != ""){				$cd 	= $bad->cd; }
				if($bad->adjustment != ""){		$ad 	= $bad->adjustment; }
				if($bad->nba != ""){			$nba 	= $bad->nba;}

				$tot_pa = round((($pa + $cd) -$ad), 2);
				$tot_as = round(($as + $cs), 2);
			
				echo '
					<div style="width:100%;">
						<h2>Payment details</h2>
						<table border="1">
							<tr>
								<td colspan="4" align="left">
									<div><b>DEYAK Id : </b>'. $d->cid.'</div>
									<div><b>Name : 	</b>'. $d->consumer_name .'</div>
									<div><b>Address : </b>'. $d->consumer_address .'</div>
								</td>
							</tr>
							<tr>
								<th colspan="2" style="font-size:28px;">Credit Balance</th>
							</tr>
							<tr>
								<td colspan="2" style="font-size:28px;" align="center">Rs '. number_format((float)$credit,2) .'</td>
							</tr>
							<tr>
								<th>Total Principal</th>
								<th>Total Surcharge</th>
							</tr>
							<tr>
								<td align="center">Rs '. number_format((float)$tot_pa,2) .'</td>
								<td align="center">Rs '. number_format((float)$tot_as,2) .'</td>
							</tr>
							<tr>
								<th colspan="2" style="font-size:28px;">Net Bill Amount</th>
							</tr>
							<tr>
								<td colspan="2" align="center"><h2>Rs '. number_format((float)$nba,2) .'</h2></td>
							</tr>
							
							<tr>
								<td colspan="2" align="center">
									<script>
									var cp = '. $bdd_cp .';
									var pa = '. (float)$tot_pa .';
									var as = '. (float)$tot_as .';
									var cr = '. (float)$credit .';
									var ad = 0;
									</script>

									<input id="pay_amount_calc" type="text" style="width:50%;margin:10px 0;" autocomplete="off" spellcheck="false" placeholder="Type Payment Amount for calculation"  value="" onkeydown="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" onkeyup="number_only(this.value,this.id);" />
									<button type="button" onclick="calculate_amount(this);">Calculate</button>
								</td>
							</tr>
							<tr>
								<th>New Principal</th>
								<th>New Surcharge</th>
							</tr>
							<tr>
								<td align="center">Rs <span id="pa_show">0.00</span></td>
								<td align="center">Rs <span id="as_show">0.00</span></td>
							</tr>
							<tr>
								<th>New Credit</th>
								<th>New Adjustment</th>
							</tr>
							<tr>
								<td align="center">Rs <span id="cr_show">0.00</span></td>
								<td align="center">Rs <span id="ad_show">0.00</span></td>
							</tr>

							<tr>
								<td colspan="2" align="center">
									<hr/>
									<script>
									var conid = '. $d->id .';
									var pay_amount_confirm = "";
									</script>
									<input id="pay_amount" type="text" style="width:50%;margin:10px 0;" autocomplete="off" spellcheck="false" placeholder="Type Payment Amount"  value="" onkeydown="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" onkeyup="number_only(this.value,this.id);" />
									<br/>
									<textarea id="pay_ref" type="text" style="width:50%; margin:10px 0; text-transform:none;" autocomplete="off" spellcheck="false" placeholder="Type reference details here"  value=""></textarea>
									<br/>
									<button type="button" onclick="pay(this);">Pay</button>
									<br/>
									<div id="pay_msg"></div>
								</td>
							</tr>
							
						</table>
						
					</div>
				';

				mysql_data_seek($baq,0);
				echo '
				<hr/>
				<h2>Transaction List</h2>
				
				<table border="1" style="font-size:12px;">
					<tr>
						<th rowspan="2" style="width:80px;">Date</th>
						<th rowspan="2" style="width:90px;">Time</th>
						<th rowspan="2">Reference No</th>
						<th rowspan="2">Current Demand</th>
						<th rowspan="2">Payment</th>
						<th rowspan="2">Principal</th>
						<th colspan="2">Surcharge</th>
						<th rowspan="2">Due Date</th>
						<th rowspan="2">Credit</th>
						<th rowspan="2">Adjust</th>
						<th rowspan="2">Action</th>
					</tr>
					<tr>
						<th>Old</th>
						<th>Current</th>
					</tr>
				';
				while($pd = mysql_fetch_object($baq)){
						
						if($pd->type == 0){
							echo '
							<tr>
								<td colspan="2" align="center">Migration</td>
							';
						}else{
							echo '
							<tr>
								<td align="center">'. date('d-m-Y', $pd->datetime) .'</td>
								<td align="center">'. date('h:i:s a', $pd->datetime) .'</td>
							';
						}

						$pa = 0;
						$as = 0;
						$cs = 0;
						$cr = 0;
						$ad = 0;
						if($pd->pa !=""){
							$pa = $pd->pa;
						}
						if($pd->asr !=""){
							$as = $pd->asr;
						}
						if($pd->cs !=""){
							$cs = $pd->cs;
						}
						if($pd->credit !=""){
							$cr = $pd->credit;
						}
						if($pd->adjustment !=""){
							$ad = $pd->adjustment;
						}

						if($pd->type == 1){
							echo '
								<td align="center">-</td>
								<td align="center">'. $pd->cd .'</td>
								<td align="center">-</td>
								<td align="center">'. $pa .'</td>
								<td align="center">'. $as .'</td>
								<td align="center">'. $cs .'</td>
								<td align="center">'. date('d-m-Y',$pd->due_datetime) .'</td>
								<td align="center">'. $cr .'</td>
								<td align="center">'. $ad .'</td>
								<td align="center">-</td>
							';
						}else if($pd->type == 0){
							echo '
								<td align="center">-</td>
								<td align="center">-</td>
								<td align="center">-</td>
								<td align="center">'. $pa .'</td>
								<td align="center">'. $as .'</td>
								<td align="center">'. $cs .'</td>
								<td align="center">-</td>
								<td align="center">'. $cr .'</td>
								<td align="center">'. $ad .'</td>
								<td align="center">-</td>
							';
						}else if($pd->type == 2){
							echo '
								<td align="center">-</td>
								<td align="center">-</td>
								<td align="center">'. $pd->payment .'</td>
								<td align="center">'. $pa .'</td>
								<td align="center">'. $as .'</td>
								<td align="center">'. $cs .'</td>
								<td align="center">-</td>
								<td align="center">'. $cr .'</td>
								<td align="center">'. $ad .'</td>
								<style>
								.pr{
									color:blue;
									cursor:pointer;
								}
								.pr:hover{
									text-decoration:underline;
								}
								</style>
								<td align="center"><span class="pr" onclick="print_receipt('. $pd->payid .');">Print</a></td>
							';
						}
						echo '</tr>';
				}
				echo '
				</table>
				
				';


			}else{
				echo '
					<div style="width:100%;">
						<h2>Payment details</h2>
						<table border="1">
							<tr>
								<td align="left">
									<div><b>DEYAK Id : </b>'. $d->cid.'</div>
									<div><b>Name : </b>'. $d->consumer_name .'</div>
									<div><b>Address : </b>'. $d->consumer_address .'</div>
								</td>
							</tr>
							
							<tr>
								<td align="center">
									<div><span style="color:#BD1010;">No payment data is available</span></div>
								</td>
							</tr>
							
						</table>
						
					</div>
				';
			}
				
		}
		else{
			echo '<span style="color:#BD1010;">Consumer not found</span>';
		}
	}else{
		echo '<span style="color:#BD1010;">Data Problem</span>';
	}
}
else{
	echo "Unauthorized user";
}
?>