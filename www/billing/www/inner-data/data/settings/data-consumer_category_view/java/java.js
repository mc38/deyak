

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

$(function(){
	list_load();
});


function list_load(){
	$("#agent_list").html('<tr><td>Loading...</td></tr>');
	$("#agent_list").load(llink + "list.php");
	document.getElementById("search_str").value="";
}

function list_search(){
	var str = document.getElementById("search_str").value;
	var str_s = $.base64.encode(str);
	
	if(str !=""){
		$("#agent_list").html('<tr><td>Loading...</td></tr>');
		$("#agent_list").load(llink + "list.php?s="+str_s);
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