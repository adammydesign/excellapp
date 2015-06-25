<?php
session_start();

include('../../includes/config.php');
include('../../includes/functions.php');

is_loggedin($_SESSION['username']);

//check if the user is admin
if(!empty($_SESSION['user_admin']) != 1 || empty($_SESSION['user_admin'])) {
   header('location: '.ROOT_URL.'');
}

$title = 'Excell Supply &raquo Admin &raquo Edit User';
$page = 'useradmin';

//Get user_id
$user_id = $_GET['user_id'];

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

      <!-- Form -->
      <form class="admin-form" id="edit-form" action="./update.php?action=edit&user_id=<?php echo $user_id; ?>" method="post">

   		<div class="page-header clearfix">

			   <!-- Save btn -->
			   <button type="submit" class="btn btn-success pull-right header-btn">Save Changes <i class="fa fa-fw fa-save"></i></button>
			
			   <!-- Title -->
	   		<h1 class="pull-left text-left">
				  Edit User<br/>
              <small>Currently editing <?php echo get_user_data($link, $user_id, 'user_fullname'); ?></small>
	   		</h1>

	   	</div>

         <!-- Edit main row -->
         <div class="row">

            <!-- Edit main column -->
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">

               <!-- Name -->
               <div class="form-group">
                  <label>Full name:</label>
                  <div class="input-group">
                     <input name="user_fullname" type="text" class="form-control edit-input-lg" value="<?php echo get_user_data($link, $user_id, 'user_fullname'); ?>" />
                     <span class="input-group-addon" id="basic-addon2"><i data-toggle="tooltip" data-original-title="Edit users full name" data-placement="left" class="fa fa-pencil"></i></span>
                  </div>
               </div>

               <!-- Email -->
               <div class="form-group">
                  <label>Email Address:</label>
                  <div class="input-group">
                     <input name="user_email" type="email" class="form-control edit-input" value="<?php echo get_user_data($link, $user_id, 'user_email'); ?>" />
                     <span class="input-group-addon" id="basic-addon2"><i data-toggle="tooltip" data-original-title="Edit users email address" data-placement="left" class="fa fa-pencil"></i></span>
                  </div>
               </div>

               <!-- Contact Number -->
               <div class="form-group">
                  <label>Contact Number:</label>
                  <div class="input-group">
                     <input name="user_contactnum" type="text" class="form-control edit-input" value="<?php echo get_user_data($link, $user_id, 'user_contactnum'); ?>" placeholder="Contact Number" />
                     <span class="input-group-addon" id="basic-addon2"><i data-toggle="tooltip" data-original-title="Edit users contact number" data-placement="left" class="fa fa-pencil"></i></span>
                  </div>
               </div>

               <!-- Contact Number -->
               <div class="form-group">
                  <label>Date of Birth:</label>
                  <div class="input-group">
                     <input name="user_dob" type="text" class="form-control edit-input" value="<?php echo date("d/m/Y", strtotime(get_user_data($link, $user_id, 'user_dob'))); ?>" placeholder="Date of Birth" id="user_dob"/>
                     <span class="input-group-addon date" id="basic-addon2"><i data-toggle="tooltip" data-original-title="Edit users date of birth" data-placement="left" class="fa fa-pencil"></i></span>
                  </div>
               </div>

               <!-- National Insurance -->
               <div class="form-group">
                  <label>NI Number:</label>
                  <div class="input-group">
                     <input name="user_ni" type="text" class="form-control edit-input" value="<?php echo get_user_data($link, $user_id, 'user_ni'); ?>" placeholder="NI Number" />
                     <span class="input-group-addon" id="basic-addon2"><i data-toggle="tooltip" data-original-title="Edit users NI number" data-placement="left" class="fa fa-pencil"></i></span>
                  </div>
               </div>

               <!-- New Password -->
               <div class="form-group">
                  <label>New Password:</label>
                  <div class="input-group">
                     <input name="user_password" type="password" id="user_password" class="form-control edit-input" placeholder="New password" />
                     <span class="input-group-addon" id="basic-addon2"><i data-toggle="tooltip" data-original-title="Edit users NI number" data-placement="left" class="fa fa-lock"></i></span>
                  </div>
               </div>

                <!-- Repeat New Password -->
               <div class="form-group" id="password-repeat" style="display: none;">
                  <label>Repeat New Password:</label>
                  <div class="input-group">
                     <input name="user_password_repeat" type="password" class="form-control edit-input" placeholder="Repeat New Password" />
                     <span class="input-group-addon" id="basic-addon2"><i data-toggle="tooltip" data-original-title="Edit users NI number" data-placement="left" class="fa fa-lock"></i></span>
                  </div>
               </div>

            </div>

            <!-- Edit side Column -->
            <aside class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
               
               <!-- Permissions Panel -->
               <div class="panel panel-default">
                  
                  <div class="panel-heading clearfix" role="tab" id="headingOne">
                     <h4 class="panel-title">
                        <span class="heading-text pull-left">Account Permissions</span>
                        <a class="pull-right" data-toggle="collapse" href="#accountpermissions" aria-expanded="true" aria-controls="accountpermissions">
                           <span class="fa-stack fa">
                              <i class="fa fa-circle fa-stack-2x stack-bg"></i>
                              <i class="fa fa-chevron-down fa-stack-1x fa-inverse"></i>
                           </span>
                        </a>
                     </h4>
                  </div>

                  <div id="accountpermissions" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="accountpermissions">
                     <div class="panel-body">

                        <!-- Is admin Checkbox -->
                        <div class="form-group">
                           <label class="radio-label">User Admin</label>
                           <div class="radio radio-info radio-inline">
                              <input type="radio" name="user_admin" id="user_admin" value="1" <?php if (get_user_data($link, $user_id, 'user_admin') == 1) echo 'checked'; ?>>
                              <label for="user_admin">
                                 Yes
                              </label>
                           </div>
                           <div class="radio radio-info radio-inline">
                              <input type="radio" name="user_admin" id="user_admin" value="0" <?php if (get_user_data($link, $user_id, 'user_admin') == 0) echo 'checked'; ?> >
                              <label for="user_admin">
                                 No
                              </label>
                           </div>
                        </div>

                        <!-- Is Account Manager -->
                        <div class="form-group">
                           <label class="radio-label">Manager</label>
                           <div class="radio radio-info radio-inline">
                              <input type="radio" name="user_accountmanager" id="user_accountmanager" value="1" <?php if (get_user_data($link, $user_id, 'user_accountmanager') == 1) echo 'checked'; ?>>
                              <label for="user_admin">
                                 Yes
                              </label>
                           </div>
                           <div class="radio radio-info radio-inline">
                              <input type="radio" name="user_accountmanager" id="user_accountmanager" value="0" <?php if (get_user_data($link, $user_id, 'user_accountmanager') == 0) echo 'checked'; ?>>
                              <label for="user_admin">
                                 No
                              </label>
                           </div>
                        </div>

                     </div>

                  </div>

               </div>
               <!-- ./Permissions panel -->

               <!-- Account Manager Panel -->
               <div class="panel panel-default">
                  
                  <div class="panel-heading clearfix" role="tab" id="headingOne">
                     <h4 class="panel-title">
                        <span class="heading-text pull-left">Account Manager</span>
                        <a class="pull-right" data-toggle="collapse" href="#accountmanager" aria-expanded="true" aria-controls="accountmanager">
                           <span class="fa-stack fa">
                              <i class="fa fa-circle fa-stack-2x stack-bg"></i>
                              <i class="fa fa-chevron-down fa-stack-1x fa-inverse"></i>
                           </span>
                        </a>
                     </h4>
                  </div>

                  <div id="accountmanager" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="accountmanager">
                     <div class="panel-body">

                        <!-- Is the account managed -->
                        <div class="form-group">
                           <label class="radio-label">User Managed</label>
                           
                           <div class="radio radio-info radio-inline">
                              <input type="radio" name="user_managed" id="user_managed1" value="1" <?php if (!empty(get_user_data($link, $user_id, 'user_accountmanagerid')) != 0) echo 'checked'; ?>>
                              <label for="user_managed">
                                 Yes
                              </label>
                           </div>

                           <div class="radio radio-info radio-inline">
                              <input type="radio" name="user_managed" id="user_managed2" value="0" <?php if (get_user_data($link, $user_id, 'user_accountmanagerid') == 0 || empty(get_user_data($link, $user_id, 'user_accountmanagerid'))) echo 'checked'; ?> >
                              <label for="user_managed">
                                 No
                              </label>
                           </div>
                        </div>

                        
                         <div id="user_accountmanagerdata" class="form-group" <?php if (!empty(get_user_data($link, $user_id,'user_accountmanagerid')) != 0) echo 'style = "display:block;"'; ?> >
                            <label>Select Account Manager</label>
                            <select class="form-control" name="user_accountmanagerid">
                              <?php 
                              //if account is managed return current account manager
                              if (!empty(get_user_data($link, $user_id,'user_accountmanagerid')) != 0) {
                                    $manager_id = get_user_data($link, $user_id, 'user_accountmanagerid');
                                    echo '<option value="'.$manager_id.'">'.get_user_data($link, $manager_id, 'user_fullname').'</option>';
                                 } else {
                                    echo '<option>Select manager .. </option>';
                                 }
                              ?>

                              <?php
                              //get current account manager if it exists

                              //Query for managers and do loop for select
                              $get_managers = mysqli_query($link, "SELECT `user_id`, `user_fullname` FROM `excell_users` WHERE `user_accountmanager` = 1 AND `user_id` != '$manager_id'");
                              //Do manager loop
                              while($managers = mysqli_fetch_array($get_managers)) {
                                 echo '<option value="'.$managers['user_id'].'">'.$managers['user_fullname'].'</option>';
                              }

                              ?>
                              
                            </select>
                         </div>

                     </div>

                  </div>
               </div>
               <!-- ./Account Manager panel -->

            </aside>
            <!-- ./side column -->

         </div>
         <!-- ./row -->

   	</div>
   	<!-- ./main span -->

      </form>
      <!--  form end -->

	</div>
	<!-- ./row -->

</div>
<!-- ./container -->

<?php
//include footer
include('../../includes/footer.php');
?>