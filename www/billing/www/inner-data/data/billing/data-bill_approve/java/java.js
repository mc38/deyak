
function approve(doc){
	var d 		= doc.value;
	
	var msgid	= "action_msg_"+ d;
	
	if(d !="" ){
		
		show_msg(doc,msgid,"#000099","Please Wait...");
		var c = getcode(10);
		
		var data = new Array();
		data = [d];
		var dd = $.base64.encode(JSON.stringify(data));
		
		var conf = confirm("Please confirm the data you want to update?");
		
		if(conf){
			doc.setAttribute("disabled","disabled");
			
			$.ajax({
				url	:llink + "server/approve.php",
				type:"POST",
				data:{c:c,d:dd},
				success:function(response){
					//alert(response);
					response = $.trim(response);
					if(c == response){
						process_complete(d);
					}
					else if(response == '0'){
						show_msg(doc,msgid,"#98261A","Authentication problem");
					}
					else{
						show_msg(doc,msgid,"#98261A","Data problem");
					}
				},
				error:function(){
					show_msg(doc,msgid,"#98261A","Network connection problem");
				}
			});
		}
		else{
			show_msg(doc,msgid,"#98261A","");
		}
	}
	else{
		show_msg(doc,msgid,"#98261A","Fill up all the fields");
		
	}
}


function reject(doc){
	var d 		= doc.value;
	
	var msgid	= "action_msg_"+ d;
	
	if(d !="" ){
		
		show_msg(doc,msgid,"#000099","Please Wait...");
		var c = getcode(10);
		
		var data = new Array();
		data = [d];
		var dd = $.base64.encode(JSON.stringify(data));
		
		var conf = confirm("Please confirm the data you want to procced?");
		
		if(conf){
			doc.setAttribute("disabled","disabled");
			
			$.ajax({
				url	:llink + "server/reject.php",
				type:"POST",
				data:{c:c,d:dd},
				success:function(response){
					//alert(response);
					response = $.trim(response);
					if(c == response){
						process_complete(d);
					}
					else if(response == '0'){
						show_msg(doc,msgid,"#98261A","Authentication problem");
					}
					else{
						show_msg(doc,msgid,"#98261A","Data problem");
					}
				},
				error:function(){
					show_msg(doc,msgid,"#98261A","Network connection problem");
				}
			});
		}
		else{
			show_msg(doc,msgid,"#98261A","");
		}
	}
	else{
		show_msg(doc,msgid,"#98261A","Fill up all the fields");
		
	}
}


function process_complete(d){
	$("#row_"+ d).remove();
	var dlen = document.getElementById("approvelist").getElementsByTagName("tr").length;
	if(dlen<2){
		list_search(document.getElementById("subsearch"));
	}
}


function show_large_image(d){
	var v = document.getElementById("img_"+d).value;
	$("#cover_data").html('<img src="'+ v +'" style="width:500px; height:auto;" /><br/><button type="button" onclick="close_cover();">Close</button>');
	$("#cover").css({"display":"block"});
}


////////////////////////////////////////////////////////////////
function list_search(doc){
	var s 	= document.getElementById("subdiv").value;
	var fd	= document.getElementById("fdate").value;
	var td	= document.getElementById("tdate").value;
	var c 	= document.getElementById("cid").value;
	
	if(s !="" ){
		show_msg(doc,"search_msg","#98261A","");
		
		var data = [s,c,fd,td];
		var str_s = $.base64.encode(JSON.stringify(data));
		
		$("#data_list").html('<tr><td>Loading...</td></tr>');
		$("#data_list").load(llink + "list.php?s="+str_s,function(){scrollpos_set();});
		
	}
	else{
		show_msg(doc,"search_msg","#98261A","Fill up all fields");
	}
}

//////////////////list code/////////////////////

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