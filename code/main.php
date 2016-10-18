<?php

define('PATH_LIB', PATH_CODE.'/lib');
define('PATH_LIB_VENDOR', PATH_LIB.'/vendor');
define('PATH_LIB_SMARTY', PATH_LIB_VENDOR.'/Smarty');
define('PATH_LIB_DBSIMPLE', PATH_LIB_VENDOR.'/DBSimple');

define('PATH_INI', PATH_CODE.'/ini');

define('ADMIN_EMAIL', 'iCoder.XXI@gmail.com');

$APP_CONFIG = include(PATH_INI.'/app.ini.php');

require_once(PATH_LIB.'/global.lib.php');

parseRoute();

$APP_RET = include(FILE_CTRL);

print_rd($R, $APP_RET, basename(__FILE__));
