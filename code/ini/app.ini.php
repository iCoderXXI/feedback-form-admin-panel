<?php

define('PLACEHOLDER_IMG','profile-placeholder.png');
define('URL_PLACEHOLDER_IMG', URL_IMG.'/'.PLACEHOLDER_IMG);

return [
  'DB' => [
    'DRIVER' => 'mypdo',
    'HOST' => 'localhost',
    'ADDITIONAL' => '?enc=utf8',
    'BASE' => 'beejee',
    'USER' => 'beejee',
    'PASSWORD' => 'beejee',
  ],

  'OUTPUT' => [
    'ROWS_CNT' => 20,
  ],

  'PAGE_PARAMS' => [
    'default' => [
      'default' => [
        'title' => 'Отзывы',
      ],
    ],
  ],
];
