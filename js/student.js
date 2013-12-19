$(document).ready(function() {

	$("#stuFirst").live("blur", function(event){
		name = $(this).val();
		nickname = $("#stuNickname").val();
		if(nickname == ""){
			$("#stuNickname").val(name);
		}
	});
	
	$("#stuEmail").live('blur', function(event) {
		var myEmail = this.value;
		var myStudent = $("#kStudent").val();
		var myUrl = base_url + "student/valid_email/";
		var form_data = {
				stuEmail:myEmail,
				kStudent: myStudent,
				validation: 1
		};
		
		$.ajax({
			url: myUrl,
			type:'POST',
			data: form_data,
			success: function(data){
				$('#valid-email').html(data);
			}
			
		}); //end ajax

	}// end function(event)
	);// end kPO.blur();
	
	$("#generate-email").live("click", function(event){
		var myStudent = $("#kStudent").val();
		var myFirst = $("#stuFirst").val();
		var my_url = base_url + "student/generate_email";
		var form_data = {
				kStudent: myStudent,
				first: myFirst,
				ajax: 1
		};
		$.ajax({
			url: my_url,
			type: 'POST',
			data: form_data,
			success: function(data){
				$("#stuEmail").val(data);
			}
		});
	});
	

	
	$("#stu-password-label").live("click",function(event){
		myPassword = $("#stuEmailPassword").val();
		if(myPassword == ""){
			myFirst = $("#stuFirst").val();
			myLast = $("#stuLast").val();
			form_data = {
					stuFirst: myFirst,
					stuLast: myLast
			};
			$.ajax({
				type: "get",
				url: base_url + "student/generate_password",
				data: form_data,
				success: function(data){
					$("#stuEmailPassword").val(data);
				}
			});
		}
	});
	
			$('#stuSearch').live('keyup', function(event) {
				var stuSearch = this.value;
				if (stuSearch.length > 2 && stuSearch != "find students") {
					searchWords = stuSearch.split(' ');
					myName = searchWords.join('%') + "%";
					var myUrl = base_url + "student/find_by_name";
					var formData = {
						ajax: 1,
						stuName: stuSearch
					};
					$.ajax({
						url: myUrl,
						type: 'GET',
						data: formData,
						success: function(data){
							//remove the search_list because we don't want to have a ton of them. 

							$("#search_list").css({"z-index": 1000}).html(data).position({
								my: "left top",
								at: "left bottom",
								of: $("#stuSearch"), 
								collision: "fit"
							}).show();
					}
					});
				}else{
					$("#search_list").hide();
		        	$("#search_list").css({"left": 0, "top": 0});


				}
			});// end stuSearch.keyup
			

			$('#stuSearch').live('focus', function(event) {
				$('#stuSearch').val('').css( {
					color : 'black'
				});
			});
			
			
			$('#stuSearch').live('blur', function(event) {
				
				$("#search_list").fadeOut();
				$('#stuSearch').css({color:'#666'}).val('find students');
				//$("#search_list").remove();
				
				
			});

				$('.view_student').live('click',
								function(event) {
									var kStudent = this.id.split("_")[1];
									document.location = base_url + "student/view/"
											+ kStudent;
								}// end function
						);// end click

				$('.add_student').live('click', function(event) {
					var formData = {
							ajax: '1'
					};
					var myUrl = base_url + "student/create/";
					$.ajax({
						url: myUrl,
						type: 'POST',
						data: formData,
						success: function(data) {
							showPopup('New Student', data, 'auto');
						}
						
					});
				}	
				);// end add_student.click

				$('.edit_student').live('click', function(event) {
					if (this.id) {
						var myStudent = this.id.split("_")[1];
					} else {
						var myStudent = $("#kStudent").val();
					}
					var formData = {
							kStudent: myStudent,
							ajax: 1
					};
					var myUrl = base_url + "student/edit/";
					$.ajax({
						url: myUrl,
						type: 'POST',
						data: formData,
						success: function(data){
						showPopup('Edit Student', data, 'auto');
					}
					});
					
					}// end function(event);
				);// end home.click

				$('.save_student').live('click', function(event) {
					saveStudent('save');
				}// end function(event)
				);// end click

				$('.cancel_student')
						.live(
								'click',
								function(event) {
									var kStudent = $("#kStudent").val();
									if (kStudent != '') {
										document.location = "index.php?target=student&action_task=view&kStudent="
												+ kStudent;
									} else {
										window.location.reload();
									}
								}// end function(event)
						); // end click

				$('.delete_student').live('click', function(event) {
					kStudent = $('#kStudent').val();
					deleteStudent(kStudent);
				}// end function(event);
				);// end home.click


			

				$("#baseYear").live('blur', function(event) {
					var baseYear = parseInt(this.value);
					var yearEnd = baseYear + 1;
					$("#baseYearEnd").val(yearEnd);
					getStuGrade();
					
				});// end keyUp

				$("#baseGrade").live('change', function(event) {
					grade = getStuGrade();
					
				   }// end function(event)
				);// end keyup
				
				$("#studentEditor").validate();
		$("#advanced_search").live('click', function(){
			studentSearch();
		});
		
		$(".password-box label").live("click",function(){
			$(".password-box .password-field").toggle('slow');
			if($(".password-box label").html() == "Show Password"){
				$(".password-box label").html("Hide Password");
			}else{
				$(".password-box label").html("Show Password");
			}
		});
		
		
		$(".edit-grade-preference").live("click", function(){
			my_id = this.id.split("_")[1];
			form_data = {
					id: my_id,
					ajax: 1
			};
			$.ajax({
				type: "get",
				url: base_url + "grade_preference/edit",
				data: form_data,
				success: function(data){
					showPopup("Edit Grade Preference", data, "auto");
				}
			});
			
		});

}// end ready
);// end $(document)




function getStuGrade() {
	myGrade = $("#baseGrade").val();
	myYear = $("#baseYear").val();
	form_data = {
		baseGrade: myGrade,
		baseYear: myYear
	};
	$.ajax({
		type: "get",
		url: base_url + "ajax/current_grade",
		data:form_data,
		success: function(data){
			text_grade = format_grade(data);
			$('#gradeText').html(text_grade);
			$('#stuGrade').val(data);
		}

	});
	
}

/*
 * function format_grade(myGrade){
	form_data = {
		grade:myGrade
	};
	var output = $.ajax({
		type:"get",
		url: base_url + "ajax/format_grade",
		data: form_data,
		async: false
	}).responseText;
	return output;
	}
*/
function saveStudent(action) {
	// $('#action_task').val(action);
	var myLocation = document.URL;
	$('#studentEditor').ajaxSubmit(function() {
		document.location = myLocation;
	});
	// document.forms['studentEditor'].submit();
}

function deleteStudent(myStudent) {
	action = confirm("You sure you want to delete this student? This cannot be undone!");
	if (action) {
		form_data = {
				kStudent: myStudent
		};
		
		var myUrl = base_url + "student/delete";
		$.ajax({
			url: myUrl,
			type: 'POST',
			data: form_data,
			success: function(data){
				values = data.split(",");
				alert(values[1]);
				if(values[0] == 1){
					document.location = base_url;
				}
		}
		});
	};
		
}

/*
 * @function studentSearchdescription:  only works with STUDENT_SEARCH_INC I don't
 * know why this isn't just embedded in that document @dependencies
 * STUDENT_SEARCH_INC
 * 
 */
function studentSearch() {
	myYear = parseInt($('#year').val());
	thisYear = new Date();
	thisYear = parseInt(thisYear.getFullYear());
	myLength = myYear.length;
	// myYear=parseInt(myObject.value);
	yearDiff = thisYear - myYear;
	if (myYear == "NaN") {
		alert(myYear + " is decidedly not a number!");
		$('#year').focus();
	} else if (yearDiff > 10) {
		yearDiff = thisYear - myYear;
		alert("This system does not have information on students " + yearDiff
				+ " years ago");
		$('#year').focus();
	} else if (yearDiff < 0) {
		yearDiff = myYear - thisYear;
		alert("Looking to the future, huh? You won't find much useful information "
				+ yearDiff + " years into the future.");
		$('#year').focus();
		document.forms[0].submit();
	} else {
		document.forms[0].submit();
	}
}