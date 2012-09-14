$(document).ready(function(){
	$(".report_search").live("click",function(){
		form_data = {
				report_key: $("#report_key").val(),
				report_type: $("#report_type").val()
		};
		$.ajax({
			type:"get",
			url: base_url + "report/search",
			data: form_data,
			success: function(data){
				showPopup("Search Reports",data,"auto");
				$("#date_start").blur();
				
			}
		});
	});
	
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
	
	$(".report-edit").live("click",function(){
		myReport = this.id.split("_")[1];
		form_data = {
				ajax: "1"
		};
		$.ajax({
			type:"get",
			data: form_data,
			url: base_url + "report/edit/" + myReport,
			success: function(data){
				showPopup("Editing Student Report",data,"auto");
			}
		});
		return false;
	});
	
	$(".report-add").live("click",function(){
		myStudent = this.id.split("_")[1];
		form_data = {
				ajax: "1"
		};
		$.ajax({
			type: "get",
			data: form_data,
			url: base_url + "report/create/" + myStudent,
			success: function(data){
				showPopup("Adding Student Report",data,"auto");
			}
		});
		return false;
	});
});
