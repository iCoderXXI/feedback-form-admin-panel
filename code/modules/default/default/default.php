<?php

//print_rd($R, $_SESSION);

require_once(PATH_LIB.'/db.lib.php');

$orderBy = 'dt';
$allowedOrderBy = ['dt' => 1, 'name' => 1, 'email' => 1];
$orderDirection = "DESC";
$allowedOrderDirection = ['ASC' => 1, 'DESC' => 1];
$redirect = FALSE;

if (!isset($_SESSION['orderBy'])) {
  $_SESSION['orderBy'] = $orderBy;
}
if (!isset($_SESSION['orderDirection'])) {
  $_SESSION['orderDirection'] = $orderDirection;
}

if (isset($R['orderBy']) && isset($allowedOrderBy[$R['orderBy']])) {
  $orderBy = $R['orderBy'];
  if ($_SESSION['orderBy'] == $orderBy) {
    if ($_SESSION['orderDirection'] == 'ASC') {
      $R['orderDirection'] = 'DESC';
    } else {
      $R['orderDirection'] = 'ASC';
    }
  } else {
      $R['orderDirection'] = 'ASC';
  }
  $redirect = TRUE;
  $_SESSION['orderBy'] = $orderBy;
} elseif (isset($_SESSION['orderBy']) && isset($allowedOrderBy[$_SESSION['orderBy']])) {
  $orderBy = $_SESSION['orderBy'];
}

if (isset($R['orderDirection']) && isset($allowedOrderDirection[$R['orderDirection']])) {
  $orderDirection = $R['orderDirection'];
  $_SESSION['orderDirection'] = $orderDirection;
  $redirect = TRUE;
} elseif (isset($_SESSION['orderDirection']) && isset($allowedOrderDirection[$_SESSION['orderDirection']])) {
  $orderDirection = $_SESSION['orderDirection'];
}

if ($redirect) {
  redirectTo('/');
}

if (IS_ADMIN) {
  $fb = $DB->select('SELECT id AS ARRAY_KEY, f.* FROM feedback f ORDER BY '.$orderBy.' '.$orderDirection);
} else {
  $fb = $DB->select('SELECT id AS ARRAY_KEY, f.* FROM feedback f WHERE approved ORDER BY '.$orderBy.' '.$orderDirection);
}

$li = file_get_contents(PATH_CTRL.'/lorem-ipsum.txt');

define('ORDER_BY', $orderBy);
define('ORDER_DIRECTION', $orderDirection);

return ctrlRetPrepare(
  [
    'data' => [
      'fb' => $fb,
      'feedback' => '',
      'name' => '',
      'email' => '',
//      'orderBy' => $orderBy,
//      'orderDirection' => $orderDirection,
      'json' => [
        //'message' => 'Hello World!',
      ],
    ],
    //'viewAs' => 'json',
  ]
);
