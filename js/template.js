$(document).ready(function(){
	

	
	$("#template_editor").live("change",function(){
		var subject = $("#subject").val();
		var gradeStart = $("#gradeStart").val();
		var gradeEnd = $("#gradeEnd").val();
		var grade = format_grade(gradeStart) + "-" + format_grade(gradeEnd);
		if(gradeStart == gradeEnd){
			var grade = format_grade(gradeStart);
		}
		//$("#template-title").html(subject + " " + grade);
	});
	
	
	
$(".template_search").live("click", function(event){
	var myTeach = this.id.split("_")[1];
	var myUrl = base_url + "template/search/" + myTeach;
	var form_data = {
			ajax: '1'
	};
	$.ajax({
		type:"get",
		url: myUrl,
		data: form_data,
		success: function(data){
		showPopup("Template Search", data, "auto");
	}
	});
	
});
	
	$(".template_save_continue").live('click',function(event){
		save_continue_template();
	}
			
	);//end change
	
	$('.select_template').live('click',
		function(event){
		event.preventDefault();
			var myStudent=$('#kStudent').val();
			var myTeach=$('#kTeach').val();
			var myYear = $("#year").val();
			var myTerm = $("#term").val();
			var mySubject = $("#subject").val();
			var form_data = {
					ajax: 1,
					kStudent: myStudent,
					kTeach: myTeach,
					year: myYear,
					term: myTerm,
					subject: mySubject
			};
			var myUrl = base_url + "template/show_selector";
			
			$.ajax({
				type:'post',
				url: myUrl,
				data: form_data,
				success:function(data){
					console.log($("#narrative_process").html());
				$("#ui-dialog-title-popup").html("Select Narrative Template");
				$("#popup").html(data);
			}
			});
			
		}
	);//end select_template.click
	
	
	$('.cancel_template').live('click',
		function(event){
			var kTeach=$("#kTeach").val();
			var myTerm=$('#term').val();
			var myYear=$('#year').val();
			var action = confirm("Are you sure you want to cancel? Any unsaved changes you have made will be lost!");
			if(action) {
				var really_act = confirm("This is your last chance to back out. Any unsaved changes you have made will be lost!");
				if(really_act) {
			document.location= base_url + "template/list_templates/?kTeach="+ kTeach + "&term=" + myTerm + "&year=" + myYear;
				}
			}
		}//end function(event)
	);//end click
	
	$('.delete_template').live('click',
		function(event){
			action=confirm("This will make this template inactive. It will no longer appear in any lists, but can be restored by the system administrator if desired. Do you want to continue?");
			if(action){  
				$("#isActive").val(0);
				$("#template_editor").submit();
			}//end if
		}//end function(event)
	);//end delete_template.click
	
	$(".reactivate_template").live("click",function(event){
		$("#isActive").val(1);
		$("#message").html("Template is marked to be reactiveated on the next save.");
	});
});

function save_continue_template(){
		tinyMCE.triggerSave();
		var my_action=$('#action').val();
		$("#ajax").val(1);
		var form_data = $("#template_editor").serialize();
		var myUrl = base_url + "template/" + my_action;
		$.ajax({
			dataType: "json",
			type: "POST",
			url: myUrl,
			data: form_data,
			success: function(data){
				console.log(data);
				if(my_action == "insert") {
					$("#kTemplate").val(data.kTemplate);
					$('#action').val("update");
					$("#template_editor").attr("action",base_url + "template/update");
					$("#editing-buttons .button-list").append("<li><span class='delete button delete_template'>Disable Template</span></li>");

				}
			$("#message").html(data.message).show();
			
		}//end function
		});//end ajax
		$("#ajax").val(0);
}