<?php
require(ROOT. '/plugin/GIFCoders/GIFEncoder.class.php');

function encode_gif($dir, $file) {
	if ( $dh = opendir ( $dir ) ) {
		while ( false !== ( $dat = readdir ( $dh ) ) ) {
			if ( $dat != '.' && $dat != '..' ) {
				$frames [ ] = "{$dir}/{$dat}";
				$framed [ ] = 10;
			}
		}
		closedir ( $dh );
	}
	/*
			GIFEncoder constructor:
	        =======================
	
			image_stream = new GIFEncoder	(
								URL or Binary data	'Sources'
								int					'Delay times'
								int					'Animation loops'
								int					'Disposal'
								int					'Transparent red, green, blue colors'
								int					'Source type'
							);
	*/
	$gif = new GIFEncoder(
								$frames,
								$framed,
								0,
								2,
								0, 0, 0,
								'url'
			);
	/*
			Possibles outputs:
			==================
	
	        Output as GIF for browsers :
	        	- Header ( 'Content-type:image/gif' );
	        Output as GIF for browsers with filename:
	        	- Header ( 'Content-disposition:Attachment;filename=myanimation.gif');
	        Output as file to store into a specified file:
	        	- FWrite ( FOpen ( "myanimation.gif", "wb" ), $gif->GetAnimation ( ) );
	*/
	fwrite ( fopen ( $file , 'wb' ), $gif->GetAnimation() );
}
?>
