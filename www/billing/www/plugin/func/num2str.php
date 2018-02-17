<?php

function num_2_arr($numd){
	if($numd <1000000000){
		$numd = (int) $numd;
		while($numd > 0){
			$num[]=$numd %10;
			$numd =(int) ($numd/10);
		}
		return $num;
	}
	else{
		return 0;
	}
	
}

function crt_link_arr($n_arr){
	for ($j=0;$j<sizeof($n_arr);$j++){
		$i=$j%7;
		
		if($i==0){
			if(isset($n_arr[$j+1])){
				$link[$j]=zth($n_arr[$j],$n_arr[$j+1]);
			}
			else{
				$link[$j]=zth($n_arr[$j],0);
			}
		}
		else if($i==1){
			$link[$j]=oth($n_arr[$j-1],$n_arr[$j]);
		}
		else if($i==2){
			if($n_arr[$j]>0){
				$link[$j]= "1";
			}
			else{
				$link[$j]= "0";
			}
		}
		else if($i>2){
			if($i%2==0){
				$link[$j]=oth($n_arr[$j-1],$n_arr[$j]);
			}
			else{
				if(isset($n_arr[$j+1])){
					$link[$j]=zth($n_arr[$j],$n_arr[$j+1]);
				}
				else{
					$link[$j]=zth($n_arr[$j],0);
				}
			}
		}
	}
	return $link;
}


function make_str($num,$link){
	$data_word[]=array(0=>"",1=>"one",2=>"two",3=>"three",4=>"four",5=>"five",6=>"six",7=>"seven",8=>"eight",9=>"nine");
	$data_word[]=array(0=>"",1=>"eleven",2=>"twelve",3=>"thirteen",4=>"fourteen",5=>"fifteen",6=>"sixteen",7=>"seventeen",8=>"eighteen",9=>"nineteen");
	$data_word[]=array(0=>"",1=>"ten",2=>"twenty",3=>"thirty",4=>"forty",5=>"fifty",6=>"sixty",7=>"seventy",8=>"eighty",9=>"ninety");
	
	$data_tab=array(0=>"",1=>"",2=>"hundred",3=>"thousand",4=>"",5=>"lakh",6=>"",7=>"core");
	
	$str="";
	$andc=0;
	for($i=sizeof($link) -1;($i>=0);$i--){
		if($link[$i] != "0"){
			$andc++;
			$d=((int) $link[$i]) -1;
			if($d == 1){
				$str = $str ." ". $data_word[$d][$num[$i-1]];
			}
			else{
				$str = $str ." ". $data_word[$d][$num[$i]];
			}
		}
		if($num[$i] >0 || ($i!=2 && isset($num[$i +1]) && $num[$i +1]>0)){
			$str = $str ." ".$data_tab[$i%8];
		}
		if($i%8 ==2 && (($num[1] >0) || ($num[1]==0 && $num[0]>0)) && $andc>0){
			$str = $str." and ";
			$andc =0;
		}
	}
	return $str;
}

function num_2_str($numd){
	$numd = (int) $numd;
	if($numd>0){
		$num = num_2_arr($numd);
		$link = crt_link_arr(num_2_arr($numd));
		$str = make_str($num,$link);
		return $str;
	}
	else{
		return "";
	}
}


function rupee_2_str($r){
	$in=strrpos($r,".");
	if(! $in){
		$in = $r;
	}
	
	$rupee=substr($r,0,$in);
	$paisa=substr($r,$in +1,strlen($r) - $in);
	
	$rupee = (int) $rupee;
	$paisa = (int) $paisa;
	
	$rst = num_2_str($rupee);
	$pstr = num_2_str($paisa);
	
	$rstr="";
	if($rst !=""){
		$rstr = $rst . " rupees";
	}
	if($pstr !=""){
		$rstr = $rstr ." &nbsp;&nbsp;". $pstr ." paisa";
	}
	if($rst !="" || $pstr !=""){
		$rstr = $rstr ." "." only";
	}
	
	
	return " ". $rstr ." ";
}


//All sub function

function zth($z,$o){
	if($o==0 || $o>1){
		if($z==0){
			return "0";
		}
		else if($z>0){
			return "1";
		}
	}
	else if($o==1){
		return "0";
	}
}


function oth($z,$o){
	if($o==0){
		return "0";
	}
	else if($o==1){
		if($z>0){
			return "2";
		}
		else if($z ==0){
			return "3";
		}
	}
	else if($o>1){
		return "3";
	}
}







?>