<?php

function sanitizeFN($s = '') {
  return basename($s);
}

function show404($msg = 'Something wrong!') {
  header("HTTP/1.1 404 Not Found");
  die($msg);
}

function print_rd() {
  if (!defined('CLI')) {
    header('Content-Type: text/html; charset=utf-8;');
    print('<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body><pre>');
  }
  $args = func_get_args();
  if (!empty($args)) {
    print_r($args);
  } else {
    print 'ERROR: no arguments passed...';
  }
  exit;
}




function parseRoute() {
  global $R, $URL_MA;
  $URL_PATH = $R['q'] ? $R['q'] : '';
  $URL_MA = explode('/', $URL_PATH);
  if (empty($URL_MA) || empty($URL_MA[0])) {
    $URL_MA[0] = 'default';
  }
  if (!$URL_MA[1]) {
    $URL_MA[] = 'default';
  }

  foreach($URL_MA as $uk=>&$ud) {
    $ud = sanitizeFN($ud);
  }

  define('PATH_MODULE', PATH_CODE.'/modules/'.$URL_MA[0]);

  if (!is_dir(PATH_MODULE)) show404('Не найдена директория контроллера ['.$URL_MA[0].']');

  define('PATH_CTRL', PATH_MODULE.'/'.$URL_MA[0]);
  if (!is_dir(PATH_CTRL)) show404('Не найдена директория контроллера ['.$URL_MA[0].'/'.$URL_MA[1].']');

  define('FILE_CTRL', PATH_CTRL.'/'.$URL_MA[0].'.php');
  if (!is_file(FILE_CTRL)) show404('Не найден файл контроллера ['.$URL_MA[0].'/'.$URL_MA[1].'/'.$URL_MA[1].'.php'.']');

}



function ctrlRetPrepare($ret = []) {
  if (!isset($ret['viewAs'])) $ret['viewAs'] = 'html';
  if (!isset($ret['data'])) $ret['data'] = [];
  return $ret;
}



function cfg2dsn() {
  global $APP_CONFIG;
  $ret = FALSE;

  if (isset($APP_CONFIG['DB']) && !empty($APP_CONFIG['DB'])) {
    $ret =  $APP_CONFIG['DB']['DRIVER'].'://'.
        $APP_CONFIG['DB']['USER'].':'.
        $APP_CONFIG['DB']['PASSWORD'].'@'.
        $APP_CONFIG['DB']['HOST'].'/'.
        $APP_CONFIG['DB']['BASE'].
        $APP_CONFIG['DB']['ADDITIONAL'];
  }
  return $ret;
}


function initDB() {

  require_once(PATH_LIB.'/db.lib.php');

  return $DB;
}
