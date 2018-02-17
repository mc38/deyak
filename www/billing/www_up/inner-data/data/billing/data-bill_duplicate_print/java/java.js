

//////////////// List ////////////////////


function list_search(doc){
	var sm = document.getElementById("search_month").value;
	var sy = document.getElementById("search_year").value;
	
	var cid = document.getElementById("conid").value;
	
	
	if(sm !="" && sy !="" && cid !="" ){
		show_msg(doc,"search_msg","#98261A","");
		
		var data = [sm,sy,cid];
		var str_s = $.base64.encode(JSON.stringify(data));

		document.getElementById("gotobill_s").value = str_s;
		document.getElementById("gotobill").submit();
		document.getElementById("gotobill_s").value = "";
		
		//$("#data_list").html('<tr><td>Loading...</td></tr>');
		//$("#data_list").load(llink + "list.php?s="+str_s,function(){scrollpos_set();});
		
	}
	else{
		show_msg(doc,"search_msg","#98261A","Fill up all fields");
	}
}

////////////print///////////

function print_report(){
	var d = $("#data_print").html();
	if(d !=""){
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