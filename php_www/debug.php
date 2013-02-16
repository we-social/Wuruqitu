<?php
error_reporting(E_ALL);
require(ROOT. '/plugin/Timer.class.php');

$arr_prof = array();
function profile($key) {
	global $arr_prof;
	if (array_key_exists($key, $arr_prof)) {
		$timer = $arr_prof[$key];
		$timer->stop();
		$spent = $timer->spent();
		echo "[$key] - {$spent}ms";
		$timer = null;
		unset($arr_prof[$key]);
	} else {
		$timer = new Timer();
		$timer->start();
		$arr_prof[$key] = $timer;
	}
}
?>
