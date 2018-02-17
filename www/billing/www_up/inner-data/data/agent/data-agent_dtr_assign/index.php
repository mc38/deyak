<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/data/agent/data-agent_dtr_assign/";
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
                	<div class="head">Entry Area</div>
                    <div class="content">
                    	<input type="hidden" id="adid" value="0" />
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
                                <td class="med"></td>
                                <td><label>DTR</label></td>
                            </tr>
                            <tr class="gap">
                            	<td>
                                	<?php
									if($subdiv >0){
										echo '
											<select id="agent_id">
                                    			<option value="">Select Agent</option>
										';
										
                                		$aq = mysql_query("select id,name from agent_info where subdiv='". $subdiv ."' and status='0'");
										if(mysql_num_rows($aq)>0){
											while($ad = mysql_fetch_object($aq)){
												echo '<option value="'. $ad->id .'">'. $ad->name .'</option>';
											}
										}
										
										echo '
                                			</select>
										';
									}else{
										
										echo '
											<select id="agent_id">
                                    			<option value="">Select Agent</option>
                                			</select>
										';
									}
									?>
                                </td>
                                <td class="med"></td>
                                <td><input id="dtr" type="text" autocomplete="off" spellcheck="false" placeholder="DTR no" value="" onkeydown="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" onkeyup="number_only(this.value,this.id);" /></td>
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
                	<div class="head">List</div>
                    
                    <button type="button" style="width:120px; margin-left:0px;" onclick="list_load();">Refresh</button>
                    
                    <div class="content-spl" id="data_print">        
                           
                        
                        
                    	<style>.cus_det{width:32% !important;}.cus_act{width:19% !important;}</style>
                    	<table border="0" class="content-list content-list-head">
                        	<tr>
                            	<th class="cus_sln">Sl No</th>
                                <th class="cus_det">Agent</th>
                                <th class="cus_det">DTR</th>
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