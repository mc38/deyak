<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/data/agent/data-agent_backdoorpass/";
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
                	<div class="head">Agent Details</div>
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
                        </table>
                    </div>
                </div>
				
                <div class="sub-form">
                	<div class="head">Search Agent</div>
                    <div class="content">
                    	<table border="0">
                        	<tr>
                            	<td><div class="errormsg" id="errormsg" style="display:none;"></div></td>
                            </tr>
                        	<tr>
                            	<td><label>Agent name</label></td>
                            </tr>
                            <tr>
                            	<td colspan="2"><input id="search_str" type="text" autocomplete="off" spellcheck="false" placeholder="Type Here" /></td>
                            </tr>
                            <tr>
                            	<td colspan="2"><button type="button" style="width:120px; margin-left:0px;" onclick="list_search();">Search</button></td>
                            </tr>
                            
                        </table>
                    </div>
                </div>
                
                <div class="sub-form">
                	<div class="head">Agent List</div>
                    
                    <button type="button" style="width:120px; margin-left:0px;" onclick="list_search();">Refresh</button>
                    
                    <div class="content-spl" id="data_print">        
                           
                        
                        
                    	
                    	<table border="0" class="content-list content-list-head">
                        	<tr>
                            	<th class="cus_sln">Sl No</th>
                            	<th class="cus_date">Expire</th>
                                <th class="cus_det">Name</th>
                                <th class="cus_act">Password</th>
                            </tr>
                        </table>
                        <div class="list-scroll stylist-scroll">
                        <table border="0" class="content-list" id="agent_list"> 
                        	
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
    
    
    
</div></body>
</html>