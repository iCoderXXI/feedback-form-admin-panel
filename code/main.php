<?php

mb_internal_encoding("UTF-8");

define('PATH_TMP', PATH_ROOT.'/temp');
if (!is_dir(PATH_TMP) && !mkdir(PATH_TMP, 0777)) {
	throw new Exception('FATAL: Unable to create temp dir.');
}

define('PATH_RESOURCES', PATH_ROOT.'/resources');
if (!is_dir(PATH_RESOURCES) && !mkdir(PATH_RESOURCES, 0777)) {
	throw new Exception('FATAL: Unable to create resources dir.');
}

define('PATH_FEEDBACKS', PATH_RESOURCES.'/feedbacks');
if (!is_dir(PATH_FEEDBACKS) && !mkdir(PATH_FEEDBACKS, 0777)) {
	throw new Exception('FATAL: Unable to create feedbacks dir.');
}

define('URL_FEEDBACKS', 'assets/feedbacks');

define('IS_LOCAL', is_file(PATH_TMP.'/LOCAL'));

define('PATH_LIB', PATH_CODE.'/lib');
define('PATH_TPL', PATH_CODE.'/tpl');
define('PATH_ASSETS', PATH_TPL.'/assets');
define('PATH_IMG', PATH_ASSETS.'/img');
define('PATH_FEEDBACK_IMG', PATH_TMP.'/img');


define('PATH_LIB_VENDOR', PATH_LIB.'/vendor');
define('PATH_LIB_SMARTY', PATH_LIB_VENDOR.'/Smarty');
define('PATH_LIB_DBSIMPLE', PATH_LIB_VENDOR.'/DBSimple');

define('PATH_INI', PATH_CODE.'/ini');

define('ADMIN_EMAIL', 'iCoder.XXI@gmail.com');

require_once(PATH_LIB.'/global.lib.php');
require_once(PATH_INI.'/sessions.ini.php');

$R = $_REQUEST;
$APP_CONFIG = include(PATH_INI.'/app.ini.php');

parseRoute();

$APP_RET = include(FILE_CTRL);

session_write_close();

if (!$APP_RET['viewAs']){
  throw new Exception('Missing [viewAs] in ret of '.MODULE.'/'.CTRL.'.');
} else {
  define('FILE_VIEW', PATH_LIB.'/view.'.$APP_RET['viewAs'].'.php');
  if (!is_file(FILE_VIEW)) {
    throw new Exception('Wrong [viewAs - '.$APP_RET['viewAs'].'] in ret of '.MODULE.'/'.CTRL.'.');
  } else {
    require_once(FILE_VIEW);
    if (!function_exists('viewRender')) {
      throw new Exception('Missing viewRender() in [viewAs - '.$APP_RET['viewAs'].'].');
    } else {
      viewRender($APP_RET);
    }
  }
}

//print_rd($R, $APP_RET, basename(__FILE__));
