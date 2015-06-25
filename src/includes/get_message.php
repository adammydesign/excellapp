<?php
/*******
Get Message Content
********/
include('./config.php');
include('./functions.php');

//get message id
$message_id = $_GET['message_id'];

//set todays date
$today = date('Y-m-d');

//get message from database
$get_message = mysqli_query($link, "SELECT * FROM `excell_messages` WHERE `message_id`='$message_id'");

//check if message exists and is available
if(mysqli_num_rows($get_message) == 1) {

	$message_content = mysqli_fetch_assoc($get_message);

	$message = '<div class="message-content"><h2 class="text-center">'.$message_content['subject'].'<br/><small>From: '.get_user_data($link,$message_content['sent_id'],'user_fullname').' - <em>'.date('l, jS F', strtotime($message_content['date_created'])).'</em></small></h2>';
	$message .= '<p>'.nl2br($message_content['message_body']).'</p></div>';
	$message .= '<button data-dismiss="modal" class="btn btn-default pull-left">Close <i class="fa fa-close"></i></button>';//close btn
	$message .= '<div class="btn-group pull-right">
	<a href="#" class="btn btn-info reply-btn" data-subject="'.$message_content['subject'].'" data-parentmessage="'.$message_content['message_id'].'" id="'.$message_content['sent_id'].'" data-sender="'.get_user_data($link,$message_content['sent_id'],'user_fullname').'">Reply <i class="fa fa-reply"></i></a>
	</div>';

	//respond done and echo message content
	http_response_code(200);
	echo $message;

	//if message is unread mark message as read
	if($message_content['opened'] == 0) {
		mysqli_query($link, "UPDATE `excell_messages` SET `opened`='1', `date_opened`='$today' WHERE `message_id`='$message_id'");
	}

	exit;

} else {
	
	http_response_code(400);
	echo '<div class="alert alert-info white">There seems to be an issue retrieving this message. There is either a server error or the message no longer exists. If the issue persists then please contact a member of staff.</div>';
	echo $message_id;
	exit;

}
?>
