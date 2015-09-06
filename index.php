<?php
	//include the google auth class
	require_once("src/Google/autoload.php");

	//set your client_id,client_secret & redirect_uri

	const CLIENT_ID = "29777059787-pr1g9kf25533a33bscr8ervf9g3sonhu.apps.googleusercontent.com";
	const CLIENT_SECRET = "rnSoEJtZluyuoqhqfNxFjGch";
	const REDIRECT_URI = "http://demo.hackerkernel.com/google_oauth_login_with_php/index.php";

	session_start();

	//set the your constant to the google auth class
	$client = new Google_Client();
	$client->setClientId(CLIENT_ID);
	$client->setClientSecret(CLIENT_SECRET);
	$client->setRedirectUri(REDIRECT_URI);
	$client->setScopes("email");

	//call the google+ libray
	$plus = new Google_Service_Plus($client);

	//when user click logout
	if (isset($_REQUEST['logout'])) {
		session_unset();
	}

	//if the request is return from the google server 
	if (isset($_GET['code'])) {
		$client->authenticate($_GET['code']);
		$_SESSION["token"] = $client->getAccessToken();
		$redirect_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		$redirect = filter_var($redirect_url, FILTER_SANITIZE_URL);
		header("Location: {$redirect}");
	}

	//fetch data from google
	if (isset($_SESSION['token']) && $_SESSION['token']) {
		$client->setAccessToken($_SESSION['token']);
		//call the google+ library and fetch the data brah
		$me = $plus->people->get("me");

		$id = $me['id'];
		$name = $me['displayName'];
		$email = $me['emails'][0]['value'];
		$profile_pic = $me['image']['url'];
		$profile_url = $me['url'];
	}else{
		//get the login url
		$login_url = $client->createAuthUrl();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Google Oauth login 2015 Demo Hackerkernel</title>
</head>
<body style="background:#eee;">
	<center>
<a target="_blank" href="http://blog.hackerkernel.com/index.php/2015/09/04/google-oauth-login-with-php">Tutorial</a> / 
<a target="_blank" href="#">Download Script</a> / 
<a target="_blank" href="https://www.facebook.com/hackerkernel">Facebook Page</a> / 
<a target="_blank" href="https://www.facebook.com/hunk.husain">Be Husain Friend</a><br>
<br>
<br>
<br>
<br>
	<?php 
		//display the login button
		if (isset($login_url)) {
			echo "<a href='{$login_url}'><img src='sign-in-with-google.png' height='50'></a>";
		}
		//if user is logged in display the user info (You can store the information in the database)
		else{
			echo "<img src='{$profile_pic}'><br>";
			echo "id: {$id}<br>";
			echo "Name: {$name}<br>";
			echo "Email: {$email}<br>";
			echo "Profile Url: {$profile_url}<br>";
			echo "<a href='?logout'>Logout</a>";
		}
	?>
	<iframe src="../counter.html" style="display:none;"></iframe>
</center>
</body>
</html>

