<?php /* ЙЦУКЕН */

$errMsg = FALSE;

$errMsg = 'Feedback edit functionality is not ready yet!';

if (!IS_ADMIN) {
  $errMsg = 'Палехче дружище!';
} else {

  require_once(PATH_LIB.'/db.lib.php');

  //$R['id']
  //$R['feedback']
  //$R['approved']
  $data = [
    'approved' => $R['approved'],
  ]
  $pfb = $DB->selectCell('SELECT feedback FROM feedback WHERE id = ?d', $R['id']);

  if ($R['feedback'] !== $pfb) {
    $data['feedback'] = $R['feedback'];
    $data['edited'] = 1;
  }

  $DB->query('UPDATE feedback SET ?a', $data);
}

return ctrlRetPrepare(
  [
    'data' => [
      'message' => $errMsg ? $errMsg : $message,
      'success' => !$errMsg,
      'ret' => $R,
    ],
    'viewAs' => 'json',
  ]
);
