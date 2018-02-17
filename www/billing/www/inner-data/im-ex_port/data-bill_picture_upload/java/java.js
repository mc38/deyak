function reset_subdiv(doc){
	
	var ele = document.getElementById("subdiv_srch");
	ele.value = "";
	ele.removeAttribute("disabled");
	$("#subdiv_ch_but").css({"display":""});
	$("#subdiv_re_but").css({"display":"none"});
	show_msg(doc,"create_subdiv_msg","#98261A","");
	document.getElementById("subdiv_id").value = "";
	document.getElementById("subdiv_lvl").innerHTML = "ID";
	subdiv = 0;
	image_upload();
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
						subdiv = rd_arr[1];
						image_upload();
					}
				}
			},
			error:function(){
				show_msg(doc,"create_subdiv_msg","#98261A","Network connection problem");
			}
		});
		
	}
}

/*File Upload--------------------------------------------------------*/

function image_upload(){
	$("#file_holder").dropzone({
		url : llink + "server/upload.php",
		params : {s:subdiv},
		maxFilesize: 2,
		parallelUploads : 1,
		maxFiles : 1000,
		acceptedFiles : "image/jpeg",
		dictInvalidFileType : "Upload only JPEG files.",
		dictFileTooBig : "File is too big ({{filesize}}MB). Max filesize: {{maxFilesize}}MB.",
		success : function(file,response){
			$(file.previewElement).find('.dz-progress').css({"display":"none"});

			//alert(response);

			var message = "";
			var doc = $(file.previewElement).find('.dz-error-message');
			var mcolor = "#fff";
			if(response == "0"){
				mcolor = "rgb(152, 38, 26)";
				message = "Authentication error";
			}else if(response == "1"){
				mcolor = "#22582C";
				message = "Upload successfully";
			}else if(response == "2"){
				mcolor = "rgb(152, 38, 26)";
				message = "Image already uploaded";
			}else if(response == "3"){
				mcolor = "rgb(152, 38, 26)";
				message = "Billing data not uploaded yet. Upload billing data first.";
			}else if(response == "4"){
				mcolor = "rgb(152, 38, 26)";
				message = "Select Subdivision -> "+subdiv;
			}else{
				mcolor = "rgb(152, 38, 26)";
				message = "Data problem";
			}
			doc.text(message);
			doc.css({"color":mcolor});
		},
		queuecomplete: function(){
			window.location.href="";
		}
	});
}

// supporting functions
function show_msg(doc,id,color,msg){
	$("#"+id).css({"color":color});
	document.getElementById(id).innerHTML = msg;
	doc.removeAttribute("disabled");
}