<?php /* ЙЦУКЕН */

	if (!function_exists('mb_ucfirst')) {
		function mb_ucfirst($str,$encoding) {
			$f = mb_strtoupper(mb_substr($params[0],0,1,$encoding),$encoding);
			$l = mb_strtolower(mb_substr($params[0],1,mb_strlen($params[0],$encoding)-1,$encoding),$encoding);
			return $f.$l;
		}
	}

	require_once PATH_SMARTY.'/Smarty.class.php';
	$smarty = new Smarty;
	$smarty->force_compile = defined('IS_LOCAL')?true:false;

	define('PATH_TEMPLATES', PATH_TPL.'/templates/');
	$smarty->template_dir = PATH_TEMPLATES;
	$smarty->compile_dir = PATH_APP_TMP.'/templates_c/';
	$smarty->config_dir = PATH_TPL.'/configs/';
	$smarty->cache_dir = PATH_APP_TMP.'/cache/';
//	$smarty->caching = true;

	if (!defined('SKIN')) define('SKIN',FALSE);

	if (!is_dir($smarty->compile_dir) && !mkdir($smarty->compile_dir)) {
		throw new Exception('FATAL: Unable to create '.$smarty->compile_dir);
	}

	if (!is_dir($smarty->cache_dir) && !mkdir($smarty->cache_dir)) {
		throw new Exception('FATAL: Unable to create '.$smarty->cache_dir);
	}
/*
	$smarty->setCaching(true);
	$smarty->setCacheLifetime(3600);
	$smarty->setCompileCheck(true);
*/

	function protectEMail($tpl_output, Smarty_Internal_Template $template)
	{
		$tpl_output =
			preg_replace('!(\S+)@([a-zA-Z0-9\.\-]+\.([a-zA-Z]{2,3}|[0-9]{1,3}))!',
						'$1<span class="at">(at)</span>$2', $tpl_output);
		return $tpl_output;
	}

	// register the outputfilter
	if (FALSE && (!defined('NO_EMAIL_PROTECTION') || !NO_EMAIL_PROTECTION)) {
		$smarty->registerFilter("output","protectEMail");
	}

	function tplDisplay($tplName='main.tpl.html', $isJS = false) {
		global $smarty, $CACHER, $engineTimerLog;
		$tplSTime=microtime(true);
		$engineTimerLog['out.php:beforeFetch'] = microtime(true);

		$out = $smarty->fetch($tplName,defined('CACHE_ID')?CACHE_ID:FALSE);

		$engineTimerLog['out.php:afterFetch'] = microtime(true);
		if (function_exists('tplModifier')) {
			$out = tplModifier($out);
			$engineTimerLog['out.php:afterTPLModifier'] = microtime(true);
		}
		$tplETime = microtime(true);
		$engineTimerLog['out.php:beforePrint'] = microtime(true);
		print $out;
		$engineTimerLog['out.php:afterPrint'] = microtime(true);
		if (TIMER) {
			if ($isJS) {
				$sc = '/*';
				$ec = '*/';
			} else {
				$sc = '<!--';
				$ec = '-->';
			}

			print $sc.'Total (& Smarty) time: '.
						($tplETime-TIME_STARTED).' ('.($tplETime-$tplSTime).') sec. '.
						($smarty->isCached($tplName,defined('CACHE_ID')?CACHE_ID:FALSE)?'CACHED':'').$ec;
		}
		/*if (isset($CACHER) && is_object($CACHER)) {
			$CACHER->setCached($out);
		}*/
	}

?>
