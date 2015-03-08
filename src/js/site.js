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
