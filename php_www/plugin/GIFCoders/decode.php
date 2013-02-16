<?php 
require(ROOT. '/plugin/GIFCoders/GIFDecoder.class.php' );

function decode_gif($file, $dir) {
	$gifDecoder = new GIFDecoder(fread(fopen($file,'rb' ), filesize($file)));
	$i = 1;
	foreach($gifDecoder->GIFGetFrames() as $frame) {
		if ($i < 10) {
			$new_name = "{$dir}/frame00{$i}.gif";
		} else if ($i < 100) {
			$new_name = "{$dir}/frame0{$i}.gif";
		} else if ($i < 1000) {
			$new_name = "{$dir}/frame{$i}.gif";
		} else {
			break;
		}
		fwrite(fopen($new_name, 'wb'), $frame);
		$i++; 
	} 
}
?>