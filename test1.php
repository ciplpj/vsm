<?php require_once("includes".DIRECTORY_SEPARATOR."Facebook".DIRECTORY_SEPARATOR."Facebook.php"); ?>
<?php require_once("includes".DIRECTORY_SEPARATOR."Facebook".DIRECTORY_SEPARATOR."autoload.php"); ?>

<?php
  session_start();
  $fb = new Facebook\Facebook([
  'app_id' => '1108487205858532', // Replace {app-id} with your app id
  'app_secret' => '3cf72784651bc90a584236561d5a53cf',
  ]);

$helper = $fb->getRedirectLoginHelper();

try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  header('Location: index.php');
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  header('Location: index.php');
  exit;
}

if (! isset($accessToken)) {
  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
    header('Location: index.php');
  } else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
    header('Location: index.php');
  }
  exit;
}

// Logged in
echo '<h3>Access Token</h3>';
//var_dump($accessToken->getValue());

// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
// echo '<h3>Metadata</h3>';
// var_dump($tokenMetadata);
// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId('1108487205858532'); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
if(!$tokenMetadata->validateUserId($tokenMetadata->getField("user_id"));){
  header('Location: index.php');
}
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
  // Exchanges a short-lived access token for a long-lived one
  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
    header('Location: index.php');
    exit;
  }

  // echo '<h3>Long-lived</h3>';
  // var_dump($accessToken->getValue());
}

$_SESSION['fb_access_token'] = (string) $accessToken;

$fb->setDefaultAccessToken($accessToken);

try {
  $response = $fb->get('/me?locale=en_US&fields=name,email');
  $userNode = $response->getGraphUser();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  header('Location: index.php');
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  header('Location: index.php');
  exit;
}

echo 'Logged in as ' . $userNode->getName();
echo '<br />Using Email ID ->'. $userNode->getField('email');
 
// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');
?>
<form action=>
