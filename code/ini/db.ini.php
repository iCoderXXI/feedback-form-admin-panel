<?php

require_once PATH_LIB_DBSIMPLE.'/Connect.php';

if ($dsn = cfg2dsn()) {

  $DB = new DbSimple_Connect($dsn);
  //$DB->setErrorHandler('databaseErrorHandler');

	//Исправить, вынести в конфиг и поставить условие.
	$DB->query("SET @@session.time_zone='+04:00'");

  // Код обработчика ошибок SQL.
  function databaseErrorHandler($message, $info)
  {
     global $DB;

     // Если использовалась @, ничего не делать.
     if (!error_reporting() && !(defined('DEBUG_CONTENTS') && DEBUG_CONTENTS)) return;
     //throw new Exception('MySQL Error: '.$message.' '.var_export($info,TRUE));

     // To send HTML mail, the Content-type header must be set
     $headers  = 'MIME-Version: 1.0' . "\r\n";
     $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
     $headers .= 'To: '. ADMIN_EMAIL . "\r\n";
     $headers .= 'From: MySQL-Error@'.$_SERVER['SERVER_NAME'] . "\r\n";

     // Mail it
     mail(ADMIN_EMAIL, "MySQL Error on ".$_SERVER['SERVER_NAME'], $message."<br>\n".var_Export($info,TRUE), $headers);
     //requestLog(array($message,$info),'mysqlError');
     print_rd($message, $info);

     die('MySQL FATAL: information logged, admin noticed.');
  }



  //$DB->setLogger('myLogger');

  function myLogger($db, $sql)
  {
    if (defined('DEBUG_CONTENTS') && DEBUG_CONTENTS) {
      print_r($sql);
    }
    if(defined('DB_LOG') && DB_LOG) {
      if (substr($sql,0,3)!=='SET' && substr(trim($sql),0,2)!=='--')
       //requestLog($sql,'myLogger');
       print_rd($sql);
    }
  }

} else {
  throw new Exception('DB parameters missing in $APP_CONFIG.');
}

function skipEmpty($value='',$replace=DBSIMPLE_SKIP) {
  return (empty($value)?$replace:$value);
}
