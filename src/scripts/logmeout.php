<?php
session_start();

include_once('../includes/config.php');

//log user out
session_unset();
session_destroy();
header('location: '.ROOT_URL.'/login.php?status=logout');

?>