$(document).ready(function(){
	$("#category").live("mouseup",function(){
		if($("#category").val() == "other"){
			$("#cat_span").html("<input type='text' id='category' name='category' value=''/>");
			$("#category").focus();
		}
	});
	
	$(".assignment-edit").live("click",function(){
		myAssignment = this.id.split("_")[1];
		form_data = {
				kAssignment: myAssignment,
				ajax: 1
		};
		
		$.ajax({
			type:"get",
			url: base_url + "assignment/edit",
			data: form_data,
			success: function(data){
				showPopup("This is it",data,"auto");

			}
		});
		
	});
	
	$(".grade-points.edit").live("click",function(){
		myId = this.id.split("_");
		myAssignment = myId[1];
		myStudent = myId[2];
		$(this).removeClass("edit");
		myPoints = $(this).html();
		$(this).html("<input type='text' class='point-editor' name='points' id='points_" + myAssignment + "_" + myStudent + "' value='" + myPoints + "' size=4/>");
		
	});
	
	$(".point-editor").live("blur",function(){
		myPoints = $(this).val();
		$(this).parent(".grade-points").html(myPoints).addClass("edit");
	});
	
	
});