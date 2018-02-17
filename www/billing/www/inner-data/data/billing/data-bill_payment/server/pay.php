<?php
require_once("../../../../../db/command.php");
require_once("../../../../../plugin/func/authentication.php");

if($u = authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		
		$data = json_decode(base64_decode($_POST['d']));
		
		$conid 		= $data[0];
		$amnt		= $data[1];
		$reference 	= $data[2];
		
		$conq = mysql_query("select id,consumer_name,cid from consumer_details where id='". $conid ."'");
		if(mysql_num_rows($conq) ==1){
			$cond = mysql_fetch_object($conq);
			if($amnt >0){

				$cd 	= 0;
				$pa 	= 0;
				$as 	= 0;
				$cs 	= 0;
				$ad 	= 0;
				$cr 	= 0;

				$q = mysql_query("select mydate,pa,asr,cs,cd,adjustment,due_datetime,credit from bill_amount where conid='". $conid ."' and datetime<". $datetime ." order by id desc limit 0,1");
				if(mysql_num_rows($q) >0){
					$d = mysql_fetch_object($q);

					$n_due_dtime = $d->due_datetime;
					if($d->credit !=""){		$cr			= $d->credit;}


					$bdq = mysql_query("select id,status,done from bill_details where conid='". $conid ."' order by id desc limit 0,1");
					if(mysql_num_rows($bdq)>0){
						$bdd = mysql_fetch_object($bdq);

						//billing payment
						$pcol = array();			$pval = array();
						$pcol[] = "conid";			$pval[] = $conid;
						$pcol[] = "conname";		$pval[] = $cond->consumer_name;
						$pcol[] = "amount";			$pval[] = $amnt;
						$pcol[] = "user";			$pval[] = $u;
						$pcol[] = "reference";		$pval[] = $reference;
						$pcol[] = "datetime";		$pval[] = $datetime;

						$pcol_str = implode(',',$pcol);	$pval_str = implode("','",$pval);
						mysql_query("insert into bill_payment(". $pcol_str .") values('". $pval_str ."')");
						$pid = mysql_insert_id();

						$pid_code = $datetime;
						if($u == "a"){
							$u  = 0;
						}
						$ucode = 1000 + $u;
						$user_code = "10".$u;
						$pid_dataid = $user_code . $pid_code;
						mysql_query("update bill_payment set dataid='". $pid_dataid ."' where id='". $pid ."'");

						if(($bdd->status == 1) && ($bdd->done == 0)){
							if($d->cd !=""){			$cd 		= $d->cd;}
							if($d->pa !=""){			$pa 		= $d->pa;}
							if($d->asr !=""){			$as 		= $d->asr;}
							if($d->cs !=""){			$cs			= $d->cs;}
							if($d->adjustment !=""){	$ad			= $d->adjustment;}
							$n_cr = $cr + $amnt;

							//billing amount
							$bacol = array();			$baval = array();
							$bacol[] = "mydate";		$baval[] = $d->mydate;
							$bacol[] = "conid";			$baval[] = $conid;
							$bacol[] = "datetime";		$baval[] = $datetime;
							$bacol[] = "type";			$baval[] = "2";
							$bacol[] = "due_datetime";	$baval[] = $n_due_dtime;
							$bacol[] = "credit";		$baval[] = $n_cr;
							$bacol[] = "payment";		$baval[] = $amnt;
							$bacol[] = "payid";			$baval[] = $pid;

						}else{

							if($d->cd !=""){			$cd 		= $d->cd;}
							if($d->pa !=""){			$pa 		= $d->pa;}
							if($d->asr !=""){			$as 		= $d->asr;}
							if($d->cs !=""){			$cs			= $d->cs;}
							if($d->adjustment !=""){	$ad			= $d->adjustment;}
							$n_cr = $cr + $amnt;
							$n_pa = round((($cd + $pa) - $ad), 2);
							$n_as = round(($as + $cs), 2);

							$payment_arr = array($n_as,$n_pa);
							$i =0;
							while($i<sizeof($payment_arr)){
								if($payment_arr[$i] > $n_cr){
									$payment_arr[$i] = $payment_arr[$i] - $n_cr;
									$n_cr = 0;
									break;
								}else{
									$n_cr = $n_cr - $payment_arr[$i];
									$payment_arr[$i] = 0;
								}
								$i++;
							}
							$n_as = $payment_arr[0];
							$n_pa = $payment_arr[1];
							$n_ad = 0;
							if($n_cr <10){$n_ad = $n_cr; $n_cr =0;}

							//billing amount
							$bacol = array();			$baval = array();
							$bacol[] = "mydate";		$baval[] = $d->mydate;
							$bacol[] = "conid";			$baval[] = $conid;
							$bacol[] = "datetime";		$baval[] = $datetime;
							$bacol[] = "type";			$baval[] = "2";
							$bacol[] = "pa";			$baval[] = $n_pa;
							$bacol[] = "asr";			$baval[] = $n_as;
							$bacol[] = "adjustment";	$baval[] = $n_ad;
							$bacol[] = "due_datetime";	$baval[] = $n_due_dtime;
							$bacol[] = "credit";		$baval[] = $n_cr;
							$bacol[] = "payment";		$baval[] = $amnt;
							$bacol[] = "payid";			$baval[] = $pid;

						}

						$bacol_str = implode(',',$bacol);	$baval_str = implode("','",$baval);
						mysql_query("insert into bill_amount(". $bacol_str .") values('". $baval_str ."')");
						$baid_n = mysql_insert_id();
					}

					$_SESSION['pay'] = $pid;
					echo $_POST['c'];
					
				}else{
					echo 3;
				}
			}else{
				echo 2;
			}
		}else{
			echo 1;
		}
	}
}
else{
	echo 0;
}
?>