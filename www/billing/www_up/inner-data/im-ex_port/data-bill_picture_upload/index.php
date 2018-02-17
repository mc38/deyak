<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/im-ex_port/data-bill_picture_upload/";
?>
<link rel="stylesheet" type="text/css" href="<?php echo $link; ?>style/css/style.css" />

<script>var llink = "<?php echo $link; ?>";</script>
<script src="<?php echo $link ?>java/java.js"></script>
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
            <h2><?php echo $page_tag; ?></h2>
            </div>
            
            <div class="form-data">
            	
				<style type="text/css">
                    #file_holder{
                        overflow-y:scroll;
                    }
                    .dz-preview{
                        padding: 5px !important;
                        float:left !important;
                        margin: 5px !important;
                        border:1px solid #ccc !important;
                        box-shadow: 1px 1px 2px 1px rgba(0,0,0,0.3) !important;
                        width: 100%;
                        height: auto;
                    }
                    .dz-image{
                        float:left;
                    }
                    .dz-details{
                        float:left;
                        margin: 30px 0;
                        text-align: left;
                        padding-left: 30px;
                    }
                    .dz-error-message{
                        float: right;
                        margin-top: 30px;
                        color: rgb(152, 38, 26);
                    }
                    .dz-progress{
                        float: right;
                        margin-top: 30px;
                        width: 200px;
                        height: 5px;
                        border:1px solid;
                    }
                    .dz-upload{
                        float: left;
                        height: 5px;
                        background:rgb(152, 38, 26);
                    }
                    .dz-success-mark{
                        display: none;
                    }
                    .dz-error-mark{
                        display: none;
                    }
                </style>
                
				
				<div id="detail_show" >
					<span id="tempdata" style="display:;"></span>
				
				<div class="sub-form">
                	<div class="head">Drag and Drop Image files here</div>
                    <div id="file_holder" class="content">
                    	
                    </div>
                </div>
                
		        </div>             
                
                
                
                
            </div>
        </div>
        
        
    </div>
    
    <div id="cover" class="cover">
        <div class="cover-main" id="cover_data">
            
        </div>
    </div>
</div></body>

</html>