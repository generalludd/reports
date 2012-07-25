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
});
