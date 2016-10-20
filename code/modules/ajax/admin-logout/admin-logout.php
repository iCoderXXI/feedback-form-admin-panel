<?php /* ЙЦУКЕН */

if (IS_ADMIN) {
  adminAuthClear();
  $success = TRUE;
  $message = "До свидания!";
} else {
  $success = FALSE;
  $message = "Ендпоинт сей теребишь не напрасно ли ты???";
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
