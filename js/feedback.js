$(document).ready(function(){
	$(".send_feedback").live('click',function(){
		var mySubject = $('#subject').val();
		var myRank = $('#rank').val();
		var myFeedback = $('#feedback').val();
		var myUrl = base_url + "feedback/add";
		var form_data = {
				subject: mySubject,
				rank: myRank,
				feedback: myFeedback
		};
		$.ajax({
			type: "get",
			url: myUrl,
			data: form_data,
			success: function(reply){
				$("#feedback-div").html(reply);
				
			}
		});
		return false;
		
	});
	
	$(".create_feedback").live('click',function(data){
		var myUrl = base_url + "feedback/create";
		var myLocation = document.location;
		var myPath = myLocation.toString().split(base_url);
		form_data = {
				path: myPath[1]
		};
		
		$.ajax({
			type: "get",
			url: myUrl,
			data: form_data,
			success: function(reply){
				showPopup('Create Feedback', reply, 'auto');
			}
		});
		
	});
	
	

});