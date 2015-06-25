<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?></title>

    <!-- Bootstrap -->
    <link href="<?php echo ROOT_URL; ?>/css/bootstrap.min.css" rel="stylesheet">

    <!-- Extra CSS -->
    <link href="<?php echo ROOT_URL; ?>/css/font-awesome.min.css" rel="stylesheet">
    <link href='<?php echo ROOT_URL; ?>/css/fonts.css' rel='stylesheet' type='text/css'>
    <link href="<?php echo ROOT_URL; ?>/css/datepicker.min.css" rel="stylesheet">
    <link href="<?php echo ROOT_URL; ?>/css/main.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
  </head>
<body <?php if(!empty($page)) echo 'class="'.$page.'-body"'; ?> >

<!-- Navigation -->
<nav class="navbar navbar-inverse main-nav">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo ROOT_URL; ?>">
        <img src="<?php echo ROOT_URL; ?>/img/logo-white.png" alt="Excell Supply Portal" class="logo"/>
      </a>
    </div>
    <div id="navbar" class="navbar-collapse collapse" aria-expanded="false" style="height: 1px;">
      <ul class="nav navbar-nav">
        <li <?php if($page == 'staffroom') echo 'class="active"'; ?> ><a href="<?php echo ROOT_URL; ?>">Staff Room</a></li>
        <li <?php if($page == 'resources') echo 'class="active"'; ?> ><a href="#">Resources</a></li>
        <li <?php if($page == 'users') echo 'class="active"'; ?> ><a href="#">Users</a></li>
        <?php
        //check if current logged in user is an admin
        if(!empty($_SESSION['user_admin']) == 1) {
        ?>
        <li <?php if($page == 'admin' || $page == 'createuser' || $page == 'useradmin') echo 'class="active"'; ?> ><a href="<?php echo ROOT_URL; ?>/admin">Admin</a></li>
        <?php
        }
        ?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="search-form">
          <input class="header-search" name="search" type="text">
        </li>
        <li>
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <?php echo get_avatar($link, $_SESSION['user_id']); ?> <?php echo get_user_data($link,$_SESSION['user_id'], 'user_fullname'); ?> <i class="fa-fw fa fa-caret-down"></i>
          </a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="<?php echo ROOT_URL; ?>/user/profile/<?php echo $_SESSION['user_id']; ?>"><i class="fa fa-user fa-fw"></i> Profile</a></li>
            <li><a href="<?php echo ROOT_URL; ?>/user/messages/<?php echo $_SESSION['user_id']; ?>"><i class="fa fa-comment fa-fw"></i> Messages 
            <?php 
            //Check if there are unread messages
            if(unread_messages($link) != 0) {
            ?>
            <span class="badge upper-badge"><?php echo unread_messages($link); ?></span>
            <?php
            }
            ?>
            </a></li>
            <?php
            //check if current logged in user is an admin
            if(!empty($_SESSION['user_admin']) == 1) {
            ?>
            <li><a href="<?php echo ROOT_URL; ?>/user/admin/availability"><i class="fa fa-calendar fa-fw"></i> Availability</a></li>
            <?php
            } else {
            ?>
            <li><a href="<?php echo ROOT_URL; ?>/user/availability/<?php echo $_SESSION['user_id']; ?>"><i class="fa fa-calendar fa-fw"></i> Availability</a></li>
            <?php
            }
            ?>
            <li class="divider"></li>
            <li><a href="<?php ROOT_URL; ?>/scripts/logmeout.php"><i class="fa fa-power-off fa-fw"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav>
<!-- Navigation End -->

<?php
//call alert
if($_GET['alert'] == 'true') {
  alert($_GET['alert'], $_GET['type'], $_GET['method'], $_GET['status'], $_GET['user_id'], $_GET['forum_id'], $_GET['name'], $link);
}

//check availability reminder
if($_SESSION['availability_check'] != 'done') {
  availability_reminder($link, $_SESSION['user_id']);
}
?>

<!-- result from form processes -->
<div class="form-result alert alert-success" style="display: none">

</div>



