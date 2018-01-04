<?php
  session_start();
  require_once __DIR__ .'/../vendor/autoload.php';

  require_once __DIR__ . '/../../../config/fb/fb_constants.php';

  use Libs\AdminUser\AdminUser;

  $fb = new Facebook\Facebook([
  'app_id' => APP_ID,
  'app_secret' => APP_SECRET,
  'default_graph_version' => 'v2.9',
  'persistent_data_handler' => 'session'
  ]);
  $helper = $fb->getRedirectLoginHelper();

  try {
  $accessToken = $helper->getAccessToken();
  } catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
  }

  if (! isset($accessToken)) {
  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
  }
  exit;
  }

  // Logged in
  // echo '<h3>Access Token</h3>';
  // var_dump($accessToken->getValue());

  // The OAuth 2.0 client handler helps us manage access tokens
  $oAuth2Client = $fb->getOAuth2Client();

  // Get the access token metadata from /debug_token
  $tokenMetadata = $oAuth2Client->debugToken($accessToken);
  // echo '<h3>Metadata</h3>';
  // var_dump($tokenMetadata);

  // Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId(APP_ID); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
  // Exchanges a short-lived access token for a long-lived one
  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
    exit;
  }
  // echo '<h3>Long-lived</h3>';
  // var_dump($accessToken->getValue());
}

  $_SESSION['fb_access_token'] = (string) $accessToken;

  if (isset($accessToken)) {
    # we have an access token

    $response = $fb->get('/me?fields=name,email,picture', $_SESSION['fb_access_token']);
    $userNode = $response->getGraphUser();
    $name = $userNode->getName();
    $email = $userNode->getEmail();
    $picture = $userNode->getPicture()->getUrl();

    $adminUser = new AdminUser();

    # it must have been a log in request so check if their email is already
    // in the database and then send them to the dashboard
    if ($adminUser->getAdminUserInfo('company_email', $email, $userInfo = 'id')) {
      # if they are a registered user
      header('Location: ../dashboard.php');//send them to dashboard
    }else {
        # they are not a registered user, so, why not.. register them then!
        // create an account for the person
        $adminUser->createAdminUserAccount($name, $email, '', $picture);
        header('Location: ../dashboard.php');//send them to dashboard
      }

  }
?>
