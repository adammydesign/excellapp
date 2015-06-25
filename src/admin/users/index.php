<?php
session_start();

include('../../includes/config.php');
include('../../includes/functions.php');

is_loggedin($_SESSION['username']);

//check if the user is admin
if(!empty($_SESSION['user_admin']) != 1 || empty($_SESSION['user_admin'])) {
   header('location: '.ROOT_URL.'');
}

$title = 'Excell Supply &raquo Admin &raquo Users';
$page = 'useradmin';
//get sort variable from URL
$sort = $_GET['sort'];

//Sort statements
if(!empty($sort)) {

	if($sort == 'all') {
		$sort = '';
	} elseif($sort == 'recruit') {
		$sort = 'WHERE `user_admin` = 0 AND `user_accountmanager` = 0';
	} elseif($sort == 'managers') {
		$sort = 'WHERE `user_accountmanager` = 1';
	} elseif($sort == 'admin') {
		$sort = "WHERE `user_admin` = '1'";
	} else  {
		$sort = '';
	}
}

//do loops for users page
$pagenum = (int)(!isset($_GET["page"]) ? 1 : $_GET["page"]);
if ($pagenum <= 0) $page = 1;
$per_page = 10; // Set how many records do you want to display per page.
$startpoint = ($pagenum * $per_page) - $per_page;
$statement = "`excell_users` $sort ORDER BY `user_fullname` ASC"; // Change `records` according to your table name.
$results = mysqli_query($link ,"SELECT `user_id`, `user_email`, `user_fullname`, `user_admin`, `user_accountmanager`, `user_active` FROM {$statement} LIMIT {$startpoint} , {$per_page}");

//include header
include('../../includes/header.php');
?>

<!-- Container -->
<div class="container page-content">

	<!-- Row -->
	<div class="row">

		<!-- Sidebar Column -->
		<div class="col-lg-3 col-md-3 col-sm-4 hidden-xs">

			<?php
			//include root sidebar nav
			include_once('../../includes/admin_nav.php');
			?>

   		</div>
   		<!-- ./sidebar column -->

   		<!-- Main Span -->
   		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">

            <!-- page header -->
   			<div class="page-header clearfix">
	   			
	   			<!-- Sort button -->
				   <div class="btn-group pull-right header-btn">
				   	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				       	Sort Users <span class="caret"></span>
				   	</button>
				   	<ul class="dropdown-menu" role="menu">
				       	<li><a href="./">All users</a></li>
				       	<li><a href="?sort=recruit">Recruits</a></li>
				       	<li><a href="?sort=managers">Account managers</a></li>
				       	<li><a href="?sort=admin">Administrators</a></li>
				     	</ul>
				   </div>

				   <!-- Create btn -->
				   <a href="./create_user.php" class="btn btn-success pull-right header-btn">Create User <i class="fa fa-fw fa-plus"></i></a>
				
				   <!-- Title -->
	   			<h1 class="pull-left">
					Users
	   			</h1>

	   		</div>
            <!-- ./page header -->

   			<?php
   			//If we have results then display them
   			if(mysqli_num_rows($results) != 0) {
   			?>
   				<!-- User table -->
   				<table class="table" role="table" id="user-table">

   					<!-- table header -->
   					<thead>
	   					<tr>
	   						<th>Full Name</th>
	   						<th>Email Address</th>
	   						<th class="text-center">Status</th>
	   						<th>Actions</th>
	   					</tr>
   					</thead>
   					<!-- ./tabble header -->

   					<!-- table body -->
   					<tbody>

   						<?php
   						//loop for user table contents
   						while($user_row = mysqli_fetch_array($results)) {
   							
   							//Create status icon
   							if($user_row['user_active'] == 1) {
   								$status = '<a href="'.ROOT_URL.'/scripts/status_update.php?status=1&user_id='.$user_row['user_id'].'" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Disable '.get_user_data($link, $user_row['user_id'], 'user_fullname').'"><i class="fa fa-check"></i></a>';
   							} else {
   								$status = '<a href="'.ROOT_URL.'/scripts/status_update.php?status=0&user_id='.$user_row['user_id'].'" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Enable '.get_user_data($link, $user_row['user_id'], 'user_fullname').'"><i class="fa fa-remove"></i></a>';
   							}

   							//Create label for user type
   							
   							if($user_row['user_admin'] == 1) {
   								$user_type .= '<span class="label label-primary">Admin</span> ';
   							}
   							if($user_row['user_accountmanager'] == 1) {
   								$user_type .= '<span class="label label-success">Manager</span>';
   							}
   							if($user_row['user_admin'] != 1 && $user_row['user_accountmanager'] != 1) {
   								$user_type .= '<span class="label label-default">Recruit</span>';
   							}

   							//echo user row
   							echo '<tr class="user-table-row">';
   							echo '<td>'.$user_row['user_fullname'].' '.$user_type.'</td>';
   							echo '<td>'.$user_row['user_email'].'</td>';
   							echo '<td class="text-center user-status-row">'.$status.'</td>';
   							echo '<td class="user-actions"><a href="#" data-toggle="tooltip" data-original-title="View '.get_user_data($link, $user_row['user_id'], 'user_fullname').'"><i class="fa fa-eye"></i></a> <a href="./edit.php?user_id='.$user_row['user_id'].'" data-toggle="tooltip" data-original-title="Edit '.get_user_data($link, $user_row['user_id'], 'user_fullname').'"><i class="fa fa-pencil"></i></a> <a href="" class="delete" id="'.$user_row['user_id'].'" data-toggle="modal"><span data-toggle="tooltip" data-original-title="Delete '.get_user_data($link, $user_row['user_id'], 'user_fullname').'"><i class="fa fa-trash"></i></span></a></td>';
   							echo  '</tr>';

   							//re-assign $user_type to stop duplicate
   							$user_type = '';

   						}
   						//End llop
   						?>

   					</tbody>
   					<!-- ./table body -->
   				

   				</table>
   				<!-- ./table -->	

   			<?php
            //echo pagination
            echo pagination($statement,$per_page,$pagenum,$url='?');
   			//else echo no results and link to create a new user
   			} else {
   				echo '<p>Sorry, there appears to be no users matching your criteria currently on the system. <a href="'.ROOT_URL.'/admin/create_user.php">Create one now.</a></p>';
   			}
   			?>


   		</div>
   		<!-- ./main span -->

	</div>
	<!-- ./row -->

</div>
<!-- ./container -->

<div class="modal fade" id="delete-modal">
   <div class="modal-dialog">
      <!-- Load Ajax data here -->
      <div class="modal-content">
      </div>
   </div>
</div>


<?php
//include footer
include('../../includes/footer.php');
?>