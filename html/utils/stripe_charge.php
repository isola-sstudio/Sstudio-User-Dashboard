<?php require_once __DIR__ . '/../vendor/autoload.php'; ?>
<?php require_once __DIR__ . '/../../config/stripe/stripe_config.php'; ?>
<?php require_once __DIR__ . '/../../config/db/db_constants.php'; ?>
<?php require_once __DIR__ . '/../php/send_pricing_card_form.php'; ?>

<?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //set some of the received data values
    $companyName = $_POST['name'];
    $companyEmail = $_POST['email'];
    $contactNumber = $_POST['phone'];
    $projectDescription = $_POST['project_description'];
    $subscriptionPlan = $_POST['package'];

    # user preference for time of payment
    $timeOfPayment = $_POST['preference'];

    # check if the form submitted was from a custom package or the customer
    //wants to pay later
    if ($timeOfPayment == 'talk' || $timeOfPayment == 'later') {
      # the customer has chosen a custom package and wants to talk
      //send the customer's details to server
      $serverConn = new mysqli(SERVER_NAME, USER, USER_PASSWORD);//connect to server
      if (!$serverConn -> connect_error){//connection was successful
        //prepare query to insert info into database
        $query = "INSERT INTO `thestart_upstudio`.`tss_package_subscription` (`company_name`,
          `company_email`, `contact_number`, `project_description`, `subscription_plan`)
          VALUES ('$companyName', '$companyEmail', '$contactNumber', '$projectDescription',
            '$subscriptionPlan')";
        //try sending to database
        if ($serverConn -> query($query) === TRUE){
          //sending to db was successful
          $dbResponse = 'success';
        }else {
            # sending to db was not successful
            $dbResponse = 'failure';
          }
      }else {
          # connection was not successful, so do something
          $dbResponse = 'connection_error';
        }

      //also send a mail to hello@sstudio.io
      //well i decided to do this independent of sending to server so that if
      //sending to server fails, mail might not and vice versa
      if (sendMailFromPricingCardForm($companyName, $companyEmail, $contactNumber, $_POST['package'], $projectDescription)) {
        # mail was sent
        $mailResponse = 'success';
      }else {
          # mail was not sent
          $mailResponse = 'failure';
        }
    }

    //in a situation where the customer wants to actually pay now
    if (isset($_POST['stripeToken']) && !empty($_POST['stripeToken'])) {
      //user package and price
      if ($_POST['package'] == 'basic') {
        # plan id was set to 1 on stripe
        $planId = 1;
        $subscriptionPlan = 'Basic';
        $planAmount = 15300;
      }elseif ($_POST['package'] == 'standard') {
          # plan id was set to 2 on stripe
          $planId = 2;
          $subscriptionPlan = 'Standard';
          $planAmount = 27800;
        }
      \Stripe\Stripe::setApiKey($stripe['secret']);

      $token = $_POST['stripeToken'];

      //regardless of whatever payment time the user has chosen,
      try {
        // Create a Customer:
        $customer = \Stripe\Customer::create(array(
          "email" => $_POST['pay_email'],
          "source" => $token,
          "plan" => $planId,
        ));

        $stripeCustomerId = $customer->id;//the just created customer id
        $subscriptionStatus = 1;//since the customer has decided to pay now

        # charge the customer only if they decide to pay now
        // Charging the created Customer
        $charge = \Stripe\Charge::create(array(
          "amount" => $planAmount,
          "currency" => "usd",
          "description" => "Subscription",
          "customer" => $customer->id
        ));

        $stripeResponse = 'success';//well at this point, the payment has been made

      } catch (Stripe_CardError $e) {
          $stripeResponse = 'failure';//well at this point, the payment could not be made
        }

      //decided to pay now, store details on server whether the payment was successful or not
      $serverConn = new mysqli(SERVER_NAME, USER, USER_PASSWORD);//connect to server
      if (!$serverConn -> connect_error){//connection was successful
        //prepare query to insert info into database
        $query = "INSERT INTO `thestart_upstudio`.`tss_package_subscription` (`company_name`,
          `company_email`, `contact_number`, `project_description`, `stripe_customer_id`,
          `subscription_plan`, `subscription_status`)
          VALUES ('$companyName', '$companyEmail', '$contactNumber', '$projectDescription',
            '$stripeCustomerId', '$subscriptionPlan', '$subscriptionStatus')";

        //try sending to database
        if ($serverConn -> query($query) === TRUE){
          //sending to db was successful
          $dbResponse = 'success';
        }else {
            # sending to db was not successful
            $dbResponse = 'failure';
          }
        }else {
            # connection was not successful, so do something
            $dbResponse = 'connection_error';
          }

      //also send a mail to hello@sstudio.io
      //well i decided to do this independent of sending to server so that if
      //sending to server fails, mail might not and vice versa
      if (sendMailFromPricingCardForm($companyName, $companyEmail, $contactNumber, $subscriptionPlan, $projectDescription)) {
        # mail was sent
        $mailResponse = 'success';
      }else {
          # mail was not sent
          $mailResponse = 'failure';
        }

    }//end checking for token

  }//end checking for post
?>
