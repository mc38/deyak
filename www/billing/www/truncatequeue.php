<?php
include "db/command.php";
include "plugin/func/authentication.php";
if($u = authenticate()){
	if($u ==0){
		$table = array();
		$table[] = "in_data_queue";

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