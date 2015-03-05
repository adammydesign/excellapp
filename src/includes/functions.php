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

	echo $salt;
}
?>