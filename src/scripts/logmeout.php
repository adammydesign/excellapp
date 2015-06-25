<?php
session_start();

include_once('../includes/config.php');

$today = date('Y-m-d');
$user_id = $_SESSION['user_id'];

//change last logged in date in the database
mysqli_query($link, "UPDATE `excell_users` SET `user_lastloggedin` = '$today' WHERE `user_id` = '$user_id'");

//log user out
session_unset();
session_destroy();
header('location: '.ROOT_URL.'/login.php?status=logout');

?>