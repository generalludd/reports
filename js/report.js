$(document).ready(function(){
	$(".report_search").live("click",function(){
		form_data = {
				key: this.id.split("_")[1],
				action: this.id.split("_")[0]
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
});
