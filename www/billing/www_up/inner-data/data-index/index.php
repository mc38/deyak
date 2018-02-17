<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
$link="inner-data/data-index/";
?>
<link rel="stylesheet" type="text/css" href="<?php echo $link; ?>style/css/style.css" />

<script>var llink = "<?php echo $link; ?>";</script>
<script src="inner-data/data-index/java/java.js"></script>
</head>

<body><div class="body" align="center">
<div class="head">
	<font>Deyak</font>
	<sub style="color: #fff; font-size: 12px;"><?php echo $system_name; ?></sub>
</div>

<div class="main-container">
	<div class="content">
    	<span class="code-tag" id="tag">Login Here</span>
    	<div class="code-input">
        	<div><input type="text" autocomplete="off" spellcheck="false" placeholder="Enter Username" id="uname" /></div>
            <div><input type="password" placeholder="Enter Password" id="upass" /></div>
            <div><button type="button" id="loginbut" onclick="login_action(this);">Login</button></div>
            <div id="emsg" class="errormsg"></div>
       	</div>
    </div>

		<style>
		.down{
			width: 324px;
			height: 40px;
			font-size: 18px;
			margin-top: 12px;
			border-radius: 5px;
			background: linear-gradient(rgba(152,38,26,0.8),rgba(152,38,26,1));
			color: #fff;
			outline: none;
			border: 1px solid rgba(86, 22, 13,1);
			box-shadow: 0px 2px 7px 1px rgba(0,0,0,0.1785);
			text-shadow: 0px 0px 2px rgba(86, 22, 13,0.4);
			cursor: pointer;
		}
		</style>
		<script>
		function downloadapp(d){
			var link_data = new Array();
			link_data[0] = "https://play.google.com/store/apps/details?id=com.arkipl.deyakagentbillinglite";
			link_data[1] = "http://apdcl.deyak.in/app/DEYAKAgentBillingLite_v_3_0_0.apk";
			link_data[2] = "http://apdcl.deyak.in/app/DEYAKAgentBillingLite_v_3_1_1.apk";
			window.location.href=link_data[d];
		}
		</script>
		<div><button class="down" type="button" onclick="downloadapp(0);">Google Play Store DEYAK App Vs 1.8.0</button></div>
		<div><button class="down" type="button" onclick="downloadapp(1);">DEYAK App Offline Download Vs 3.0.0 (Stable)</button></div>
		<div><button class="down" type="button" onclick="downloadapp(2);">DEYAK App Offline Download Vs 3.1.1</button></div>

</div>

</div></body>
</html>
