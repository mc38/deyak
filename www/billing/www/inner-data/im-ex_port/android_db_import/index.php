<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/im-ex_port/android_db_import/";
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
            <h2>Import Android DB</h2>
            </div>
            
            <div class="form-data">
            	
				
                
				
				<div id="detail_show" >
					<span id="tempdata" style="display:;"></span>
				
                
                <div class="sub-form">
                	<div class="head">Android DB File upload and read</div>
                    <div class="content">
                    	<table border="0">
                            <?php
                            if($subdiv >0){
                                echo '<input id="subdiv_id" type="hidden" value="'. $subdiv .'" />';
                            }else{
                                echo '
                                    <tr>
                                        <td><label>Sub-Division <span id="subdiv_lvl" style="color:#000;">ID</span></label></td>
                                        <td class="med"></td>
                                    </tr>
                                    <tr class="gap">
                                        <td colspan="3">
                                            <input id="subdiv_srch" type="text" autocomplete="off" spellcheck="false" placeholder="Type Subdiv ID and Check" value="" style="width:560px;" />
                                            <button type="button" id="subdiv_ch_but" style="width:120px; margin-left:7px; margin-right:0px; display:;" onclick="check_subdiv(this);">Check</button>
                                            <button type="button" id="subdiv_re_but" style="width:120px; margin-left:7px; margin-right:0px; display:none;" onclick="reset_subdiv(this);">Reset</button>
                                            <div id="create_subdiv_msg"></div>
                                        </td>
                                    </tr>
                                    <input id="subdiv_id" type="hidden" value="" />
                                    <tr class="gap">
                                        <td></td>
                                        <td class="med"></td>
                                    </tr>
                                ';
                            }
                            ?>

                            <tr>
                                <td><label>Agent</label></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <?php
                                    if($subdiv >0){
                                        
                                        echo '
                                            <select id="agent_id">
                                                <option value="">Select Agent</option>
                                        ';
                                        
                                        $aq = mysql_query("select id,name from agent_info where subdiv='". $subdiv ."' and status='0'");
                                        if(mysql_num_rows($aq)>0){
                                            while($ad = mysql_fetch_object($aq)){
                                                echo '<option id="'. $ad->id .'">'. $ad->name .'</option>';
                                            }
                                        }
                                        
                                        echo '
                                            </select>
                                        ';
                                        
                                    }else{
                                        
                                        echo '
                                            <select id="agent_id" onchange="upload_show();">
                                                <option value="">Select Agent</option>
                                            </select>
                                        ';
                                    }
                                    ?>
                                </td>
                            </tr>

                            <tr class="gap">
                                <td></td>
                                <td class="med"></td>
                            </tr>

                            <tbody id="upload_body" style="display: none;">
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

                            </tbody>
                            
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