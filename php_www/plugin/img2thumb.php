<?php
/**
 *		仅支持 jpg(jpeg), gif, png 图片格式  不支持透明
 *		宽和高必须至少指定一项   若缺仅缺一项 则自动填补相同值
 *		若不剪裁 宽高充当最大值限制 输出图像照源图像比例
 *		若剪裁 宽高为输出图像的指定值
 */
function img2thumb($srcfile, $dstfile, $width=0, $height=0, $cut=false) {
	if (!is_file($srcfile)) return false;
	if ($width==0 && $height==0) return false;
	
	$outext = strtolower(pathinfo($dstfile, PATHINFO_EXTENSION));
	$extok = $outext==='jpg' || $outext==='jpeg'
				|| $outext==='gif' || $outext==='png';
	if (!$extok) return false;
	$outfun = 'image'.($outext==='jpg'? 'jpeg': $outext);
	
	$srcinfo = getimagesize($srcfile);
	$src_w = $srcinfo[0];
	$src_h = $srcinfo[1];
	$src_r = $src_h / $src_w;
	$creext = strtolower(substr(image_type_to_extension($srcinfo[2]), 1));
	$crefun = 'imagecreatefrom'.($creext==='jpg'? 'jpeg': $creext);
		if (! $cut) {
			$x = $y = 0;
			$dst_w = $width;
			$dst_h = $height;
			
			if($dst_w && $dst_h) {
				$src_prop = $src_w / $src_h;
				$dst_prop = $dst_w / $dst_h;
				if ($src_prop > $dst_prop) {
					$dst_h = $src_h / $src_w * $dst_w;
				} else {
					$dst_w = $src_w / $src_h * $dst_h;
				}
			} else {
				if ($dst_w) {
					$dst_h = $dst_w * $src_r;
				} else if ($dst_h) {
					$dst_w = $dst_h / $src_r;
				} else {
					return false;
				}
			}
		} else {
			$width = $width? $width: $height;
			$height = $height? $height: $width;
			
			$prop = min(max($width/$src_w, $height/$src_h), 1);
			$dst_w = (int)round($src_w * $prop);
			$dst_h = (int)round($src_h * $prop);
			$x = ($width - $dst_w) / 2;
			$y = ($height - $dst_h) / 2;
		}
	$src = $crefun($srcfile);
	$dst = imagecreatetruecolor($width? $width: $dst_w, $height? $height: $dst_h);
	$white = imagecolorallocate($dst, 255, 255, 255);
	imagefill($dst, 0, 0, $white);

	if(function_exists('imagecopyresampled')) {
		imagecopyresampled($dst, $src, $x, $y,
						   0, 0, $dst_w, $dst_h, $src_w, $src_h);
	} else {
		imagecopyresized($dst, $src, $x, $y,
						 0, 0, $dst_w, $dst_h, $src_w, $src_h);
	}
	$outfun($dst, $dstfile);
	imagedestroy($dst);
	imagedestroy($src);
	return true;
}
?>