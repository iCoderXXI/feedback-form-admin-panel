<?php

function sanitizeFN($s = '') {
  return basename($s);
}

function show404($msg = 'Something wrong!') {
  header("HTTP/1.1 404 Not Found");
  die('404: '.$msg);
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
  if (isset($R['q'])) {
    $URL_PATH = $R['q'];
  } else {
    $URL_PATH = '';
  }
  if (substr($URL_PATH,0,1)=="/") {
    $URL_PATH = substr($URL_PATH,1);
  }

  $URL_MA = explode('/', $URL_PATH);

  //print_rd($URL_MA, $R);

  if (empty($URL_MA) || !isset($URL_MA[0]) || empty($URL_MA[0])) {
    $URL_MA[0] = 'default';
  }
  if (!isset($URL_MA[1]) || !$URL_MA[1]) {
    $URL_MA[] = 'default';
  }

  foreach($URL_MA as $uk=>&$ud) {
    $ud = sanitizeFN($ud);
  }

  define('PATH_MODULE', PATH_CODE.'/modules/'.$URL_MA[0]);

  if (!is_dir(PATH_MODULE)) show404('Не найдена директория контроллера ['.$URL_MA[0].']');

  define('PATH_CTRL', PATH_MODULE.'/'.$URL_MA[1]);
  if (!is_dir(PATH_CTRL)) show404('Не найдена директория контроллера ['.$URL_MA[0].'/'.$URL_MA[1].']');

  define('FILE_CTRL', PATH_CTRL.'/'.$URL_MA[1].'.php');
  if (!is_file(FILE_CTRL)) show404('Не найден файл контроллера ['.$URL_MA[0].'/'.$URL_MA[1].'/'.$URL_MA[1].'.php'.']');

  define('MODULE', $URL_MA[0]);
  define('CTRL', $URL_MA[1]);

}



function ctrlRetPrepare($ret = []) {
  global $APP_CONFIG;
  if (!isset($ret['viewAs'])) $ret['viewAs'] = 'html';
  if (!isset($ret['data'])) $ret['data'] = [];

  if (isset($APP_CONFIG['PAGE_PARAMS'][MODULE][CTRL])) {
    foreach($APP_CONFIG['PAGE_PARAMS'][MODULE][CTRL] as $k=>$v) {
      if (!isset($ret[$k])) {
        $ret[$k] = $v;
      }
    }
  }

  if (!isset($ret['title'])) {
    $ret['title'] = MODULE.'/'.CTRL;
  }

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



if (!function_exists('mb_ucfirst')) {
	function mb_ucfirst($str,$enc) {
    $l = mb_strlen($params[0],$enc);
		$f = mb_strtoupper(mb_substr($params[0],0,1,$enc),$enc);
		$e = mb_strtolower(mb_substr($params[0],1,$l-1,$enc),$enc);
		return $f.$e;
	}
}


function getUserDefinedConstants() {
  $tmp = get_defined_constants(TRUE);
  return $tmp['user'];
}



function imgTypeByFileExt($fileName='') {
  $imgTypes = array(
    "jpg"  => "jpeg",
    "jpeg" => "jpeg",
    "gif"  => "gif",
    "png"  => "png",
  );
  $ret = FALSE;
  $extPos = strrpos($fileName,'.');
  if ($extPos !== FALSE) {
    $ext = strtolower(substr($fileName,$extPos+1));
    if (isset($imgTypes[$ext])) { $ret = $imgTypes[$ext];}
  }
  return $ret;
}


function prettyJSON($data = [], $pretty = TRUE) {
  return json_encode($data,JSON_UNESCAPED_UNICODE+$pretty*JSON_PRETTY_PRINT+JSON_NUMERIC_CHECK);
}

function imgMime2Ext($mime) {
  $m2e = [
    'image/gif' => 'gif',
    'image/png' => 'png',
    'image/jpeg' => 'jpg',
  ];
  return isset($m2e[$mime]) ? $m2e[$mime] : 'jpg';
}


function fbImgUrl($img) {
  return URL_FEEDBACKS.'/'.$img;
}


function redirectTo($url) {
  global $R;
  session_write_close();
  header('Location: '.$url);
  //print_rd($_SESSION, $R);
  exit;
}
