$(function(){
	$("#file_holder").dropzone({
		url : llink + "server/upload.php",
		maxFilesize: 2,
		parallelUploads : 1,
		maxFiles : 10,
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
})