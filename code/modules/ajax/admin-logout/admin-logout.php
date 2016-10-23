<?php /* ЙЦУКЕН */

if (IS_ADMIN) {
  adminAuthClear();
  $success = TRUE;
  $message = "До свидания!";
} else {
  adminAuthClear();
  $success = TRUE;
  $message = "Однако, прощайте!";
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
