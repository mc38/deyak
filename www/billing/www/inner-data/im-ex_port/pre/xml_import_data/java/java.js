var ufile_name = "";

$(document).ready(function(){
	$("#data_type").change(function(){
		reset_data();
		var d = document.getElementById("data_type").value;
		if(d !=""){
			$("#secondary_data").css({"display":""});
		}
	});
	
	$("#file_upload_but").click(function(){
		$("#file_upload").click();
	});
	
	
	$("#file_upload").change(function(){
		var doc = document.getElementById("file_upload_but");
		
		var fd=document.getElementById("file_upload").files[0];
		var fdname=fd.name;
		var fdtype=fd.type;
		var fdsize=fd.size;
		
		if(fdtype == "text/xml" ){
			var sizes = Math.floor(fdsize / 1024);
			if(sizes <= 1024){
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
				show_msg(doc,"file_upload_msg","#98261A","Upload file size must be less than 1MB");
			}
		}
		else{
			reset_upload_file();
			show_msg(doc,"file_upload_msg","#98261A","Upload XML file only");
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







//////////////////////////////////////////////////////////////////////////////////////




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
	var t = document.getElementById("data_type").value;
	var d = document.getElementById("file_name").value;
	if(d !="" && t !=""){
		$("#file_upload_msg").css({"color":"#000099"});
		document.getElementById("file_upload_msg").innerHTML = "Please Wait";
		var c = getcode(10);
		
		var data = new Array();
		data = [t,d];
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
					var msg ="";
					if(t == '1'){
						msg = 'No data found for Importation. May be some errors are there!';
					}
					else if(t == '2'){
						msg = 'Importation Problem, May be some errors are there!';
					}
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
	
	var type = document.getElementById("data_type").value;
	if(type !=""){
		var data = new Array();
		data = [str,type];
		var str_s = $.base64.encode(JSON.stringify(data));
		
		if(str !=""){
			$("#data_list").html('<tr><td>Loading...</td></tr>');
			$("#data_list").load(llink + "list.php?s="+str_s);
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