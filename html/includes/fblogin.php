<?php
  session_start();

  require_once __DIR__ . '/../vendor/autoload.php';

  //config constants for facebook login
  require_once __DIR__ . '/../../../config/fb/fb_constants.php';

  $fb = new Facebook\Facebook([
  'app_id' => APP_ID,
  'app_secret' => APP_SECRET,
  'default_graph_version' => 'v2.9',
  'persistent_data_handler' => 'session',
  ]);

  $helper = $fb->getRedirectLoginHelper();

  $permissions = ['email']; // Optional permissions
  $loginUrl = $helper->getLoginUrl('http://localhost/dashboard/html/utils/fb-callback.php', $permissions);

?>
