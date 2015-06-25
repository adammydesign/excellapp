<?php
session_start();
/*******
Send message
********/

//include files
include('./config.php');
include('./functions.php');

//Get form variables
$message_type = sanitize($link, $_POST['message_type']);
$message_body = sanitize($link, $_POST['message_body']);
$recipient = sanitize($link, $_POST['recipient']);
$parent = sanitize($link, $_POST['parent_message']);
$subject = sanitize($link, $_POST['subject']);

//Todays date
$today = date('Y-m-d');

//sender from session
$sender = $_SESSION['user_id'];

//if is a new message
if($message_type == 'new') {

}

//if message is a reply
if($message_type == 'reply') {

	//check that the reply box is not empty
	if(empty($message_body)) {
		
		http_response_code(400);
		echo 'You need to enter something for a reply.';
		exit;

	} else {

		//insert message into database
		$insert_messsage = mysqli_query($link, "INSERT INTO `excell_messages` (`sent_id`, `received_id`, `opened`, `subject`, `message_body`, `date_created`, `parent_message`) VALUES ('$sender','$recipient','0','$subject','$message_body','$today','$parent')");

		if($insert_messsage) {
			http_response_code(200);
			echo 'Message Sent.';
			exit;
		}

	}
}




?>