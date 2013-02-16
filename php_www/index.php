<?php
require('globalvars.php');
//require(ROOT. '/debug.php');
require(ROOT. '/conn/dbc.php');
require(ROOT. '/Smarty/smarty.php');
$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
$lang = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);

$zh = strpos($lang, 'zh-') !== false;	// if Chinese language

$mobi = false;	// if mobile terminate
$m = false;
if (isset($_REQUEST['_m'])) {
	$m = cutstr($_REQUEST['_m'], 1) !== '0';
} else {
	$m = strpos($agent,'iPhone') || strpos($agent,'iPad') || strpos($agent,'Android')
		|| strpos($agent,'UCWEB') || strpos($agent,'Opera Mini') || strpos($agent,'SymbianOS')
		|| strpos($agent,'NetFront') || strpos($agent,'MIDP-2.0') || strpos($agent,'Windows CE');
	$m = $m !== false;
}
$mobi = $m;

$cols = array(); 	// cols of ups
$ups = array();
$num_ups = 0; $num_cols = 0;
if ($mobi) {
	$num_ups = 6;
	$num_cols = 3;
} else {
	$num_ups = 8;
	$num_cols = 4;
}
for ($i=0; $i< $num_cols; $i++) {
	$cols[] = array();
}

$pages = 0;	// total number of pages
$res = mysql_query("SELECT COUNT(*) AS total FROM ". TB_UP);
$r = mysql_fetch_array($res);
$total = $r['total'];
$pages = ceil($total / $num_ups);
$pages = $pages > 0? $pages: 1;

$page = 0;	// the current page number
$p = isset($_REQUEST['_p'])?
		cutstr($_REQUEST['_p'], 4): '';
$p = (int) $p;
$p = $p > 0? $p: 1;
$page = $p > $pages? $pages: $p;

$order = '';	// ups ordered by
$order_seg = '';
$o = isset($_REQUEST['_o'])?
		cutstr($_REQUEST['_o'], 8): '';
if ($o === 'comms') {
	$order_seg = "upcomms DESC";
} else if ($o === 'goods') {
	$order_seg = "upgoods DESC";
} else {
	$order_seg = "upat DESC";
	$o = 'late';
}
$order = $o;

$start = ($page-1) * $num_ups;
$res = mysql_query("SELECT upid, uppic, upnote, upfrom, upgoods, upcomms, upat FROM ". TB_UP
					." WHERE uppic<>'' ORDER BY {$order_seg} LIMIT {$start}, {$num_ups}");
$up = '';
while($up = mysql_fetch_array($res)) {
	$up['can_good'] = can_good($up['upid']);
	$up['is_new'] = is_new(strtotime($up['upat']));
	$is_gif = substr($up['uppic'], -4) === '.gif';
	$up['is_gif'] = $is_gif;
	if ($is_gif) {
		$up['uppic1'] = $up['uppic'];
		$up['uppic'] = str_replace('.gif', '_.gif', $up['uppic']);
	}
	$ups[] = $up;
}
for ($i=0; $i < $num_ups; $i++) {
	if (isset($ups[$i])) {
		$up = $ups[$i];
		//$cols[$i % $num_cols][] = $up;
		$cols[floor($i / ceil($num_ups / $num_cols))][] = $up;
	} else {
		break;
	}
}

function query($params) {
	global $mobi, $page, $order;
	$query = '?';
	$arr = array(
					'_m' => $mobi? 1: 0,
					'_p' => $page,
					'_o' => $order
				);
	if ($params) {
		$a = explode('&', $params);
		$t = '';
		foreach ($a as $seg) {
			$tmp = explode('=', $seg);
			$k = $tmp[0];
			$v = $tmp[1];
			$arr[$k] = $v;
		}
	}
	foreach ($arr as $k => $v) {
		$query .= "{$k}={$v}&";
	}
	return preg_replace('/&$/', '', $query);
}
$smarty->registerPlugin('function', 'query', 'query');
$smarty->assign('cols', $cols);
$smarty->assign('mobi', $mobi);
$smarty->assign('order', $order);
$smarty->assign('page', $page);
$smarty->assign('pages', $pages);
$smarty->assign('PRJ_NAME_CN', PRJ_NAME_CN);
$smarty->assign('dir_up', to_rel_path(DIR_UP));
$smarty->assign('dir_up_thumb', to_rel_path(DIR_UP_THUMB));
$smarty->display(ROOT. '/templates/index.tpl');
?>
