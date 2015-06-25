<?php
session_start();
$id = $_GET['user_id'];

include('../includes/config.php');
include('../includes/functions.php');

$title = 'Excell Supply &raquo Staff Room';
$page = 'messages';
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

        <!-- page header -->
        <div class="page-header clearfix">
        
          <!-- Title -->
          <h1 class="pull-left">
          Messages <span class="badge upper-badge"><?php echo unread_messages($link); ?></span>
          </h1>

        </div>
        <!-- ./page header -->

        <?php
        include_once('../loops/message_loop.php');
        ?>


   		</div>
   		<!-- ./main span -->

	</div>
	<!-- ./row -->

</div>
<!-- ./container -->


<?php
include('../includes/footer.php');
?>