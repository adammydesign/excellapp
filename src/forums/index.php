<?php
session_start();

include('../includes/config.php');
include('../includes/functions.php');

is_loggedin($_SESSION['username']);

$title = 'Excell Supply &raquo Staff Room';
$page = 'forums';
$subpage = $_GET['subpage'];

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

   			<p>Sorry, there appears to be no forums on the system yet. <a href="http://app.excell-supply.local/forums/create_user.php">Create forum now.</a></p>

   		</div>
   		<!-- ./main span -->

	</div>
	<!-- ./row -->

</div>
<!-- ./container -->

<?php
//include footer
include('../includes/footer.php');
?>