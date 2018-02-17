<?php
session_start();
include "db/command.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="cache-control" content="no-store" />
<meta http-equiv="expires" content="-1" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />

<?php
$system_name = "";
$sn_q = mysql_query("select value from zzdev where parameter='SYSTEM'");
if(mysql_num_rows($sn_q) ==1){
	$sn_d = mysql_fetch_object($sn_q);
	$system_name = $sn_d->value;
}
?>

<title>Deyak <?php echo $system_name; ?></title>
<script src="plugin/java/plugins/jquery.min.js"></script>
<script src="plugin/java/plugins/moment.js"></script>
<script src="plugin/java/plugins/jquery.base64.min.js"></script>
<script src="plugin/java/plugins/jquery.base64.js"></script>
<script src="plugin/java/plugins/jquery.form.js"></script>
<script src="plugin/java/plugins/jquery.jkey.js"></script>
<script src="plugin/java/plugins/jquery.jkey.min.js"></script>
<script src="plugin/java/plugins/dropzone.js"></script>



<script src="plugin/java/raw/java.js"></script>
<script src="plugin/java/raw/listload.js"></script>
<link rel="stylesheet" type="text/css" href="plugin/style/css/style.css" />
<link rel="stylesheet" type="text/css" href="plugin/style/css/listload.css" />

<?php
include "plugin/func/index.php";
require_once("plugin/func/authentication.php");
?>
</head>

<body><div class="body">


<?php
		if(((isset($_GET['t']) && $_GET['t'] == '0_3'))){
			include "inner-data/im-ex_port/spl_export_public_data/download.php";
		}
		else{

			if($u=authenticate()){
				if($u == 'a'){
					$u ='0';
				}

				$page_tag = "";
				$aq = mysql_query("select uactive,auth from zzuserdata where id='". $u ."' and status ='0'");
				if($aq && mysql_num_rows($aq)==1){
					$ad = mysql_fetch_object($aq);
					if($ad->uactive == ""){

						if((!isset($_GET['t']) || (isset($_GET['t']) && $_GET['t'] == '0'))){
							$page_tag = "Welcome";
							include "inner-data/data-welcome/index.php";
						}
						/*__________________________________________________________________________*/
						else if(((isset($_GET['t']) && $_GET['t'] == '0_1_1'))){
							include "inner-data/im-ex_port/android_db_export_blank/download.php";
						}
						else if(((isset($_GET['t']) && $_GET['t'] == '0_1_2'))){
							include "inner-data/im-ex_port/android_db_export_rejected/download.php";
						}
						else if(((isset($_GET['t']) && $_GET['t'] == '0_1_3'))){
							include "inner-data/im-ex_port/android_db_export_unbilled/download.php";
						}
						else if(((isset($_GET['t']) && $_GET['t'] == '0_2_1'))){
							include "inner-data/print/bill/index.php";
						}
						/*_____________________________________________________________________________*/
						else if(((isset($_GET['t']) && $_GET['t'] == '1000'))){
							$page_tag = "Change Password";
							include "inner-data/data-change_pass/index.php";
						}
						/*_____________________________________________________________________________*/
						else if(((isset($_GET['t']) && $_GET['t'] == '100')) && (isset($user) && $u ==0)){
							$page_tag = "Page Tag Manage";
							include "inner-data/admin/data-page_tag_registration/index.php";
						}
						else if(((isset($_GET['t']) && $_GET['t'] == '200')) && (isset($user) && $u ==0)){
							$page_tag = "Page Manage";
							include "inner-data/admin/data-page_registration/index.php";
						}
						else if(((isset($_GET['t']) && $_GET['t'] == '300')) && (isset($user) && $u ==0)){
							$page_tag = "Temp File Clear";
							include "inner-data/admin/dev/data-temp_clear/index.php";
						}
						else if(((isset($_GET['t']) && $_GET['t'] == '400')) && (isset($user) && $u ==0)){
							$page_tag = "Parameter Settings";
							include "inner-data/admin/dev/data-parameter_settings/index.php";
						}

						else{

							$page_id=array(); $page_loc = array(); $ptag_arr = array();
							$access_id=array();

							$pq = mysql_query("select id,name,location from zzpage where status='0'");
							if($pq && mysql_num_rows($pq)>0){
								while($pd = mysql_fetch_object($pq)){
									$page_id[]=$pd->id;
									$page_loc[$pd->id]=$pd->location;
									$ptag_arr[$pd->id]=$pd->name;
								}
							}


							if($u==0){
								$access_id = $page_id;
							}
							else{
								$accq = mysql_query("select access from zzauth where id='". $ad->auth ."'");
								$accd = mysql_fetch_object($accq);
								$access_id = json_decode(base64_decode($accd->access));
							}

							if(isset($_GET['t']) && in_array($_GET['t'],$access_id) && in_array($_GET['t'],$page_id)){
								$page_tag = $ptag_arr[$_GET['t']];
								include $page_loc[$_GET['t']];
							}
							else{

								echo '
									<script>$(function(){ window.location.href="?"; });</script>
								';

							}
						}
					}else{
						include "inner-data/data-change_pass/index.php";
					}
				}else{
					index_page();
				}
			}else{
				index_page();
			}
		}


function index_page(){
	global $system_name;
	include "inner-data/data-index/index.php";
	$_SESSION['us']="";
	session_destroy();
}
?>
</div></body>
<link rel="stylesheet" type="text/css" href="plugin/style/css/lowresu.css" />
</html>
