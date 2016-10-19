<?php

require_once(PATH_LIB.'/db.lib.php');

$tm = $DB->selectCell('SELECT NOW() AS tm');

$li = file_get_contents(PATH_CTRL.'/lorem-ipsum.txt');

return ctrlRetPrepare(
  [
    'data' => [
      'tm' => $tm,
      'li' => $li,
      'name' => 'iCoder XXI',
      'email' => 'iCoder.XXI@gmail.com',
      'json' => [
        'message' => 'Hello World!',
      ],
    ],
    //'viewAs' => 'json',
  ]
);
