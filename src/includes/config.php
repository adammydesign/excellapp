<?php
/*****
Configuration File for Excell Supply Portal
******/

//Database connection settings
$dbhost = 'localhost';
$dbname = 'excell_app';
$dbuser = 'root';
$dbpwd = 'root';

$link = mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname) or die("Error " . mysqli_error($link));

//Debug mode when needed
//ini_set('display_errors', 'On');

//Defined variables
define('ROOT_URL', 'http://app.excell-supply.local');

?>