<?php
include('./config.php');
include('./functions.php');

//Get submitted information
$fullname = sanitize($link, $_POST['confirm_full_name']);
$contactnum = sanitize($link, $_POST['confirm_contact_num']);
$email = sanitize($link, $_POST['confirm_email_address']);
$ninumber = sanitize($link, $_POST['confirm_ni_number']);
$password = sanitize($link, $_POST['confirm_password']);
$confirm_password = sanitize($link, $_POST['confirm_password_confirm']);

//Get hidden elements
$user_id = sanitize($link, $_POST['user_id']);
$user_salt = sanitize($link, $_POST['user_salt']);



//check if name and email are still filled in
if(empty($fullname) || empty($email)) {
	http_response_code(400);
	echo 'Name and email are required fields. Please ensure these are filled in.';
	exit;
}

//Update user details and activate acccount
$update = mysqli_query($link , "UPDATE `excell_users` SET `user_fullname`='$fullname',`user_email`='$email', `user_contactnum`='$contactnum',`user_ni`='$ni', `user_active`='1' WHERE `user_id`='$user_id'");

//check for new password
if(!empty($password)) {

	//check if passwords match
	if($password != $confirm_password) {
		http_response_code(400);
		echo 'Make sure that both passwords match.';
		exit;
	}

	if(strlen($password) < 5) {
		http_response_code(400);
		echo 'Your password must be at least 5 characters long.';
		exit;
	}	

	$password = md5($password.$user_salt);

	//insert password update
	$update = mysqli_query($link , "UPDATE `excell_users` SET `user_password`='$password' WHERE `user_id`='$user_id'");


}

//check if users password is empty
if(empty(get_user_data($link,$user_id,'user_password'))) {

	if(empty($password)) {
		http_response_code(400);
		echo 'A password is required to complete your account.';
		exit;
	} else {
		
		if($password && $update) {
			http_response_code(200);
			exit;
		} else {
			http_response_code(400);
			echo 'Something went wrong there. Please try again.';
			exit;
		}
	}
	
} else {
	if($update) {
		http_response_code(200);
		exit;
	} else {
		http_response_code(400);
		echo 'Something went wrong there. Please try again.';
		exit;
	}
}


?>