$('#popupContainer').ready(function(){

	
	$('#new_password').live('keyup',function(){
		matchPasswords();
	});
	
	$('#check_password').live('keyup',function(){
		matchPasswords();
	});
	
	$('.change_password').live('click',function(){
		var myTeach = $("#kTeach").val();
		var myCurrentPassword=$('#current_password').val();
		var myNewPassword=$('#new_password').val();
		var myCheckPassword = $("#check_password").val();
		var validPassword=$("#valid_password").val();
		var form_data = {
				kTeach: myTeach,
				current_password: myCurrentPassword,
				new_password: myNewPassword,
				check_password: myCheckPassword,
				ajax: 1
		};
		var myUrl = base_url + "user/change_password";
		if(validPassword=="true" && myCurrentPassword!="") {
			$.ajax({
				type: "POST",
				url: myUrl,
				data: form_data,
				success: function(data){
				$("#password_form").html("<div class='notice'>" + data + "</div>");
				
			}
			});
		}else {
			var message="You have the following error(s):";
			if(validPassword!="true") {;
				message=message + "\rYour passwords do not match!";
				$("#check_password").val("");
				$("#new_password").val("").focus();

				
			}
			if(myCurrentPassword=="") {
				message=message+ "\rYou have not entered your current password!";
				
			}
			
			alert(message);
		}// end if validPassword;
		return false;
			
	});
	
	$('.log_out').live('click', function(){
		document.location = "index.php?target=logout";
	}// end function
	);// end log_out
	
});

function matchPasswords() {
	var newPassword=$('#new_password').val();
	var checkPassword=$('#check_password').val();
	if(checkPassword!="" && newPassword!="") {
		if(newPassword==checkPassword) {
			$('#valid_password').val("true");
			$('#password_note').fadeIn().html("Passwords Match");
			$('#change-password').fadeIn();
		}else {
			$('#valid_password').val("false");
			$('#password_note').fadeIn().html("Passwords Do Not Match");
			$('#change-password').fadeOut();

		}
	}
}