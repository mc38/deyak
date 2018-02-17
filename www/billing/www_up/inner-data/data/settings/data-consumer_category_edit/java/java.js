var slab_t = "";

function slab_type(doc){
	slab_type_reset();
	var v = doc.value;
	if(v !=""){
		slab_t = v;
		if(v == "1"){
			$("#slab_spl").css({"display":""});
		}
	}
}
function slab_type_reset(){
	$("#slab_spl").css({"display":"none"});
	slab_spl_data = [];
	lab_spl_make_complete = false;
	put_slab_spl_data();
	slab_spl_form_set();
}

//////////////////////////////////////////////////////////////

var slab_spl_data = new Array();
var slab_spl_make_complete = false;

$(function(){
	slab_spl_form_set();
});

function slab_spl_form_set(){
	var mslab_splf_e = document.getElementById("cceta_spl_mslabf");
	if(slab_spl_data.length ==1){
			
			mslab_splf_e.value ="";
			
			document.getElementById("cceta_spl_mslabt").setAttribute("disabled","disabled");
	
			document.getElementById("cceta_spl_fchrg").setAttribute("disabled","disabled");
			document.getElementById("cceta_spl_mamnt").setAttribute("disabled","disabled");
			document.getElementById("cceta_spl_msubsidy").setAttribute("disabled","disabled");
		
	}
	else{
		mslab_splf_e.value = 1;
		document.getElementById("cceta_spl_mslabt").removeAttribute("disabled");
	
		document.getElementById("cceta_spl_fchrg").removeAttribute("disabled");
		document.getElementById("cceta_spl_mamnt").removeAttribute("disabled");
		document.getElementById("cceta_spl_msubsidy").removeAttribute("disabled");
	}
	mslab_splf_e.setAttribute("disabled","disabled");
}

function add_slab_spl_data(){
	var mslabf = document.getElementById("cceta_spl_mslabf").value;
	var mslabt = document.getElementById("cceta_spl_mslabt").value;
	
	var fchrg = document.getElementById("cceta_spl_fchrg").value;
	var mamnt = document.getElementById("cceta_spl_mamnt").value;
	var msubsidy = document.getElementById("cceta_spl_msubsidy").value;
	
	slab_spl_data_index = parseInt(slab_spl_data.length -1);
	if(mamnt !="" && ((slab_spl_data.length >0 && mslabt !="" && (parseInt(mslabt) > slab_spl_data[slab_spl_data_index][1] )) || (slab_spl_data.length ==0) || mslabt=="")){
		
			var dtemp = new Array();
			dtemp = [mslabf,mslabt,mamnt,fchrg,msubsidy];
			slab_spl_data = slab_spl_data.concat([dtemp]);
			
			document.getElementById("cceta_spl_mslabt").value="";
			document.getElementById("cceta_spl_fchrg").value="";
			document.getElementById("cceta_spl_mamnt").value="";
			document.getElementById("cceta_spl_msubsidy").value="0";
			
			slab_spl_form_set();
	}
	put_slab_spl_data();
}

function put_slab_spl_data(){
	slab_spl_make_complete = false;
	var i;
	var sout ="";
	for(i=0;i<slab_spl_data.length;i++){
		var j = i+1;
		
		var slabshow = slab_spl_data[i][0] +' to '+ slab_spl_data[i][1];
		if(slab_spl_data[i][1] ==""){
			var sto = Math.floor(parseInt(slab_spl_data[i][0]) -1);
			slabshow = "Over" + sto;
			slab_spl_make_complete = true;
		}
		
		if(slab_spl_data[i][1] =="" && slab_spl_data[i][0] ==1){
			slabshow = "All Units";
			slab_spl_make_complete = true;
		}
		
		
		var samount = parseFloat(slab_spl_data[i][2]);
		var famount = parseFloat(slab_spl_data[i][3]); 
		var subsidy = parseFloat(slab_spl_data[i][4]);
		
		sout +='<tr>';
		sout +='	<th class="cus_sln"><span>'+ j +'</span></th>';
		sout +='	<td class="cus_rslab" valign="top">'+ slabshow +'</td>';
		
		sout +='	<td class="cus_mamnt_spl" align="center" valign="top">Rs '+ samount.toFixed(2) +'</td>';
		sout +='	<td class="cus_mamnt_spl" align="center" valign="top">Rs '+ famount.toFixed(2) +'</td>';
		sout +='	<td class="cus_mamnt_spl" align="center" valign="top">Rs '+ subsidy.toFixed(2) +'</td>';
		sout +='	<td class="cus_act" valign="top"><button type="button" value="'+i+'" onclick="del_slab_spl_data(this.value);" style="width:40px; margin-right:0px;">Del</button></td>';
		sout +='</tr>';
	}
	document.getElementById("slab_spl_data").innerHTML = sout;
}

function del_slab_spl_data(d){
	
	if(d < slab_spl_data.length -1 && slab_spl_data[d][1] !=""){
		var last_slab_r = slab_spl_data[d][0];
		slab_spl_data[Math.floor(parseInt(d) +1)][0]=last_slab_r;
	}
	
	slab_spl_data.splice(d,1);
	put_slab_spl_data();
	slab_spl_form_set();
}


////////////////////////////////////////////////////////////////////
var slab_data = new Array();
var slab_make_complete = false;

$(function(){
	slab_form_set();
});

function slab_form_set(){
	var mslabf_e = document.getElementById("cceta_mslabf");
	if(slab_data.length >0){
		var lastno = slab_data.length -1;
		var lastm = slab_data[lastno][1];
		if(lastm == ""){
			mslabf_e.value ="";
			
			document.getElementById("cceta_mslabt").setAttribute("disabled","disabled");
	
			document.getElementById("cceta_fchrg").setAttribute("disabled","disabled");
			document.getElementById("cceta_mamnt").setAttribute("disabled","disabled");
			document.getElementById("cceta_msubsidy").setAttribute("disabled","disabled");
		}
		else{
			mslabf_e.value = Math.floor(parseInt(slab_data[lastno][1]) +1);
			
			document.getElementById("cceta_mslabt").removeAttribute("disabled");
	
			document.getElementById("cceta_fchrg").removeAttribute("disabled");
			document.getElementById("cceta_mamnt").removeAttribute("disabled");
			document.getElementById("cceta_msubsidy").removeAttribute("disabled");
		}
	}
	else{
		mslabf_e.value = 1;
		document.getElementById("cceta_mslabt").removeAttribute("disabled");
	
		document.getElementById("cceta_fchrg").removeAttribute("disabled");
		document.getElementById("cceta_mamnt").removeAttribute("disabled");
		document.getElementById("cceta_msubsidy").removeAttribute("disabled");
	}
	mslabf_e.setAttribute("disabled","disabled");
}

function add_slab_data(){
	var mslabf = document.getElementById("cceta_mslabf").value;
	var mslabt = document.getElementById("cceta_mslabt").value;
	
	var fchrg = document.getElementById("cceta_fchrg").value;
	var mamnt = document.getElementById("cceta_mamnt").value;
	var msubsidy = document.getElementById("cceta_msubsidy").value;
	
	slab_data_index = parseInt(slab_data.length -1);
	if(mamnt !="" && ((slab_data.length >0 && mslabt !="" && (parseInt(mslabt) > slab_data[slab_data_index][1] )) || (slab_data.length ==0) || mslabt=="")){
		
			var dtemp = new Array();
			dtemp = [mslabf,mslabt,mamnt,fchrg,msubsidy];
			slab_data = slab_data.concat([dtemp]);
			
			document.getElementById("cceta_mslabt").value="";
			document.getElementById("cceta_fchrg").value="";
			document.getElementById("cceta_mamnt").value="";
			document.getElementById("cceta_msubsidy").value="0";
			
			slab_form_set();
	}
	put_slab_data();
}

function put_slab_data(){
	slab_make_complete = false;
	var i;
	var sout ="";
	for(i=0;i<slab_data.length;i++){
		var j = i+1;
		
		var slabshow = slab_data[i][0] +' to '+ slab_data[i][1];
		if(slab_data[i][1] ==""){
			var sto = Math.floor(parseInt(slab_data[i][0]) -1);
			slabshow = "Over" + sto;
			slab_make_complete = true;
		}
		
		if(slab_data[i][1] =="" && slab_data[i][0] ==1){
			slabshow = "All Units";
			slab_make_complete = true;
		}
		
		
		var samount = parseFloat(slab_data[i][2]);
		var famount = parseFloat(slab_data[i][3]); 
		var subsidy = parseFloat(slab_data[i][4]); 
		
		sout +='<tr>';
		sout +='	<th class="cus_sln"><span>'+ j +'</span></th>';
		sout +='	<td class="cus_rslab" valign="top">'+ slabshow +'</td>';
		
		sout +='	<td class="cus_mamnt_spl" align="center" valign="top">Rs '+ samount.toFixed(2) +'</td>';
		sout +='	<td class="cus_mamnt_spl" align="center" valign="top">Rs '+ famount.toFixed(2) +'</td>';
		sout +='	<td class="cus_mamnt_spl" align="center" valign="top">Rs '+ subsidy.toFixed(2) +'</td>';
		sout +='	<td class="cus_act" valign="top"><button type="button" value="'+i+'" onclick="del_slab_data(this.value);" style="width:40px; margin-right:0px;">Del</button></td>';
		sout +='</tr>';
	}
	document.getElementById("slab_data").innerHTML = sout;
}

function del_slab_data(d){
	
	if(d < slab_data.length -1 && slab_data[d][1] !=""){
		var last_slab_r = slab_data[d][0];
		slab_data[Math.floor(parseInt(d) +1)][0]=last_slab_r;
	}
	
	slab_data.splice(d,1);
	put_slab_data();
	slab_form_set();
}


//////////////////////////////////////////////////////////////////////
function edit_category(doc,t){
	var cceta_id = document.getElementById("cceta_id").value;
	var eduty = document.getElementById("cceta_eduty").value;
	var schrg = document.getElementById("cceta_schrg").value;
	var fppa = document.getElementById("cceta_fppa").value;

	if(t == 0){
		if(cceta_id !="" && ( eduty !="" || schrg !="" || fppa !="" )){
			show_msg(doc,"create_system_msg_1","#000099","Please Wait...");
			
			var c = getcode(10);
			
			var data = new Array();
			data = [0,cceta_id,eduty,schrg,fppa];
			var dd = $.base64.encode(JSON.stringify(data));
			
			var conf = confirm("Please confirm the data you want to edit?");
			
			if(conf){
				doc.setAttribute("disabled","disabled");
				
				$.ajax({
					url	:llink + "server/add_new.php",
					type:"POST",
					data:{c:c,d:dd},
					success:function(response){
						//alert(response);
						response = $.trim(response);
						if(c == response){
							show_msg(doc,"create_system_msg_1","#135400","Successful");
							blank_catedetail();
						}
						else if(response == '0'){
							show_msg(doc,"create_system_msg_1","#98261A","Authentication problem");
						}
						else{
							show_msg(doc,"create_system_msg_1","#98261A",response);
						}
					},
					error:function(){
						show_msg(doc,"create_system_msg_1","#98261A","Network connection problem");
					}
				});
			}
			else{
				show_msg(doc,"create_system_msg_1","#98261A","");
			}
		}
		else{
			show_msg(doc,"create_system_msg_1","#98261A","Select Category and Update something");
		}
	}else{
		if(cceta_id !="" && slab_data.length >0 && slab_spl_data.length <2 ){
			if(slab_make_complete){
				show_msg(doc,"create_system_msg","#000099","Please Wait...");
				
				var c = getcode(10);
				
				var data = new Array();
				data = [1,cceta_id, $.base64.encode(JSON.stringify(slab_data)),$.base64.encode(JSON.stringify(slab_spl_data))];
				var dd = $.base64.encode(JSON.stringify(data));
				
				var conf = confirm("Please confirm the data you want to edit?");
				
				if(conf){
					doc.setAttribute("disabled","disabled");
					
					$.ajax({
						url	:llink + "server/add_new.php",
						type:"POST",
						data:{c:c,d:dd},
						success:function(response){
							//alert(response);
							response = $.trim(response);
							if(c == response){
								show_msg(doc,"create_system_msg","#135400","Successful");
								blank_catedetail();
							}
							else if(response == '0'){
								show_msg(doc,"create_system_msg","#98261A","Authentication problem");
							}
							else{
								show_msg(doc,"create_system_msg","#98261A",response);
							}
						},
						error:function(){
							show_msg(doc,"create_system_msg","#98261A","Network connection problem");
						}
					});
				}
				else{
					show_msg(doc,"create_system_msg","#98261A","");
				}
			}
			else{
				show_msg(doc,"create_system_msg","#98261A","Complete slab making");
			}
		}
		else{
			show_msg(doc,"create_system_msg","#98261A","Select Category and fill up slab");
			
		}
	}
	
	
}



function blank_catedetail(){
	document.getElementById("cceta_id").value = "";
	document.getElementById("cceta_eduty").value ="";
	document.getElementById("cceta_schrg").value ="";
	document.getElementById("cceta_fppa").value = "";
	
	document.getElementsByName("sd").item(0).checked="checked";
	slab_type_reset();
	
	slab_data = [];
	put_slab_data();
	slab_form_set();
	
	slab_spl_data = [];
	put_slab_spl_data();
	slab_spl_form_set();
}


////////////print///////////

function print_report(){
	var d = $("#data_print").html();
	if(d !=""){
		var w = window.open();
		w.document.write(d);
		w.document.getElementsByTagName("table").item(0).style.display="none";
		w.document.getElementsByTagName("table").item(1).border="1";
		w.document.getElementsByTagName("table").item(1).width="100%";
		w.print();
		w.close();
	}
}

//////////////// List ////////////////////



// supporting functions
function show_msg(doc,id,color,msg){
	$("#"+id).css({"color":color});
	document.getElementById(id).innerHTML = msg;
	doc.removeAttribute("disabled");
}

function generate_code(len){
	var data="123456789";
	var i;
	var out="";
	for(i=0;i<len;i++){
		out += data.charAt(Math.floor(Math.random() * data.length));
	}
	return out;
}