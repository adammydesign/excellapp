<?php
session_start();
$id = $_GET['user_id'];

include('../includes/config.php');
include('../includes/functions.php');

$title = 'Excell Supply &raquo Staff Room';
$page = 'availability';
$subpage = $_GET['subpage'];

//include header
include('../includes/header.php');
?>

<?php
echo get_user_data($link,$id,'user_fullname').' Availability';
?>

<?php
include('../includes/footer.php');
?>