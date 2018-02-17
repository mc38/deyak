<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/admin/data-page_registration/";
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
                	<div class="head">Page Manage</div>
                    <div class="content">
                    	
                    	<table border="0">
                        	<tr>
                            	<td><label>Tag Select</label></td>
                                <td class="med"></td>
                                
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<select id="tag_name">
                                    	<option value="">Select Tag</option>
                                    	<?php
										$q = mysql_query("select id,name from zzpagetag order by srl");
										if($q && mysql_num_rows($q)>0){
											while($d = mysql_fetch_object($q)){
												echo '<option value="'.$d->id.'">'.$d->name.'</option>';
											}
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
                            	<td><label>Page Name</label></td>
                                <td class="med"></td>
                                
                            </tr>
                            <tr class="gap">
                            	<td colspan="3"><input id="page_name" type="text" autocomplete="off" spellcheck="false" placeholder="Type here" value="" /></td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            
                            <tr>
                            	<td><label>Page Location</label></td>
                                <td class="med"></td>
                                
                            </tr>
                            <tr class="gap">
                            	<td colspan="3"><input id="page_location" type="text" autocomplete="off" spellcheck="false" placeholder="Type here" value="" style="text-transform:none;" /></td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            
                            <tr>
                            	<td><div id="create_page_msg"></div></td>
                                <td class="med"></td>
                                <td align="right"><button type="button" style="width:200px; margin-left:0px; margin-right:0px;" onclick="create_page(this);">Create Page</button></td>
                            </tr>
                        </table>
                    </div>
                </div>
				
                <div class="sub-form">
                	<div class="head">Page List</div>
                    
                    <button type="button" style="width:120px; margin-left:0px;" onclick="list_load();">Refresh</button>
                    
                    <div class="content-spl" id="data_print">        
                           
                    	<table border="0" class="content-list content-list-head">
                        	<tr>
                            	<th class="cus_sln">Sl No</th>
                                <th class="cus_det">Detail</th>
                                <th class="cus_act">Action</th>
                            </tr>
                        </table>
                        <div class="list-scroll stylist-scroll">
                        <table border="0" class="content-list" id="page_list"> 
                        	
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