<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" href="inner-data/data-control/style/css/style.css" />
<?php
$link="inner-data/data-control/";
?>
<script>var clink = "<?php echo $link; ?>";</script>
</head>

<body>
		<div class="left-panel">
        	<div class="main-tag">
            	DEYAK
            	<sub style="color: #fff; font-size: 12px;"><?php echo $system_name; ?></sub>
            </div>
            <script>
			function linkit(l){
				window.location.href="?t="+l;
			}
			</script>
            <?php
			$uq = mysql_query("select name,fname,lname,contact,auth from zzuserdata where id='". $u ."'");
			$ud = mysql_fetch_object($uq);
			echo '<div class="user-tag" title="Login as '. $ud->name .'">'. $ud->name .'</div>';
			
			$subdiv = 0;
			$usq = mysql_query("select sid from zzuser_subdiv where uid='". $u ."'");
			if(mysql_num_rows($usq) >0){
				$usd = mysql_fetch_object($usq);
				$subdiv = $usd->sid;
			}
			echo '<script>var subdiv ="'. $subdiv .'";</script>';
			?>
            
            <div class="all-control stylist-scroll"><!-- 24 -->
            	
                <div class="control-tab <?php if(!isset($_GET['t']) || (isset($_GET['t']) && $_GET['t'] == '0')){echo "active";} ?>" <?php if(isset($_GET['t']) && $_GET['t'] != '0'){echo "onclick='linkit(0)'";}?>>Welcome</div>
                
				
				<?php
				$access_id=array();
				if($u >0){
					$aq = mysql_query("select access from zzauth where id='". $ud->auth ."'");
					if($aq && mysql_num_rows($aq)==1){
						$ad = mysql_fetch_object($aq);
						$access_id = json_decode(base64_decode($ad->access));
					}
				}
                ?>
                
                
                <?php
				$tq = mysql_query("select id,name from zzpagetag where status='0' order by srl");
				if($tq && mysql_num_rows($tq)>0){
					while($td = mysql_fetch_object($tq)){
						
						$mitem = $td->id;
						$mitem_name = $td->name;
						
						$subitem = array();
						$subitem_name = array();
						
						$pq = mysql_query("select id,name from zzpage where link='".$td->id ."' and status='0' order by srl");
						if($pq && mysql_num_rows($pq)>0){
							while($pd = mysql_fetch_object($pq)){
								if($u == 0 || ($u >0 && in_array($pd->id,$access_id))){
									$subitem[] = $pd->id;
									$subitem_name[] = $pd->name;
								}
							}
						}
						
						if(isset($_GET['t']) && (in_array($_GET['t'],$subitem))){$h= "auto";$c="glow";$a="act";$ac="1";}
						else{$h= "0px";$c="";$a="";$ac="0";}
						
						if(sizeof($subitem) >0){
							echo '
							<div class="submenu control-tab '. $c .'" data-menu="'. $mitem .'">'. $mitem_name .'<i class="'. $a .'" data-show="'. $ac .'"></i></div>
							<div class="control-tab-sub '. $c .'" id="submenu'. $mitem .'" style="height:'. $h .';">
								<ul>
							';        
									if(sizeof($subitem) == sizeof($subitem_name)){
										for($i=0;$i<sizeof($subitem);$i++){
											$d = $subitem[$i];
											if(isset($_GET['t']) && ($_GET['t'] == $subitem[$i])){
												$dp = 'class="active"';
											}
											else{
												$dp = 'onclick="linkit('. $subitem[$i] .')"';
											}
											echo '<li '. $dp .'><i></i>'. $subitem_name[$i] .'</li>';
										}
									}
									
							echo '
								</ul>
							</div>
							';
						}
					}
				}
				?>
                
                
               <!--------------------------  DEV Menu --------------------------------->
                
                <?php
				$mitem = 200;
				$mitem_name = "DEV Setup";
				$subitem = array(300,400);
				$subitem_name = array("Temp File Clear","Parameter Settings");
				
				if(isset($_GET['t']) && (in_array($_GET['t'],$subitem))){$h= "auto";$c="glow";$a="act";$ac="1";}
				else{$h= "0px";$c="";$a="";$ac="0";}
				?>
                <?php 
					if($u <1){
                		echo '
							<div class="submenu control-tab '. $c .'" data-menu="'. $mitem .'">'. $mitem_name .'<i class="'. $a .'" data-show="'. $ac .'"></i></div>
							<div class="control-tab-sub '. $c .'" id="submenu'. $mitem .'" style="height:'. $h .';">
								<ul>
							';        
									if(sizeof($subitem) == sizeof($subitem_name)){
										for($i=0;$i<sizeof($subitem);$i++){
											$d = $subitem[$i];
											if(isset($_GET['t']) && ($_GET['t'] == $subitem[$i])){
												$dp = 'class="active"';
											}
											else{
												$dp = 'onclick="linkit('. $subitem[$i] .')"';
											}
											echo '<li '. $dp .'><i></i>'. $subitem_name[$i] .'</li>';
										}
									}
									
							echo '
								</ul>
							</div>
						'; 
					}
                ?>
                
                
                <!--------------------------  Page Setup Menu --------------------------------->
                
                <?php
				$mitem = 100;
				$mitem_name = "Page Setup";
				$subitem = array(100,200);
				$subitem_name = array("Tag Manage","Page Manage");
				
				if(isset($_GET['t']) && (in_array($_GET['t'],$subitem))){$h= "auto";$c="glow";$a="act";$ac="1";}
				else{$h= "0px";$c="";$a="";$ac="0";}
				?>
                <?php 
					if($u <1){
                		echo '
							<div class="submenu control-tab '. $c .'" data-menu="'. $mitem .'">'. $mitem_name .'<i class="'. $a .'" data-show="'. $ac .'"></i></div>
							<div class="control-tab-sub '. $c .'" id="submenu'. $mitem .'" style="height:'. $h .';">
								<ul>
							';        
									if(sizeof($subitem) == sizeof($subitem_name)){
										for($i=0;$i<sizeof($subitem);$i++){
											$d = $subitem[$i];
											if(isset($_GET['t']) && ($_GET['t'] == $subitem[$i])){
												$dp = 'class="active"';
											}
											else{
												$dp = 'onclick="linkit('. $subitem[$i] .')"';
											}
											echo '<li '. $dp .'><i></i>'. $subitem_name[$i] .'</li>';
										}
									}
									
							echo '
								</ul>
							</div>
						'; 
					}
                ?>
                
                
                <script>
                function resetmenu(){
					$(".submenu").attr("class","submenu control-tab");
					$(".control-tab-sub").css({"height":"0px"});
					$(".control-tab-sub.glow").css({"height":"auto"});
					$(".submenu i[data-show=0]").removeAttr("class");
				}
				
				$(".submenu").click(function(){
					resetmenu();
					var m = $(this).data("menu");
					$("#submenu"+ m).css({"height":"auto"});
					$(this).attr("class","submenu control-tab glow");
					this.getElementsByTagName("i")[0].setAttribute("class","act");
				});
                </script>
                
                
                
                <!-------------------Agent Menu------------------------->
                <div class="control-tab <?php if((isset($_GET['t']) && $_GET['t'] == '1000')){echo "active";} ?>" <?php if(!isset($_GET['t']) || (isset($_GET['t']) && $_GET['t'] != '1000')){echo "onclick='linkit(1000)'";}?>>Change Password</div>
                
                
                
                <!------------------------ Logout ------------------------->
                
                <div class="control-tab" onclick="logout();">Logout</div>
                <script>
				function logout(){
					$.ajax({
						url:clink + "server/logout.php",
						type:"GET",
						success:function(){
							window.location.href="";
						},
						error:function(){
							alert("Internet Connection Problem");
						}
					});
				}
				
				$(function(){
					var t = function(){
						setTimeout(function(){
							$.ajax({
								url:clink + "server/active.php",
								type:"GET",
								success:function(res){
									res = $.trim(res);
									if(res == "1"){
										window.location.href="";
									}else{
										t();
									}
								}
							});
						},30000);
					}
					t();
				});
				</script>
                
            </div>
            
            <div class="developer">
            	<span class="tag">Designed &amp; Developed by</span><br /> <span class="name" onclick="deti_show();">ARK Informatics Tech Team</span>
                <script>
				/*var t="";
				function deti_show(){
					clearTimeout(t);
					document.getElementById("deve_d").style.display="block";
					t=setTimeout(function(){
						document.getElementById("deve_d").style.display="none";
					},120000);
				}*/
				function deti_show(){
					window.open("http://arkipl.com");
				}
				</script>
                <div class="dev-detail" id="deve_d" style="display:none; cursor:pointer;" onclick="document.getElementById('open_me').submit();">
                	<form id="open_me" action="http://arksolution.com" target="_blank"></form>
                    
                	<font style="font-size:14px;">Tripura, Agartala</font><br /><hr />
                    info@smararkinfo.com<br />
                    0381-230-1707
                </div>
            </div>
        </div>
</body>
</html>