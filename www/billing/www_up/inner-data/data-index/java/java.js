// JavaScript Document

function login_action(doc){
	var uname = document.getElementById("uname").value;
	var upass = document.getElementById("upass").value;
	if(uname !="" && upass !=""){
		var ud = Array();
		ud=[uname,upass];
		var udd = $.base64.encode(JSON.stringify(ud));
		
		var c = getcode(16);
		
		$("#emsg").html("Please Wait");
		doc.setAttribute("disabled","disabled");
		$.ajax({
			url:llink + "server/pcheck.php",
			type:"POST",
			data:{c:c,d:udd},
			success:function(response){
				//alert(response);
				response = $.trim(response);
				if(response == c){
					window.location.href="";
				}
				else{
					$("#emsg").html("Error : Wrong username or password");
					doc.removeAttribute("disabled");
				}
			},
			error:function(request,ecode){
				doc.removeAttribute("disabled");	
			}
		});
		
		
	}
	else{
		$("#emsg").html("Error : Username and Password is blank");
	}
}

$(document).jkey("enter",function(){
	login_action(document.getElementById("loginbut"));
});