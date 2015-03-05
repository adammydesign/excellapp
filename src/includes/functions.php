<?php

//Create a salt for password
function create_salt() {

	$num = rand(100000,200000).rand(200000,300000);

	$letters = range('a','z');	
	$letters2 = range('A','Z');	
	$letters = array_merge($letters,$letters2);
	$pos = array('1','3','5','7','9','11','13','15','17','19','21','23');

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


?>