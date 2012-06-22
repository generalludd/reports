$(document).ready(function(){
	
	$('.add_subject').live('click', function(event){
			var myTeach = $("#kTeach").val();
			var myStart = $("#gradeStart").val();
			var myEnd = $("#gradeEnd").val();
			var myUrl = base_url + "index.php/teacher/add_subject";
				var form_data = {
						kTeach:myTeach,
						gradeStart: myStart,
						gradeEnd: myEnd,
						ajax: '1'
				};
				$.ajax({
				type:"GET",
				url: myUrl,
				data: form_data,
				success: function(data){
					$("#subject_list").append(data);
				}//end function success
					
				});//end ajax
				
		}//end function(event)
	);//end click
	
	$('.insert_subject').live('click',function(event){
		var myTeach = $("#kTeach").val();
		var myStart = $("#subGradeStart").val();
		var myEnd = $("#subGradeEnd").val();
		var mySubject = $("#subject").val();
		var form_data = {
				kTeach: myTeach,
				subject: mySubject,
				gradeStart: myStart,
				gradeEnd:myEnd
		};
		var myUrl = base_url + "index.php/teacher/insert_subject";
		$.ajax({
			type: "POST",
			url: myUrl,
			data: form_data,
			success: function(data){
			$("#subject_list").html(data);
		}
		});
	});

		$('.delete_subject').live('click',
			function(event){
				var mySubject = this.id.split("_")[1];
				var myTeach = $('#kTeach').val();
				var myUrl = base_url + "index.php/teacher/delete_subject";
				var form_data = {
						kSubject:mySubject,
						kTeach: myTeach
				};
				
				$.ajax({
					type: "POST",
					url: myUrl,
					data: form_data,
					success: function(data){
					$("#subject_list").html(data);
				}
			});
				
		});//end click
	
	
	$('.save_teacher').live('click',
			function(event){
				var kTeach=$('#kTeach').val();
				$('#teacher_edit').ajaxSubmit(
					function(response){
						document.location="index.php?target=teacher&action_task=view&kTeach="+kTeach;
				});//end ajaxSubmit(function(response)
			});//end click
	
	$('.save_subject').live('click',
			function(event){
				var kTeach=$('#kTeach').val();
				$('#teacher_subject').ajaxSubmit(function(response){
					document.location="index.php?target=teacher&action_task=edit&kTeach="+kTeach;
			});
	});//end clicck
	
	
	$('.teacher_edit').live('click',function(){
		var myTeach=this.id.split('_')[1];
		editTeacher(myTeach);
		return false;
	});
	
	
	$('.teacher_view').live('click',function(){
		var myTeach="";
		var myTeach=this.id.split('_')[1];
		viewTeacher(myTeach);
	});
	
	
	$('.teacher_list').live('click',function(){
		listTeachers(true,true);
	});
	
	
	$('.teacher_student_list').live('click',function(){
		var myTeach=this.id.split('_')[1];
		listTeacherStudents(myTeach);
	});
	
	
	$('.teacher_narratives_list').live('click',function(event){
		var myTeach=this.id.split('_')[1];
		listTeacherNarratives(myTeach);
	});
	
	
	$('.teacher_narratives_print').live('click',function(event){
		var myTeach=this.id.split('_')[1];
		printTeacherNarratives(myTeach);
	});
	
	
	$('.teacher_templates_print').live('click',function(event){
		var myTeach=this.id.split('_')[1];
		printTeacherTemplates(myTeach);
	});
	
	$('.teacher_create').live("click", function(event){
		var myUrl = base_url + "index.php/teacher/create";
		var form_data = {
				ajax: 1
		};
		$.ajax({
			type:"post",
			url: myUrl,
			data: form_data,
			success: function(data){
				showPopup("Add a new teacher",data,"auto");
			}
		});
		return false;
	});
	
	$(".teacher_search").live("click",function(event){
		$.ajax({
			type:"GET",
			url: base_url + "teacher/show_search",
			success: function(data){
				showPopup("Search for Users",data,"auto");
			}
		});
	});
	
});// end ready


function editTeacher(myTeach) {
	form_data = {
			kTeach: myTeach,
			ajax: '1'
	};
	var myUrl = base_url + "index.php/teacher/edit";
	
	$.ajax({
		type: "get",
		url: myUrl,
		data: form_data,
		success: function(data){
		showPopup("Editing User", data, "auto");
	}
		
	});
	return false;
//	var myTeach=this.id.split("_")[1];
//	alert(myTeach);
//	$.get('ajax.switch.php',{target:'teacher',action_task:'view',kTeach:myTeach},
//		function(data){
//			showPopup('Edit Teacher',data,10,10,'auto');
//	})
}

function viewTeacher(myTeach) {
	document.location="index.php?target=teacher&action_task=view&kTeach="+myTeach;
	return false;
}

function listTeachers(showAdmin,showInactive) {
	var url="index.php?target=teacher&action_task=list";
	if(showAdmin) {
		url=url + "&showAdmin=true";
	}
	if(showInactive) {
		url=url + "&showInactive=true";
	}
	document.location=url;
	return false;
}

function listTeacherStudents(myTeach) {
	//document.location="index.php?target=teacher&action_task=list_students&kTeach="+myTeach;
	return false;
}

function listTeacherNarratives(myTeach) {
	//document.location="index.php?target=narrative&action_task=teacher_list&kTeach="+myTeach;
	return false;
}

function printTeacherNarratives(myTeach) {
	//window.open('ajax.switch.php?target=narrative&action_task=print_teacher_narratives&kTeach='+myTeach);
	return false;
}

function printTeacherTemplates(myTeach) {
	//window.open('ajax.switch.php?target=template&action_task=print_teacher_templates&kTeach='+myTeach);
	return false;
}