<?php
  session_start();

  /**This script is meant to take all request that could be made by a user on
   * the user's account
   */

  require_once __DIR__ .'/../vendor/autoload.php';

  //use Libs\AdminUser\AdminUser;
  use Libs\Validation\Validation;
  use Libs\AdminUser\AdminUser;

  $adminUser = new AdminUser();

  if (isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
    # the needed session variables are set.. also means the user is logged in
    // bring in all the details of the user
    $adminUserDetails = $adminUser->getAdminUserInfo('id', $_SESSION['user_id']);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
      if (!Validation::isBlank($_POST['password'])) {# check that the password field is not blank
        //create an Admin User object
        $adminUser = new AdminUser();

        $password = md5($_POST['password']);
        if ($adminUser->getAdminUserInfo('id', $_SESSION['user_id'], 'password') == $password) {
          # check that user's password is correct

          //set the $info array
          foreach ($_POST as $key => $value) {
            # for each one of these post variables
            if (!empty($_POST["$key"]) && $_POST["$key"] != 'password') {
              //check that each field to be updated is not empty and that the particular field is not password
              // password can't be changed here. it s only used to validate the request

              # dynamically add up to the $info array
              $info["$key"] = $value;
            }
          }

         if ($adminUser->updateAdminUserInfo($info, $_SESSION['user_id'])) {
           //we set the response
           $updateResponse = 'Profile successfully updated.';
         }else {
             # user profile could not be updated
             //most likely, server problem or badly formatted inputs
             $updateError = 'We are having problems updating your account! Please try again in a moment';
           }
        }else {
            # the user's password is not correct, so we cannot do this operation now
            // appropriate message
            $updateError = "Looks like your password is wrong";
            die($updateError);
          }
      }else {
          # password must be set before we can update profile
          $updateError = 'Password must be set to update profile';
        }
    }
  }
?>
