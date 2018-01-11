<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//so i decided to just duplicate this file and reeidt the name and content
//because i don't want to break the mail.php since it must have been working fine
//before

//for reusability, am going to turn this into a function
function sendMail($postName, $postEmail, $postNumber, $postPlan, $postProjectDescription){

    require_once __DIR__ . '/../../config/smtp/smtp_auth.php';//needed for smtp auth
    require_once __DIR__ . '/../_lib/phpmailer/PHPMailerAutoload.php';

    // CONFIG YOUR FIELDS
    //well, for now, just the needed variables (i.e. the variables that would be
    //posted from the form that is going to use it), and whenever more variables
    //are sent from any other form, they could just be initialized with an if statement
    //============================================================
    $name = filter_var($postName, FILTER_SANITIZE_STRING);
    $email = filter_var($postEmail, FILTER_SANITIZE_EMAIL);
    $contactNumber = filter_var($postNumber, FILTER_SANITIZE_STRING);
    $plan = filter_var($postPlan, FILTER_SANITIZE_STRING);
    $formMessage = filter_var($postProjectDescription, FILTER_SANITIZE_STRING);

    // CONFIG YOUR EMAIL MESSAGE
    //============================================================
    $message = '<p>The following are the details of the latest subscriber: </p>';
    $message .= '<p>Company Name: ' . $name . '</p>';
    $message .= '<p>Company Email: ' . $email . '</p>';
    $message .= '<p>Contact Number: ' . $contactNumber . '</p>';
    $message .= '<p>Plan: ' . $plan . ' package</p>';
    $message .= '<p>Project Description: ' . $formMessage .'</p>';

    // CONFIG YOUR MAIL SERVER
    //============================================================
    $mail = new PHPMailer;
    $mail->isSMTP();                                    // Enable SMTP authentication
    $mail->SMTPAuth = true;                             // Set mailer to use SMTP
  //        $mail->SMTPDebug = 4;
    //Sign up with MAIL GUN
    $mail->Host = 'smtp.gmail.com';

    $mail->Username = SENDMAIL_SMTP_USERNAME;                  // SMTP username
    $mail->Password = SENDMAIL_SMTP_PASSWORD;                         // SMTP password
    $mail->SMTPSecure = 'tls';                          // Enable encryption, 'ssl' also accepted
    $mail->Port = 587;
//    $mail->SMTPSecure = 'ssl';                          // Enable encryption, 'ssl' also accepted
  //  $mail->Port = 465;

    $mail->From = $email;
    $mail->FromName = $name;
    $mail->AddReplyTo($email, $name);
    $mail->addAddress('hello@thestartupstudio.org', $name);  // Add a recipient

    $mail->WordWrap = 50;                               // Set word wrap to 50 characters
    $mail->isHTML(true);                                // Set email format to HTML

    $mail->Subject = 'Subscription Notification';
    $mail->Body    = $message;

    if(!$mail->send()) {
    //    $data['error']['title'] = 'Message could not be sent.';
      //  $data['error']['details'] = 'Mailer Error: ' . $mail->ErrorInfo;
       return FAlSE;
    }else {
        return TRUE;
      }
//    $data['success']['title'] = 'Message has been sent';
  //  echo json_encode($data);
  }
?>
