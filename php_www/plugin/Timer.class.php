<?php
class Timer {
	private $startTime; 
	private $stopTime; 
	function __construct() {
		$this->startTime = 0;
		$this->stopTime = 0; 
	}
	function start() {
		$this->startTime = microtime(true);
	}
	function stop() {
		$this->stopTime = microtime(true);
	}
	function spent() {
		return round(($this->stopTime - $this->startTime)* 1000, 2); 
	}
}
?>