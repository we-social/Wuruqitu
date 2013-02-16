<?php
if (IS_LOCAL) {
	define('DB_USER', 'root');
	define('DB_PASS', 'root');
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'db_'. strtolower(PRJ_NAME));
} else {
	define('DB_USER', 'xxxxxxxxxxx');
	define('DB_PASS', 'xxxxxxxx');
	define('DB_HOST', 'xxx.xxx.x.xx');
	define('DB_NAME', 'xxxxxxxxxxx');
}

define('TB_UP', 'tb_up');
define('TB_COMM', 'tb_comm');
define('TB_GOOD', 'tb_good');

define('TB_ADMIN', 'tb_admin');
define('TB_STAT', 'tb_stat');
?>
