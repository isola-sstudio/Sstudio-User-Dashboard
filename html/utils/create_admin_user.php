<?php
  session_start();
  ob_start();
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
      if (!Validation::isBlank($_POST['email'], $_POST['password'])) {# check that none of the submitted fields is blank
        //create a validation object
        $validation = new Validation();
        if (!$validation->ifExists('company_email', $_POST['email'])) {# check that the user had not previously registered
          $adminUser = new AdminUser();

          if ($adminUser->createAdminUserAccount('', $_POST['email'], '', '', $_POST['password'])) {
            //so we set the necessary SESSION variables and send them to the
            //dashboard
            $_SESSION['user_id'] = $adminUser->getAdminUserInfo('company_email', $_POST['email'], 'id');




            if (isset($_POST['subscribe']) && !empty($_POST['subscribe'])) {
              // there is a subscribe variable in the post
              if ($_POST['subscribe'] == 'basic' || $_POST['subscribe'] == 'standard' ||
              $_POST['subscribe'] == 'custom') {
                $subscribe = $_POST['subscribe'];
                header("Location: dashboard.php?subscribe=$subscribe");
              }else {
                # just send them to dashboard
                header('Location: dashboard.php');//send them to dashboard
              }
            }else {
              # just send them to dashboard
              header('Location: dashboard.php');//send them to dashboard
            }





              }else {
              # user account could not be created
              //most likely, server problem or badly formatted inputs
              $signupError = "Something's not right! Check back in a moment";
              $signupErrorJSON = json_encode($signupError);
              ?>
              <script type="text/javascript">
                var signupError = JSON.parse('<?php echo addslashes($signupErrorJSON) ?>');
              </script>
              <?php
            }
        }else {
            # the user had been registered with this email so we send an
            // appropriate message
            $signupError = "Looks like you have been here before. Click <a href=\"javascript:void(0)\" id=\"to-signin\">here</a> to log in";
            $signupErrorJSON = json_encode($signupError);
            ?>
            <script type="text/javascript">
              var signupError = JSON.parse('<?php echo addslashes($signupErrorJSON) ?>');
            </script>
            <?php
          }
      }else {
          # at least one of the submitted fields is blank
          $signupError = "Let's start by knowing your email. Your password is safe!";
          $signupErrorJSON = json_encode($signupError);
          ?>
          <script type="text/javascript">
            var signupError = JSON.parse('<?php echo addslashes($signupErrorJSON) ?>');
          </script>
          <?php
        }
    }
?>
