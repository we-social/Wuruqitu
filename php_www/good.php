<?php
require('globalvars.php');
require(ROOT. '/conn/dbc.php');
$tar = $_REQUEST['_t'];
$tar = addslashes((cutstr($tar, 4)));
$tar = (int) $tar;
$ip = $_SERVER['REMOTE_ADDR'];

if (! can_good($tar)) {
	output(array(
				'state' => "fail",
				'msg' => "相同 IP 一天内不能重复赞。"
			));
}

$res = mysql_query("SELECT upgoods FROM ". TB_UP ." WHERE upid='". $tar ."'");
$r = mysql_fetch_array($res);
$num = (int) $r['upgoods'];
mysql_query("UPDATE ". TB_UP . " SET upgoods='". ($num+1) ."' WHERE upid='". $tar ."'");

mysql_query("INSERT INTO ". TB_GOOD . " (" .
		"gdtar," .
		"gdip," .
		"gdat" .
		") VALUES ('" .
		$tar ."','" .
		$ip ."','" .
		timestr() .
		"')");
$pass = mysql_affected_rows() > 0;
if ($pass) {
	output(array(
				'state' => "pass",
				'msg' => "赞成功。"
			));
} else {
	output(array(
				'state' => "fail",
				'msg' => "数据库写入错误。"
			));
}
?>
