<?php

require_once(PATH_LIB.'/db.lib.php');

$fb = $DB->select('SELECT id AS ARRAY_KEY, f.* FROM feedback f WHERE approved ORDER BY dt DESC');

$li = file_get_contents(PATH_CTRL.'/lorem-ipsum.txt');

return ctrlRetPrepare(
  [
    'data' => [
      'fb' => $fb,
      'li' => $li,
      'name' => 'iCoder XXI',
      'email' => 'iCoder.XXI@gmail.com',
      'json' => [
        //'message' => 'Hello World!',
      ],
    ],
    //'viewAs' => 'json',
  ]
);
