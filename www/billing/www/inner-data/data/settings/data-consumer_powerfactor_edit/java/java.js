///////////////////////////////////////////////////////////////////////////////////////////
//GLOBAL Variable
var slab_t = "";
var thpf = "";

var slab_low_data = new Array();
var slab_low_make_complete = false;

var slab_hgh_data = new Array();
var slab_hgh_make_complete = false;

///////////////////////////////////////////////////////////////////////////////////////////
$(document).ready(function(){
	$("#cceta_id").change(function(){
		reset_th();
	});
})

function fix_th(){
	var ceta_id = document.getElementById("cceta_id").value;
	if(ceta_id !=""){
		var t = document.getElementById("cceta_thsld").value;
		if(t != ""){
			if(parseInt(t) <=100 && parseInt(t) >=0){
				document.getElementById("cceta_thsld").setAttribute("disabled","disabled");
				thpf = t;
				slab_low_form_set();
				slab_hgh_form_set();

				$("#pf_body").css({"display":""});
			}else{
				alert("PF value must be between 0 to 100");
			}
		}else{
			alert("Important to add threshold PF value");
		}
	}else{
		alert("Select category first");
	}
}


function reset_th(){
	slab_t = "";
	thpf = "";
	document.getElementById("cceta_thsld").value = "";
	document.getElementById("cceta_thsld").removeAttribute("disabled");

	slab_low_data = new Array();
	slab_low_make_complete = false;
	slab_low_form_set()
	put_low_slab_data();

	slab_hgh_data = new Array();
	slab_hgh_make_complete = false;
	slab_hgh_form_set()
	put_hgh_slab_data();


	$("#pf_body").css({"display":"none"});
}

//////////////////////////////////////////////////////////////////////////////////////////////
// PF threshold Under

function slab_low_form_set(){
	var pff_e = document.getElementById("cceta_low_pff");

	if(slab_low_data.length >0){
		var lastno = slab_low_data.length -1;
		var lastm = slab_low_data[lastno][1];
		if(lastm == ""){
			pff_e.value ="";
			
			document.getElementById("cceta_low_pft").setAttribute("disabled","disabled");
	
			document.getElementById("cceta_low_rchange").setAttribute("disabled","disabled");
			document.getElementById("cceta_low_quant").setAttribute("disabled","disabled");
		}
		else{
			pff_e.value = Math.floor(parseInt(slab_low_data[lastno][1]));
			
			document.getElementById("cceta_low_pft").removeAttribute("disabled");
	
			document.getElementById("cceta_low_rchange").removeAttribute("disabled");
			document.getElementById("cceta_low_quant").removeAttribute("disabled");
		}
	}
	else{
		pff_e.value = thpf;
		document.getElementById("cceta_low_pft").removeAttribute("disabled");
	
		document.getElementById("cceta_low_rchange").removeAttribute("disabled");
		document.getElementById("cceta_low_quant").removeAttribute("disabled");
	}
	pff_e.setAttribute("disabled","disabled");
}

function add_low_slab_data(){
	var pff = document.getElementById("cceta_low_pff").value;
	var pft = document.getElementById("cceta_low_pft").value;
	
	var rchange = document.getElementById("cceta_low_rchange").value; 
	var quant = document.getElementById("cceta_low_quant").value;
	
	var slab_low_data_index = parseInt(slab_low_data.length -1);
	if(quant !="" && rchange !="" && ((slab_low_data.length >0 && pft !="" && (parseInt(pft) < slab_low_data[slab_low_data_index][1] )) || (slab_low_data.length ==0 && pff !="" && (parseInt(pft) < parseInt(pff))) || (pft == ""))){
		
		var dtemp = new Array();
		dtemp = [pff,pft,rchange,quant];
		slab_low_data = slab_low_data.concat([dtemp]);
		
		document.getElementById("cceta_low_pft").value="";
		document.getElementById("cceta_low_rchange").value="";
		document.getElementById("cceta_low_quant").value="";
		
		slab_low_form_set();
	}
	put_low_slab_data();
}

function put_low_slab_data(){
	slab_low_make_complete = false;
	var i;
	var sout ="";
	for(i=0;i<slab_low_data.length;i++){
		var j = i+1;
		
		var slabshow = slab_low_data[i][0] +' to '+ slab_low_data[i][1];
		if(slab_low_data[i][1] ==""){
			var sto = Math.floor(parseInt(slab_low_data[i][0]));
			slabshow = "Below" + sto;
			slab_low_make_complete = true;
		}
		
		if(slab_low_data[i][1] =="" && slab_low_data[i][0] ==1){
			slabshow = "All Units";
			slab_low_make_complete = true;
		}
		
		
		var chng = slab_low_data[i][2] +""+ slab_low_data[i][3];
		
		sout +='<tr>';
		sout +='	<th class="cus_sln"><span>'+ j +'</span></th>';
		sout +='	<td class="cus_rslab" valign="top">'+ slabshow +'</td>';
		
		sout +='	<td class="cus_mamnt_spl" align="center" valign="top">'+ chng +'%</td>';
		sout +='	<td class="cus_act" valign="top"><button type="button" value="'+i+'" onclick="del_low_slab_data(this.value);" style="width:40px; margin-right:0px;">Del</button></td>';
		sout +='</tr>';
	}
	document.getElementById("slab_low_data").innerHTML = sout;
}

function del_low_slab_data(d){
	
	if(d < slab_low_data.length -1 && slab_low_data[d][1] !=""){
		var last_slab_r = slab_low_data[d][0];
		slab_low_data[Math.floor(parseInt(d) +1)][0]=last_slab_r;
	}
	
	slab_low_data.splice(d,1);
	put_low_slab_data();
	slab_low_form_set();
}



//////////////////////////////////////////////////////////////////////////////////////////////
// PF threshold Above

function slab_hgh_form_set(){
	var pff_e = document.getElementById("cceta_hgh_pff");

	if(slab_hgh_data.length >0){
		var lastno = slab_hgh_data.length -1;
		var lastm = slab_hgh_data[lastno][1];
		if(lastm == ""){
			pff_e.value ="";
			
			document.getElementById("cceta_hgh_pft").setAttribute("disabled","disabled");
	
			document.getElementById("cceta_hgh_rchange").setAttribute("disabled","disabled");
			document.getElementById("cceta_hgh_quant").setAttribute("disabled","disabled");
		}
		else{
			pff_e.value = Math.floor(parseInt(slab_hgh_data[lastno][1]));
			
			document.getElementById("cceta_hgh_pft").removeAttribute("disabled");
	
			document.getElementById("cceta_hgh_rchange").removeAttribute("disabled");
			document.getElementById("cceta_hgh_quant").removeAttribute("disabled");
		}
	}
	else{
		pff_e.value = thpf;
		document.getElementById("cceta_hgh_pft").removeAttribute("disabled");
	
		document.getElementById("cceta_hgh_rchange").removeAttribute("disabled");
		document.getElementById("cceta_hgh_quant").removeAttribute("disabled");
	}
	pff_e.setAttribute("disabled","disabled");
}

function add_hgh_slab_data(){
	var pff = document.getElementById("cceta_hgh_pff").value;
	var pft = document.getElementById("cceta_hgh_pft").value;
	
	var rchange = document.getElementById("cceta_hgh_rchange").value; 
	var quant = document.getElementById("cceta_hgh_quant").value;
	
	var slab_hgh_data_index = parseInt(slab_hgh_data.length -1);
	if(quant !="" && rchange !="" && ((slab_hgh_data.length >0 && pft !="" && (parseInt(pft) > slab_hgh_data[slab_hgh_data_index][1] )) || (slab_hgh_data.length ==0 && pff !="" && (parseInt(pft) > parseInt(pff))) || (pft == ""))){
		
		var dtemp = new Array();
		dtemp = [pff,pft,rchange,quant];
		slab_hgh_data = slab_hgh_data.concat([dtemp]);
		
		document.getElementById("cceta_hgh_pft").value="";
		document.getElementById("cceta_hgh_rchange").value="";
		document.getElementById("cceta_hgh_quant").value="";
		
		slab_hgh_form_set();
	}
	put_hgh_slab_data();
}

function put_hgh_slab_data(){
	slab_hgh_make_complete = false;
	var i;
	var sout ="";
	for(i=0;i<slab_hgh_data.length;i++){
		var j = i+1;
		
		var slabshow = slab_hgh_data[i][0] +' to '+ slab_hgh_data[i][1];
		if(slab_hgh_data[i][1] ==""){
			var sto = Math.floor(parseInt(slab_hgh_data[i][0]));
			slabshow = "Above" + sto;
			slab_hgh_make_complete = true;
		}
		
		if(slab_hgh_data[i][1] =="" && slab_hgh_data[i][0] ==1){
			slabshow = "All Units";
			slab_hgh_make_complete = true;
		}
		
		
		var chng = slab_hgh_data[i][2] +""+ slab_hgh_data[i][3];
		
		sout +='<tr>';
		sout +='	<th class="cus_sln"><span>'+ j +'</span></th>';
		sout +='	<td class="cus_rslab" valign="top">'+ slabshow +'</td>';
		
		sout +='	<td class="cus_mamnt_spl" align="center" valign="top">'+ chng +'%</td>';
		sout +='	<td class="cus_act" valign="top"><button type="button" value="'+i+'" onclick="del_hgh_slab_data(this.value);" style="width:40px; margin-right:0px;">Del</button></td>';
		sout +='</tr>';
	}
	document.getElementById("slab_hgh_data").innerHTML = sout;
}

function del_hgh_slab_data(d){
	
	if(d < slab_hgh_data.length -1 && slab_hgh_data[d][1] !=""){
		var last_slab_r = slab_hgh_data[d][0];
		slab_hgh_data[Math.floor(parseInt(d) +1)][0]=last_slab_r;
	}
	
	slab_hgh_data.splice(d,1);
	put_hgh_slab_data();
	slab_hgh_form_set();
}


///////////////////////////////////////////////////////////////////////////////////////////////////////
//update data

function edit_pf(doc){
	var cceta_id = document.getElementById("cceta_id").value;

	if(cceta_id !="" && thpf !="" && slab_low_data.length >0 && slab_hgh_data.length >0 ){
		if(slab_low_make_complete && slab_hgh_make_complete){
			show_msg(doc,"create_system_msg","#000099","Please Wait...");
			
			var c = getcode(10);
			
			var data = new Array();
			data = [cceta_id, thpf, $.base64.encode(JSON.stringify(slab_low_data)),$.base64.encode(JSON.stringify(slab_hgh_data))];
			var dd = $.base64.encode(JSON.stringify(data));
			
			var conf = confirm("Please confirm the data you want to update?");
			
			if(conf){
				doc.setAttribute("disabled","disabled");
				
				$.ajax({
					url	:llink + "server/update.php",
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

function blank_catedetail(){
	document.getElementById("cceta_id").value = "";
	reset_th();
}

/////////////////////////////////////////////////////
//supporting
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