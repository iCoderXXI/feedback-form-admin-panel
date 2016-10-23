<?php /* ЙЦУКЕН */

$errMsg = FALSE;
$code = isset($R['code']) && $R['code']>0 ? $R['code'] : FALSE;
$feedbackID = isset($_SESSION['feedbacks'][$code]) && $_SESSION['feedbacks'][$code]>0 ?
              $_SESSION['feedbacks'][$code] : FALSE;

if (isset($_FILES['imgupload']) && $code && $feedbackID) {
  $F = $_FILES['imgupload'];
  $imgName = $feedbackID.'.'.imgMime2Ext($f['type']);
  $imgPath = PATH_FEEDBACKS.'/'.$imgName;
    if (move_uploaded_file($F['tmp_name'][0], $imgPath)) {

      require_once(PATH_LIB.'/db.lib.php');
      if (!$DB->query('UPDATE feedback SET img = ? WHERE id = ?d', $imgName, $feedbackID)) {
          $errMsg = '* Не удалось сохранить информацию об изображении.';
      } else {
        $message = 'Изображение успешно сохранено.';
        unset($_SESSION['feedbacks'][$code]);
      }

    } else {
      $errMsg = '* Не удалось сохранить изображение.';
    }
} else {
  $errMsg = '* Ошибка при сохранении изображения.';
}

return ctrlRetPrepare(
  [
    'data' => [
      'message' => $errMsg ? $errMsg : $message,
      'success' => !$errMsg,
    ],
    'viewAs' => 'json',
  ]
);
