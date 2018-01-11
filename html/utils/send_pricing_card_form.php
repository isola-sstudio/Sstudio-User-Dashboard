<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $_SERVER['REQUEST_METHOD'] == 'POST') {
//for reusability, am going to turn this into a function
function sendMailFromPricingCardForm($postName, $postEmail, $postNumber, $postPlan, $postProjectDescription){

	# The Form Data
    //$form_json = json_decode($_POST['data'], true);
	# Subscriber's Email
	//$user_email = $form_json['email'];


	# Include Swift Mailer Library
    require_once 'swift_mailer/vendor/autoload.php';

	## Replace With Your SMTP Details !!
	$smtp_server = 'mail.sstudio.io';
	$smtp_port = 587; //587
	$smtp_username = 'bakare@sstudio.io';
	$smtp_pass = '}mA)ct1[mgH1';

	# The Transport
    $transport = (new Swift_SmtpTransport(
		$smtp_server,
		$smtp_port
	))->setUsername($smtp_username)->setPassword($smtp_pass);

    #Init Swift
    $mailer = new Swift_Mailer($transport);

	## Email Headers
	## Replace With Your Details !!!
	$email_to = 'hello@sstudio.io';
	$email_to_name = 'Startup Studio';
	$email_from = $smtp_username;
	$email_from_name = $postName;
	$email_subject = 'Interest in a Subscription Plan';

	# Responder Email HTML Body
	$email_body = '<p>The following are the details of the latest subscriber: </p>';
  $email_body .= '<p>Company Name: ' . $postName . '</p>';
  $email_body .= '<p>Company Email: ' . $postEmail . '</p>';
  $email_body .= '<p>Contact Number: ' . $postNumber . '</p>';
  $email_body .= '<p>Plan: ' . $postPlan . ' package</p>';
  $email_body .= '<p>Project Description: ' . $postProjectDescription .'</p>';

	# The Email
	$responder_mail = (new Swift_Message())
        ->setFrom(array($email_from => $email_from_name))
        ->setSubject($email_subject)
        ->setTo(array($email_to => $email_to_name))
		->setBody($email_body, 'text/html');

	# Send -> -> ->
	if($mailer->send($responder_mail)){
		#return TRUE
    return TRUE;
  }else{
		  #Return false
		  return FAlSE;
	 }
}
if (isset($_POST['name'])) {
  sendMailFromPricingCardForm('Tolu', 'i_tolu@yahoo.com', '09092923990', 'Custom', "Project Fame");
}
?>
