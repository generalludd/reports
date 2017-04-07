
$('.cancel_form').live('click',
		function(event){
			var kTeach=$("#kTeach").val();
			var myTerm=$('#term').val();
			var myYear=$('#year').val();
			var mySubject = $("#subject").val();
			var gradeStart = $("#gradeStart").val();
			var gradeEnd = $("#gradeEnd").val();
			var action = confirm("Are you sure you want to cancel? Any unsaved changes you have made will be lost!");
			if(action) {
				var really_act = confirm("This is your last chance to back out. Any unsaved changes you have made will be lost!");
				if(really_act) {
			document.location= base_url + "overview/view/?kTeach="+ kTeach + "&term=" + myTerm + "&year=" + myYear + "&subject=" + mySubject + "&gradeStart=" + gradeStart + "&gradeEnd=" + gradeEnd;
				}
			}
		}//end function(event)
	);//end click

$(".delete_record").live("click", 
	function(event){
	var action =confirm("Are you sure you want to delete this? This cannot be undone!");
		if(action){
				my_form = $(this).parents("form").attr("id");
				my_url = $("#" + my_form).attr("action") + "?delete=TRUE"; //tell the update script to delete the record instead
				$("#" + my_form).attr("action",my_url);
				
				$("#" + my_form).submit();
		}
	}
);

$("#activation-button").live("click",function(event){
	me = $(this);
	if(me.hasClass("deactivate")){
		me.removeClass("deactivate").addClass("activate").addClass("edit").html("Activate Overview");
		$("#isActive").val(0);
	}else if(me.hasClass("activate")){
		me.addClass("deactivate").removeClass("activate").removeClass("edit").html("Deactivate Overview");;
		$("#isActive").val(1);
	}
});

function save_continue_overview(){
	tinyMCE.triggerSave();
	var my_action=$('#action').val();
	$("#ajax").val(1);
	var form_data = $("#overview_editor").serialize();
	var myUrl = base_url + "overview/" + my_action;
	$.ajax({
		dataType: "json",
		type: "POST",
		url: myUrl,
		data: form_data,
		success: function(data){
			console.log(data);
			if(my_action == "insert") {
				$("#kOverview").val(data.kOverview);
				$('#action').val("update");
				$("#overview_editor").attr("action",base_url + "overview/update");
			}

		$("#message").html(data.message).show();
		
		},//end function
		error: function(data){
			console.log(data);
		}
	});//end ajax
	$("#ajax").val(0);
}