function clear_temp(doc){
	
	var msgid = "action_msg";
	
	$("#"+msgid).css({"color":"#000099"});
	document.getElementById(msgid).innerHTML = "Please Wait";
	var c = getcode(10);
		
	$.ajax({
		type:"POST",
		url:llink + "server/process.php",
		data:{c:c},
		success:function(res){
			//alert(res);
			if(res == c){
				list_search();
				show_msg(doc,msgid,"#00500E"," Completed");
			}
			else if(res == "0"){
				show_msg(doc,msgid,"#98261A","Authentication problem");
			}
			else{
				show_msg(doc,msgid,"#98261A","Data problem");
			}
		},
		error:function(){
			show_msg(doc,msgid,"#98261A","Internet Connection Problem");
		}
	});
}

//////////////////////////////////////

$(function(){
	list_search();
});

function list_search(){
	
	$("#data_list").html('<tr><td>Loading...</td></tr>');
	$("#data_list").load(llink + "list.php");
	
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