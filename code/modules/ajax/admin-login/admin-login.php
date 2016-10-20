<?php /* ЙЦУКЕН */

$pwd = isset($R['pwd'])?$R['pwd']:FALSE;

if ($pwd == "123") {
  adminAuthRegister();
  $success = TRUE;
  $message = "Добро пожаловать уважаемый админ!";
} else {
  $success = FALSE;
  $message = "Нащяльнике, твая пароля плахой анднака!";

}

return ctrlRetPrepare(
  [
    'data' => [
      'success' => $success,
      'message' => $message,
    ],
    'viewAs' => 'json',
  ]
);
