<?php
session_start();

include('../../includes/config.php');
include('../../includes/functions.php');

is_loggedin($_SESSION['username']);

//get URL variables
$user_id = $_GET['user_id'];
$action = $_GET['action'];

//if action is create
if($action == 'create') {

	//get create form results
	$name = sanitize($link, $_POST['user_fullname']);
	$email = sanitize($link, $_POST['user_email']);
	$contactnum = sanitize($link, $_POST['user_contactnum']);
	$dob = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['user_dob'])));
	$ni = sanitize($link, $_POST['user_ni']);
	$password = sanitize($link, $_POST['user_password']);
	$repeat_password = sanitize($link, $_POST['user_password_repeat']);
	$user_admin	= $_POST['user_admin'];
	$user_manager = $_POST['user_accountmanager'];
	$user_managed = $_POST['user_managed'];
	$user_accountmanagerid = $_POST['user_accountmanagerid'];


	//check fields
	if(empty($name) || empty($email)) {
		http_response_code(400);
		echo '<i class="fa fa-warning"></i> Name and email address are required fields.';
		exit;
	}
	// ./check fields

	$salt = create_salt();

	//check for password
	if(!empty($password)) {
		
		//check if password has been repeated
		if(!empty($repeat_password)) {
			
			//check length of password 
			if(strlen($password) < 5) {
				
				http_response_code(400);
				echo '<i class="fa fa-bell fa-fw"></i> Whoops. Make sure the new password is at least 5 characters long.';
				exit;

			} else {

				//check if the repeat password is the same as the new one
				if($password != $repeat_password) {
					
					http_response_code(400);
					echo '<i class="fa fa-bell fa-fw"></i> Whoops. Make sure the password fields match.';
					exit;

				} else {

					//crate salt and salt new password for database
					$plaintext_pwd = $password;
					$password = md5($password.$salt);
					$is_password = 'yes';
				}
			}

		} else {

			http_response_code(400);
			echo '<i class="fa fa-bell fa-fw"></i> Whoops. Please repeat the password to confirm, or clear password field.';
			exit;

		}

	}
	// ./password check


	//insert new user
	$insert_user = mysqli_query($link, "INSERT INTO `excell_users`(`user_id`, `user_fullname`, `user_email`, `user_salt`, `user_contactnum`, `user_dob`, `user_ni`, `user_accountmanagerid`, `user_admin`, `user_accountmanager`, `user_active`) VALUES (NULL,'$name','$email', '$salt', '$contactnum','$dob','$ni','$user_accountmanagerid','$user_admin','$user_manager','0')");

	//get inserted id
	$insert_id = mysqli_insert_id($link);

	//check query
	if($insert_user) {

		//check if a password has been added
		if(!empty($is_password)) {
			
			//insert password
			mysqli_query($link, "UPDATE `excell_users` SET `user_password`='$password' WHERE `user_id`='$insert_id'");
		
		}

		//Send Password to the new user
		email_new_user($link, $insert_id, $plaintext_pwd);

		http_response_code(200);
		echo ROOT_URL.'/admin/users/?alert=true&type=user&method=create&status=success&user_id='.$insert_id.'&name='.$name.'';
		exit;

	} else {

		http_response_code(400);
		echo '<i class="fa fa-warning fa-fw"></i> Something went wrong there, please try again.';
		exit;

	}

}

//If action is edit as admin
if($action == 'edit') {

	//get edit form results
	$name = sanitize($link, $_POST['user_fullname']);
	$email = sanitize($link, $_POST['user_email']);
	$contactnum = sanitize($link, $_POST['user_contactnum']);
	$dob = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['user_dob'])));
	$ni = sanitize($link, $_POST['user_ni']);
	$password = sanitize($link, $_POST['user_password']);
	$repeat_password = sanitize($link, $_POST['user_password_repeat']);
	$user_admin	= $_POST['user_admin'];
	$user_manager = $_POST['user_accountmanager'];
	$user_managed = $_POST['user_managed'];
	$user_accountmanagerid = $_POST['user_accountmanagerid'];

	//check for password
	if(!empty($password)) {
		
		//check that both are empty
		if(!empty($password) && !empty($repeat_password)) {
			
			//check length of password 
			if(strlen($password) < 5) {
				
				http_response_code(400);
				echo '<i class="fa fa-bell fa-fw"></i> Whoops. Make sure the new password is at least 5 characters long.';
				exit;

			} else {

				//check if the repeat password is the same as the new one
				if($password != $repeat_password) {
					
					http_response_code(400);
					echo '<i class="fa fa-bell fa-fw"></i> Whoops. Make sure the password fields match.';
					exit;

				} else {

					//crate salt and salt new password for database
					$salt = create_salt();
					$password = md5($password.$salt);
					$password_insert = " `user_password`='$password', `user_salt`='$salt',";
				}
			}

		} else {

			http_response_code(400);
			echo '<i class="fa fa-bell fa-fw"></i> Whoops. Please repeat new password to confirm or clear new password.';
			exit;

		}
	}

	//check for manager id
	if($user_managed == 1) {
		$user_accountmanagerid = $_POST['user_accountmanagerid'];
	} else {
		$user_accountmanagerid = 0;
	}

	//create update mysqli query
	$update = mysqli_query($link , "UPDATE `excell_users` SET `user_fullname`='$name',`user_email`='$email',$password_insert `user_contactnum`='$contactnum',`user_dob`='$dob',`user_ni`='$ni',`user_accountmanagerid`='$user_accountmanagerid',`user_admin`='$user_admin',`user_accountmanager`='$user_manager' WHERE `user_id`='$user_id'");

	//check the query and show notification result
	if($update) {

		http_response_code(200);
		echo '<i class="fa fa-bell fa-fw"></i> Success. User details saved.';
		exit;

	} else {

		http_response_code(400);
		echo '<i class="fa fa-bell fa-fw"></i> Whoops. Something went wrong there, please try again.';
		exit;

	}

}
//end edit user via admin

//confirm delete
if($action == 'confirm') {

	if(!empty($user_id)) {
		http_response_code(200);
		echo '<h3>Are you sure you want to delete '.get_user_data($link, $user_id, 'user_fullname').'?</h3><br/><a href="./update.php?user_id='.$user_id.'&action=delete" class="btn pull-right btn-danger"><i class="fa fa-remove"></i> Delete User</a> <a style="margin-right: 10px;" href="" class="pull-right btn btn-default" class="close" data-dismiss="modal">Cancel</a>';
		exit;
	} else {
		http_response_code(400);
		echo '<h4>Something wasn\'t right there. Pleas try again.</h4><br/><a href="" class="modal-dismiss" data-toggle="modal" class="btn btn-info">Close</a>';
	}

}

//Delete User
if($action == 'delete') {

	$name = get_user_data($link, $user_id, 'user_fullname');

	$update = mysqli_query($link, "DELETE FROM `excell_users` WHERE `user_id` = '$user_id'");

	if($update) {
		header('location: '.ROOT_URL.'/admin/users/?alert=true&type=user&method=delete&status=success&name='.$name.'');
	} else {
		header('location: '.ROOT_URL.'/admin/users/?alert=true&type=user&method=delete&status=&name='.$name.'');
	}

}

?>