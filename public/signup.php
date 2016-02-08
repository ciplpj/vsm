<?php require_once('..'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'bootstrap.config.php') ?>
<?php require_once('..'.DIRECTORY_SEPARATOR."includes".DIRECTORY_SEPARATOR."Facebook".DIRECTORY_SEPARATOR."Facebook.php"); ?>
<?php require_once('..'.DIRECTORY_SEPARATOR."includes".DIRECTORY_SEPARATOR."Facebook".DIRECTORY_SEPARATOR."autoload.php"); ?>

<?php
  session_start();
  $fb = new Facebook\Facebook([
  'app_id' => '1108487205858532', // Replace {app-id} with your app id
  'app_secret' => '3cf72784651bc90a584236561d5a53cf',
  ]);

$helper = $fb->getRedirectLoginHelper();
if(isset($_SESSION['fb_access_token'])){
  $expires = time() + 60 * 60 * 100;
  try{
  		$accessToken = new Facebook\Authentication\AccessToken($_SESSION['fb_access_token'], $expires);
  	}catch(Facebook\Exceptions\FacebookResponseException $e) {
  	// When Graph returns an error
  		unset($_SESSION['fb_access_token']);
  		echo 'Graph returned an error: ' . $e->getMessage();
  		header('Location: index.php');
  	exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		unset($_SESSION['fb_access_token']);
  	// When validation fails or other local issues
  		echo 'Facebook SDK returned an error: ' . $e->getMessage();
  		header('Location: index.php');
  	exit;
	}
}else{
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
try{
	$fb_username = $userNode->getName();
	$fb_emailid = $userNode->getField('email');
}catch(Exception $e){
	header('Location: index.php');
}
//echo 'Logged in as ' . $userNode->getName();
//echo '<br />Using Email ID ->'. $userNode->getField('email');
 
// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');
?>
<?php
	$fb_email = trim($userNode->getField('email'));
	$id = User::get_id_by_uid($fb_email);
	//when a user is found in the database
	if(!empty($id) && !is_bool($id)){
		$sh_id = StockHolder::shid_by_uid($id);
		$session->login($id,$sh_id);
		header("Location: home.php");
	}
?>

?>
<?php
	if(isset($_POST['submit'])){
		if(isset($_POST['mobile']) && $_POST['college']) ){
			$college = $database->escape_value($_POST['college']);
			$mobile = $database->escape_value($_POST['mobile']);
			User::add_new_user($fb_emailid,$_SESSION['fb_access_token'],$fb_username,Database::date(),$mobile,$college);
			$new_id = User::get_id_by_uid($fb_emailid);
			if(!empty($new_id) && !is_bool($new_id)){
				$sh_id = StockHolder::shid_by_uid($new_id);
				$session->login($id,$sh_id);
				header("Location: home.php");
			}
		}else{
			$message = "All fields are required fields kindly enter them all".
		}
	}
?>
<!DOCTYPE html>
<html> 
	<head>
		<title>User Login: Details</title>
		<style type="text/css">
		body{
			/*background-color:#80ff80;*/
			background-color: white;
		}
		.message{
			width:14%;
			margin:auto;
			margin-top:10%;
			border: #1a1a1a solid 2px;
			padding:3em;
			background-color:#bfff80;	border-radius:50px;
		}
		input[type=text]{
			border:none;
			border-bottom: 3px solid red;
			border-radius : 5px;
			background-color: #bfff80;
			margin: 8px 0;
			padding : 1em;
			font-size: 1.3em;
		}
		input[type=submit]{
			border:none;
			border: 3px solid #003322;
			border-radius : 5px;
			background-color: #bfff80;
			margin: 8px 0;
			padding : 1em;
			font-size: 1.3em;
		}
		input[type=submit]:hover{
			border:none;
			border: 3px solid #ffffff;
			border-radius : 5px;
			background-color: #003322;
			margin: 8px 0;
			padding : 1em;
			font-size: 1.3em;
			color:white;
		}
		div.login{
			width:14%;
			margin:auto;
			margin-top:10%;
			border: #1a1a1a solid 2px;
			padding:3em;
			background-color:#bfff80;	border-radius:50px;
			box-shadow:12px 17px 10px black;
		}
		.center{
			margin-left: 5.6em;
		}
		</style>
	</head>
	<body>
		<?php if(isset($message)){ ?>
		<div class="message">
			<p><?php echo $message ?></p>
		</div>
		<div class="login">
			<p>Your signup is not complete yet. Enter The following details to become a Stock Holder in VSM.</p>
			<form action="signup.php" method="post">
				<p style="font-family:sans-serif;font-size:2em;">Mobile Number:</p>
				<input type="text" name="mobile" />
				<p style="font-family:sans-serif;font-size:2em;">College:</p>
				<input type="text" name="college" />
				<p class="center"><input type="submit" name="submit" value="Submit"></p>
			</form>	
		</div>
	</body>
</html>		
