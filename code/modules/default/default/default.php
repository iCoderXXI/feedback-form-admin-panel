<?php

require_once(PATH_LIB.'/db.lib.php');

$tm = $DB->selectCell('SELECT NOW() AS tm');

return ctrlRetPrepare(
  [
    'data' => [
      'tm' => $tm,
      'json' => [
        'message' => 'Hello World!',
      ],
    ],
    //'viewAs' => 'json',
  ]
);
