<?php

 require_once dirname(__FILE__) . '/Connect.php';

 if ($dsn = cfg2dsn()) {

  // Подключаемся к БД.
  $DB = new DbSimple_Connect($dsn);


/*
  // Устанавливаем обработчик ошибок.
  $DB->setErrorHandler('databaseErrorHandler');

  // Код обработчика ошибок SQL.
  function databaseErrorHandler($message, $info)
  {
     global $DB;
     // Если использовалась @, ничего не делать.
     if (!error_reporting()) return;
     // Выводим подробную информацию об ошибке.
     echo "<div class='dbError'>SQL Error: $message<br><pre>"; 
     print_r($info);
     echo "</pre></div>";
     exit();
  }

*/

  $DB->setLogger('myLogger');

  function myLogger($db, $sql)
  {
    if (defined('DEBUG_CONTENTS') && DEBUG_CONTENTS) {
      print_r($sql); 
    }
  }
  
 } else {
  throw new Exception('DB parameters missing in $appConfig.');
 }
 
 function skipEmpty($value='',$replace=DBSIMPLE_SKIP) {
  return (empty($value)?$replace:$value);
 }

?>