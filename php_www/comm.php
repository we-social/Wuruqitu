<?php
require('globalvars.php');
require(ROOT. '/conn/dbc.php');
$tar = $_REQUEST['_t'];
$rw = $_REQUEST['_rw'];
$tar = addslashes(cutstr($tar, 4));
$rw = cutstr($rw, 1);
$ip = $_SERVER['REMOTE_ADDR'];

if ($rw === 'r') {	// read
	$p = isset($_REQUEST['_p'])?
			cutstr($_REQUEST['_p'], 4): '';
	$p = (int) $p;
	$p = $p? $p: 1;

	$rows_per_page = 4;
	$res = mysql_query("SELECT COUNT(*) AS total FROM ". TB_COMM ." WHERE cmtar='". $tar ."'");
	$r = mysql_fetch_array($res);
	$total = $r['total'];
	$pages = ceil($total / $rows_per_page);
	$pages = $pages? $pages: 1;
	if ($p < 1 || $p > $pages) {
		$p = 1;
	}
	$start = ($p-1) * $rows_per_page;

	$comms = array();
	$res = mysql_query("SELECT cmfrom, cmtext FROM ". TB_COMM ." WHERE cmtar='". $tar
				."' ORDER BY cmid DESC LIMIT ". $start .", ". $rows_per_page);
	while ($r = mysql_fetch_array($res)) {
		$comms[] = $r;
	}
	output(array(
				'state' => 'pass',
				'msg' => '获取评论列表成功。',
				'pages' => $pages,
				'comms' => $comms
			));
} else {	// write
	$text = $_REQUEST['_text'];
	$text = addslashes(cutstr($text, 140, true));
	if ($text === '') {
		output(array(
					'state' => 'fail',
					'msg' => '评论内容不能为空。'
				));
	}

	if (! can_comm($tar)) {
		output(array(
					'state' => 'fail',
					'msg' => '相同 IP 一个小时内不能重复评论。'
				));
	}
	$pass = false;

	require(ROOT. '/conn/HttpClient.php');
	$from = get_location($ip);

	mysql_query("INSERT INTO ". TB_COMM . " (" .
			"cmtar," .
			"cmtext," .
			"cmip," .
			"cmfrom," .
			"cmat" .
			") VALUES ('" .
			$tar ."','" .
			$text ."','" .
			$ip ."','" .
			$from ."','" .
			timestr() .
			"')");
	$pass = mysql_affected_rows() > 0;
	if ($pass) {
		$res = mysql_query("SELECT upcomms FROM ". TB_UP ." WHERE upid='". $tar ."'");
		$r = mysql_fetch_array($res);
		$num = strval($r['upcomms']);
		mysql_query("UPDATE ". TB_UP . " SET upcomms='". ($num+1) ."' WHERE upid='". $tar ."'");

		output(array(
					'state' => 'pass',
					'msg' => '评论成功。'
				));
	} else {
		output(array(
					'state' => 'fail',
					'msg' => '数据库写入错误。'
				));
	}
}
?>
