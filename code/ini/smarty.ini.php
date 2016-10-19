<?php /* ЙЦУКЕН */

require_once PATH_LIB_SMARTY.'/Smarty.class.php';
$smarty = new Smarty;
$smarty->force_compile = defined('IS_LOCAL')?true:false;

define('PATH_TEMPLATES', PATH_TPL.'/templates/');
$smarty->template_dir = PATH_TEMPLATES;
$smarty->compile_dir = PATH_TMP.'/templates/';
$smarty->config_dir = PATH_TPL.'/configs/';
$smarty->cache_dir = PATH_TMP.'/cache/';

if (!is_dir($smarty->compile_dir) && !mkdir($smarty->compile_dir)) {
	throw new Exception('FATAL: Unable to create '.$smarty->compile_dir);
}

if (!is_dir($smarty->cache_dir) && !mkdir($smarty->cache_dir)) {
	throw new Exception('FATAL: Unable to create '.$smarty->cache_dir);
}


function tplDisplay($tplName='main.tpl.html') {
	global $smarty;

	$out = $smarty->fetch($tplName, FALSE);

	if (function_exists('tplModifier')) {
		$out = tplModifier($out);
	}

	print $out;
}
