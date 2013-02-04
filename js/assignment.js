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
		form_data = {
			kStudent: myStudent,
			kAssignment: myAssignment
		};
		$.ajax({
			type:"get",
			url: base_url + "grade/edit_cell",
			data: form_data,
			success: function(data){
				//showPopup("test",data,"auto");
				$("#sag_" + myAssignment + "_" + myStudent).html(data);
				$("#points_" + myAssignment + "_" + myStudent).focus();
			}
		});
		//$(this).html("<input type='text' class='point-editor' name='points' id='points_" + myAssignment + "_" + myStudent + "' value='" + myPoints + "' size=4/>");
		
	});
	
	$(".point-editor").live("blur",function(){
		myPoints = $(this).val();
		$(this).parent(".grade-points").html(myPoints).addClass("edit");
	});
	
	$(".search-assignments").live("click",function(){
		myTeach = this.id.split("_")[1];
		form_data = {
				kTeach: myTeach
		};
		$.ajax({
			type: "get",
			data: form_data,
			url: base_url + "assignment/search",
			success: function(data){
				showPopup("Search for Assigment Charts",data, "auto");
			}
		});
	});
	
	$(".show-student-selector").live("click",function(){
		
		form_data = {
				kTeach: $("#kTeach").val(),
				term: $("#term").val(),
				year: $("#year").val()	
		};
		
		$.ajax({
			type:"get",
			url: base_url + "grade/select_student",
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
			var myUrl = base_url + "student/find_by_name";
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
					$("#search_list").css({"z-index": 2000}).html(data).position({
						my: "left top",
						at: "left bottom",
						of: $("#student-dropdown"), 
						collision: "fit"
					}).show();
			}
			});
		}else{
			$("#search_list").fadeOut();


		}
	});// end stuSearch.keyup
	$('#student-dropdown').live("blur",function(){
		$("#search_list").fadeOut();
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
			url: base_url + "grade/edit",
			success: function(data){
				$("#mini-selector").html(data);
			}
			
		});
		
		
	});
	
	$(".assignment-column-edit").live("click",function(){
		myAssignment = this.id.split("_")[1];
		form_data = {
				kAssignment: myAssignment,
				ajax: 1
		};
		myUrl = base_url + "grade/edit_column";
		
		$.ajax({
			type: "POST",
			url: myUrl,
			data: form_data,
			success: function(data){
				showPopup("Editing All Grades for an Assignment", data, "auto");
			}
		});
		
	});

	
	$(".edit_student_grades").live("click",function(){
		myTeach = $("#kTeach").val();
		myStudent = this.id.split("_")[1];
		form_data = {
			kTeach: myTeach,
			kStudent: myStudent,
		};
		myUrl = base_url + "grade/edit";
		$.ajax({
			type:"GET",
			url: myUrl,
			data: form_data,
			success: function(data){
				showPopup("Editing Student Grades",data, "auto");
			}
			
		});
	});
	
	$(".save_student_grade").live("click",function(){
		myID = this.id.split("_");
		myAssignment = myID[1];
		myStudent = myID[2];		
		save_student_points(myAssignment,myStudent);
	});
	
	$("input.assignment-field").live("click",function(){
		$(this).select();
	});
	
	$("input.assignment-field").live("blur",function(){
		myID = this.id.split("_");
		myAssignment = myID[1];
		myStudent = myID[2];
		//the parent tr of the entry has the grade id
		myGrade = $(this).closest("tr").attr("id");
		myValue = $(this).val();
		myKey = this.name;
		save_points_inline(myAssignment, myStudent, myKey, myValue, myGrade);
	});
	
	$("select.assignment-field").live("change",function(){
		myID = this.id.split("_");
		myAssignment = myID[1];
		myStudent = myID[2];
		//the parent tr of the entry has the grade id
		myGrade = $(this).closest("tr").attr("id");
		myValue = $(this).val();
		myKey = this.name;
		save_points_inline(myAssignment, myStudent, myKey, myValue, myGrade);
		//save_student_points(myAssignment);
	});
	
	$(".save_cell_grade").live("click",function(){
		myStudent = $("#kStudent").val();
		myAssignment = $("#kAssignment").val();
		myPoints = $("#points_" + myAssignment + "_" + myStudent).val();
		myStatus = $("#status_" + myAssignment + "_" + myStudent).val();
		myFootnote = $("#footnote_" + myAssignment + "_" + myStudent).val();
		myUrl = base_url + "grade/update";
		form_data = {
				kStudent: myStudent,
				kAssignment: myAssignment,
				status: myStatus,
				footnote: myFootnote,
				points: myPoints
				
		};
		$.ajax({
			type: "POST",
			url: myUrl,
			data: form_data,
			success: function(data){
				//$(".save_cell_grade").parent().html(data);
				window.location.reload();
			}
		});
	});

	
	$(".close_grade_editor").live("click",function(){
		window.location.reload();
	});
	
	$(".assignment-delete").live("click",function(){
		choice = confirm("Are you sure you want to delete this assignment? It will delete all the related student grades along with it!");
		if(choice){
			second_chance = confirm("Are you absolutely sure? This cannot be easily undone if at all.");
			if(second_chance){
				form_data = {
						kAssignment: $("#kAssignment").val()
				};
				$.ajax({
					type: "post",
					url: base_url + "assignment/delete",
					data: form_data,
					success: function(data){
						window.location.reload();
					}
				});
				
			}
		}
	});
	
	$(".update-category, .update-weight, .update-gradeStart, .update-gradeEnd").live("keyup",function(){
		myId = this.id.split("_")[1];
		$("#fun").val(myId);
		$("#update-category_" + myId).fadeIn();
	});
	
	$(".category-update").live("click",function(){
		myId = this.id.split("_")[1];
		myCategory = $("#category_" + myId).val();
		myWeight = $("#weight_" + myId).val();
		myStart = $("#gradeStart_" + myId).val();
		myEnd = $("#gradeEnd_" + myId).val();
		form_data = {
				kCategory: myId,
				category: myCategory,
				weight: myWeight,
				gradeStart: myStart,
				gradeEnd: myEnd
		};
		$.ajax({
			type: "post",
			url: base_url + "assignment/update_category",
			data: form_data,
			success: function(data){
				$("#update-category_" + myId).fadeOut();
			}
			
		});
		
		
	});
	
	
	$(".assignment_categories_edit").live("click",function(){
		$.ajax({
			url: base_url + "assignment/edit_categories/" + $("#kTeach").val(),
			type: "get",
			success: function(data){
				showPopup("Editing Categories",data,"auto");
			}
			
		});
		
	});
	
	$(".add-category").live("click",function(){
		myTeach = this.id.split("_")[1];
		
		$.ajax({
			type:"get",
			url: base_url + "assignment/create_category/" + myTeach,
			success: function(data){
				$("#category-table tbody").append(data);
				//$(this).fadeOut();
			}
			
		});
	});
	
	$(".category-insert").live("click",function(){
		myTeach = this.id.split("_")[1];
		myCategory = $("#tr-teach_" + myTeach + " .insert-category").val();
		myWeight = $("#tr-teach_" + myTeach + " .insert-weight").val();
		myStart = $("#tr-teach_" + myTeach + " .insert-gradeStart").val();
		myEnd = $("#tr-teach_" + myTeach + " .insert-gradeEnd").val();

		form_data = {
				kTeach: myTeach,
				category: myCategory,
				weight: myWeight,
				gradeStart: myStart,
				gradeEnd: myEnd
		};
		$.ajax({
			type: "post",
			url: base_url + "assignment/insert_category",
			data: form_data,
			success: function(data){
				$("#tr-teach_" + myTeach).remove();
				$("#category-table tbody").append(data);				
			}
		});
		//$(this).html(myTeach);
	});
	
	$(".get-student-grades").live("click",function(){
		myStudent = this.id.split("_")[1];
		form_data = {
				kStudent: myStudent
		};
		$.ajax({
			type:"get",
			data: form_data,
			url: base_url + "grade/select_report_card",
			success: function(data){
				showPopup("Selecting Grade Report", data,"auto");
			}
		});
		
	});
	
});

function save_student_points(myAssignment,myStudent)
{
	myPoints = $("#g_" + myAssignment).val();
	myStatus = $("#status_" + myAssignment).val();
	myFootnote = $("#footnote_" + myAssignment).val();
	myUrl = base_url + "grade/update";
	form_data = {
			kStudent: myStudent,
			kAssignment: myAssignment,
			status: myStatus,
			footnote: myFootnote,
			points: myPoints
			
	};
	$.ajax({
		type: "POST",
		url: myUrl,
		data: form_data,
		success: function(data){
			$("#save_" + myAssignment  + "_" + myStudent).html(data).show().fadeOut(2000);

		}
	});
	
}

function save_points_inline(myAssignment,myStudent,myKey,myValue, myGrade){
	//if the kGrade value is 0 then this is a new entry for the student for the term. 
	if(myGrade != 0 ){
		form_data = {
				kStudent:myStudent,
				kAssignment:myAssignment,
				key:myKey,
				value: myValue
		};
		myUrl = base_url + "grade/update_value";
		$.ajax({
			type:"POST",
			url: myUrl,
			data: form_data,
			success: function(data){
				$("#save_" + myAssignment + "_" + myStudent).html(data).show().fadeOut(2000);
			}
		});
	}else{
		save_student_points(myAssignment,myStudent);
	}
}