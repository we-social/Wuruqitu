<?php
//Code By Safe3 
function customError($errno, $errstr, $errfile, $errline){ 
 echo "<b>Error number:</b> [$errno], error on line $errline in $errfile<br />";
 die();
}
set_error_handler("customError",E_ERROR);
$getfilter="'|(and|or)\\b.+?(>|<|=|in|like)|\\/\\*.+?\\*\\/|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
$postfilter="\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
$cookiefilter="\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
function StopAttack($StrFiltKey,$StrFiltValue,$ArrFiltReq){  
	if(is_array($StrFiltValue)) {
	    $StrFiltValue=implode($StrFiltValue);
	}  
	if (preg_match("/".$ArrFiltReq."/is",$StrFiltValue)==1){   
	        slog("操作IP: ".$_SERVER["REMOTE_ADDR"]."\r\n操作时间: ".strftime("%Y-%m-%d %H:%M:%S")."\r\n操作页面:".$_SERVER["PHP_SELF"]."\r\n提交方式: ".$_SERVER["REQUEST_METHOD"]."\r\n提交参数: ".$StrFiltKey."\r\n提交数据: ".$StrFiltValue."");
	        header("Location: ../../");
	}
}  
//$ArrPGC=array_merge($_GET,$_POST,$_COOKIE);
foreach($_GET as $key=>$value){ 
	StopAttack($key,$value,$getfilter);
}
foreach($_POST as $key=>$value){ 
	StopAttack($key,$value,$postfilter);
}
foreach($_COOKIE as $key=>$value){ 
	StopAttack($key,$value,$cookiefilter);
}
function slog($logs) {
  $toppath=ROOT. '/log_attacks.txt';
  $Ts=fopen($toppath,"a+");
  fputs($Ts,$logs."\r\n\r\n");
  fclose($Ts);
}
?>