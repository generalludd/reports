$(document).ready(function(){
	$("#category").live("mouseup",function(){
		if($("#category").val() == "other"){
			$("#cat_span").html("<input type='text' id='category' name='category' value=''/>");
			$("#category").focus();
		}
	});
	$("#edit-assignment #points").live("keyup",function(e){
		console.log("here");
		if($(this).val() == 0){
			$.ajax({
				type:"get",
				url: base_url + "assignment/point_types_menu",
				success:function(data){
					$("#points-type").html(data);
				}
			})
	
		}
		
	});
	
	
	$(".editable .grade-points.edit").live("click",function(e){
		e.preventDefault();
		myId = this.id.split("_");
		myAssignment = myId[1];
		myStudent = myId[2];
		$(this).removeClass("edit");
		myPoints = $(this).html();
		form_data = {
			kStudent: myStudent,
			kAssignment: myAssignment,
			ajax: 1,
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
	
	$("editable .point-editor").live("blur",function(){
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
	
	$(".select-student-for-grades").live("click",function(e){
		e.preventDefault();
		my_student = this.id.split("_")[1];
		my_teach = $("#kTeach").val();
		form_data = {
				kStudent: my_student,
				kTeach: my_teach,
				term:$("#term").val(),
				year: $("#year").val(),
				ajax: 1
		};
		$.ajax({
			type:"get",
			data: form_data,
			url: base_url + "grade/edit/" + my_student + "/" + my_teach ,
			success: function(data){
				$("#mini-selector").html(data);
			}
			
		});
		
		
	});


	
	$(".editable .save_student_grade").live("click",function(){
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
		form_data = {
				kStudent: myStudent,
				kAssignment: myAssignment,
				status: myStatus,
				footnote: myFootnote,
				points: myPoints
				
		};
		$.ajax({
			type: "POST",
			url: base_url + "grade/update",
			data: form_data,
			success: function(data){
				$(".save_cell_grade").parents("td.grade-points").html(data).addClass('edit');
				console.log(data);
				//window.location.reload();
			}
		});
	});

	
	$(".close_grade_editor").live("click",function(){
		window.location.reload();
	});
	
	$(".editable .grade-delete-row .button").live("click",function(){
		choice = confirm("Are you sure you want to delete this student's grade entries for the entire term? This cannot be undone!");
		if(choice){
			second_chance = confirm("This will delete all the grades entered for this student for the current term. Click OK only if you are absolute sure you want to do this!");
			if(second_chance){
				myTeach = $("#kTeach").val();
				myID = this.id.split("_");
				myStudent = myID[1];

				myTerm = myID[2];
				myYear = myID[3];
				form_data = {
						kTeach: myTeach,
						kStudent: myStudent,
						term: myTerm,
						year: myYear
				};
				myUrl = base_url + "grade/delete_row";
				
				$.ajax({
					type: "post",
					url: myUrl,
					data: form_data,
					success: function(data){
						window.location.reload();
					}
				});
			}
		}
	});
	
	$(".assignment-delete").live("click",function(){
		href = window.location.href;
	
		choice = confirm("Are you sure you want to delete this assignment? It will delete all the related student grades along with it!");
		if(choice){
			second_chance = confirm("Are you absolutely sure? This cannot be easily undone if at all.");
			if(second_chance){
				form_data = {
						kAssignment: $("#kAssignment").val(),
						url: href
				};
				$.ajax({
					type: "post",
					url: base_url + "assignment/delete",
					data: form_data,
					success: function(data){
						window.location.href = href;
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
		myYear = $("#year_" + myId).val();
		myTerm = $("#term_" + myId).val();
		form_data = {
				kCategory: myId,
				category: myCategory,
				weight: myWeight,
				gradeStart: myStart,
				gradeEnd: myEnd,
				year: myYear,
				term: myTerm
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
		myYear = $("#tr-teach_" + myTeach + " .insert-year").val();
		myTerm = $("#tr-teach_" + myTeach + " #term_new").val();
		form_data = {
				kTeach: myTeach,
				category: myCategory,
				weight: myWeight,
				gradeStart: myStart,
				gradeEnd: myEnd,
				year: myYear,
				term: myTerm,
				ajax: 1
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
	
	
	$(".add-batch-assignment-row").live("click",function(){
		
		$("table#batch-assignment-table tr.assignment:last").clone(true).insertAfter("table#batch-assignment-table tr.assignment:last");
		if($("table#batch-assignment-table tr").length == 4){
		$("table#batch-assignment-table tr.assignment:last").append("<td><a class='button delete-row delete'>Delete</a></td>");
		}
	//$("table.list tr.assignment:last input, table.list tr.assignment:last select").attr("id",this.name + $("table.list tr.assignment").length.toString() );
	});
	
	$("table#batch-assignment-table .delete-row").live("click",function(){
		$(this).parents("tr").remove();
	});
	
	$("#batch-insert-assignments input[type='submit']").live('click', function(e){
		e.preventDefault();
		document.forms[0].submit();
	});
	
	$(".batch-print-grades").live("click",function(e){
		e.preventDefault();
		batch_print_grades();
	});
	
	
});

function set_id(target,me,val){
	
}

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
			points: myPoints,
			ajax: 1
			
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
				value: myValue,
				ajax: 1
		};
		myUrl = base_url + "grade/update_value";
		$.ajax({
			type:"POST",
			url: myUrl,
			data: form_data,
			success: function(data){
				console.log(data);
				$("#save_" + myAssignment).html(data).show().fadeOut(2000);
			}
		});
	}else{
		save_student_points(myAssignment,myStudent);
	}
}
/**
 * The .map() function allows us to iterate through the grade-chart-row items for ids (for each student id)
 * to generate a printable chart of grades for teachers to use in conferences. 
 */

function batch_print_grades(){
	var id_array = $.map($(".grade-chart-row"),function(n,i){
		return n.id.split("_")[1];
	});

	form_data = {
			ids: id_array,
			kTeach: $("#kTeach").val(),
			action: "select",
			ajax: 1
	};
	
	console.log(form_data);
	
	$.ajax({
		type:"post",
		data: form_data,
		url: base_url + "grade/batch_print",
		success: function(data){
			showPopup("Batch Grade Printer",data,"auto");
		}
		
	});
}

