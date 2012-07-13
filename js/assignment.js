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
				showPopup("Edit Assigment",data,"auto");

			}
		});
		
	});
	
	$(".assignment-create").live("click",function(){
		$.ajax({
			type:"get",
			url: base_url + "assignment/create",
			success: function(data){
				showPopup("Add Assignment",data,"auto");
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
	
	$(".show-student-selector").live("click",function(){
		
		form_data = {
				kTeach: $("#kTeach").val(),
				term: $("#term").val(),
				year: $("#year").val()	
		};
		
		$.ajax({
			type:"get",
			url: base_url + "index.php/grade/select_student",
			data:form_data,
			success:function(data){
				showPopup("Select Student",data,"auto");
			}
		});
	});
	
	$('#student-dropdown').live('keyup', function(event) {
		var stuSearch = this.value;
		if (stuSearch.length > 2 && stuSearch != "find students") {
			searchWords = stuSearch.split(' ');
			myName = searchWords.join('%') + "%";
			var myUrl = base_url + "index.php/student/find_by_name";
			var formData = {
				ajax: 1,
				type: "mini",
				js_class:$("#js_class").val(),
				stuName: stuSearch
			};
			$.ajax({
				url: myUrl,
				type: 'GET',
				data: formData,
				success: function(data){
					$("#searchList").css({"z-index": 2000}).html(data).position({
						my: "left top",
						at: "left bottom",
						of: $("#student-dropdown"), 
						collision: "fit"
					}).show();
			}
			});
		}else{
			$("#searchList").hide();
        	$("#searchList").css({"left": 0, "top": 0});


		}
	});// end stuSearch.keyup
	$('#student-dropdown').live("blur",function(){
		$("#searchList").fadeOut();
	});
	
	$(".select-student-for-grades").live("click",function(){
		form_data = {
				kStudent: this.id.split("_")[1],
				kTeach: $("#kTeach").val(),
				term:$("#term").val(),
				year: $("#year").val()
		};
		$.ajax({
			type:"get",
			data: form_data,
			url: base_url + "index.php/grade/edit",
			success: function(data){
				$("#mini-selector").html(data);
			}
			
		});
		
		
	});
});