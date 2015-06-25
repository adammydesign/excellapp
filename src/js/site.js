// If search box is active, hide the search icon
$('.header-search').blur(function(){
	
	if($(this).val()) {
		$('.header-search').css("background", "#313B3D");
	} else {
    	$('.header-search').css("background", "url('../img/search-icon.png') no-repeat 8% center #313B3D");
    }
})

$('.header-search').focus(function() {		
   	$('.header-search').css("background", "#313B3D");
});


//Hide header alert after 5 seconds
$('.alert-header-dismiss').delay(4200).fadeOut(800);

//Create session on dismiss of alert ajax
$('.availability-alert .btn').click (function () {

    $.ajax({
        url: "../scripts/dismiss_session.php",
    }).done(function() {
    	$('.availability-alert').fadeOut(800);
    });

}); 

//create session when arriving on availability page
$('.availability-body').load(function () {
    $.ajax({
        url: "../scripts/dismiss_session.php",
    });
}); 

//fade in panel for login form
$('.login-body').ready(function () {
    $('.login-panel').fadeIn(1000);
    //logout text faade
    $('.logout-text').delay(2500).fadeOut(800);
});

//Login form via ajax
$(function(){
   
    //Create form variable
    var form = $('#login-form');

    $(form).submit(function(event){
        
        event.preventDefault();

        //serialize the form data
        var formData = $(form).serialize();

        $.ajax({
            type: 'POST',
            url: $(form).attr('action'),
            data: formData,
            beforeSend: function(){ $("#login").html('<i class="fa fa-refresh fa-spin"></i>');}
        })

        .done(function(response) {
            $('#login_result').html('<span class="text-success text-center" style="width:100%; display:block;">Login successful. Redirecting you now...</span>');
            $("#login").html('<i class="fa fa-check"></i>');
            setTimeout(function() {
                 window.location.href = "./";
            }, 2000);
        })

        .fail(function(data) {
            $('#login_result').html('<span class="text-danger text-center" style="width:100%; display:block;">Login failed. Please try again.</span>');
            $("#login").html('Login');
        });

    });

});

//initialize tooltips
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

//Edit form JS

//check to see if account managed is selected yes
$('input#user_managed1').click(function(){
    $('#user_accountmanagerdata').css('display', 'block');
});
$('input#user_managed2').click(function(){
    $('#user_accountmanagerdata').css('display', 'none');
});

//datepicker for date of birth
$('#user_dob').datepicker({
    format: "dd/mm/yyyy"
});

//new password repeat show
$('#user_password').keyup(function(){

    if($('#user_password').val() != '') {
        $('#password-repeat').slideDown(300);
    } else {
        $('#password-repeat').hide();
    }

});

//ajax create user form
$(function() {
    
    var create_form = $('#create-user-form');

    $(create_form).submit(function(event){

        event.preventDefault();

        //serialize the form data
        var formData = $(create_form).serialize();

        $.ajax({
            type: 'POST',
            url: $(create_form).attr('action'),
            data: formData,
            beforeSend: function(){ $(".form-result").slideDown(400).html('<i class="fa fa-refresh fa-spin"></i> Checking form.');}
        })

        .done(function(response) {
            window.location.replace(response);
        })

        .fail(function(data) {
            $('.form-result').removeClass('alert-success');
            $('.form-result').addClass('alert-warning');
            $('.form-result').html(data.responseText);
            $('.form-result').delay(3000).fadeOut(800);
        });

    });

});

//ajax submit edits
$(function() {

    var edit_form = $('#edit-form');

    $(edit_form).submit(function(event){
        
        event.preventDefault();

        //serialize the form data
        var formData = $(edit_form).serialize();

        $.ajax({
            type: 'POST',
            url: $(edit_form).attr('action'),
            data: formData,
            beforeSend: function(){ $(".form-result").slideDown(400).html('<i class="fa fa-refresh fa-spin"></i> Saving changes.');}
        })

        .done(function(response) {
            $('.form-result').removeClass('alert-warning');
            $('.form-result').addClass('alert-success');
            $('.form-result').html(response);
            $('.form-result').delay(3000).fadeOut(800);
        })

        .fail(function(data) {
            $('.form-result').removeClass('alert-success');
            $('.form-result').addClass('alert-warning');
            $('.form-result').html(data.responseText);
            $('.form-result').delay(3000).fadeOut(800);
        });

    });

});

//ajax delete modal
$("a.delete").click(function() {   
    
    var user_id = $(this).attr('id');

    $.ajax({
        method: 'GET',
        url: 'update.php',
        data: 'user_id='+user_id+'&action=confirm',
    })

    .done(function(response) {
        $('#delete-modal').modal('show');
        $('#delete-modal .modal-content').html(response);
        console.log(response);
    })

    .fail(function(data){
        $('#delete-modal').modal('show');
        $('#delete-modal .modal-content').html(data.responseText);
        console.log(data.responseText);
    });

});

/***
User activation Page
****/

//page animations
$("a.confirm-btn").click(function(){

    $(".activation-body").animate({'padding-top' : 50}, "slow");
    $("a.confirm-btn").animate("slow").hide();
    $("#confirm-details").animate("slow").show();;
});

//Submit details confirm form
//ajax submit edits
$(function() {

    var confirm_form = $('#confirm-details');

    $(confirm_form).submit(function(event){
        
        event.preventDefault();

        //serialize the form data
        var formData = $(confirm_form).serialize();

        $.ajax({
            type: 'POST',
            url: $(confirm_form).attr('action'),
            data: formData,
            beforeSend: function(){ $(".submit-confirmation").html('Checking Details <i class="fa fa-refresh fa-spin"></i>.');}
        })

        .done(function(response) {
            $(".submit-confirmation").html('Great <i class="fa fa-check"></i>.');
            $(".activation-container").fadeOut("slow");
            $(".confirmation-container").html('<div class="text-center center-block"><span class="fa-stack fa-4x"><i class="fa fa-circle fa-stack-2x white"></i><i class="fa fa-check fa-stack-1x fa-inverse red"></i></span><h2 class="white text-center">Account Activated<br/><small class="white">Congratulations, your account has been created and confirmed. Login now to visit the Excell Supply portal.</small></h2><br/><a href="./login.php" class="btn btn-success btn-lg">Click to Login</a></div>');
            $(".activation-body").animate({'padding-top' : 150}, "slow");
            $(".confirmation-container").animate("slow").show();
        })

        .fail(function(data) {
            $(".activation-result").slideDown(400);
            $('.activation-result').html(data.responseText);
            $('.submit-confirmation').html('Confirm <i class="fa fa-check"></i>');
            $('.activation-result').delay(5000).fadeOut(800);
        });

    });

});

/**********************
Messages Jquery & Ajax 
***********************/

//View Message Modal
$("a.open-message").click(function() {   
    
    var message_id = $(this).attr('id');

    $.ajax({
        method: 'GET',
        url: '../../includes/get_message.php',
        data: 'message_id='+message_id,
        beforeSend: function(){ 
            $("#message-modal").modal('show');
            $("#message-modal .modal-content").addClass('text-center');
            $("#message-modal .modal-content").html('<i class="fa fa-refresh fa-spin fa-2x"></i>');
        }
    })

    .done(function(response) { 
        $("#message-modal .modal-content").removeClass('text-center');
        $('#message-modal .modal-content').html(response);
    })

    .fail(function(data){
         $('#message-modal .modal-content').html(data.responseText);
    });

});

//open reply modal
$('#message-modal').on("click", "a.reply-btn", function() {

    //reply to contact id
    var reply_to = $(this).attr('id');
    var subject = $(this).data('subject');
    var parent = $(this).data('parentmessage');
    var sender = $(this).data('sender');


    $("#message-modal").modal('hide');
    $("#reply-modal .modal-content #reply-form").html('<h2>RE: '+subject+'<br/><small>To: '+sender+'</small></h2><p>Enter your reply below. Max 800 characters.</p><textarea name="message_body" class="form-control" rows="8" max="800" placeholder="Enter your reply here..."></textarea><input name="message_type" type="hidden" value="reply" /><input name="recipient" type="hidden" value="'+reply_to+'" /><input name="subject" type="hidden" value="'+subject+'" /><input name="parent_message" type="hidden" value="'+parent+'" /><br/><div class="message-alert alert alert-warning white"></div><button data-dismiss="modal" class="pull-left btn btn-default">Cancel <i class="fa fa-close"></i></button><button class="pull-right btn btn-success send-message" type="submit">Send Reply <i class="fa fa-send"></i></button>');
    $("#reply-modal").modal('show');

});

//Send Reply message
$(function() {

    var reply_form = $('#reply-form');

    $(reply_form).submit(function(event){
        
        event.preventDefault();

        //serialize the form data
        var formData = $(reply_form).serialize();

        $.ajax({
            type: 'POST',
            url: $(reply_form).attr('action'),
            data: formData,
            beforeSend: function(){ $("button.send-message").html('Sending <i class="fa fa-refresh fa-spin"></i>');}
        })

        .done(function(response) {
            $('.message-alert').removeClass('alert-warning');
            $('.message-alert').addClass('alert-success');
            $('.message-alert').html(response);
            $('.message-alert').show();
            $('.send-message').html('Message Sent <i class="fa fa-check"></i>');
            setTimeout(function() {$('#reply-modal').modal('hide')}, 3000);
        })

        .fail(function(data) {
            $('.message-alert').html(data.responseText);
            $('.message-alert').show();
            $('.send-message').html('Send Reply <i class="fa fa-send"></i>');
        });

    });

});
