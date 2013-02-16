<?php
require(ROOT. '/plugin/360/360_safe3.php');
require(ROOT. '/conn/dbvars.php');

mysql_connect(DB_HOST, DB_USER, DB_PASS);
mysql_query("SET NAMES UTF8");
mysql_query("USE ". DB_NAME);
?>
