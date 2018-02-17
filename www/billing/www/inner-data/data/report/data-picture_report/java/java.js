

////////////List///////////
function list_search(){
	var sd = document.getElementById("subdiv").value;
	var smon = document.getElementById("search_month").value;
	var syar = document.getElementById("search_year").value;
	
	if(sd !="" && smon !="" && syar !=""){
		var sdate = "01-"+ smon +"-"+ syar;

		var sdata = [sd,sdate];
		var str_s = $.base64.encode(JSON.stringify(sdata));
		var url = llink + "list.php?s="+str_s;
	
		scrollpos_get();
		var endfunction = function(){
			scrollpos_set();
		};
		
		var doc = document.getElementById("data_list");
		listload(doc,url,endfunction,30);
	}
	
	
	
}

////////////print///////////

function print_report(){
	var d = $("#data_print").html();
	if(d !=""){
		var w = window.open();
		d = d + '<style>@page{size: landscape; margin:0%; margin-top:10px;}body{size: landscape; margin:0%; margin-top:10px;}</style>';
		w.document.write(d);
		w.document.getElementById("data_list").removeAttribute("style");
		w.print();
		w.close();
	}
}

////////////////////////////////////////
function show_large_image(t,d){
	var id ="";
	if(t==0){id="imgn_"+ d;}else{id="imgb_"+ d;}
	var v = document.getElementById(id).value;
	$("#cover_data").html('<img src="'+ v +'" style="width:500px; height:auto;" /><br/><button type="button" onclick="close_cover();">Close</button>');
	$("#cover").css({"display":"block"});
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