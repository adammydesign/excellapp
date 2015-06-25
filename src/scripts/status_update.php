<?php
session_start();
//change user status script

include('../includes/config.php');
include('../includes/functions.php');

is_loggedin($_SESSION['username']);

//get current status
$user_id = $_GET['user_id'];
$current_status = $_GET['status'];

//if the user is currently active
if($current_status == 1) {

	//update user_active in the database to 0
	$update = mysqli_query($link, "UPDATE `excell_users` SET `user_active` = '0' WHERE `user_id` = '$user_id'");

} elseif($current_status == 0) {

	//update user active in the database to 1
	$update = mysqli_query($link, "UPDATE `excell_users` SET `user_active` = '1' WHERE `user_id` = '$user_id'");

} else {

	//throw error alert
	header('location: '.ROOT_URL.'/admin/users/?alert=true&type=user&method=status&user_id='.$user_id.'');
}

if($update) {

	//throw success alert
	header('location: '.ROOT_URL.'/admin/users/?alert=true&type=user&method=status&status=success&user_id='.$user_id.'');

} else {

	//throw error alert
	header('location: '.ROOT_URL.'/admin/users/?alert=true&type=user&method=status&user_id='.$user_id.'');
} 
?>