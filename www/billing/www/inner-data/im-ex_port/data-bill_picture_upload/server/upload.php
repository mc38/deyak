<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	if (isset($_POST['s']) && $_POST['s']!="") {
		$sq = mysql_query("select ftp_img_server,ftp_img_user,ftp_img_pass from settings_subdiv_data where id='". $_POST['s'] ."'");
		if(mysql_num_rows($sq) >0){
			$sd = mysql_fetch_object($sq);

			$ftp_server = $sd->ftp_img_server;
			$ftp_user 	= $sd->ftp_img_user;
			$ftp_pass 	= $sd->ftp_img_pass;
	
			if(! file_exists("temp/")){
				mkdir("temp");
			}
			
			date_default_timezone_set('Asia/Kolkata');
			$datetime=date($_SERVER['REQUEST_TIME']);
			
			//////////////////////
			$fname = $_FILES['file']['name'];
			$tmp_name = $_FILES['file']['tmp_name'];
			
			$ftp_conn = ftp_connect($ftp_server);
			if($ftp_conn){
				$ftp_login = ftp_login($ftp_conn, $ftp_user, $ftp_pass);

				if (ftp_put($ftp_conn, $fname, $tmp_name, FTP_BINARY)){
					ftp_close($ftp_connect);
					echo 1;
				}

				/*
				$destination = "../../../../../file/image/data/".$fname ;
				//$destination = "temp/".$fname ;
				
				if(! file_exists($destination)){
					$q = mysql_query("select id from m_data where in_meterpic='". $fname ."'");
					if(mysql_num_rows($q) ==1){
						$tmp_name = $_FILES['file']['tmp_name'];
						$target_file = $destination;
						// Open temp file
						$out = fopen($target_file, "a");
						
						if ( $out ) {
							// Read binary input stream and append it to temp file
							$in = fopen($tmp_name, "rb");
							if ( $in ) {
								while ( $buff = fread( $in, 1048576 ) ) {
									fwrite($out, $buff);
								}   
							}
							fclose($in);
							fclose($out);
						}

						//print_r($_SERVER);

						echo 1;
					}else{
						echo 3;
					}
				}else{
					echo 2;
				}
				*/
			}
		}else{
			echo 4;
		}
	}else{
		echo 4;
	}
}else{
	echo 0;
}	
?>