<?php /* ЙЦУКЕН */

$errFldID = FALSE;
$errMsg = FALSE;
$name = isset($R['name']) ? preg_replace('/\s\s+/', ' ', $R['name']) : FALSE;
$email = isset($R['email']) ? preg_replace('/\s\s+/', '', $R['email']) : FALSE;
$feedback = isset($R['feedback']) ? preg_replace('/\s\s+/', ' ', $R['feedback']) : FALSE;
$feedbackID = FALSE;

$code = isset($R['code']) && $R['code']>0 ? $R['code'] : FALSE;

if (mb_strlen($name)<3 || count(explode(' '))>2) {
  $errMsg = '* Представьтесь пожалуйста!';
  $errFldID = 'name';
} else if (!preg_match("/.+@.+\..+/i", $email)) {
  $errMsg = '* E-Mail, нам нужен Ваш E-Mail!';
  $errFldID = 'email';
} else if (mb_strlen($feedback)<5) {
  $errMsg = '* Нам важен Ваш отзыв!';
  $errFldID = 'feedback';
} else {

  require_once(PATH_LIB.'/db.lib.php');

  $data = [
    'name' => $name,
    'email' => $email,
    'feedback' => $feedback,
  ];
  $feedbackID = $DB->query('INSERT INTO feedback (?#) VALUES (?a)', array_keys($data), array_values($data));

  if ($feedbackID) {
    $_SESSION['feedbacks'][$code] = $feedbackID;
    $message = 'Благодарим Вас за отзыв!';
  } else {
    $errMsg = 'Не удалось сохранить отзыв!';
  }
}


$ret =
  [
    'data' => [
      'message' => $errMsg ? $errMsg : $message,
      'success' => !$errMsg,
      'errFldID' => $errFldID,
      'id' => $feedbackID,
    ],
    'viewAs' => 'json',
  ];

return ctrlRetPrepare($ret);
