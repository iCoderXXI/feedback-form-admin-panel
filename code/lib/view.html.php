<?php /* ЙЦУКЕН */

	define('HTTP_HEADER','Content-Type: text/html; charset=utf-8');

	/*
		Do not load Smarty lib, when it is not needed.
	*/

	if (!isset($appData['raw'])) {
		require_once PATH_INI.'/smarty.ini.php';
	}


	function viewRender($appData) {
		global $smarty, $APP_CONFIG;

		if (defined('HTTP_HEADER')) {
			header(HTTP_HEADER);
		}

		if (isset($appData['raw']) && $appData['raw']) {
			if (!isset($appData['data'])) {
				throw new Exception('Missing data for raw html output in `'.MODULE.'`/`'.ACTION.'`.');
			}
			print $appData['data'];
		} else {

			$smarty->assign('appData',$appData);
			$smarty->assign('appConfig',$APP_CONFIG);
			$smarty->assign('CONST',getUserDefinedConstants(),TRUE);	/* DO NOT CACHE THESE */
			tplDisplay(isset($appData['tpl'])?$appData['tpl'].'.tpl.html':'main.tpl.html');

		}
	}
