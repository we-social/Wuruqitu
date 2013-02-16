<?php
require('globalvars.php');
require(ROOT. '/conn/dbc.php');
mysql_query("UPDATE ". TB_STAT . " SET lastvisitat='". timestr() ."'");
