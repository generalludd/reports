$(document).ready(function(){
	
	$('.add_subject').live('click', function(event){
			var myTeach = $("#kTeach").val();
			var myStart = $("#gradeStart").val();
			var myEnd = $("#gradeEnd").val();
			var myUrl = base_url + "teacher/add_subject";
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
				subGradeStart: myStart,
				subGradeEnd:myEnd
		};
		console.log(form_data);
		var myUrl = base_url + "teacher/insert_subject";
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
				var myUrl = base_url + "teacher/delete_subject";
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
	

	$('.save_subject').live('click',
			function(event){
				var kTeach=$('#kTeach').val();
				$('#teacher_subject').ajaxSubmit(function(response){
					document.location="index.php?target=teacher&action_task=edit&kTeach="+kTeach;
			});
	});//end clicck
	
	
//	$('.teacher_edit').live('click',function(){
//		var myTeach=this.id.split('_')[1];
//		editTeacher(myTeach);
//		return false;
//	});
	

	
	

//	$('.teacher_create').live("click", function(event){
//		var myUrl = base_url + "teacher/create";
//		var form_data = {
//				ajax: 1
//		};
//		$.ajax({
//			type:"post",
//			url: myUrl,
//			data: form_data,
//			success: function(data){
//				showPopup("Add a new teacher",data,"auto");
//			}
//		});
//		return false;
//	});
//	
//	$(".teacher_search").live("click",function(event){
//		$.ajax({
//			type:"GET",
//			url: base_url + "teacher/show_search",
//			success: function(data){
//				showPopup("Search for Users",data,"auto");
//			}
//		});
//	});
	
});// end ready


function editTeacher(myTeach) {
	form_data = {
			kTeach: myTeach,
			ajax: '1'
	};
	$.ajax({
		type: "get",
		url: base_url + "teacher/edit",
		data: form_data,
		success: function(data){
		showPopup("Editing User", data, "auto");
	}
		
	});
	return false;

}

function get_teacher_menu(group,my_target){
	form_data = {teacher_group:group,settings:"id='kTeach'"};
	$.ajax({
		data: form_data,
		url: base_url + "teacher/teacher_menu",
		type: "get",
		success: function(menu){
			console.log(menu);
			$(my_target).html(menu);
		}
	});
}


