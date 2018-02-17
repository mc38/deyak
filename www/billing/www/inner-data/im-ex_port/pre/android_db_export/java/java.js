
function reset_subdiv(doc){
	
	var ele = document.getElementById("subdiv_srch");
	ele.value = "";
	ele.removeAttribute("disabled");
	$("#subdiv_ch_but").css({"display":""});
	$("#subdiv_re_but").css({"display":"none"});
	show_msg(doc,"create_subdiv_msg","#98261A","");
	document.getElementById("subdiv_id").value = "";
	document.getElementById("subdiv_lvl").innerHTML = "ID";
	
	var ag_html='<option value="">Select Agent</option>';
	document.getElementById("agent_id").innerHTML = ag_html;
	document.getElementById("search_month").value="";
	document.getElementById("search_year").value="";
}

function check_subdiv(doc){
	
	$("#secondary_data").css({"display":"none"});
	
	document.getElementById("subdiv_id").value = "";
	document.getElementById("subdiv_srch").removeAttribute("disabled");
	var sid = document.getElementById("subdiv_srch").value;
	if( sid !="" ){
		doc.setAttribute("disabled","disabled");
		
		var c = getcode(10);
		
		var data = new Array();
		data = [sid];
		var dd = $.base64.encode(JSON.stringify(data));
		
		
		$.ajax({
			url:llink + "server/ccheck.php",
			type:"POST",
			data:{c:c,d:dd},
			success:function(res){
				//alert(res);
				if(res == "0"){
					show_msg(doc,"create_subdiv_msg","#98261A","Data problem");
				}
				else if(res == "1"){
					show_msg(doc,"create_subdiv_msg","#98261A","Subdiv ID doesnot exists");
				}
				else if(res == "2"){
					show_msg(doc,"create_subdiv_msg","#98261A","Agent not exists");
				}
				else{
					var rd_j = $.base64.decode(res);
					var rd_arr = JSON.parse(rd_j);
					if(rd_arr[0] == c){
						show_msg(doc,"create_subdiv_msg","#135400","Successful");
						document.getElementById("subdiv_id").value = sid;
						$(doc).css({"display":"none"});
						$("#subdiv_re_but").css({"display":""});
						document.getElementById("subdiv_srch").setAttribute("disabled","disabed");
						document.getElementById("subdiv_srch").value = rd_arr[2];
						document.getElementById("subdiv_lvl").innerHTML = "Name";
						
						
						var agent_str = rd_arr[3];
						var ag_j = $.base64.decode(agent_str);
						var ag_arr = JSON.parse(ag_j);
						
						var i; var ag_html='<option value="">Select Agent</option>';
						for(i=0;i<ag_arr.length;i++){
							var agn_j = $.base64.decode(ag_arr[i]['name']);
							var agn_arr = JSON.parse(agn_j);
							
							ag_html += '<option value="'+ ag_arr[i]['id'] +'">'+ agn_arr[0] +' '+ agn_arr[1] +'</option>';
						}
						document.getElementById("agent_id").innerHTML = ag_html;
					}
				}
			},
			error:function(){
				show_msg(doc,"create_subdiv_msg","#98261A","Network connection problem");
			}
		});
		
	}
}


///////////////////////download//////////////////////////////////////////
function download_start(doc){
	var s = document.getElementById("subdiv_id").value;
	var a = document.getElementById("agent_id").value;
	var dm = document.getElementById("search_month").value;
	var dy = document.getElementById("search_year").value;
	
	if(s !="" && a !="" && dm !="" && dy !=""){
		show_msg(doc,"search_msg","#98261A","");
		
		document.getElementById("frm_s").value = s;
		document.getElementById("frm_a").value = a;
		document.getElementById("frm_d").value = dm+"-"+dy;
		
		document.getElementById("frm_andown").submit();
		
		document.getElementById("agent_id").value = "";
		document.getElementById("search_month").value="";
		document.getElementById("search_year").value="";
		
	}
	else{
		show_msg(doc,"search_msg","#98261A","Fill up all field");
	}
}

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

var scroll_pos =0;
function scrollpos_get(){
	scroll_pos = $("div.list-scroll").scrollTop();
}
function scrollpos_set(){
	$("div.list-scroll").scrollTop(scroll_pos);
}