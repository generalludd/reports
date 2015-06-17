$(document).ready(function() {
//	if($("#narrativeEditor")) {
//		$(message).fadeOut().delay(60000);
//		saveNarrative();
//		$(message).fadeOut();
//	}

	
	
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
	
	$('.override-narrative-grade').live("click",function(event){
		val = $("#course_grade").html();
		$('.override-narrative-grade').hide();
		$("#course_grade").html("<input type='text' name='narrGrade' id='narrGrade' value='" + val + "'/>");
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
	

	
	$('.add_narrative').live('click',

		function(event) {
			if (this.id) {
				$("#kTemplate").val(this.id.split("_")[1]);
			}
			document.forms['template_selector'].submit();

		});// end new_narrative.click

	

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
