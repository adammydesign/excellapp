<?php
//Include files
include_once('../includes/config.php');
include_once('../includes/functions.php');

//Check if the request was via a post method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	//Get login form variables
	$email = mysqli_real_escape_string($link, trim($_POST['email']));
	$password = mysqli_real_escape_string($link, trim($_POST['password']));

	//Query the database using email address as the key
	$find_user = mysqli_query($link, "SELECT `user_id`, `user_email`, `user_password`, `user_salt` FROM `excell_users` WHERE `user_email` = '$email'");

	//check if there is a matching result with the email
	if(mysqli_num_rows($find_user) == 1) {
		
		//Create data variable
		$user_data = mysqli_fetch_assoc($find_user);

		//check if the password is correct
		$entered_password = md5($password.$user_data['user_salt']);

		if($user_data['user_password'] == $entered_password) {

			//create sessions
			session_start();
			$_SESSION['LoggedIn'] = 1;
			$_SESSION['username'] = $email;
			$_SESSION['user_id'] = $user_data['user_id'];
			http_response_code(200);
			exit;

		} else {

			//incorrect password
			http_response_code(400);
			exit;

		}

	} else {

		//No user with entered email
		http_response_code(400);
		exit;
	}

} else {
	// Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}

?>