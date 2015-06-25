<?php
//Clean Input Function
function cleanInput($input) {
 
  $search = array(
    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
  );
 
    $output = preg_replace($search, '', $input);
    return $output;

}

//sanitize input function
function sanitize($link, $input) {
    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($val);
        }
    }
    else {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $input  = cleanInput($input);
        $output = mysqli_real_escape_string($link, $input);
    }
    return $output;
}

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

//get avatar
function get_avatar($link, $user_id) {

	//check if there is an avatar
	$avatar_name = get_user_data($link, $user_id, 'user_avatar');

	if(empty($avatar_name)) {
		$avatar = '<img src="'.ROOT_URL.'/img/temp-avatar.png" class="img-circle nav-user-avatar" height="44" width="44"/>';
	} else {
		$avatar = '<img src="'.ROOT_URL.'/user/user_avatars/'.$avatar_name.'" class="img-circle nav-user-avatar" height="44" width="44" />';
	}

	return $avatar;
}

//Unread message count
function unread_messages($link) {

	$user_id = $_SESSION['user_id'];

	$check_messages = mysqli_query($link, "SELECT `message_id` FROM `excell_messages` WHERE `received_id` = '$user_id' AND `opened` = '0'");

	return mysqli_num_rows($check_messages);
}

//message_excerpt
function message_excerpt($body) {

	if(strlen($body) > 75) {
		$excerpt   = substr($body, 0, 75);
		$lastSpace = strrpos($excerpt, ' ');
		$excerpt   = substr($excerpt, 0, $lastSpace);
		$excerpt  .= '...';
	} else {
		$excerpt = $body;
	}

	return $excerpt;
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
					$message = 'New user has been created. View <a href="'.ROOT_URL.'/user/'.$user_id.'" alt="'.$name.'">'.$name.'</a>';
				} else {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-warning';
					$message = 'There seems to be a problem creating the user. Please try again.';
				}

			//if method is edit
			} elseif($method == 'status') {
				
				if($status == 'success') {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-success';	
					$message = '<a href="'.ROOT_URL.'/user/'.$user_id.'" alt="(User name function)">'.get_user_data($link, $user_id, 'user_fullname').'</a> status has been updated.';
				} else {
					$icon = '<i class="fa fa-fw fa-bell"></i>';
					$style = 'alert-warning';
					$message = 'There seems to be a problem updating the status of '.get_user_data($link, $user_id, 'user_fullname').'. Please try again.';
				}

			//if method is delete
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

function pagination($query,$per_page=10,$page=1,$url='?'){   
    global $link; 
    $query = "SELECT COUNT(*) as `num` FROM {$query}";
    $row = mysqli_fetch_array(mysqli_query($link,$query));
    $total = $row['num'];
    $adjacents = "2"; 
      
    $prevlabel = "&lsaquo; Prev";
    $nextlabel = "Next &rsaquo;";
    $lastlabel = "Last &rsaquo;&rsaquo;";
      
    $page = ($page == 0 ? 1 : $page);  
    $start = ($page - 1) * $per_page;                               
      
    $prev = $page - 1;                          
    $next = $page + 1;
      
    $lastpage = ceil($total/$per_page);
      
    $lpm1 = $lastpage - 1; // //last page minus 1
      
    $pagination = "";
    if($lastpage > 1){   
        $pagination .= "<ul class='pagination'>";
        //$pagination .= "<li class='page_info'>Page {$page} of {$lastpage}</li>";
              
        if ($page > 1) $pagination.= "<li><a href='{$url}page={$prev}'>{$prevlabel}</a></li>";
              
        if ($lastpage < 7 + ($adjacents * 2)){   
            for ($counter = 1; $counter <= $lastpage; $counter++){
                if ($counter == $page)
                    $pagination.= "<li><a href='{$url}page={$counter}' class='current'>{$counter}</a></li>";
                else
                    $pagination.= "<li><a href='{$url}page={$counter}'>{$counter}</a></li>";                    
            }
        } elseif($lastpage > 5 + ($adjacents * 2)){
              
            if($page < 1 + ($adjacents * 2)) {
                  
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
                    if ($counter == $page)
                        $pagination.= "<li><a class='current'>{$counter}</a></li>";
                    else
                        $pagination.= "<li><a href='{$url}page={$counter}'>{$counter}</a></li>";                    
                }
                //$pagination.= "<li class='dot'>...</li>";
                $pagination.= "<li><a href='{$url}page={$lpm1}'>{$lpm1}</a></li>";
                $pagination.= "<li><a href='{$url}page={$lastpage}'>{$lastpage}</a></li>";  
                      
            } elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                  
                $pagination.= "<li><a href='{$url}page=1'>1</a></li>";
                $pagination.= "<li><a href='{$url}page=2'>2</a></li>";
                //$pagination.= "<li class='dot'>...</li>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<li><a class='current'>{$counter}</a></li>";
                    else
                        $pagination.= "<li><a href='{$url}page={$counter}'>{$counter}</a></li>";                    
                }
                //$pagination.= "<li class='dot'>..</li>";
                $pagination.= "<li><a href='{$url}page={$lpm1}'>{$lpm1}</a></li>";
                $pagination.= "<li><a href='{$url}page={$lastpage}'>{$lastpage}</a></li>";      
                  
            } else {
                  
                $pagination.= "<li><a href='{$url}page=1'>1</a></li>";
                $pagination.= "<li><a href='{$url}page=2'>2</a></li>";
                //$pagination.= "<li class='dot'>..</li>";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<li><a class='current'>{$counter}</a></li>";
                    else
                        $pagination.= "<li><a href='{$url}page={$counter}'>{$counter}</a></li>";                    
                }
            }
        }
          
            if ($page < $counter - 1) {
                $pagination.= "<li><a href='{$url}page={$next}'>{$nextlabel}</a></li>";
                $pagination.= "<li><a href='{$url}page=$lastpage'>{$lastlabel}</a></li>";
            }
          
        $pagination.= "</ul>";        
    }
      
    return $pagination;
}

//new user email
function email_new_user($link, $user_id, $password) {

	// Set content-type for HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	// Email Headers
	$headers .= 'From: Excell Supply <noreply@excell-supply.com>' . "\r\n";

	// HTML email template for new registered user
	$email = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Excell Supply New Recruit Email</title>
      
      <style type="text/css">
         /* Client-specific Styles */
         #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
         body{width:600px !important; -webkit-text-size-adjust:600px; -ms-text-size-adjust:600px; margin:0; padding:0;}
         /* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */
         .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
         .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing.*/
         #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
         img {outline:none; text-decoration:none;border:none; -ms-interpolation-mode: bicubic;}
         a img {border:none;}
         .image_fix {display:block;}
         p {margin: 0px 0px !important;}
         table td {border-collapse: collapse;}
         table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
         a {color: #0a8cce;text-decoration: none;text-decoration:none!important;}
         /*STYLES*/
         table[class=full] { width: 100%; clear: both; }
      </style>
   </head>
   <body>
<!-- Start of preheader -->
<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="preheader" >
   <tbody>
      <tr>
         <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                           <tbody>
                              <!-- Spacing -->
                              <tr>
                                 <td width="100%" height="10"></td>
                              </tr>
                              <!-- Spacing -->
                              <tr>
                                 <td>
                                    <table width="100" align="left" border="0" cellpadding="0" cellspacing="0">
                                       <tbody>
                                          <tr>
                                             <td align="left" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #666666" st-content="viewonline" class="mobile-hide">
                                                <a href="'.ROOT_URL.'" style="text-decoration: none; color: #666666">Visit Portal</a> 
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                    <table width="200" align="right" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                       <tbody>
                                          <tr>
                                             <td width="100" height="30" align="right">
                                                <div class="imgpop">
                                                   <a target="_blank" href="'.ROOT_URL.'">
                                                   <img src="'.ROOT_URL.'/img/email/email-logo.png" alt="" border="0" style="display:block; border:none; outline:none; text-decoration:none;">
                                                   </a>
                                                </div>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                              <!-- Spacing -->
                              <tr>
                                 <td width="100%" height="10"></td>
                              </tr>
                              <!-- Spacing -->
                           </tbody>
                        </table>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- End of preheader -->       
<!-- Start of main-banner -->
<table width="100%" bgcolor="#F45E55" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="banner">
   <tbody>
      <tr>
         <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table width="600" align="center" cellspacing="0" cellpadding="30" border="0" class="devicewidth">
                           <tbody>
                              <tr>
                                 <!-- start of image -->
                                 <td align="center" st-image="banner-image">
                                    <div class="imgpop">
                                       <a target="_blank" href="'.ROOT_URL.'"><img border="0" alt="" border="0" style="display:block; border:none; outline:none; text-decoration:none; margin-bottom: 20px" src="'.ROOT_URL.'/img/email/header-text.png" class="banner"> <img border="0" alt="" border="0" style="display:block; border:none; outline:none; text-decoration:none;" src="'.ROOT_URL.'/img/email/text-header.png" class="banner"></a>
                                    </div>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                        <!-- end of image -->
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- End of main-banner --> 
<!-- Start of seperator -->
<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
   <tbody>
      <tr>
         <td>
            <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
               <tbody>
                  <tr>
                     <td align="center" height="20" style="font-size:1px; line-height:1px;">&nbsp;</td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- End of seperator -->   
<!-- Start Full Text -->
<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="full-text">
   <tbody>
      <tr>
         <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                           <tbody>
                              <!-- Spacing -->
                              <tr>
                                 <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                              </tr>
                              <!-- Spacing -->
                              <tr>
                                 <td>
                                    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                       <tbody>
                                          <!-- Title -->
                                          <tr>
                                             <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #333333; text-align:center; line-height: 30px;" st-title="fulltext-heading">
                                                Welcome to the team
                                             </td>
                                          </tr>
                                          <!-- End of Title -->
                                          <!-- spacing -->
                                          <tr>
                                             <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                          </tr>
                                          <!-- End of spacing -->
                                          <!-- content -->
                                          <tr>
                                             <td style="font-family: Helvetica, arial, sans-serif; font-size: 14px; color: #666666; text-align:center; line-height: 30px;" st-content="fulltext-content">
                                                Hi '.get_user_data($link, $user_id, 'user_fullname').',<br/>
                                                Welcome to the Excell Supply team. A user has been created for you on our staff room portal where you will have exclusive access to resources and where you can share your experiences with other teachers that are part of the Excell Supply team.<br/>
                                                <br/>
                                                Follow the link below to activate your account and get started;<br/>
                                                <a href="'.ROOT_URL.'/activate.php?user_id='.$user_id.'&activation='.get_user_data($link, $user_id, 'user_salt').'">'.ROOT_URL.'/activate.php?user_id='.$user_id.'&activation='.get_user_data($link, $user_id, 'user_salt').'</a>
                                             </td>
                                          </tr>
                                          <!-- End of content -->
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                              <!-- Spacing -->
                              <tr>
                                 <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                              </tr>
                              <!-- Spacing -->
                           </tbody>
                        </table>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- end of full text -->
<!-- Start of seperator -->
<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
   <tbody>
      <tr>
         <td>
            <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
               <tbody>
                  <tr>
                     <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                  </tr>
                  <tr>
                     <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                  </tr>
                  <tr>
                     <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- End of seperator -->   
</body>
</html>';
	
	// Send welcome email
	mail(get_user_data($link, $user_id,'user_email'), 'Welcome to Excell Supply', $email, $headers);

	// Check if password field is empty or not
	if(!empty($password)) {

		// Set content-type for HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// Email Headers
		$headers .= 'From: Excell Supply <noreply@excell-supply.com>' . "\r\n";

		// HTML email template for new registered user
		$email = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Your new password</title>
      
      <style type="text/css">
         /* Client-specific Styles */
         #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
         body{width:600px !important; -webkit-text-size-adjust:600px; -ms-text-size-adjust:600px; margin:0; padding:0;}
         /* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */
         .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
         .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing.*/
         #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
         img {outline:none; text-decoration:none;border:none; -ms-interpolation-mode: bicubic;}
         a img {border:none;}
         .image_fix {display:block;}
         p {margin: 0px 0px !important;}
         table td {border-collapse: collapse;}
         table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
         a {color: #0a8cce;text-decoration: none;text-decoration:none!important;}
         /*STYLES*/
         table[class=full] { width: 100%; clear: both; }
      </style>
   </head>
   <body>
<!-- Start of preheader -->
<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="preheader" >
   <tbody>
      <tr>
         <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                           <tbody>
                              <!-- Spacing -->
                              <tr>
                                 <td width="100%" height="10"></td>
                              </tr>
                              <!-- Spacing -->
                              <tr>
                                 <td>
                                    <table width="100" align="left" border="0" cellpadding="0" cellspacing="0">
                                       <tbody>
                                          <tr>
                                             <td align="left" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #666666" st-content="viewonline" class="mobile-hide">
                                                <a href="'.ROOT_URL.'" style="text-decoration: none; color: #666666">Visit Portal</a> 
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                    <table width="200" align="right" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                       <tbody>
                                          <tr>
                                             <td width="100" height="30" align="right">
                                                <div class="imgpop">
                                                   <a target="_blank" href="'.ROOT_URL.'">
                                                   <img src="'.ROOT_URL.'/img/email/email-logo.png" alt="" border="0" style="display:block; border:none; outline:none; text-decoration:none;">
                                                   </a>
                                                </div>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                              <!-- Spacing -->
                              <tr>
                                 <td width="100%" height="10"></td>
                              </tr>
                              <!-- Spacing -->
                           </tbody>
                        </table>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- End of preheader -->       
<!-- Start of main-banner -->
<table width="100%" bgcolor="#F45E55" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="banner">
   <tbody>
      <tr>
         <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table width="600" align="center" cellspacing="0" cellpadding="30" border="0" class="devicewidth">
                           <tbody>
                              <tr>
                                 <!-- start of image -->
                                 <td align="center" st-image="banner-image">
                                    <div class="imgpop">
                                       <a target="_blank" href="'.ROOT_URL.'"><img border="0" alt="" border="0" style="display:block; border:none; outline:none; text-decoration:none; margin-bottom: 20px" src="'.ROOT_URL.'/img/email/header-password.png" class="banner"> <img border="0" alt="" border="0" style="display:block; border:none; outline:none; text-decoration:none;" src="'.ROOT_URL.'/img/email/text-password.png" class="banner"></a>
                                    </div>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                        <!-- end of image -->
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- End of main-banner --> 
<!-- Start of seperator -->
<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
   <tbody>
      <tr>
         <td>
            <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
               <tbody>
                  <tr>
                     <td align="center" height="20" style="font-size:1px; line-height:1px;">&nbsp;</td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- End of seperator -->   
<!-- Start Full Text -->
<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="full-text">
   <tbody>
      <tr>
         <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                           <tbody>
                              <!-- Spacing -->
                              <tr>
                                 <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                              </tr>
                              <!-- Spacing -->
                              <tr>
                                 <td>
                                    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                       <tbody>
                                          <!-- Title -->
                                          <tr>
                                             <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #333333; text-align:center; line-height: 30px;" st-title="fulltext-heading">
                                                Welcome to the team
                                             </td>
                                          </tr>
                                          <!-- End of Title -->
                                          <!-- spacing -->
                                          <tr>
                                             <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                          </tr>
                                          <!-- End of spacing -->
                                          <!-- content -->
                                          <tr>
                                             <td style="font-family: Helvetica, arial, sans-serif; font-size: 14px; color: #666666; text-align:center; line-height: 30px;" st-content="fulltext-content">
                                                Hi '.get_user_data($link, $user_id, 'user_fullname').',<br/>
                                                A password has been created for you when your user account was created.<br/>
                                                <br/>
                                                <h3>'.$password.'</h3>
                                                <br/>
                                                You can change this in your account settings once you have activated your account.
                                          </tr>
                                          <!-- End of content -->
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                              <!-- Spacing -->
                              <tr>
                                 <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                              </tr>
                              <!-- Spacing -->
                           </tbody>
                        </table>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- end of full text -->
<!-- Start of seperator -->
<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
   <tbody>
      <tr>
         <td>
            <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
               <tbody>
                  <tr>
                     <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                  </tr>
                  <tr>
                     <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                  </tr>
                  <tr>
                     <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- End of seperator -->   
</body>
</html>';
	
	// Send password email
	mail(get_user_data($link, $user_id,'user_email'), 'Your new password', $email, $headers);

	}
	
}

?>