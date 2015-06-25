<?php
include('./includes/config.php');
include('./includes/functions.php');

//get URL parameters
$user_id = $_GET['user_id'];
$activation = $_GET['activation'];

//Check and get details
$get_user = mysqli_query($link, "SELECT `user_id` FROM `excell_users` WHERE `user_id`='$user_id' AND `user_salt`='$activation'");

$can_activate = mysqli_num_rows($get_user);

//check results
if($can_activate != '1') {
	header('location: '.ROOT_URL);
	exit;
}

//title and page
$title = 'Excell Supply &raquo User Activation';
$page = 'activation';

//include login header
include('./includes/header-login.php');
?>

<!-- Container -->
<div class="container activation-container">

		<div class="img-circle center-block" >
			<img src="<?php echo ROOT_URL;?>/img/email/header-text.png" class="center-block" />
		</div>

		<h2 class="text-center welcome-note">Welcome, <?php echo get_user_data($link, $user_id, 'user_fullname'); ?></h2>

		<?php if(get_user_data($link,$user_id,'user_active') == 1) {
			echo '<div class="center-block text-center"><p class="white text-center">Your accound is already activated.</p><br/><a href="./login.php" class="btn btn-success btn-lg">Click to Login</a></div>';
		} else { ?>

		<div class="center-block text-center">
			<a href="#" class="btn btn-success btn-lg confirm-btn">Confirm information &raquo</a>
		</div>
		
		<?php } ?>

		<form id="confirm-details" action="./includes/user-activation.php" class="center-block">

			<p class="text-center white">Please check and confirm your details below so that our records are correct.</p>
			<br/>

			<!-- form row -->
			<div class="row" id="confirm-details-row">

				<!-- col one -->
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

					<div class="form-group">
						<label class="text-left white">Full Name</label>
						<input class="form-control activation-input" name="confirm_full_name" type="text" id="confirm-full-name" value="<?php echo get_user_data($link, $user_id, 'user_fullname'); ?>" />
					</div>

					<div class="form-group">
						<label class="text-left white">Contact Number</label>
						<input class="form-control activation-input" name="confirm_contact_num" type="text" id="confirm-contact-num" value="<?php echo get_user_data($link, $user_id, 'user_contactnum'); ?>" />
					</div>

				</div>
				<!-- ./end col one -->
				
				<!-- col two -->
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

					<div class="form-group">
						<label class="text-left white">Email Address</label>
						<input class="form-control activation-input" name="confirm_email_address" type="text" id="confirm-email-address" value="<?php echo get_user_data($link, $user_id, 'user_email'); ?>" />
					</div>

					<div class="form-group">
						<label class="text-left white">NI Number</label>
						<input class="form-control activation-input" name="confirm_ni_number" type="text" id="confirm-ni-number" value="<?php echo get_user_data($link, $user_id, 'user_ni'); ?>" />
					</div>


				</div>
				<!-- ./end col two -->

			</div>
			<!-- ./end form row -->

			<?php
			//check if a password is set or not
			if(empty(get_user_data($link, $user_id,'user_password'))) {
			?>

			<!-- create password section -->
			<img src="<?php echo ROOT_URL;?>/img/email/header-password.png" class="center-block" />
			<h3 class="white text-center">Create your Password<br/><small>Password must be at least 5 characters long.</small></h3>

			<!-- Password row -->
			<div class="row">

				<div class="form-group col-sm-12 col-md-6">
					<label class="white">Password</label>
					<input type="password" name="confirm_password" class="form-control" id="confirm-password" />
				</div>

				<div class="form-group col-sm-12 col-md-6">
					<label class="white">Confirm Password</label>
					<input type="password" name="confirm_password_confirm" class="form-control" id="confirm-password-confirm" />
				</div>

			</div>
			<!-- ./end password row -->

			<?php
			}
			?>

			<div class="activation-result alert alert-warning white text-center">

			</div>

			<!-- User ID and salt -->
			<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
			<input type="hidden" name="user_salt" value="<?php echo $activation; ?>">

			<div class="center-block text-center">
				<button class="btn btn-success btn-lg submit-confirmation">Confirm <i class="fa fa-check"></i></button>
			</div>

		</form>

</div>
<!-- ./container -->

<!-- confirmation container -->
<div class="container confirmation-container">

</div>

<?php
include('./includes/footer.php');
?>