<?php /* ЙЦУКЕН */

function adminAuthRegister() {
	$_SESSION['adminAuth'] = time();
}

function adminAuthClear() {
	unset($_SESSION['adminAuth']);
}

function isAdmin() {
	return (isset($_SESSION['adminAuth']) && time()-$_SESSION['adminAuth']<ADMIN_AUTH_TIMEOUT);
}

define('SESSION_NAME', 'beejee2016tz');
define('SESSION_SALT', 'MW<ERdfo;jR:OJ5wE4%tyuAi#08ac;i;aASDj2#ewfsaqew23!#@$QR243qwe66tWErbvas');
define('SESSION_DATA_KEY_NAME', 'DATA_KEY');
define('PATH_SESSION_SAVE', PATH_TMP.'/sess');
define('ADMIN_AUTH_TIMEOUT', 3600);

if (!is_dir(PATH_SESSION_SAVE) && !mkdir(PATH_SESSION_SAVE)) {
	throw new Exception('Was unable to create session folder.');
}

session_save_path(PATH_SESSION_SAVE);
session_name(SESSION_NAME);
session_set_cookie_params(2592000);
ini_set("session.gc_maxlifetime", "7776000");

if (!session_start()) {
	throw new Exception('Was unable to start session with session_start().');
}

define('SESSION_ID',session_id());

define('IS_ADMIN', isAdmin());
