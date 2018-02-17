<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/data/consumer/data-consumer_ledger/";
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
                	<div class="head">Consumer Data</div>
                    <div class="content">
                    	
                    	<table border="0">
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
                        
                        
                        	<tr>
                            	<td><label>Consumer ID</label></td>
                                <td class="med"></td>
                                <input type="hidden" id="con_mtr" />
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<input id="con_srch" type="text" autocomplete="off" spellcheck="false" placeholder="Type Consumer ID (Last Six Digit)" value="" style="width:560px;" onkeydown="number_only(this.value,this.id);" onkeyup="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" />
                                    <button type="button" style="width:120px; margin-left:7px; margin-right:0px;" onclick="check_consumer(this);">Check</button>
                                    <div id="action_msg"></div>
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                        </table> 
                        
                    </div>
                </div>
				
                
                
                <div class="sub-form">
                	<div class="head">Ledger</div>
                    
                    <button type="button" style="width:120px; margin-left:0px;" onclick="list_show();">Refresh</button>
                    <button type="button" style="width:120px; margin-left:0px;" onclick="print_report();">Print</button>
                    
                    <div class="content-spl" id="data_print">
                        <div class="list-scroll stylist-scroll" id="data_list" style="text-align:left; overflow:scroll; padding:5px;">
                        
                        </div>
                    </div>
                </div>
                
		        </div>             
                
                
                
                
            </div>
        </div>
        
        
    </div>
    <script>
	function show_image_big(s){
		$("#big_i").attr("src",s);
		$("#big_image").css({"display":""});
	}
	function close_image(){
		$("#big_image").css({"display":"none"});
	}
	</script>
    <!------------------- Temporary ---------------------------->
    <div id="big_image" style="display:none; width:100%; height:100%; position:fixed; top:0px; left:0px; text-align:center; vertical-align:middle; background:rgba(0,0,0,0.5);">
    	<div style="margin-top:30px;">
    		<img id="big_i" src="" />
        </div>
        <div>
        	<button type="button" onclick="close_image();">Close</button>
        </div>
    </div>
    
    
</div></body>
</html>