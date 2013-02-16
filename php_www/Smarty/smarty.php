<?php
require(ROOT. '/Smarty/Smarty.class.php');
$smarty = new Smarty();

//$smarty->force_compile = true;
//$smarty->debugging = true;
//$smarty->caching = true;
//$smarty->cache_lifetime = 1800;

$smarty->setTemplateDir(ROOT. '/templates')
		->setConfigDir(ROOT. '/Smarty/configs')
		->setCompileDir(ROOT. '/Smarty/templates_c')
		->setCacheDir(ROOT. '/Smarty/cache')
		->setPluginsDir(ROOT. '/Smarty/plugins');
$smarty->left_delimiter = '{{';
$smarty->right_delimiter = '}}';
?>