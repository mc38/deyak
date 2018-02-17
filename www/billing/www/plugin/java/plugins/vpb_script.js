/**************************************************************
* This script is brought to you by Vasplus Programming Blog
* Website: www.vasplus.info
* Email: info@vasplus.info
****************************************************************/


//This is the Upload Function
function vpb_image_upload_and_resize(imagetype,upaction,ferror) 
{
	var res="";
	var c = getcode(10);
	//alert('COOL');
	$("#vpb_file_attachment_form").vPB({
		url: 'plugin/func/upload_img/upload_image.php',
		data:{imagetype:imagetype,c:c},
		success	:function(res){
			res = $.trim(res);
			var res_a = JSON.parse(res);
			//alert(res);
			if(res_a[0] == c){
				new upaction(res_a[1]);
			}
		},
		error	:function(){
			ferror();
		}
	}).submit();
}
