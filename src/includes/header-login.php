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
    <link href="<?php echo ROOT_URL; ?>/css/main.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
</head>

<body <?php if(!empty($page)) echo 'class="'.$page.'-body"'; ?> >


