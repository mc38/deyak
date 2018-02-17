<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/admin/dev/data-parameter_settings/";
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
                	<div class="head">Parameter Data</div>
                    <div class="content">
                    	<input type="hidden" id="pid" value="0" />
                    	<table border="0">
                        	<tr>
                            	<td><label>Parameter</label></td>
                                <td class="med"></td>
                                <td><label>Value</label></td>
                            </tr>
                            <tr class="gap">
                            	<td>
                                	<input id="parameter" type="text" autocomplete="off" spellcheck="false" placeholder="Type parameter name (Never Use space)" value="" style="text-transform:uppercase;" />
                                </td>
                                <td class="med"></td>
                                <td><input id="pvalue" type="text" autocomplete="off" spellcheck="false" placeholder="Parameter value" value="" /></td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            
                            
                            <tr>
                            	<td><div id="create_system_msg"></div></td>
                                <td class="med"></td>
                                <td align="right">
                                	<button type="button" style="width:150px; margin-left:10px; margin-right:0px; float:right;" onclick="update_para(this);">Update</button>
                                    <button type="button" style="width:150px; margin-left:0px; margin-right:0px; float:right;" onclick="blank_detail();">Cancel</button>
                             	</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="sub-form">
                	<div class="head">Parameter List</div>
                    
                    <button type="button" style="width:120px; margin-left:0px;" onclick="list_load();">Refresh</button>
                    
                    <div class="content-spl" id="data_print">        
                           
                        
                        
                    	<style>.cus_det{width:32% !important;}.cus_act{width:19% !important;}</style>
                    	<table border="0" class="content-list content-list-head">
                        	<tr>
                            	<th class="cus_sln">Sl No</th>
                                <th class="cus_det">Parameter</th>
                                <th class="cus_det">Value</th>
                                <th class="cus_act" style="">Action</th>
                            </tr>
                        </table>
                        <div class="list-scroll stylist-scroll">
                        <table border="0" class="content-list" id="data_list"> 
                        	
                            <!--
                        	<tr>
                            	<th class="cus_sln"><span>1</span></th>
                               	<td class="cus_date" valign="top">07-08-2014<br />08:10 PM</td>
                                <td class="cus_det" valign="top">
                                	<b>Chief Complain : </b>fever<br />
                                    <b>Disease : </b>ABCD<br />
                                </td>
                                <td class="cus_act" valign="top"><button type="button">View</button></td>
                         	</tr>
                            -->
                            
                        </table>
                        </div>
                        
                        
                        
                        
                    </div>
                </div>
                
		        </div>             
                
                
                
                
            </div>
        </div>
        
        
    </div>
    
    
    
    <div class="cover">
    	<div class="cover-main">
        
        </div>
    </div>
</div></body>
</html>