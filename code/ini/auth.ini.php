<?php /* ЙЦУКЕН */

	describeMyself(__FILE__);

	$authName = isset($_SERVER['PHP_AUTH_USER'])?$_SERVER['PHP_AUTH_USER']:FALSE;
	$authPwd = isset($_SERVER['PHP_AUTH_PW'])?$_SERVER['PHP_AUTH_PW']:FALSE;

	if (!isset($appConfig['auth'][$authName]) || $appConfig['auth'][$authName]!==$authPwd) {
		header('WWW-Authenticate: Basic realm="'.APP_NAME.'"');
		header('HTTP/1.0 401 Unauthorized');
		die('Доступ запрещен!');
	}
	
	define('AUTH_NAME',$authName);

?>