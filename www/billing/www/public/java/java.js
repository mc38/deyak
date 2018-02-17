function file_download(doc){
	document.getElementById("d").value = $(doc).data("type");
	document.getElementById("sd").value = $(doc).data("subdiv");
	document.getElementById("sdd").value = $(doc).data("mydate");
	document.getElementById("xd").value = $(doc).data("xdate");
	document.getElementById("xf").value = $(doc).data("xfrom");
	document.getElementById("xt").value = $(doc).data("xtotl");
	document.getElementById("pd").value = $(doc).data("pdate");
	document.getElementById("pt").value = $(doc).data("ptotl");
	document.getElementById("ct").value = $(doc).data("ctotl");
	$("#download_form").submit();
	
	reset_data();
}

function reset_data(){
	document.getElementById("d").value = "";
	document.getElementById("sd").value ="";
	document.getElementById("sdd").value ="";
	document.getElementById("xd").value = "";
	document.getElementById("xf").value = "";
	document.getElementById("xt").value = "";
	document.getElementById("pd").value = "";
	document.getElementById("pt").value = "";
	document.getElementById("ct").value = "";
}

//////////////////////////////////////////
var col = "#FEC942";
function highlight_d(doc){
	$(".high_trig").css({"background":"none"});
	$(".high_eff").css({"background":"none"});
	
	var d = $(doc).data("high");
	var s = $(doc).data("show");
	
	$(".high_trig").data("show","0");
	
	if(s == '0'){
		var hlist = JSON.parse($.base64.decode(d));
		var i;
		for(i=0;i<hlist.length;i++){
			$("#h_"+hlist[i]).css({"background":col});	
		}
		$(doc).css({"background":col});
		;
		$(doc).data("show","1");
		return null;
	}else if(s == '1'){
		$(doc).css({"background":"none"});
		$(".high_eff").css({"background":"none"});
		
		$(doc).data("show","0");
		return null;
	}
}





//////////////// List ////////////////////


function list_search(doc){
	var str = document.getElementById("search_str").value;
	var smon = document.getElementById("search_month").value;
	var syar = document.getElementById("search_year").value;
	
	
	if(str !="" && smon !="" && syar !=""){
		var sdate = "01-"+ smon +"-"+ syar;
		
		var data = [str.toLowerCase(),sdate];
		var str_s = $.base64.encode(JSON.stringify(data));
		
		$("#data_list").html('<tr><td>Loading...</td></tr>');
		$("#data_list").load(llink + "list.php?s="+str_s,function(){scrollpos_set();});
	}
	else{
		show_msg(doc,"search_msg","#98261A","Fill up all fields");
	}
}

////////////print///////////


////////////////////////////////////////


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