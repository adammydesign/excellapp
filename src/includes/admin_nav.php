<!-- Root Sidebar Nav -->
<ul class="nav nav-pills nav-stacked sidebar-nav" style="max-width:100%">

	<li role="presentation" <?php if($page == 'admin' && empty($_GET['subpage'])) echo 'class="active"'; ?>>
		<a href="<?php echo ROOT_URL; ?>/admin"><i class="fa fa-fw fa-bar-chart"></i> Dashboard</a>
	</li>

	<li role="presentation" <?php if($page == 'useradmin' || $page == 'createuser') echo 'class="active"'; ?> >
		<a href="<?php echo ROOT_URL; ?>/admin/users"><i class="fa fa-fw fa-users"></i> Users</a>
		
		<ul class="nav submenu">
			<li <?php if($page == 'createuser') echo 'class="active"'; ?> >
				<a href="<?php echo ROOT_URL; ?>/admin/users/create_user.php">Create User</a>
			<li>
		</ul>

	</li>
	
	<li role="presentation" <?php if($page == 'checkavailability') echo 'class="active"'; ?>>
		<a href="<?php echo ROOT_URL; ?>/user/admin/availability" ><i class="fa fa-fw fa-calendar"></i> Check Availability</a>
	</li>
	
	<li role="presentation" <?php if($page == 'forumsadmin') echo 'class="active"'; ?>>
		<a href="<?php echo ROOT_URL; ?>/admin/forums"><i class="fa fa-fw fa-book"></i> Posts to Approve</a>
	</li>

</ul>