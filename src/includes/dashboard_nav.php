<!-- Root Sidebar Nav -->
<ul class="nav nav-pills nav-stacked sidebar-nav" style="max-width:100%">

	<li role="presentation" <?php if($page == 'staffroom') echo 'class="active"'; ?> >
		<a href="<?php echo ROOT_URL; ?>"><i class="fa fa-fw fa-trello"></i> Activity</a>
		
		<ul class="nav submenu">
			<li <?php if($subpage == 'forum') echo 'class="active"'; ?> >
				<a href="<?php echo ROOT_URL; ?>?subpage=forum">Forum Activity</a>
			<li>
			<li <?php if($subpage == 'news') echo 'class="active"'; ?> >
				<a href="<?php echo ROOT_URL; ?>?subpage=news">News Activity</a>
			</li>
			<li <?php if($subpage == 'users') echo 'class="active"'; ?> >
				<a href="<?php echo ROOT_URL; ?>?subpage=users">User Activity</a>
			</li>
		</ul>

	</li>
	
	<li role="presentation">
		<a href="<?php echo ROOT_URL; ?>/user/messages/<?php echo $_SESSION['user_id']; ?>"><i class="fa fa-fw fa-comment"></i> Messages</a>
	</li>
	
	<li role="presentation">
		<a href="<?php echo ROOT_URL; ?>/forums"><i class="fa fa-fw fa-book"></i> Staff Room Forums</a>
	</li>
	
	<li role="presentation">
		<a href="<?php echo ROOT_URL; ?>/user/availability/<?php echo $_SESSION['user_id']; ?>"><i class="fa fa-fw fa-briefcase"></i> Availability</a>
	</li>

</ul>