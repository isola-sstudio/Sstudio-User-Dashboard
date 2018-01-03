<?php
  session_start();
  /**
   **This script accepts a post request from anywhere to sign in a user and it
   * goes through some validation and returns the appropriate response in php or
   * JSON
   */

   require_once __DIR__ .'/../vendor/autoload.php';

   use Libs\AdminUser\AdminUser;
   use Libs\Validation\Validation;

    //check if there was a POST request
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
      if (!Validation::isBlank($_POST['email'], $_POST['password'])) {# check that neither of the submitted fields is blank
        //create a validation object
        $validation = new Validation();
        if ($validation->ifExists('company_email', $_POST['email'])) {# check if the user actually exists
          $adminUser = new AdminUser();
          if ($adminUser->logAdminUserIn($_POST['email'], $_POST['password'])) {# check if username and password match
            //so we set the necessary SESSION variables and send them to the
            //dashboard
            $_SESSION['user_id'] = $adminUser->getAdminUserInfo('company_email', $_POST['email'], 'id');
            $_SESSION['email'] = $_POST['email'];
            header('Location: dashboard.php');
          }else {
              # username and password do not match so we send appropraite message
              //incorrect password, most likely, since we have checked that the
              //username exists already except for server issues
              //also let's have a way to link the 'here' to a forgot password service
              $loginError = 'That password is wrong! Click here to reset your password';
            }
        }else {
            # the user does not exist so we send an appropriate message
            //ask for a popup here or a signup page
            $loginError = "We couldn't find your email in our database.";
          }
      }else {
          # at least one of the submitted fields is blank
          $loginError = 'Both fields are required!';
        }
    }
?>
