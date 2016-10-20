<?php

define('PATH_TMP', PATH_ROOT.'/temp');

if (!is_dir(PATH_TMP) && !mkdir(PATH_TMP)) {
	throw new Exception('FATAL: Unable to create temp dir.');
}

define('IS_LOCAL', is_file(PATH_TMP.'/LOCAL'));

define('PATH_LIB', PATH_CODE.'/lib');
define('PATH_TPL', PATH_CODE.'/tpl');

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
