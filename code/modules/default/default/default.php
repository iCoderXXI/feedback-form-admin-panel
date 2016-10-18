<?php

$DB = initDB();

$tm = $DB->selectCell('SELECT NOW() AS tm');

return ctrlRetPrepare(
  [
    'data' => [
      'tm' => $tm
    ],
    'viewAs' => 'json',
  ]
);
