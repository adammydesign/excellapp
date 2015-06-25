<?php
session_start();
$user_id = $_GET['user_id'];

include('../includes/config.php');
include('../includes/functions.php');

$title = 'Excell Supply &raquo Staff Room';
$page = 'profile';

//include header
include('../includes/header.php');
?>

<!-- Container -->
<div class="container page-content">

	<!-- Row -->
	<div class="row">

		<!-- Sidebar Column -->
		<div class="col-lg-3 col-md-3 col-sm-4 hidden-xs">

			<?php
			//include root sidebar nav
			include_once('../includes/dashboard_nav.php');
			?>

		</div>
   		<!-- ./sidebar column -->

   		<!-- Main Span -->
   		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">

	        <div class="fb-profile clearfix">
	        	<div class="fb-image-lg profile-cover pull-left">
	        		<img src="http://lorempixel.com/850/280/nightlife/5/" alt="Profile image example"/>
	        	</div>
	        	<div class="fb-image-profile pull-left">
	        		<img class="img-circle" src="http://lorempixel.com/180/180/people/9/" alt="Profile image example"/>
	        	</div>
	        	<div class="fb-profile-text">
	            	<h1><?php echo get_user_data($link, $user_id, 'user_fullname'); ?></h1>
	        	</div>
	    	</div>

   		</div>
   		<!-- ./main span -->

	</div>
	<!-- ./row -->

</div>
<!-- ./container -->


<?php
include('../includes/footer.php');
?>