<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/data/billing/data-bill_approve/";
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
                	<div class="head">Enter Details</div>
                    <div class="content">
                    	
                    	<table border="0">
                        	<!-- ------------------------------------------------------------ -->
                        	<tr>
                            	<td><label>Subdivision</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<select id="subdiv">
                                    	<option value="">Select Sub-Division</option>
                                        <?php
										$where = "";
										if($subdiv >0){
											$where = "where id='". $subdiv ."'";
										}
										$sub_q = mysql_query("select sid,name from settings_subdiv_data ".$where);
										while($sub_d = mysql_fetch_object($sub_q)){
											echo '<option value="'. $sub_d->sid .'">'. $sub_d->name .'</option>';
										}
										?>
                                    </select>
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            
                            <tr>
                            	<td><label>From Date</label></td>
                                <td class="med"></td>
                                <td><label>To Date</label></td>
                            </tr>
                            <tr class="gap">
                            	<td><input id="fdate" type="date" value="" /></td>
                                <td class="med"></td>
                                <td><input id="tdate" type="date" value="" /></td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            
                            <tr>
                            	<td><label>DEYAK ID</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<input id="cid" type="text" autocomplete="off" spellcheck="false" placeholder="Type Here" />
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            <!-- ------------------------------------------------------------ -->
                            <!-- ------------------------------------------------------------ -->
                            <tr>
                            	<td><div id="search_msg"></div></td>
                                <td class="med"></td>
                                <td align="right"><button id="subsearch" type="button" style="width:200px; margin-left:0px; margin-right:0px;" onclick="list_search(this);">Search</button></td>
                            </tr>
                        </table>
                    </div>
                </div>
				
                
                <div class="sub-form">
                    
                    <div class="content-spl" id="data_print">        
                           
                        <div class="list-scroll stylist-scroll" id="data_list" style="text-align:left; overflow:scroll; font-size:14px; font-family:Arial, Helvetica, sans-serif;">
                        
                        </div>
                        
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