

//////////////// List ////////////////////


function list_search(doc){
	var str = document.getElementById("search_str").value;
	var smon = document.getElementById("search_month").value;
	var syar = document.getElementById("search_year").value;
	
	var rd = document.getElementById("row_data").value;
	var cd = document.getElementById("col_data").value;
	
	if(str !="" && smon !="" && syar !="" && rd !="" && cd !=""){
		var sdate = "01-"+ smon +"-"+ syar;
		if(rd != cd){
			show_msg(doc,"search_msg","#98261A","");
			
			var data = [str.toLowerCase(),sdate,rd,cd];
			var str_s = $.base64.encode(JSON.stringify(data));
			
			$("#data_list").html('<tr><td>Loading...</td></tr>');
			$("#data_list").load(llink + "list.php?s="+str_s,function(){scrollpos_set();});
		}
		else{
			show_msg(doc,"search_msg","#98261A","Row and Column Type cannot be same");
		}
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