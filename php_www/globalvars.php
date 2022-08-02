<?php
error_reporting(0);

date_default_timezone_set('PRC');
header('Content-Type: text/html; charset=utf-8');

define('SERVER_IP', gethostbyname($_SERVER['SERVER_NAME']));

// define('IS_LOCAL', SERVER_IP !== 'xxx.xxx.x.xxx');
// docker
define('IS_LOCAL', true);

define('PRJ_NAME', 'Wuruqitu');
define('PRJ_NAME_CN', '误入岐图');
define('PRJ_DIR', IS_LOCAL? '/'.PRJ_NAME: '');

// define('ROOT', $_SERVER['DOCUMENT_ROOT'] .PRJ_DIR);
define('ROOT', dirname(__FILE__));

define('INTV_UP', -1*60*60);	// 1h
define('INTV_COMM', 1*60*60);	// 1h
define('INTV_GOOD', 24*60*60);	// 24h

define('DIR_UP_READY', ROOT. '/ready_to_up');
define('DIR_UP', ROOT. '/up');
define('DIR_UP_THUMB', DIR_UP. '/thumbs');

define('WIDTH_UP', 240);
define('WIDTH_UP_BIG', 480);
define('WIDTH_UP_THUMB', 100);

define('NUM_MAX_UP', 100);

function timestr($tstamp = NULL) {
	return $tstamp===NULL? date('Y-m-d H:i:s'):
		date('Y-m-d H:i:s', $tstamp);
}
function output($arr) {
	die(json_encode($arr));
}
function quickmsg($msg) {
	die(json_encode(array(
						'state' => 'fail',
						'msg' => $msg
					)));
}

function cutstr($str, $len, $dot = false) {
	if ($dot && strlen($str) > $len-2) {
		$str = mb_substr($str, 0, $len-2, 'utf-8') .'..';
	} else {
		$str = mb_substr($str, 0, $len, 'utf-8');
	}
	return $str;
}
function to_3_bits($str) {
	return str_pad($str, 3, '0', STR_PAD_LEFT);
}
function rand_key($len) {
	$list = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$max = strlen($list) -1;
	$str = '';
	for ($i=0; $i<$len; $i++) {
		$str .= substr($list, rand(0, $max), 1);
	}
	return $str;
}

function object_to_array($obj){
	$arr = array();
	$_arr = is_object($obj) ? get_object_vars($obj) : $obj;
	foreach ($_arr as $key => $val){
		$val = (is_array($val) || is_object($val)) ? $this->object_to_array($val) : $val;
		$arr[$key] = $val;
	}
	return $arr;
}
function deldir($dir) {
	$dh = opendir($dir);
	while ($file = readdir($dh)) {
		if ($file != '.' && $file != '..') {
			$fullpath = $dir . '/' . $file;
				if (!is_dir($fullpath)) {
					unlink($fullpath);
				} else {
					deldir($fullpath);
				}
			}
	}
	closedir($dh);
	if (rmdir($dir)) {
		return true;
	} else {
		return false;
	}
}
function to_rel_path($abs, $deep = 0) {
	$rel = str_replace(ROOT.'/', '', $abs);
	if ($deep) {
		while ($deep-- > 0) {
			$rel = '../'. $rel;
		}
	}
	return $rel;
}

function is_new($time) {
	return $time > time()-48*60*60;
}
function can_good($tar) {
	$ip = $_SERVER['REMOTE_ADDR'];
	$res = mysql_query("SELECT gdat FROM ". TB_GOOD ." WHERE gdtar='". $tar
					."' AND gdip='". $ip ."' ORDER BY gdat DESC LIMIT 1");
	$r = mysql_fetch_array($res);
	if ($r) {
		$diff = strtotime(timestr()) - strtotime($r['gdat']);
		if ($diff < INTV_GOOD) {
			return false;
		}
	}
	return true;
}
function can_comm($tar) {
	$ip = $_SERVER['REMOTE_ADDR'];
	$res = mysql_query("SELECT cmat FROM ". TB_COMM ." WHERE cmtar='". $tar
					."' AND cmip='". $ip ."' ORDER BY cmat DESC LIMIT 1");
	$r = mysql_fetch_array($res);
	if ($r) {
		$diff = strtotime(timestr()) - strtotime($r['cmat']);
		if ($diff < INTV_COMM) {
			return false;
		}
	}
	return true;
}
function can_up() {
	$ip = $_SERVER['REMOTE_ADDR'];
	$res = mysql_query("SELECT upat FROM ". TB_UP ." WHERE upip='". $ip
					."' ORDER BY upat DESC LIMIT 1");
	$r = mysql_fetch_array($res);
	if ($r) {
		$diff = strtotime(timestr()) - strtotime($r['upat']);
		if ($diff < INTV_UP) {
			return false;
		}
	}
	return true;
}
?>
