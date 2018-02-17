
function reset_subdiv(doc){
	
	var ele = document.getElementById("subdiv_srch");
	ele.value = "";
	ele.removeAttribute("disabled");
	$("#subdiv_ch_but").css({"display":""});
	$("#subdiv_re_but").css({"display":"none"});
	show_msg(doc,"create_subdiv_msg","#98261A","");
	document.getElementById("subdiv_id").value = "";
	document.getElementById("subdiv_lvl").innerHTML = "ID";
	
	var ag_html='<option value="">Select Agent</option>';
	document.getElementById("agent_id").innerHTML = ag_html;

	upload_show();
}

function check_subdiv(doc){
	
	$("#secondary_data").css({"display":"none"});
	
	document.getElementById("subdiv_id").value = "";
	document.getElementById("subdiv_srch").removeAttribute("disabled");
	var sid = document.getElementById("subdiv_srch").value;
	if( sid !="" ){
		doc.setAttribute("disabled","disabled");
		
		var c = getcode(10);
		
		var data = new Array();
		data = [sid];
		var dd = $.base64.encode(JSON.stringify(data));
		
		
		$.ajax({
			url:llink + "server/ccheck.php",
			type:"POST",
			data:{c:c,d:dd},
			success:function(res){
				//alert(res);
				if(res == "0"){
					show_msg(doc,"create_subdiv_msg","#98261A","Data problem");
				}
				else if(res == "1"){
					show_msg(doc,"create_subdiv_msg","#98261A","Subdiv ID doesnot exists");
				}
				else if(res == "2"){
					show_msg(doc,"create_subdiv_msg","#98261A","Agent not exists");
				}
				else{
					var rd_j = $.base64.decode(res);
					var rd_arr = JSON.parse(rd_j);
					if(rd_arr[0] == c){
						show_msg(doc,"create_subdiv_msg","#135400","Successful");
						document.getElementById("subdiv_id").value = rd_arr[1];
						$(doc).css({"display":"none"});
						$("#subdiv_re_but").css({"display":""});
						document.getElementById("subdiv_srch").setAttribute("disabled","disabed");
						document.getElementById("subdiv_srch").value = rd_arr[2];
						document.getElementById("subdiv_lvl").innerHTML = "Name";
						
						
						var agent_str = rd_arr[3];
						var ag_j = $.base64.decode(agent_str);
						var ag_arr = JSON.parse(ag_j);
						var i; var ag_html='<option value="">Select Agent</option>';
						for(i=0;i<ag_arr.length;i++){
							ag_html += '<option value="'+ ag_arr[i]['id'] +'">'+ ag_arr[i]['name'] +'</option>';
						}
						document.getElementById("agent_id").innerHTML = ag_html;
					}
				}
			},
			error:function(){
				show_msg(doc,"create_subdiv_msg","#98261A","Network connection problem");
			}
		});
		
	}
}

function upload_show(){
	var aid = document.getElementById("agent_id").value;
	if(aid == ""){
		$("#upload_body").css({"display":"none"});
	}else{
		$("#upload_body").css({"display":""});
	}

	list_show(ufile_name);
}


/*----------------------------*/


var ufile_name = "";

$(document).ready(function(){
	
	$("#file_upload_but").click(function(){
		$("#file_upload").click();
	});
	
	
	$("#file_upload").change(function(){
		var doc = document.getElementById("file_upload_but");
		
		var fd=document.getElementById("file_upload").files[0];
		var fdname=fd.name;
		var fdtype=fd.type;
		var fdsize=fd.size;
		
		
			var sizes = Math.floor(fdsize / 1024);
			if(sizes <= 40960){
				var filed="";
				filed += "<table>";
				filed += "	<tr> <th>Name</th> <td>"+ fdname +"</td> </tr/>";
				filed += "	<tr> <th>Size</th> <td>"+ sizes +" kB</td> </tr>";
				filed += "</table>";
				
				document.getElementById("file_details").innerHTML = filed;
				
				ufile_name = getcode(64);
				document.getElementById("file_name").value = ufile_name;
				$("#file_upload_but").css({"display":"none"});
				$("#file_upload_submit").click();
			}
			else{
				reset_upload_file();
				show_msg(doc,"file_upload_msg","#98261A","Upload file size must be less than 40MB");
			}
		
		
		
	});
	
	$("#file_upload_submit").click(function(){
		$("#loadingh").css({"display":""});
		$("#loading").css({"width":"0%"});
		sendRequest();
	});
	
	
});
//////////////////////////upload large files//////////////////////////////////////
var blob;
var start;
var end;
var part;
var SIZE;
var BYTES_PER_CHUNK;
var xhr;
var chunk;

function sendRequest() {
	blob = document.getElementById('file_upload').files[0];
	BYTES_PER_CHUNK = 1048576; // 1MB chunk sizes.
	SIZE = blob.size;
	start = 0;
	part = 0;
	end = BYTES_PER_CHUNK;

	chunk = blob.slice(start, end);
	uploadFile(chunk,part);
	start = end;
	end = start + BYTES_PER_CHUNK;
	part = part + 1;
};
//------------------------------------------------------------------------------------------------------------------------------------

function uploadFile(blobFile,part) {
	var file = document.getElementById('file_upload').files[0];
	var fname = document.getElementById("file_name").value;
	var fd = new FormData();
	fd.append("file_upload", blobFile);
	
	var xhr = new XMLHttpRequest();
	xhr.upload.addEventListener("progress", uploadProgress, false);
	xhr.addEventListener("load", uploadComplete, false);
	xhr.addEventListener("error", uploadFailed, false);
	xhr.addEventListener("abort", uploadCanceled, false);

	var php_file =  llink + "server/upload.php"
	
	
	xhr.open("POST", php_file +"?"+"file="+fname+"&num=" + parseInt(part) );
	xhr.onload = function(e) {
		//alert(e);
	};
	xhr.setRequestHeader('Cache-Control','no-cache');
	xhr.send(fd);
	return;

};
//------------------------------------------------------------------------------------------------------------------------------------

function uploadProgress(evt){
	if(evt.lengthComputable){
		var percentComplete = Math.round(evt.loaded * 100 / evt.total);
	}else{
		var doc = document.getElementById("file_upload_but");
		show_msg(doc,"file_upload_msg","#1469EA","Unable to compute");
	}
};
//------------------------------------------------------------------------------------------------------------------------------------

function uploadComplete(evt) {
	if( start < SIZE ) {
		chunk = blob.slice(start, end);
		uploadFile(chunk,part);
		start = end;
		end = start + BYTES_PER_CHUNK;
		part = part + 1;
		
		var percentComplete = Math.round((start/SIZE)*100);
		if(percentComplete >100){percentComplete=100;}
		$("#loading").css({"width": percentComplete +"%"});
		var doc = document.getElementById("file_upload_but");
		show_msg(doc,"file_upload_msg","#1469EA",percentComplete + "% uploaded");
	}
	else{
		var doc = document.getElementById("file_upload_but");
		$("#file_read").css({"display":""});
		$("#loadingh").css({"display":"none"});
		show_msg(doc,"file_upload_msg","#00500E","Upload completed");
		list_show(ufile_name);
	}
};
//------------------------------------------------------------------------------------------------------------------------------------
function uploadFailed(evt) {
	var doc = document.getElementById("file_upload_but");
	show_msg(doc,"file_upload_msg","#1469EA","There was an error attempting to upload the file.");
};
//------------------------------------------------------------------------------------------------------------------------------------
function uploadCanceled(evt) {
	xhr.abort();
	xhr = null;
	var doc = document.getElementById("file_upload_but");
	show_msg(doc,"file_upload_msg","#1469EA","The upload has been canceled by the user or the browser dropped the connection.");
};




/////////////////////////////////////////////////////////////////
function reset_data(){
	$("#secondary_data").css({"display":"none"});
	reset_upload_file();
}

function reset_upload_file(){
	var doc = document.getElementById("file_upload_but");
	
	document.getElementById("file_name").value ="";
	document.getElementById("file_details").innerHTML ="";
	
	document.getElementById("file_upload").files[0] ="";
	document.getElementById("file_upload").value ="";
	
	$("#loadingh").css({"display":"none"});
	$("#file_upload_but").css({"display":""});
	
	ufile_name ="";
	
	show_msg(doc,"file_upload_msg","#98261A","");
	$("#data_list").html('');
	
	$("#file_read").css({"display":"none"});
}



function file_reset_but(){
	
	var d = document.getElementById("file_name").value;
	if(d !=""){
		$("#file_upload_msg").css({"color":"#000099"});
		document.getElementById("file_upload_msg").innerHTML = "Please Wait";
		var c = getcode(10);
		
		$.ajax({
			type:"POST",
			url:llink + "server/reset.php",
			data:{c:c,d:d},
			success:function(res){
				//alert(res);
				if(res == c){
					reset_upload_file();
					var doc = document.getElementById("file_upload_but");
					show_msg(doc,"file_upload_msg","#00500E","Reset Completed");
				}
				else if(res == "0"){
					var doc = document.getElementById("file_upload_but");
					show_msg(doc,"file_upload_msg","#98261A","Authentication problem");
				}
				else{
					var doc = document.getElementById("file_upload_but");
					show_msg(doc,"file_upload_msg","#98261A","Data problem");
				}
			},
			error:function(){
				var doc = document.getElementById("file_upload_but");
				show_msg(doc,"file_upload_msg","#98261A","Internet Connection Problem");
			}
		});
	}
}

function file_import_but(){
	var d = document.getElementById("file_name").value;
	var a = document.getElementById("agent_id").value;
	if(d !="" && a !="" ){
		$("#file_upload_msg").css({"color":"#000099"});
		document.getElementById("file_upload_msg").innerHTML = "Please Wait";
		var c = getcode(10);
		
		var data = new Array();
		data = [d,a];
		var dd = $.base64.encode(JSON.stringify(data));
		
		$.ajax({
			type:"POST",
			url:llink + "server/add_new.php",
			data:{c:c,d:dd},
			success:function(res){
				//alert(res);
				if(res == c){
					reset_upload_file();
					var doc = document.getElementById("file_upload_but");
					show_msg(doc,"file_upload_msg","#00500E","Import Completed");
				}
				else if(res == "0"){
					var doc = document.getElementById("file_upload_but");
					show_msg(doc,"file_upload_msg","#98261A","Authentication problem");
				}
				else if(res == "1"){
					reset_upload_file();
					var doc = document.getElementById("file_upload_but");
					show_msg(doc,"file_upload_msg","#98261A","Importation file not found");
				}
				else if(res == "2"){
					reset_upload_file();
					var doc = document.getElementById("file_upload_but");
					var msg ="No data found for Importation. May be some errors are there!";
					show_msg(doc,"file_upload_msg","#98261A",msg);
				}
				else{
					var doc = document.getElementById("file_upload_but");
					show_msg(doc,"file_upload_msg","#98261A","Data problem");
				}
			},
			error:function(){
				var doc = document.getElementById("file_upload_but");
				show_msg(doc,"file_upload_msg","#98261A","Internet Connection Problem");
			}
		});
	}
}


////////////print///////////

function print_report(){
	var d = $("#data_list").html();
	if(d !=""){
		var w = window.open();
		w.document.write(d);
		w.print();
		w.close();
	}
}

////////// List ////////////

function list_show(str){
	var aid = document.getElementById("agent_id").value;
	if(aid !=""){
		var data = new Array();
		data = [str,aid];
		var str_s = $.base64.encode(JSON.stringify(data));
		
		if(str !=""){
			scrollpos_get();
			$("#data_list").html('<tr><td>Loading...</td></tr>');
			$("#data_list").load(llink + "list.php?s="+str_s,function(){scrollpos_set();});
		}
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