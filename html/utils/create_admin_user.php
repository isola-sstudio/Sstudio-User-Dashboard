<?php
  session_start();
  /**
   **This script accepts a post request from anywhere to sign up or create a
   * user and it goes through some validation and returns the appropriate
   * response in php or JSON
   */

   require_once __DIR__ .'/../vendor/autoload.php';

   use Libs\AdminUser\AdminUser;
   use Libs\Validation\Validation;

    //check if there was a POST request
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
      if (!Validation::isBlank($_POST['name'], $_POST['email'], $_POST['password'], $_POST['phone'])) {# check that none of the submitted fields is blank
        //create a validation object
        $validation = new Validation();
        if (!$validation->ifExists('company_email', $_POST['email'])) {# check that the user had not previously registered
          $adminUser = new AdminUser();

          if ($adminUser->createAdminUserAccount($_POST['name'], $_POST['email'], $_POST['password'], $_POST['phone'])) {
            //so we set the necessary SESSION variables and send them to the
            //dashboard
            $_SESSION['user_id'] = $adminUser->getAdminUserInfo('company_email', $_POST['email'], 'id');
            $_SESSION['email'] = $_POST['email'];
            header('Location: dashboard.php');
          }else {
              # user account could not be created
              //most likely, server problem or badly formatted inputs
              $signupError = 'We are having problems creating your account! Please try again in a moment';
            }
        }else {
            # the user had been registered with this email so we send an
            // appropriate message
            $signupError = "Looks like you have been signed up already. Click here to login";
          }
      }else {
          # at least one of the submitted fields is blank
          $signupError = 'All fields are required!';
        }
    }
?>
