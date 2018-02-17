<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/im-ex_port/csv_import_data/";
$ulink = $link;
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
            	
				
                
				
				<div id="detail_show" >
					<span id="tempdata" style="display:;"></span>
				
				
				
                
                
                
                <div class="sub-form">
                	<div class="head">CSV File upload and read</div>
                    <div class="content">
                        <div align="right">
                           <!-- 
                           <button style="width:300px;" type="button" onclick="window.location.href='http://apdcl.deyak.in/doc/format_of_data_migration.csv';">Download CSV Format</button>
                           -->
                        </div>
                    	<table border="0">
                        	<tr>
                            	<td align="center">
                                	<button type="button" id="file_upload_but" style="margin:0;">Upload</button>
                                    <div id="file_read" style="display:none;">
                                    	<button type="button" onclick="file_import_but();" style="margin:0;">Import</button>
                                        <button type="button" onclick="file_reset_but();" style="margin:0;">Reset</button>
                                    </div>
                                   	<form id="upload_form" action="<?php echo $ulink ?>server/upload.php" method="post" enctype="multipart/form-data">
                                    	<input type="hidden" name="n" id="file_name" value="" />
                                    	<input id="file_upload" name="file" type="file" style="display:none;" />
                                        <button id="file_upload_submit" type="button" style="display:none;"></button>
                                    </form>
                                    
                                    
                                    <style>
                                    .loading_holder{
										width:100%;
										height:20px;
										border:1px solid #000;
										box-shadow:0px 0px 2px 1px rgba(0,0,0,0.3);
										border-radius:10px;
										text-align:left;
									}
									.loading{
										width:30%;
										height:100%;
										background:linear-gradient(rgba(152,38,26,0.6),rgba(152,38,26,1));
										border-radius:10px;
										float:left;
									}
                                    </style>
                                    <div id="loadingh" class="loading_holder" style="display:none;">
                                    	<div id="loading" class="loading"></div>
                                    </div>
                                    
                                </td>
                                <td class="med"></td>
                                <td>
                                	<div id="file_details">
                                    
                                    </div>
                                </td>
                            </tr>
                            
                            <tr>
                            	<td><div id="file_upload_msg"></div></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="sub-form">
                	<div class="head">Report</div>
                    <button type="button" onclick="print_report();">Print</button>
                    <div class="content-spl">
                        <div class="list-scroll stylist-scroll" id="data_list" style="text-align:left; overflow:scroll;">
                        
                        </div>
                    </div>
                </div>
                            
                
                </div>
                
                
            </div>
        </div>
        
        
    </div>
    
    
    
</div></body>
</html>