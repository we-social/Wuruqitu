<?php
require('globalvars.php');
require(ROOT. '/conn/dbc.php');
if (! can_up()) {
	output(array(
				'state' => "fail",
				'msg' => "相同 IP 一个小时内不能重复发图。"
			));
}

$files = $_FILES['_pic'];
$name = $files['name'][0];
$type = $files['type'][0];
$tmp_name = $files['tmp_name'][0];
$size = $files['size'][0];
$err = $files['error'][0];
if ($err !== 0 || count($files['name']) !== 1) {
	output(array(
				'state' => "fail",
				'msg' => "图片数量不正确。"
			));
}

$ext = substr($type, 6);
$ext = $ext==='jpeg'? 'jpg': $ext;
if ($ext !== 'jpg' && $ext !== 'gif' && $ext !== 'png') {
	output(array(
				'state' => "fail",
				'msg' => "图片格式不正确。"
			));
}

if ($size > 500*1024 || $size < 10*1024) {
	output(array(
				'state' => "fail",
				'msg' => "图片大小不正确。"
			));
}

$sizeinfo = getimagesize($tmp_name);
$w = $sizeinfo[0];
$h = $sizeinfo[1];
$r = $h / $w;
if ($r > 3 || $r < 1/3) {
	output(array(
				'state' => "fail",
				'msg' => "图片长宽比例不当。"
			));
}

$ready_path = DIR_UP_READY .'/'. rand_key(16) .".{$ext}";
move_uploaded_file($tmp_name, $ready_path);

require(ROOT. '/conn/httpclient.php');
require(ROOT. '/upload.php');
$note = $_REQUEST['_note'];
$deg = $_REQUEST['_deg'];
$note = addslashes(cutstr($note, 140, true));
$deg = (int) cutstr($deg, 3);
$deg = round($deg/90)*90;

output(upload($ready_path, $note, $deg));
?>
