<?php
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 */

/** 
 * You may wish to use the Minify URI Builder app to suggest
 * changes. http://yourdomain/min/builder/
 *
 * See http://code.google.com/p/minify/wiki/CustomSource for other ideas
 **/

return array(
	'css' => array(
					'//ey/ey_css.css',
					'//ey/gifplayer/gifplayer.css',
					'//ey/imgrotator/imgrotator.css',
					'//css/style.css',
    				'//plugin/bootstrap/css/bootstrap-responsive.min.css'
    				),
    'js' => array(
					'//plugin/jquery/jquery-1.9.1.min.js',
					'//plugin/bootstrap/js/bootstrap.min.js',
					'//plugin/blackbirdjs/blackbird.js',
					'//plugin/jfileupload.min.js',
    				'//ey/ey_js.js',
    				'//ey/form.js',
    				'//ey/gifplayer/gifplayer.js',
    				'//ey/imgrotator/imgrotator.js',
					'//js/script.js'
					)
);