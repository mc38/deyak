<?php
include "db/command.php";
include "plugin/func/authentication.php";
if($u = authenticate()){
	if($u ==0){
		$table = array();
		$table[] = "bill_amount";
		$table[] = "bill_details";
		$table[] = "bill_payment";
		$table[] = "bill_reading";
		$table[] = "consumer_details";

		$table[] = "in_data_queue";
		$table[] = "m_data";
		$table[] = "m_data_reject";

		$query = "";
		for($i=0;$i<sizeof($table);$i++){
			$query = "TRUNCATE ". $table[$i] ."";
			mysql_query($query);

			echo $table[$i] ." -> truncated <br/>";
		}
		//var_dump($table);
		echo "________________________________________<br/>";
		echo "All table empty now";
	}else{
		echo "Dev only";
	}
}else{
	echo "Authentication required";
}
?>