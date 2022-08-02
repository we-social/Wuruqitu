<?php
require('../globalvars.php');
require(ROOT. '/conn/dbc.php');
require(ROOT. '/upload.php');

require_once(ROOT. '/debug.php');

// danger: db reset: custom auth logic
// ...................
$ip = $_SERVER['REMOTE_ADDR'];
$a = $_REQUEST['_a'];
if ($a !== 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx' || true) {
	die("xxxx is wrong.");
}

deldir(DIR_UP);
mkdir(DIR_UP);
mkdir(DIR_UP_THUMB);
if (IS_LOCAL) {	// del db
	mysql_query("DROP DATABASE ". DB_NAME);
	mysql_query("CREATE DATABASE ". DB_NAME);
	mysql_query("USE ". DB_NAME);
} else {	// del tb
	mysql_query("DROP TABLE ". TB_GOOD);
	mysql_query("DROP TABLE ". TB_COMM);
	mysql_query("DROP TABLE ". TB_UP);
	mysql_query("DROP ADMIN ". TB_UP);
	mysql_query("DROP STAT ". TB_UP);
}

mysql_query("CREATE TABLE ". TB_UP ." (" .
		"upid int(2) not null primary key auto_increment," .
		"uppic char(16) not null," .
		"upnote varchar(512)," .
		"upip char(16)," .
		"upfrom char(32) not null," .
		"upat datetime," .
		"upgoods int(3) not null default 0," .
		"upcomms int(3) not null default 0" .
		")");
mysql_query("CREATE TABLE ". TB_COMM ." (" .
		"cmid int(4) not null primary key auto_increment," .
		"cmtar int(2) not null," .
		"cmtext varchar(512) not null," .
		"cmip char(16)," .
		"cmfrom char(32) not null," .
		"cmat datetime" .
		")");
mysql_query("CREATE TABLE ". TB_GOOD ." (" .
		"gdid int(4) not null primary key auto_increment," .
		"gdtar int(2) not null," .
		"gdip char(16)," .
		"gdat datetime" .
		")");

mysql_query("CREATE TABLE ". TB_ADMIN ." (" .
		"adid int(1) not null primary key auto_increment," .
		"adname char(16) not null," .
		"adpass char(32) not null," .
		"regat datetime" .
		")");
mysql_query("CREATE TABLE ". TB_STAT ." (" .
		"lastvisitat datetime" .
		")");
mysql_query("INSERT INTO ". TB_ADMIN ." (" .
				"adname," .
				"adpass," .
				"regat" .
			") VALUES ('" .
				'h5lium'. "','" .
				md5('LinLiang') ."','" .
				timestr() .
			"')");
mysql_query("INSERT INTO ". TB_STAT ." (" .
				"lastvisitat" .
			") VALUES ('" .
				timestr(0) .
			"')");

$notes = array("操蛋！！", "卧槽", "。。。。", "你懂的！", "额。", "神马情况！？", "这也行！", "牛逼", "靠。。");
$max = count($notes) -1;
$dir = DIR_UP_READY;
$dh = opendir($dir);
while ($file = readdir($dh)) {
	if ($file !== '.' && $file !== '..') {
		$fullpath = $dir . '/' . $file;
		upload($fullpath, $notes[rand(0, $max)]);
	}
}
closedir($dh);
echo "reset complete.";
?>
