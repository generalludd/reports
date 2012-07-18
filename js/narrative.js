$(document).ready(function() {
//	if($("#narrativeEditor")) {
//		$(message).fadeOut().delay(60000);
//		saveNarrative();
//		$(message).fadeOut();
//	}
	$('.view_narratives').live('click', function(event) {
				if (this.id) {
					var kStudent = this.id.split("_")[1];
				} else {
					var kStudent = $("#kStudent").val();
				}
				document.location = base_url +  "narrative/student_list/"
						+ kStudent;
			}// end function(event);
	);// end home.click
	

	$('.edit_narrative').live('click', function(event) {
		var myNarrative = this.id.split('_')[1];
		editNarrative(myNarrative);
	}// end function(event)
	);// end edit_narrative.click
	
	
	$('.edit_narrative_inline').live('click', function(event){
		//var action = confirm("Are you sure you want to edit this here? This provides limited editing here and provides text only in raw html.");
		//if(action) {
			var myTeach = $("#kTeach").val();
			var myNarrative = this.id.split('_')[1];
			
			var form_data = {
					kNarrative: myNarrative,
					kTeach: myTeach,
			};
			var myUrl = base_url + "narrative/edit_inline";

			$.ajax({
				url: myUrl,
				type: 'POST',
				data: form_data,
				success: function(data){
						$("#text_" + myNarrative).html(data);
						$("#enil_" + myNarrative).html("Save").addClass("new").removeClass("edit").removeClass("edit_narrative_inline").addClass("save_narrative_inline");
						document.location = document.location + "enil_" + myNarrative;
				} //end function
			});//end ajax

		//}//end if

	}); //end edit_narrative_inline
		
	$('.save_narrative_inline').live('click', function(event){
			var myNarrative = $("#kNarrative").val();
			var myTeach = $("#kTeach").val();
			var myText = $("#narrText").val();
			
			var form_data = {
					kNarrative: myNarrative,
					narrText: myText,
					kTeach: myTeach
			};
			var myUrl = base_url + "narrative/update_inline";
			$.ajax({
				url: myUrl,
				type: 'POST',
				data: form_data,
				success: function(data){
					output = data.split("||");
					$("#text_" + myNarrative).html(output[0]);
					$("#time_" + myNarrative).html(output[1]);
					$("#enil_" + myNarrative).html("Edit Inline").addClass("edit").removeClass("new").removeClass("save_narrative_inline").addClass("edit_narrative_inline").focus();
				}// end success-function
			}); //end ajax
			
	});//end edit_narrative_inplace
	

	$('.grade_edit').live("click",function(event){
		var myId = this.id.split("_")[1];
		var myValue = $("#ngtext_" + myId).html();
		$("#ngtext_" + myId).html("<input type='text' id='grade_" + myId + "' value='" + myValue + "'/>");
		$(this).html("Save").removeClass("grade_edit").addClass("grade_save").removeClass("edit").removeClass("new");
	});
	
	$('.grade_save').live("click",function(event){
		var myId = this.id.split("_")[1];
		var myValue = $("#grade_" + myId).val();
		var myUrl = base_url + "narrative/update_grade";
		form_data = {
				kNarrative: myId,
				narrGrade: myValue
		};
		$(this).html("Edit Grade").addClass("edit").removeClass("grade_save").addClass("grade_edit");
		$.ajax({
			type:"POST",
			url: myUrl,
			data: form_data,
			success: function(data){
				$("#ngtext_" + myId).html(data);//expecting the grade returned. 
			}
		});
		
	});
	
	$('.teacher_narrative_search').live("click", function(event){
		myTeach =  this.id.split("_")[1];

		$.ajax({
			type:"POST",
			url: base_url + "narrative/search_teacher_narratives/"+ myTeach,
			success:function(data){
				showPopup("Search Teacher Narratives",data,"auto");
			}
		});
		
		
	});
	
	
	$('.missing_narrative_search').live('click', function(event){
		 myTeach = this.id.split("_")[1];
		 myUrl = base_url + "narrative/search_missing";
		form_data = {
				kTeach: myTeach,
				ajax: 1
		};
		$.ajax({
			url: myUrl,
			type: 'GET',
			data: form_data,
			success: function(data){
			showPopup("Search for Missing Narratives", data, 'auto');
		}
		});
	});

	$('#narrSubject').live('change', function(event) {
		var narrSubject = this.value;
	}// end function(event);
	);// end change

	$('.view_narrative').live('click', function(event) {
			var myNarrative = this.id.split("_")[1];
			document.location = base_url + 'narrative/view/' + myNarrative;
		}// end function(event)
	);// end view_narrative.click

	$('.suggest_edits').live('click', function() {
			var myNarrative = this.id.split('_')[1];
			
			document.location = "index.php?target=narrative&action_task=suggest_edits&kNarrative="
				+ myNarrative;
	}); // end suggest_edits

	
	$('.select_narrative_type').live('click', function(event) {
			var userRole = $('#userRole').val();
			var defaultTerm = $('#defaultTerm').val();
			var defaultYear = $('#defaultYear').val();
			var myStudent = this.id.split("_")[1];
			 myTeach = $("#userID").val();
			
			form_data = {
					kTeach: myTeach,
					kStudent: myStudent,
					narrYear: defaultYear,
					narrTerm: defaultTerm,
					ajax: 1
			};
			 myUrl = base_url + "narrative/select_type";
			$.ajax({
				url: myUrl,
				type: 'POST',
				data: form_data,
				success: function(data){
				showPopup('Select Narrative Type',
						data, 'auto');
			}
			});
	});// end select_narrative_type

	$('.list_student_narratives').live('click', function(event) {
			var kStudent = $('#kStudent').val();
			var kTeach = $('#kTeach').val();
			document.location = base_url + 'narrative/student_list/' + kStudent;
	});

	$('.list_teacher_narratives').live('click',function(event) {
			var myTeacher = this.id.split('_')[1];
			document.location = 'index.php?target=narrative&action_task=teacher_list&' + myTeacher;
	});

	$('.print_teacher_narratives').live('click', function(event) {
				 myTeach = this.id.split('_')[1];
				// var myWindow=new Window();
			// myWindow.document="ajax.switch.php?target=narrative&action_task=print_teacher_narratives&kTeach="
			// + myTeach;
	});

	$('.narrative_print_select').live('click', function(event){
		var myStudent = $("#kStudent").val();
		var myTerm = this.id.split("_")[1];
		var myYear = this.id.split("_")[2];
		form_data = {
				kStudent: myStudent,
				narrTerm: myTerm,
				narrYear: myYear,
				ajax: 1
		};
		 myUrl = base_url + "narrative/student_print";
		$.ajax({
			url: myUrl,
			type: 'POST',
			data: form_data,
			success: function(data){
			showPopup("Select Narrative Report", data, 'auto');
		}
		});
	
	});
	
	
	$('.print_narrative').live('click', function(event) {
		document.forms['narrativePrintSelect'].submit();
	});// end click

	$('.narrative_change_sort').live('click', function(event){
		var myStudent = $("#kStudent").val();
		var myTerm = this.id.split("_")[1];
		var myYear = this.id.split("_")[2];
		form_data = {
				kStudent: myStudent,
				narrTerm: myTerm,
				narrYear: myYear
		};
		 myUrl = base_url + "narrative/show_sorter/";
		$.ajax({
			url: myUrl,	
			type: 'POST',
			data: form_data,
			success: function(data){
			showPopup('Editing Subject Sort Order',data,'auto' );
		}
		});
	});

	$('.save_close_narrative').live("click",function(event) {
		var narrText = $('#narrText').val();
		if (narrText == "") {
			alert("You haven't entered any text in the narrative yet. No need to save.");
		} else {
			$('#save_action').val('view');
			document.forms['narrativeEditor'].submit();
		}
	});// end function(event) end click

	
	$('.save_continue_narrative').live('click', function(event) {
		var narrText = $('#narrText').val();
		$("#originalText").val(narrText);
		var action = $("#action").val();
		if (narrText == "") {
			alert("You haven't entered any text in the narrative yet. No need to save.");
		} else {
			$("#ajax").val(1);
			var formData = $("#narrativeEditor").serialize();
			 myUrl = base_url + "narrative/" + action;
			$.ajax({
				url: myUrl,
				type: 'POST',
				data: formData,
				success: function(data){
					var strings = data.split("|");
					if(action == "insert"){
						$("#kNarrative").val(strings[0]);
						$("#action").val("update");
						$("#narrativeEditor").attr("action",base_url + "narrative/update");
						$(".delete-container").html("<span class='delete button delete_narrative' id='dn_" + strings[0] + "'>Delete</span>");
					}
					$("#message").html("Narrative last updated " + strings[1]).show();
					
				},
				error: function(data){
					$("#message").html("An error occurred.").show();
				}
			});
			$("#ajax").val(0);
			//saveNarrative();
		}
	}); // end function(event)
	

	$('.save_edits').live('click',function(event) {
			var originalText = $('#originalText').val();
			var narrText = $('#narrText').val();
			if (narrText == originalText) {
				alert("You have not made any changes to the original document. No need to save.");
			} else {
				$('#save_action').val('view');
				document.forms['narrativeEditor'].submit();
			}
	});

	
	$('.save_continue_edits').live('click', function(event) {
		var myText = $('#narrText').val();
		var myNarrative = $('#kNarrative').val();
		$.post('ajax.switch.php', {
			target : 'narrative',
			action_task : 'save_edits',
			save_action : 'edit',
			narrText : myText,
			kNarrative : myNarrative
		}, function(data) {
			var message = data;
			$('.message').html(message).fadeIn('slow');
			$('#status').val(true);

		});// end post
	}); // end save_continue_edits

	$('.delete_narrative').live('click', function(event) {
		var kNarrative = $("#kNarrative").val();
		var kStudent = $("#kStudent").val();
		if(kNarrative == ""){
			kNarrative = this.id.split("_")[1];
		}
		delete_narrative(kNarrative, kStudent);
	}); // end function(event)

	
	$('.cancel_narrative').live('click',function(event) {
		var myNarrative = $("#kNarrative").val();
		var narrText = $('#narrText').val();
		var myStudent = $("#kStudent").val();
		action = confirm("You sure you want to cancel? Any changes you have made will not be saved.\r(Some day I'll be able to tell if you have actually made any changes since the last save.)");
		if (action) {
			if (myNarrative != "") {
				document.location = base_url + "narrative/view/" + myNarrative;
			} else {
				document.location = base_url + "narrative/student_list/"
						+ myStudent;
			}
		}
	});
	
	$("#teacher_narratives_search #kTeach").live("change",function(event){
		myTeach = $(this).val();
		$.ajax({
			type:"GET",
			data: {kTeach:myTeach},
			url: base_url + "teacher/subject_menu",
			success: function(data){
				$("#teacher_narratives_search #subject_menu").html(data);
			}
		});
		
		$.ajax({
			type:"GET",
			data: {kTeach:myTeach},
			url: base_url + "teacher/grade_range",
			success: function(data){
				$("#teacher_narratives_search #grade_range").html(data);
			}
		});
	});
	

	/*
 	$('#kTeach').live('change', function(event) {
		var myTeach = $('#kTeach').val();
		$.get('ajax.switch.php', {
			target : 'teacher',
			action_task : 'teacher_menu',
			kTeach : myTeach,
			id : 'narrSubject'
		}, function(data) {
			$('#subjectMenu').html(data);
		});
	});
*/
	
	$('.add_narrative').live('click',

		function(event) {
			if (this.id) {
				$("#kTemplate").val(this.id.split("_")[1]);
			}
			document.forms['template_selector'].submit();

		});// end new_narrative.click



	
	$('.view_edits').live('click',function() {
		 form_data = {
				kNarrative: $('#kNarrative').val()
		};
		 myUrl = base_url + "suggestion/view";
		$.get('ajax.switch.php', {
			target : 'narrative',
			action_task : "show_edits",
			kNarrative : myNarrative
		}, function(data) {
			showSidebar('Proposed Changes', data,
					'95%', '60%', '35%');
			$('#sidebarContainer').animate( {
				top : '25%'
			});
		});// end get
	});// end show_edits
		
	
	$(".delete_edits").live('click', function(){
			var myNarrative = $("#kNarrative").val();
			action = confirm("Are you sure you want to delete these edits? This cannot be undone!");
			if(action) {
				second_action = confirm("Are you really sure? There is no going back here except to redo your edits. (The original narrative is not affected)");
				if(second_action) {
					$.post('ajax.switch.php', 
					 {
						target: 'narrative',
						action_task: "delete_suggestions",
						kNarrative: myNarrative
					 }, function(data){
						 document.location = "index.php?target=narrative&action_task=view&kNarrative=" + myNarrative;
					 });
				}
			}
		});

	
	$('.suggest_phrases').live('click',function(event) {
		alert("Due to quirks in the suggested-phrase system, this feature is currently disabled. Thank you for your patience and continued patronage while we (I) work on this problem!");
		return false;
		var myTeacher = $('#kTeach').val();
		var mySubject = $('#narrSubject').val();
		var myStudent = $('#kStudent').val();
		$.get('ajax.switch.php', {
			target : 'template',
			action_task : 'suggest_phrases',
			kStudent : myStudent,
			subject : mySubject
		}, function(data) {
			showSidebar('Suggested Phrases',
					data, '95%', '65%', '25%');
	
		});// end get
	});// end suggest_phrases.click

	$('.return_to_narrative').live('click', function(event) {
		var myNarrative = this.id.split('_')[1];
		editNarrative(myNarrative);
	}); // end function(event)

	$(".draggable").draggable(function() {
		$("#narrText").fadeIn();
	});

	$("#narrText").droppable( {
		drop : function(event, ui) {
			var existingValue = $("#narrText").val();
			textValue = ui.draggable.text();
			$(this).val(existingValue + " " + textValue);
			ui.draggable.fadeOut();
		}
	});

	$('#narrYear').live('change', function() {
		var myYear = parseInt($('#narrYear').val());
		var nextYear = myYear + 1;
		$('#yearEnd').val(nextYear);
		});
	
	}// end function()
	);// end document.ready



function editNarrative(myNarrative) {
	document.location = base_url + "narrative/edit/"+ myNarrative;
	return false;
}
/*
 * @function mergeSentences @params myCount intdescription:  used in the
 * sentence-display format of narrative reports to count the number of sentences
 * (fields). @dependencies form_submission.js:saveData()
 */
function mergeSentences(myCount) {
	// repeat through a predictable set of fields and copy their values to a
	// final container
	var myText = "";
	for ( var i = 0; i < myCount; i++) {
		if (document.getElementById("line" + i)) {
			myText = myText + " " + document.getElementById("line" + i).value;
		}
	}
	if (myText != "") {
		narrText = document.getElementById('narrText');
		narrText.value = myText;
	}
}

/*
 * @function deleteNarrative @params kNarrative int database keydescription: 
 * delete a narrative from the database (note that the narrative is removed from
 * active use and stored in a backups database. See narrative_edit.php for
 * details. @dependencies NARRATIVE_EDIT_INC
 */
function delete_narrative(myNarrative, myStudent) {
	action = confirm("You sure you want to delete this?");
	if (action) {
		form_data = {
			kNarrative : myNarrative,
			kStudent : myStudent
		};
		myUrl = base_url + "narrative/delete";
		$.ajax({
			url: myUrl,
			type: 'post',
			data: form_data,
			success: function(data) {
			alert(data);
			document.location = base_url + "narrative/student_list/" + myStudent;
		}
		});
	}//end if
}
