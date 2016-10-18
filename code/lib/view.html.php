<?php /* ЙЦУКЕН */

	define('HTTP_HEADER','Content-Type: text/html; charset=utf-8');

	/*
		Do not load Smarty lib, when it is not needed.
	*/

	if (!isset($appData['RET']['RAW_OUTPUT'])) {
		require_once PATH_ENGINE.'/smarty.ini.php';
	}

	
	function output($appData) {
		global $smarty, $appConfig, $errorMsg;
                
		if (defined('HTTP_HEADER')) {
			header(HTTP_HEADER);
		}

		if (isset($appData['RET']['RAW_OUTPUT']) && $appData['RET']['RAW_OUTPUT']) {
			if (!isset($appData['RET']['DATA'][$appData['RET']['RAW_OUTPUT']])) {
				throw new Exception('Data ['.$appData['RET']['RAW_OUTPUT'].'] '.
									'for RAW_OUTPUT missing in `'.MODULE.'`/`'.ACTION.'`.');
			}
			if ($errorMsg) {
				print '<script type="text/JavaScript"> var errorMsg="'.addslashes($errorMsg).'"</script>';
			} else {
				print $appData['RET']['DATA'][$appData['RET']['RAW_OUTPUT']];
				if (TIMER) print	"\n".'<!--Total time: '.(microtime(true)-TIME_STARTED).' sec.-->';
			}
		} else {
			
			if(isset($_SESSION['Err404'])){
			    if(isset($_SESSION['Err404']['module']) && $_SESSION['Err404']['module']!='default'){
				$smarty->assign('404Module',$_SESSION['Err404']['module']);
			    }else{
				$smarty->assign('404Module','');
			    }
			    if(isset($_SESSION['Err404']['action']) && $_SESSION['Err404']['action']!='default'){
				$smarty->assign('404Action',$_SESSION['Err404']['action']);
			    }else{
				$smarty->assign('404Action','');
			    }
			    
			   // unset($_SESSION['404']);
			}
                      
			$smarty->assign('appData',$appData);
			$smarty->assign('appConfig',$appConfig);
			$smarty->assign('appParams',getAppParams(),TRUE);	/* DO NOT CACHE THESE */
			$smarty->assign('CONST',getUserDefinedConstants(),TRUE);	/* DO NOT CACHE THESE */
			tplDisplay(isset($appData['RET']['TPL'])?$appData['RET']['TPL'].'.tpl.html':'main.tpl.html');
                        
		}
		
	}
?>