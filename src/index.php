<?php
session_start();

include('./includes/config.php');
include('./includes/functions.php');

is_loggedin($_SESSION['username']);

$title = 'Excell Supply &raquo Staff Room';
$page = 'staffroom';
$subpage = $_GET['subpage'];

//include header
include('./includes/header.php');
?>

<!-- Container -->
<div class="container page-content">

	<!-- Row -->
	<div class="row">

		<!-- Sidebar Column -->
		<div class="col-lg-3 col-md-3 col-sm-4 hidden-xs">

			<?php
			//include root sidebar nav
			include_once('./includes/dashboard_nav.php');
			?>

   		</div>
   		<!-- ./sidebar column -->

   		<!-- Main Span -->
   		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">

   			<?php 
   			//if subpage is set to forum then display forum activity loop
   			if($subpage == 'forum') {
   				include_once('./loops/forum_loop.php');
   			//else if subpage is set to news activity
   			} elseif($subpage == 'news') {
   				include_once('./loops/news_loop.php');
   			//ese if subpage is set to users activity	
   			} elseif($subpage == 'users') {
   				include_once('./loops/users_loop.php');
   			//else display normal loop	
   			} else {
   				include_once('./loops/activity_loop.php');
   			}
   			
   			?>


   		</div>
   		<!-- ./main span -->

	</div>
	<!-- ./row -->

</div>
<!-- ./container -->

<?php
//include footer
include('./includes/footer.php');
?>