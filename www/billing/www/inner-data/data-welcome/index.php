<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/data-welcome/";
?>
<link rel="stylesheet" type="text/css" href="<?php echo $link; ?>style/css/style.css" />

<script>var llink = "<?php echo $link; ?>";</script>
</head>

<body><div class="body" align="center">
    
    <div class="main-container">
    	
		<?php
			include "inner-data/data-control/index.php";
		?>
        <?php
		$sid = "";
		if(isset($_GET['s']) && $_GET['s'] !=""){
			$sid = $_GET['s'];
		}
		?>
        
    	<div class="main-panel">
        	
        	<div class="action-tab">
            <h2>Welcome</h2>
            </div>
            
            <div class="form-data">
            	
                <div class="content">
                	<div class="code-tag">Welcome</div>
                    <div class="code-tag_1">to</div>
                    <div class="code-tag_2">Deyak <?php echo $system_name; ?></div>
                    
                    <div class="code-tag_3">Login completed by <span style="text-transform:uppercase;"><?php $uq = mysql_query("select fname,lname from zzuserdata where id='".$u."'"); $ud= mysql_fetch_object($uq); echo $ud->fname ." ". $ud->lname; ?></span></div>
                </div>

                <div><button style="width: 324px;" type="button" value="apdcl_server_upload.php" onclick="window.open(this.value,'_blank');">Download APDCL Server Bill File</button></div>

                <div><button style="width: 324px;" type="button" value="management/" onclick="window.open(this.value,'_blank');">Go to Management Report Section</button></div>

                <?php
                if($u == 0){
                    echo '
                    <script>
                    function trancatequeue(doc){
                        window.open(doc.value,"_blank");
                    }
                    </script>
                    <div><button style="width: 324px;" type="button" value="truncatequeue.php" onclick="trancatequeue(this);">Delete Previous Month Queue</button></div>';
                }
                ?>
            
            </div>
            
        </div>
        
        
    </div>
    
    
    
</div></body>
</html>