

////////////List///////////
function list_search(){
	var sm = document.getElementById("search_month").value;
	var sy = document.getElementById("search_year").value;
	
	var sid = document.getElementById("subdiv").value;
	
	if(sm !="" && sy !="" && sid !="" ){
		var sdata = [sm,sy,sid];
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
	var d = $("#data_print").html();
	if(d !=""){
		d = d + '<style>@page{size: letter portrait;}body{size: letter portrait; }#data_list{overflow:hidden !important;}</style>';
		var w = window.open();
		w.document.write(d);
		setTimeout(function(){
			w.print();
			w.close();
		},2000);
		
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