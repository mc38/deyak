function lock(doc){
	bprocess_done = false;
	var d = document.getElementById("mydate").value;
	var s = document.getElementById("subdiv").value;
	
	
	if(d !="" && s !=""){
		var msgid = "search_msg";
		
		$(msgid).css({"color":"#000099"});
		document.getElementById(msgid).innerHTML = "Please Wait";
		var c = getcode(10);
		
		var data = new Array();
		data = [d,s];
		var dd = $.base64.encode(JSON.stringify(data));
		
		var conf = confirm("Do you really want to proceed?");

		if(conf){
			doc.setAttribute("disabled","disabled");
			
			$.ajax({
				url	:llink + "server/process.php",
				type:"POST",
				data:{c:c,d:dd},
				success:function(response){
					//alert(response);
					response = $.trim(response);
					if(response == c){
						show_msg(doc,msgid,"#135400","successful");
						list_search(doc);
					}
					else if(response == '0'){
						show_msg(doc,msgid,"#98261A","Authentication problem");
					}else{
						show_msg(doc,msgid,"#98261A","Data problem");
					}
				},
				error:function(){
					show_msg(doc,msgid,"#98261A","Network connection problem");
				}
			});
		}
	}
	else{
		show_msg(doc,msgid,"#98261A","");
	}
	
}


//////////////// List ////////////////////


function list_load(){
	list_search();
}

function list_search(doc){
	var str = document.getElementById("search_str").value;
	var smon = document.getElementById("search_month").value;
	var syar = document.getElementById("search_year").value;
	if(str !="" && smon !="" && syar !="" ){
		var sdate = "01-"+ smon +"-"+ syar;
		
		var data = [str.toLowerCase(),sdate,subdiv];
		var str_s = $.base64.encode(JSON.stringify(data));
		
		$("#data_list").html('<tr><td>Loading...</td></tr>');
		$("#data_list").load(llink + "list.php?s="+str_s,function(){scrollpos_set();bprocess_done = true;});
	}
	else{
		show_msg(doc,"search_msg","#98261A","Fill up all fields");
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