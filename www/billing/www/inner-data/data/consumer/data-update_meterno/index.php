<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/data/consumer/data-update_meterno/";
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
                                	<select id="en_subdiv">
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
                            <!-- ------------------------------------------------------------ -->
                            <tr>
                            	<td><label>DTR no</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<input id="en_dtrno" type="text" value="" autocomplete="off" spellcheck="false" placeholder="Type here" onkeydown="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" onkeyup="number_only(this.value,this.id);" />
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            <!-- ------------------------------------------------------------ -->
                            <tr>
                            	<td><label>Consumer No</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<input id="en_conno" type="text" value="" autocomplete="off" spellcheck="false" placeholder="Type here" onkeydown="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" onkeyup="number_only(this.value,this.id);" />
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            <!-- ------------------------------------------------------------ -->
                            <!-- ------------------------------------------------------------ -->
                            
                            
                            <!-- ------------------------------------------------------------ -->
                            <!-- ------------------------------------------------------------ -->
                            
                            <tr>
                            	<td><div id="create_system_msg"></div></td>
                                <td class="med"></td>
                                <td align="right">
                                	<button type="button" style="width:200px; margin-left:0px; margin-right:0px;" onclick="get_data(this);">Search</button>
                              	</td>
                            </tr>
                            
                            <!-- ------------------------------------------------------------ -->
                            <!-- ------------------------------------------------------------ -->
                            <tbody id="edit_data">
                            
                            </tbody>
                        </table>
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