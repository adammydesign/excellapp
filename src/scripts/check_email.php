<?php
include('../includes/config.php');

//Check if email is available
$email = mysql_real_escape_string(strtolower($_POST["user_email"]));

$check = mysqli_query($link, "SELECT `user_email` FROM `excell_users` WHERE LOWER(`user_email`) = '$email'");

if(mysqli_num_rows == 1) {
	//we have result
	echo 1;
} else {
	//no result found
	echo 0;
}

?>