$(document).ready(function(){
	
	$(".report_delete").live("click",function(){
		myReport = $("#kReport").val();
		myStudent = $("#kStudent").val();
		action = $("#report-editor").attr("action");
		question = confirm("Are you sure you want to delete this? This cannot be undone.");
		if(question){
			question = confirm("Are you really sure? This really cannot be undone!");
			if(question){
				$("#report-editor").attr("action",base_url + "report/delete");
				$("#report-editor").submit();
			}
		}
		
	});
	
	
	
	$("#orange-slip #category").live("change",function(){
		value = $("#orange-slip #category").val();
		if(value == "Missing Homework"){
			$("#orange-slip #assignment-status-field").fadeIn();
		}else{
			$("#orange-slip #assignment-status-field").fadeOut();
			$("#orange-slip #assignment_status").attr("checked",false);
		}

	});
	
	$(".report-is-read").live("click",function(){
		myID = this.id.split("_")[1];
		myStatus = $(this).attr("checked");
		isRead = 0;
		if(myStatus == "checked"){
			isRead = 1;
		}
		
		form_data = {
				kReport: myID,
				target_value: isRead,
				target_field: "is_read"
		};
		
		$.ajax({
			type: "post",
			data: form_data,
			url: base_url + "report/update_value/",
			success: function(data){
				//$(this).attr("checked",data);
			}
		});
	});
	
});
