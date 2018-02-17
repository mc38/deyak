<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/data/settings/data-consumer_powerfactor_edit/";
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
                	<div class="head">Category Details</div>
                    <div class="content">
                    	
                    	<table border="0">
                        	<tr>
                            	<td><label>Category Name</label></td>
                                <td class="med"></td>
                                
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<select id="cceta_id">
                                        <option value="">Select One</option>
                                        <?php
                                        $cq = mysql_query("select id,name from settings_consumer_cate");
                                        while($cd = mysql_fetch_object($cq)){
                                            echo '<option value="'. $cd->id .'">'. $cd->name .'</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <!-- --------------------------------- -->
                            
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3"><hr /></td>
                            </tr>
                            <tr>
                                <td colspan="3">PF varies from 0% to 100%</td>
                            </tr>

                            <!-- --------------------------------- -->

                            <tr class="gap">
                                <td colspan="3"><hr /></td>
                            </tr>
                            <tr>
                                <td colspan="3"><label>Threshold PF</label></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <input id="cceta_thsld" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type threshold PF" onkeyup="number_only(this.value,this.id);" onkeydown="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" />

                                    <button type="button" onclick="fix_th();" style="margin-left: 0px;">Fix</button>
                                    <button type="button" onclick="reset_th();">Reset</button>
                                </td>
                            </tr>
                            <tr class="gap">
                                <td colspan="3"><hr /></td>
                            </tr>

                            <!-- --------------------------------- -->

                            <tbody id="pf_body" style="display: none;">

                                <!-- --------------------------------- -->
                                <div>
                                    <tr>
                                        <td colspan="3"><h3>Lower than threshold</h3><hr/></td>
                                    </tr>

                                	<tr>
                                    	<td><label>PF From</label></td>
                                        <td class="med"></td>
                                        <td><label>PF To</label></td>
                                    </tr>
                                    <tr>
                                    	<td>
                                        	<input id="cceta_low_pff" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type PF From" onkeyup="number_only(this.value,this.id);" onkeydown="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" />
                                        </td>
                                        <td class="med"></td>
                                        <td>
                                        	<input id="cceta_low_pft" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type PF To (For balance PF leave blank)" onkeyup="number_only(this.value,this.id);" onkeydown="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" />
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td><label>Reading change</label></td>
                                        <td class="med"></td>
                                    	<td><label>Change in percentage per 1% PF change</label></td>
                                    </tr>
                                    <tr>
                                    	<td>
                                        	<select id="cceta_low_rchange">
                                                <option value="">Select One</option>
                                                <option value="+">Penalty</option>
                                                <option value="-">Rebate</option>
                                            </select>
                                        </td>
                                    	<td class="med"></td>
                                    	<td>
                                        	<input id="cceta_low_quant" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type change in percentage per 1% PF change"  onkeyup="number_only(this.value,this.id);" onkeydown="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" />
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td colspan="3" align="right"><button type="button" style="width:80px; margin-left:0px; margin-right:0px;" onclick="add_low_slab_data();">Add</button></td>
                                    </tr>
                                    <tr>
                                   		<td colspan="3">
                                         	<div class="content-spl">
                                            	<style>
        										.cus_mamnt_spl{
        											width:20% !important;
        										}
        										</style>
                                                <table border="0" class="content-list content-list-head">
                                                    <tr>
                                                        <th class="cus_sln">Sl No</th>
                                                        <th class="cus_rslab">PF</th>
                                                        <th class="cus_mamnt_spl" align="center">Reading Change</th>
                                                        <th class="cus_act">Action</th>
                                                    </tr>
                                                </table>
                                                <div class="list-scroll stylist-scroll" style="height:200px;">
                                                    <table border="0" class="content-list" id="slab_low_data"> 
                                                        
                                                        <!--
                                                        <tr>
                                                            <th class="cus_sln"><span>1</span></th>
                                                            <td class="cus_rslab" valign="top">75</td>
                                                            <td class="cus_mamnt_spl" valign="top">3.50</td>
                                                            <td class="cus_mamnt_spl" valign="top">15.00</td>
                                                            <td class="cus_act" valign="top"><button type="button">Del</button></td>
                                                        </tr>
                                                        -->
                                                        
                                                    </table>
                                                </div>
                                            
                                            </div>
                                    	</td>
                                        
                                    </tr>
                                    <tr class="gap">
                                    	<td colspan="3"><hr /></td>
                                    </tr>
                                </div>

                                <!-- --------------------------------- -->
                                <div>
                                    <tr>
                                        <td colspan="3"><h3>Upper than threshold</h3><hr/></td>
                                    </tr>

                                    <tr>
                                        <td><label>PF From</label></td>
                                        <td class="med"></td>
                                        <td><label>PF To</label></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input id="cceta_hgh_pff" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type PF From" onkeyup="number_only(this.value,this.id);" onkeydown="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" />
                                        </td>
                                        <td class="med"></td>
                                        <td>
                                            <input id="cceta_hgh_pft" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type PF To (For balance PF leave blank)" onkeyup="number_only(this.value,this.id);" onkeydown="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Reading change</label></td>
                                        <td class="med"></td>
                                        <td><label>Change in percentage per 1% PF change</label></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select id="cceta_hgh_rchange">
                                                <option value="">Select One</option>
                                                <option value="+">Penalty</option>
                                                <option value="-">Rebate</option>
                                            </select>
                                        </td>
                                        <td class="med"></td>
                                        <td>
                                            <input id="cceta_hgh_quant" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type change in percentage per 1% PF change"  onkeyup="number_only(this.value,this.id);" onkeydown="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" align="right"><button type="button" style="width:80px; margin-left:0px; margin-right:0px;" onclick="add_hgh_slab_data();">Add</button></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <div class="content-spl">
                                                <style>
                                                .cus_mamnt_spl{
                                                    width:20% !important;
                                                }
                                                </style>
                                                <table border="0" class="content-list content-list-head">
                                                    <tr>
                                                        <th class="cus_sln">Sl No</th>
                                                        <th class="cus_rslab">PF</th>
                                                        <th class="cus_mamnt_spl" align="center">Reading Change</th>
                                                        <th class="cus_act">Action</th>
                                                    </tr>
                                                </table>
                                                <div class="list-scroll stylist-scroll" style="height:200px;">
                                                    <table border="0" class="content-list" id="slab_hgh_data"> 
                                                        
                                                        <!--
                                                        <tr>
                                                            <th class="cus_sln"><span>1</span></th>
                                                            <td class="cus_rslab" valign="top">75</td>
                                                            <td class="cus_mamnt_spl" valign="top">3.50</td>
                                                            <td class="cus_mamnt_spl" valign="top">15.00</td>
                                                            <td class="cus_act" valign="top"><button type="button">Del</button></td>
                                                        </tr>
                                                        -->
                                                        
                                                    </table>
                                                </div>
                                            
                                            </div>
                                        </td>
                                        
                                    </tr>
                                    <tr class="gap">
                                        <td colspan="3"><hr /></td>
                                    </tr>
                                </div>

                                <!-- --------------------------------- -->
                                
                                <tr class="gap">
                                	<td></td>
                                    <td class="med"></td>
                                </tr>
                                
                                <tr>
                                	<td><div id="create_system_msg"></div></td>
                                    <td class="med"></td>
                                    <td align="right"><button type="button" style="width:200px; margin-left:0px; margin-right:0px;" onclick="edit_pf(this,1);">Update Power Factor</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
				
                
                
		        </div>             
                
                
                
                
            </div>
        </div>
        
        
    </div>
    
    
    
</div></body>
</html>