$('#popupContainer').ready(function(){

	
	$('#new_password').live('keyup',function(){
		matchPasswords();
	});
	
	$('#check_password').live('keyup',function(){
		matchPasswords();
	});

	
	$('.log_out').live('click', function(){
		document.location = "index.php?target=logout";
	}// end function
	);// end log_out
	
});

function matchPasswords() {
	var newPassword=$('#new_password').val();
	var checkPassword=$('#check_password').val();
	if(checkPassword!=="" && newPassword!=="") {
		if(newPassword===checkPassword) {
			$('#valid_password').val("true");
			$('#password_note').addClass('hidden').html("Passwords Match");
			$('#change-password').removeClass('hidden');
		}else {
			$('#valid_password').val("false");
			$('#password_note').removeClass('hidden').html("Passwords Do Not Match");
			$('#change-password').addClass('hidden');

		}
	}
}