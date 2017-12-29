<?php
  session_start();
  /**
   **This script accepts a post request from anywhere to log out a user
   */

   require_once __DIR__ .'/../vendor/autoload.php';

   use Libs\AdminUser\AdminUser;

   //call in the log out method
   AdminUser::logAdminUserOut();
   //send them home
   AdminUser::sendAdminUserHome();
?>
