<?php
require_once(ROOT. '/conn/httpclient.php');
require(ROOT. '/plugin/img2thumb.php');
require(ROOT. '/plugin/GIFCoders/decode.php');
require(ROOT. '/plugin/GIFCoders/encode.php');

function upload($path, $note, $deg=0) {
	if ($deg != 0) {
		flip($path, $path, 360-$deg);
	}

	$ip = $_SERVER['REMOTE_ADDR'];
	$loc = get_location($ip);
	$time = timestr();

	$res = mysql_query("SELECT COUNT(*) AS total FROM ". TB_UP);
	$r = mysql_fetch_array($res);
	$total = $r['total'];
	if ($total < NUM_MAX_UP) {	// insert
		$result = mysql_query("INSERT INTO ". TB_UP ." (" .
					"uppic," .
					"upnote," .
					"upip," .
					"upfrom," .
					"upat".
				") VALUES ('','" .
					$note. "','" .
					$ip ."','" .
					$loc ."','" .
					$time .
				"')");
		if (!$result) {
			die('Invalid query: ' . mysql_error());
		}
		$id = mysql_insert_id();
	} else {	// update
		// get old
		$res = mysql_query("SELECT upid, uppic, upat FROM ". TB_UP
							. " ORDER BY upcomms, upgoods, upat LIMIT 1");
		if (!$res) {
			die('Invalid query: ' . mysql_error());
		}
		$r = mysql_fetch_array($res);
		$id = $r['upid'];
		$pic = $r['uppic'];
		// del old info
		if ($pic) {
			unlink(DIR_UP. "/{$pic}");
			unlink(DIR_UP_THUMB. "/{$pic}");
		}
		mysql_query("DELETE FROM ". TB_GOOD ." WHERE gdtar='{$id}'");
		mysql_query("DELETE FROM ". TB_COMM ." WHERE cmtar='{$id}'");
		// replace
		mysql_query("UPDATE ". TB_UP ." SET uppic='', upnote='{$note}', upip='{$ip}',"
				. " upfrom='{$loc}', upat='{$time}', upcomms='0', upgoods='0' WHERE upid='{$id}'");
	}

	$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
	$oldpath = $path;
	$path = DIR_UP_READY .'/'. rand_key(16). $ext;
	rename($oldpath, $path);

	$new_name = to_3_bits($id). ".{$ext}";
	$name1 = DIR_UP. "/{$new_name}";
	$name2 = DIR_UP_THUMB. "/{$new_name}";
	$name3 = DIR_UP_READY. "/{$new_name}";

	if ($ext === 'gif') {
		// decode
		$dir = $path.'_';
		mkdir($dir);
		decode_gif($path, $dir);
		// resize and pick the first frame
		$dh = opendir ( $dir );
		mkdir($name1.'_');
		mkdir($name2.'_');
		mkdir($name3.'_');
		$flag = false;
		while ( false !== ( $dat = readdir ( $dh ) ) ) {
			if ( $dat !== '.' && $dat !== '..' ) {
				img2thumb("{$dir}/{$dat}", "{$name1}_/{$dat}", WIDTH_UP, 0);
				img2thumb("{$dir}/{$dat}", "{$name2}_/{$dat}", WIDTH_UP_THUMB, 0);
				img2thumb("{$dir}/{$dat}", "{$name3}_/{$dat}", WIDTH_UP_BIG, 0);
				if (! $flag) {
					copy("{$name1}_/{$dat}", preg_replace('/\.gif$/', '_.gif', $name1));
					copy("{$name2}_/{$dat}", preg_replace('/\.gif$/', '_.gif', $name2));
					$flag = true;
				}
			}
		}
		closedir ( $dh );
		deldir($dir);
		// encode
		encode_gif($name1.'_', $name1);
		encode_gif($name2.'_', $name2);
		encode_gif($name3.'_', $name3);
		deldir($name1.'_');
		deldir($name2.'_');
		deldir($name3.'_');
	} else {
		img2thumb($path, $name1, WIDTH_UP, 0);
		img2thumb($path, $name2, WIDTH_UP_THUMB, 0);
		img2thumb($path, $name3, WIDTH_UP_BIG, 0);
	}
	//rename($path, DIR_UP_READY .'/'. $new_name);	// rename the original picture
	unlink($path);

	mysql_query("UPDATE ". TB_UP ." SET uppic='". $new_name ."' WHERE upid='". $id ."'");
	if (mysql_affected_rows() < 1) {
		return array(
				'state' => 'fail',
				'msg' => '数据库写入错误。'
			);
	}

	return array(
				'state' => 'pass',
				'msg' => '上传成功。'
			);
}

/**
  * 修改一个图片 让其翻转指定度数
  *
  * @param string  $filename 文件名（包括文件路径）
  * @param  float $degrees 旋转度数
  * @return boolean
  * @author zhaocj
  */
   function  flip($filename,$src,$degrees = 0)
 {
  //读取图片
  $data = @getimagesize($filename);
  if($data==false)return false;
  //读取旧图片
  switch ($data[2]) {
   case 1:
    $src_f = imagecreatefromgif($filename);break;
   case 2:
    $src_f = imagecreatefromjpeg($filename);break;
   case 3:
    $src_f = imagecreatefrompng($filename);break;
  }
  if($src_f=='')return false;
  $rotate = @imagerotate($src_f, $degrees,0);
  if(!imagejpeg($rotate,$src,100))return false;
  @imagedestroy($rotate);
  return true;
 }
?>
