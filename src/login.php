<?php
session_start();

include('./includes/config.php');
include('./includes/functions.php');

is_loggedin($_SESSION['username']);

$title = 'Excell Supply &raquo Portal Login';
$page = 'login';
$status = $_GET['status'];

//include header
include('./includes/header-login.php');
?>

<!-- Container -->
<div class="container page-content">
	
	<!-- Row -->
	<div class="row vertical-offset-100">
        
        <!-- Column for form -->
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">

        	<!-- Panel -->
            <div class="panel panel-default login-panel">
                
                <div class="panel-heading">                                
                    <div class="row-fluid user-row">
                        <img src="<?php echo ROOT_URL; ?>/img/login-logo.png" class="img-responsive center-block" alt="Excell Supply Web Portal"/>
                        <h3 class="text-center">Excell Supply Portal<br/><small>Please login to continue</small></h3>
                        <div id="login_result">
                            <?php 
                            if($status == 'logout') {?>
                                <span class="text-success text-center logout-text" style="width:100%; display:block;">You have been logged out.</span>
                            <?php 
                            }
                            ?>
                        </div>
                    </div>

                </div>
               
                <div class="panel-body">
                    <form accept-charset="UTF-8" role="form" class="form-signin" id="login-form" method="post" action="./scripts/logmein.php ">
                        <fieldset>
                         	<div class="input-group">
                         		<span class="input-group-addon" id="basic-addon1"><i class="fa fa-envelope"></i></span>
                         		<input class="form-control" placeholder="Email" id="username" name="email" type="email">
                         	</div>
                         	<div class="input-group">
                         		<span class="input-group-addon" id="basic-addon1"><i class="fa fa-lock fa-lg"></i></span>
                         		<input class="form-control" name="password" placeholder="Password" id="password" type="password">
                         	</div>
                         	
                         	<button class="btn btn-lg btn-success pull-right" type="submit" id="login">Login</button>
                        </fieldset>
                    </form>
                </div>

            </div>
            <!-- ./panel -->

        </div>
        <!-- ./col -->

    </div>
    <!-- ./row -->

</div>
<!-- ./container -->

<?php
include('./includes/footer.php');
?>