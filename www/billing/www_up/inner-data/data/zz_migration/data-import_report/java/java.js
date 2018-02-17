

////////////List///////////
function list_search(){
	var sd = document.getElementById("subdiv").value;
	
	if(sd !=""){
		var sdata = [sd];
		var str_s = $.base64.encode(JSON.stringify(sdata));
		var url = llink + "list.php?s="+str_s;
	
		scrollpos_get();
		var endfunction = function(){
			scrollpos_set();
		};
		
		var doc = document.getElementById("data_list");
		listload(doc,url,endfunction,10);
	}
	
	
	
}

////////////print///////////

function print_report(){
	var d = $("#data_list").html();
	if(d !=""){
		var w = window.open();
		d = d + '<style>@page{size: landscape; margin:10px;}body{size: landscape; margin:10px;}</style>';
		w.document.write(d);
		w.document.getElementsByTagName("table").item(0).style.fontSize="10px";
		w.print();
		w.close();
	}
}

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