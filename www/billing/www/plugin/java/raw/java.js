// JavaScript Document

function print_bill(){
	document.getElementById("bill_p").submit();
}
function print_ack(){
	document.getElementById("ack_p").submit();
}
function print_recipt(id){
	id=id.substr(4,Math.floor(id.length -4));
	document.getElementById("rep"+id).submit();
}
function account_print(){
}


var char_code=new Array();
var i;
char_code=[];
for(i=64;i<=90;i++){
	char_code = char_code.concat([i]);
}
for(i=97;i<=122;i++){
	char_code = char_code.concat([i]);
}
for(i=48;i<=57;i++){
	char_code = char_code.concat([i]);
}
for(i=32;i<=38;i++){
	char_code = char_code.concat([i]);
}
for(i=40;i<=46;i++){
	char_code = char_code.concat([i]);
}
char_code.concat([39]);
/*
$(document).ready(function(){
	$("input").keyup(function(){
		var d=$(this).val();
		var i,t=0;
		for(i=0;i<d.length;i++){
			if(char_code.indexOf(d.charCodeAt(i))<0){
				var s = d.replace(d.substr(i,1),'');
				$(this).val(s);
			}
		}
	});
	
	$("input").keydown(function(){
		var d=$(this).val();
		var i,t=0;
		for(i=0; i<d.length; i++){
			if(char_code.indexOf(d.charCodeAt(i))<0){
				var s = d.replace(d.substr(i,1),'');
				$(this).val(s);
			}
		}
	});
	
});
*/

							function number_only(d,id){
								var i,t=0;
								for(i=0;i<d.length;i++){
									if((d.charCodeAt(i)<48 || d.charCodeAt(i)>57)){
										var s = d.replace(d.substr(i,1),'');
										document.getElementById(id).value = s;
									}
								}
							}
							
function getcode(l){
	var str="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	var i=0;
	var out="";
	for(i=0;i<parseInt(l);i++){
		var p=parseInt((Math.floor((Math.random() *100) +0)*(Math.floor(str.length -1) /100)));
		out = out + str.substr(p,1);
	}
	return out;
}



$(document).ready(function(){
	if(navigator.userAgent.toLowerCase().indexOf('chrome')<0){
		$("body").load("brow_error/login_page_error.php");
	}
});

var amount_code = new Array();
var i;
amount_code=[];

for(i=48;i<=57;i++){
	amount_code = amount_code.concat([i]);
}
amount_code = amount_code.concat([46]);

function check_amount(doc,d){
	var i,t=0,sp=0,sm=0;
	for(i=0;i<d.length;i++){
		if(amount_code.indexOf(d.charCodeAt(i))< 0){
			var s = d.replace(d.substr(i,1),'');
			doc.value = s;
		}
		
		if(d.charCodeAt(i)==46 && d.indexOf('.')>0 && sp==0){
			sp=1;
			sm++;
			var st = Math.floor(d.length - d.indexOf('.'));
			if(st>3){
				if(sp==1){
					var s = d.substr(0,Math.floor(d.length -1));
					doc.value = s;
					sp=0;
				}
			}
		}
		else if(d.charCodeAt(i)==46 && d.indexOf('.') == 0){
			doc.value = "";
		}
		
		if(sm>1){
			doc.value = "";
			sm=0;
		}
	}
}



function convert_strtocode(input){
	if(input !=""){
		var i,output="";
		for(i=0;i<input.length;i++){
			output = output +"&#"+ input.charCodeAt(i)+";";
		}
		return output;
	}
	else{
		return false;
	}
}



function convert_arrcode(input){
	if(input !=""){
		var i,output="",arr=new Array();
		arr=[];
		for(i=0; i<input.length; i++){
			arr = arr.concat([input.charCodeAt(i)]);
		}
		output = JSON.stringify(arr);
		return output;
	}
	else{
		return input;
	}
}


/*----------------------------------------------------------------*/
function close_cover(){
	$(".cover").css({"display":"none"});
}
