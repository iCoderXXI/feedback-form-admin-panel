<?php /* ЙЦУКЕН */

$errMsg = FALSE;
$message = "Изменения сохранены";

if (!IS_ADMIN) {
  $errMsg = 'Палехче дружище!';
} else {

  if (!isset($R['id']) || $R['id']<1) {

    $errMsg = "На задано поле id";

  } elseif (!isset($R['feedback']) && !isset($R['approve'])) {

    $errMsg = "Нечего менять...";

  } else {

    require_once(PATH_LIB.'/db.lib.php');

    //$R['id']
    //$R['feedback']
    //$R['approved']

    $pfb = $DB->selectRow('SELECT approved, feedback FROM feedback WHERE id = ?d', $R['id']);

    if ($R['approved'] == $pfb['approved'] && $R['feedback'] == $pfb) {

      $errMsg = "Данные не изменились...";

    } else {
      $data = [
        'approved' => $R['approved'],
      ];

      //print_rd($pfb, $R);

      if ($R['feedback'] !== $pfb['feedback']) {
        $data['feedback'] = $R['feedback'];
        $data['edited'] = 1;
      }

      try {
        //print_rd($R, $pfb, $data);
        $DB->query('UPDATE feedback SET ?a WHERE id = ?d', $data, $R['id']);
      } catch(Exception $e) {
        $errMsg = "FATAL: ".$e->getMessage();
      }

    }
  }
}

$ret = ctrlRetPrepare(
  [
    'data' => [
      'message' => $errMsg ? $errMsg : $message,
      'success' => !$errMsg,
      'ret' => $R,
    ],
    'viewAs' => 'json',
  ]
);

return $ret;
