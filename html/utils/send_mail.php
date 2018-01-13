<?php
  error_reporting(E_ALL);
  ini_set("display_errors", 1);

  require_once __DIR__ . '/../vendor/autoload.php';
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

function sendMail($subject, $companyName, $postEmail, $postMessageArray){
    require_once __DIR__ . '/../../../config/smtp/smtp_auth.php';//needed for smtp auth

    if(isset($companyName) && !empty($companyName)){
      $name = $companyName;
    }else {
        $name = $postEmail;
      }


    // CONFIG YOUR FIELDS
    //well, for now, just the needed variables (i.e. the variables that would be
    //posted from the form that is going to use it), and whenever more variables
    //are sent from any other form, they could just be initialized with an if statement
    //============================================================
    // $name = filter_var($postName, FILTER_SANITIZE_STRING);
    // $email = filter_var($postEmail, FILTER_SANITIZE_EMAIL);
    // $contactNumber = filter_var($postNumber, FILTER_SANITIZE_STRING);
    // $plan = filter_var($postPlan, FILTER_SANITIZE_STRING);
    // $formMessage = filter_var($postProjectDescription, FILTER_SANITIZE_STRING);

    // CONFIG YOUR EMAIL MESSAGE
    //============================================================
    $message = '';
    foreach ($postMessageArray as $key => $value) {
      # for each structured array for the message
      $message .= '<p>'.$key.': '.$value.'</p>';
    }

    // CONFIG YOUR MAIL SERVER
    //============================================================
    $mail = new PHPMailer;
    $mail->isSMTP();                                    // Enable SMTP authentication
    $mail->SMTPAuth = true;                             // Set mailer to use SMTP
          $mail->SMTPDebug = 4;
    //Sign up with MAIL GUN
    $mail->Host = 'smtp.gmail.com';

    $mail->Username = SENDMAIL_SMTP_USERNAME;                  // SMTP username
    $mail->Password = SENDMAIL_SMTP_PASSWORD;                         // SMTP password
    $mail->SMTPSecure = 'tls';                          // Enable encryption, 'ssl' also accepted
    $mail->Port = 587;
//    $mail->SMTPSecure = 'ssl';                          // Enable encryption, 'ssl' also accepted
  //  $mail->Port = 465;

    $mail->From = $postEmail;
    $mail->FromName = $name;
    $mail->AddReplyTo($email, $name);
    // $mail->addAddress('hello@thestartupstudio.org', $name);  // Add a recipient
        $mail->addAddress('isola@sstudio.io', $name);  // Add a recipient

    $mail->WordWrap = 50;                               // Set word wrap to 50 characters
    $mail->isHTML(true);                                // Set email format to HTML

    $mail->Subject = $subject;
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
