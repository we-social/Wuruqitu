<?php
require(ROOT. '/plugin/HttpClient.class.php');

function get_location($ip) {

  // fixme:
  return '未知网友';

	$ret = false;
	try {
		$url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip='. $ip;
		$data = HttpClient::quickGet($url);
		preg_match('/\{(\S)+\}/', $data, $obj);
		$ret = object_to_array(json_decode($obj[0]));
	} catch (Exception $e) {}

	if ($ret && $ret['ret'] === 1) {
		if ($ret['country'] === '中国') {
			if (! empty($ret['city'])) {
				$from = $ret['city'];
			} else if (! empty($ret['province'])) {
				$from = $ret['province'];
			} else {
				$from = $ret['country'];
			}
		} else {
			$from = $ret['country'];
		}
		$from .= '网友';
	} else {
		$from = '服务器用户';
	}
	return $from;
}
?>
