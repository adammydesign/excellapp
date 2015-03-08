<?php

//check if user is logged in function
function is_loggedin($username) {
	
	//Get current file name
	$pageName = basename($_SERVER['PHP_SELF']);
	
	//Check if seesion username is set
	if(!isset($_SESSION['username'])) {
		if ($pageName == 'login.php') {
			return;
		} else {
			header('location: '.ROOT_URL.'/login.php');
			return;
		}
	//If it is set then check they match	
	} else {
		if(($_SESSION['LoggedIn'] == 1) && ($_SESSION['username'] == $username)) {
			//If they match, check that the file name is not login.php, if it is then redirect to admin index	
			if($pageName == 'login.php') {
					header('location: '.ROOT_URL.'');
					return;
			//IF the file name is not te login page then continue
			} else {
				return;
			}
		} else {
			header('location: '.ROOT_URL.'/login.php');
			return;
		}
	}
}

//Create a salt for new password
function create_salt() {

	$num = rand(100000,200000).rand(200000,300000);

	$letters = range('a','z');	
	$letters2 = range('A','Z');	
	$letters = array_merge($letters,$letters2);
	$pos = range(1, 23, 2);

	$i=0;
	while($i<13) {
		if($i == 0) {
			$salt = substr_replace($num, $letters[array_rand($letters, 1)],$pos[$i], 0);
		} else {
			$salt = substr_replace($salt, $letters[array_rand($letters, 1)], $pos[$i], 0);
		}
		$i++;
	}

	return $salt;
}

//Get User information 
function get_user_data($link, $user_id, $key) {

	//Select fields based on $key
	$get_data = mysqli_query($link, "SELECT `$key` FROM `excell_users` WHERE `user_id`='$user_id'");

	$data = mysqli_fetch_assoc($get_data);

	return $data[$key];

}

//Get Availability
function get_availability($link, $user_id) {

	//Select required fields from the database
	$get_data = mysqli_query($link , "SELECT `user_available_mon`, `user_available_tues`, `user_available_wed`, `user_available_thur`, `user_available_fri` FROM `excell_users` WHERE `user_id`= '$user_id'");

	//get collected data as an array
	$data = mysqli_fetch_assoc($get_data);
	$days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');
	$availability = array_combine($days, $data);

	foreach($availability as $day => $value) {
		
		//relate value to day time
		if($value == 'am') {
			$value = 'Morning';
		} elseif($value == 'pm') {
			$value = 'Afternoon';
		} elseif($value == 'am/pm') {
			$value =  'All Day';
		} elseif($value == 'nil') {
			$value = 'Unavailable';
		} else {
			$value = 'Please check your settings';
		}

		//return results
		echo $day.' - '.$value.'<br/>'; 

	}
}

//check your availability reminder
function availability_reminder($link, $user_id) {

	//calculate day difference
	$datediff = strtotime(date('Y-m-d')) - strtotime(get_user_data($link,$user_id, 'user_lastloggedin'));
    $days = floor($datediff/(60*60*24));

    if($days >= 7) {
    	echo '<div class="alert alert-header alert-info availability-alert" role="alert"><i class="fa fa-fw fa-bell"></i> Are your days of availability correct? <a href="'.ROOT_URL.'/user/availability/'.$user_id.'" class="btn btn-success btn-sm">Change days</a> <a class="btn btn-danger btn-sm">Dismiss</a></div>';
    }
	
}

//alert function
function alert($alert = '', $type = '', $method = '', $status = 'success', $user_id = '', $forum_id = '', $name = '', $link) {

	if($alert == 'true') {

		//if type is user
		if($type == 'user') {
			
			//if method is create
			if($method == 'create') {
				
				if($status == 'success') {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-success';
					$message = 'New user has been created. View <a href="'.ROOT_URL.'/user/'.$user_id.'" alt="'.get_user_data($link, $user_id, 'user_fullname').'">'.get_user_data($link, $user_id, 'user_fullname').'</a>';
				} else {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-warning';
					$message = 'There seems to be a problem creating the user. Please try again.';
				}

			//if method is edit
			} elseif($method == 'edit') {
				
				if($status == 'success') {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-success';	
					$message = '<a href="'.ROOT_URL.'/user/'.$user_id.'" alt="(User name function)">(User name function)</a> has been edited.';
				} else {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-warning';
					$message = 'There seems to be a problem editing the user. Please try again.';
				}

			//if method is delete
			} elseif($method == 'delete') {

				if($status == 'success') {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-success';	
					$message = $name.' was succesfully deleted from the system';
				} else {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-warning';
					$message = 'There was an issue deleting the user, please try again.';
				}

			//if no method is defined
			} else {
				$icon = '<i class="fa fa-fw fa-warning"></i>';
				$style = 'alert-danger';
				$message = 'Error. No method was defined. Please contact administrator.';
			}

		//if type is forum
		} elseif($type == 'forum') {

			//if method is create
			if($method == 'create') {
				
				if($status == 'success') {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-success';
					$message = 'New forum has been created. <a href="'.ROOT_URL.'/forum/'.$forum_id.'">View Forum</a>';
				} else {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-warning';
					$message = 'There seems to be a problem creating the forum. Please try again.';
				}

			//if method is edit
			} elseif($method == 'edit') {
				
				if($status == 'success') {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-success';	
					$message = 'Forum has been updated. <a href="'.ROOT_URL.'/forum/'.$forum_id.'">View Forum</a>.';
				} else {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-warning';
					$message = 'There seems to be a problem editing the forum. Please try again.';
				}

			//if method is delete
			} elseif($method == 'delete') {

				if($status == 'success') {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-success';	
					$message = 'Forum was succesfully deleted from the system';
				} else {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-warning';
					$message = 'There was an issue deleting the forum, please try again.';
				}

			//if no method is defined
			} else {
				$icon = '<i class="fa fa-fw fa-warning"></i>';
				$style = 'alert-danger';
				$message = 'Error. No method was defined. Please contact administrator.';
			}

		//if type is forum post
		} elseif($type == 'forumpost') {

			//if method is create
			if($method == 'create') {
				
				if($status == 'success') {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-success';
					$message = 'New forum post has been created. <a href="'.ROOT_URL.'/forum/'.$forum_id.'">View Forum</a>';
				} else {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-warning';
					$message = 'There seems to be a problem creating your forum post. Please try again.';
				}

			//if method is edit
			} elseif($method == 'edit') {
				
				if($status == 'success') {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-success';	
					$message = 'Forum post has been updated. <a href="'.ROOT_URL.'/forum/'.$forum_id.'">View Forum</a>.';
				} else {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-warning';
					$message = 'There seems to be a problem editing the forum post. Please try again.';
				}

			//if method is delete
			} elseif($method == 'delete') {

				if($status == 'success') {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-success';	
					$message = 'Forum post was succesfully deleted from the system';
				} else {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-warning';
					$message = 'There was an issue deleting the forum post, please try again.';
				}

			//if no method is defined
			} else {
				$icon = '<i class="fa fa-fw fa-warning"></i>';
				$style = 'alert-danger';
				$message = 'Error. No method was defined. Please contact administrator.';
			}

		//if type is availability
		} elseif($type == 'availability') {
			
			//If availability is updated
			if($method == 'edit') {
				
				if($status == 'success') {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-success';	
					$message = 'Your availability has been updated.';
				} else {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-warning';
					$message = 'There seems to be a problem updating your availability. Please try again.';
				}

			//if no method is defined
			} else {
				$icon = '<i class="fa fa-fw fa-warning"></i>';
				$style = 'alert-danger';
				$message = 'Error. No method was defined. Please contact administrator.';
			}

		//if type is profile
		} elseif ($type == 'profile') {

			//If profile is updated
			if($method == 'edit') {
				
				if($status == 'success') {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-success';	
					$message = 'Your profile has been updated.';
				} else {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-warning';
					$message = 'There seems to be a problem updating your profile. Please try again.';
				}

			//if no method is defined
			} else {
				$icon = '<i class="fa fa-fw fa-warning"></i>';
				$style = 'alert-danger';
				$message = 'Error. No method was defined. Please contact administrator.';
			}

		//if type is profilepicture
		} elseif ($type == 'profilepicture') {

			//If profile is updated
			if($method == 'edit') {
				
				if($status == 'success') {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-success';	
					$message = 'Your profile picture has been updated.';
				} else {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-warning';
					$message = 'There seems to be a problem updating your profile picture. Please try again.';
				}

			//if no method is defined
			} else {
				$icon = '<i class="fa fa-fw fa-warning"></i>';
				$style = 'alert-danger';
				$message = 'Error. No method was defined. Please contact administrator.';
			}

		//if type is profilecover
		} elseif ($type == 'profilecover') {

			//If profile is updated
			if($method == 'edit') {
				
				if($status == 'success') {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-success';	
					$message = 'Your cover picture has been updated.';
				} else {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-warning';
					$message = 'There seems to be a problem updating your cover picture. Please try again.';
				}

			//if no method is defined
			} else {
				$icon = '<i class="fa fa-fw fa-warning"></i>';
				$style = 'alert-danger';
				$message = 'Error. No method was defined. Please contact administrator.';
			}

		} else {
			$icon = '<i class="fa fa-fw fa-warning"></i>';
			$syle = 'alert-danger';
			$message = 'Error. No type was defined. Please contact the administrator.';
		}

		echo '<div class="alert alert-header alert-header-dismiss '.$style.'">'.$icon.' '.$message.'</div>';
	}
}

?>