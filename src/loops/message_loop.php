<?php
/***
Message Loop
This file pulls in all messages for the user id in the URL
***/

//Get User ID
$user_id = $_GET['user_id'];

if($subpage == 'unreadmessages') {
  $unread = "AND `opened` = '0' ";
}

//Query for messages receieved
$get_messages = mysqli_query($link, "SELECT * FROM `excell_messages` WHERE `received_id` = '$user_id' $unread ORDER BY `message_id` DESC");

//Get number of messages
$num_messages = mysqli_num_rows($get_messages);
?>

<?php
//if number of messages received is greater than 0 
if($num_messages > 0) {
?>

<!-- Message List Group -->
<div class="list-group">

    <?php
    
    //Loop through messages
    while($message = mysqli_fetch_array($get_messages)) {

    	//Check if message is read or not
    	if($message['opened'] == 1) {
    		$opened = 'read-message';
    		$message_icon = '<i class="fa fa-check fa-fw"></i>';
    	} else {
    		$opened = '';
    		$message_icon = '<i class="fa fa-envelope fa-fw"></i>';
    	}

    	//Create Display Message
    	$display = '<a href="#" data-toggle="modal" id="'.$message['message_id'].'" class="list-group-item open-message '.$opened.'">';
    	$display .= '<span class="name" style="min-width: 120px; display: inline-block;">'.$message_icon.' '.get_user_data($link, $message['sent_id'], 'user_fullname').'</span>';
    	$display .= '<span class="">'.$message['subject'].'</span>';
    	$display .= '<span class="text-muted" style="font-size: 11px;">- '.message_excerpt($message['message_body']).'</span>';
    	$display .= '<span class="badge">'.date('jS M', strtotime($message['date_created'])).'</span> <span class="pull-right"></span>';
    	$display .= '</a>';

    	//Echo message display
    	echo $display;
    }

    ?>
               
</div>

<?php 
} else {
	echo '<div class="alert alert-info">You currently have no messages in your inbox.</div>';
}
?>


<!-- read message -->
<div class="modal fade" id="message-modal">
   <div class="modal-dialog">
      <!-- Load Ajax data here -->
      <div class="modal-content">
      </div>
   </div>
</div>
<!-- ./read Message -->

<!-- reply form message -->
<div class="modal fade" id="reply-modal">
   <div class="modal-dialog">
      <!-- Load Ajax data here -->
      <div class="modal-content">
      	<form action="../../includes/send_message.php" id="reply-form">

      	</form>
      </div>
   </div>
</div>
<!-- reply message form -->