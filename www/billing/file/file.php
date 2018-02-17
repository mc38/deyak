<?php
if(isset($_GET) && isset($_GET['t']) && $_GET['t']!=""){
	if($_GET['t'] == "image"){
		include "image/plugin/show.php";
	}
}
?>