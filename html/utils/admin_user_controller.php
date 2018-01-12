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

  if (isset($_SESSION['user_id'])) {
    # the needed session variables are set.. also means the user is logged in

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
        //create an Admin User object
        $adminUser = new AdminUser();


        if ($adminUser->getAdminUserInfo('id', $_SESSION['user_id'], 'password') != '') {
          # the user has a password
          if (!Validation::isBlank($_POST['password'])) {# check that the password field is not blank
            $password = md5($_POST['password']);
          if ($adminUser->getAdminUserInfo('id', $_SESSION['user_id'], 'password') == $password) {
            # check that user's password is correct

            //set the $info array
            foreach ($_POST as $key => $value) {
              # for each one of these post variables
              if (!empty($_POST["$key"]) && $key != 'password') {
                //check that each field to be updated is not empty and that the particular field is not password
                // password can't be changed here. it s only used to validate the request

                # dynamically add up to the $info array
                $info["$key"] = $value;
              }
            }

            if ($_FILES['profile_pix']) {
              # there is a picture since the user has requested to change profile picture
              $target_dir = "uploads/";

              $target_file = $target_dir . rand(10,10000) . '_' . basename($_FILES["profile_pix"]["name"]);
              $uploadOk = 1;
              $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
              // Check if image file is a actual image or fake image

              $check = getimagesize($_FILES["profile_pix"]["tmp_name"]);
              if($check == false) {
                  $updateError = "File is not an image.";
              }else {
                  // Check file size
                  if ($_FILES["profile_pix"]["size"] > 500000) {
                      $updateError = "Only file size of 5mb and below is allowed.";
                  }else {

                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif" ) {
                        $updateError = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    }else {

                      if (!move_uploaded_file($_FILES["profile_pix"]["tmp_name"], $target_file)) {
                        $updateError = "Sorry, there was an error uploading your file.";
                        // unset files
                        unset($_FILES);
                      }else {
                        $info['picture'] = $target_file;
                      }
                    }
                  }
                }
              }
            if (!$updateError) {
              if ($adminUser->updateAdminUserInfo($info, $_SESSION['user_id'])) {
                //we set the response
                $updateResponse = 'Profile successfully updated.';
              }else {
                # user profile could not be updated
                //most likely, server problem or badly formatted inputs
                $updateError = 'We are having problems updating your account! Please try again in a moment';
              }
            }
          }else {
            # the user's password is not correct, so we cannot do this operation now
            // appropriate message
            $updateError = "Looks like your password is wrong";
          }
        }else {
          # password must be set before we can update profile
          $updateError = 'Password must be set to update profile';
        }
      }else {
        # the user does not have a password
        //set the $info array
        foreach ($_POST as $key => $value) {
          # for each one of these post variables
          if (!empty($_POST["$key"])) {
            //check that each field to be updated is not empty and that the particular field is not password
            # dynamically add up to the $info array
            $info["$key"] = $value;
          }
        }

        if ($_FILES['profile_pix']) {
          # there is a picture since the user has requested to change profile picture
          $target_dir = "uploads/";

          $target_file = $target_dir . rand(10,10000) . '_' . basename($_FILES["profile_pix"]["name"]);
          $uploadOk = 1;
          $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
          // Check if image file is a actual image or fake image

          $check = getimagesize($_FILES["profile_pix"]["tmp_name"]);
          if($check == false) {
              $updateError = "File is not an image.";
          }else {
              // Check file size
              if ($_FILES["profile_pix"]["size"] > 500000) {
                  $updateError = "Only file size of 5mb and below is allowed.";
              }else {

                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                    $updateError = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                }else {

                  if (!move_uploaded_file($_FILES["profile_pix"]["tmp_name"], $target_file)) {
                    $updateError = "Sorry, there was an error uploading your file.";
                    // unset files
                    unset($_FILES);
                  }else {
                    $info['picture'] = $target_file;
                  }
                }
              }
            }
          }
        if (!$updateError) {
          $info['password'] = md5($_POST['password']);
          if ($adminUser->updateAdminUserInfo($info, $_SESSION['user_id'])) {
            //we set the response
            $updateResponse = 'Profile successfully updated.';
          }else {
            # user profile could not be updated
            //most likely, server problem or badly formatted inputs
            $updateError = 'We are having problems updating your account! Please try again in a moment';
          }
        }

      }
    }

    // bring in all the details of the user
    $adminUserDetails = $adminUser->getAdminUserInfo('id', $_SESSION['user_id']);
  }
?>
