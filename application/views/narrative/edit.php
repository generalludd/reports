<?php
$teacherName = format_name($teacher->teachFirst, $teacher->teachLast);
$stuGrade = get_value($narrative, 'stuGrade', FALSE);
if(!$stuGrade){
	$stuGrade = get_current_grade($student->baseGrade, $student->baseYear);
}
$narrYear = get_value($narrative, 'narrYear', get_current_year());
$yearEnd = $narrYear + 1;
//if it is new, we are expecting the editor to have submitted a subject, otherwise
//we get the subject from the populated $narrative object from the database
//the teacher value is also submitted differently depending on the context
if($action == "insert"){
	$subjectMenu = form_dropdown('narrSubject', $subjects, $narrSubject, 'id="narrSubject"');
}else{
	$subjectMenu = form_dropdown('narrSubject', $subjects, get_value($narrative, 'narrSubject'), 'id="narrSubject"');
	$kTeach = get_value($narrative, 'kTeach');
}
$conditional_buttons = array();
$conditional_bar = FALSE;
if($hasNeeds){
	$conditional_buttons[] = array("selection" => "support", "text" => "Show Learning Support", "class" => "button show_support", "type" => "span", "id" => "need_$hasNeeds->kSupport");
}

if($hasSuggestions){
	$conditional_buttons[] = array("selection" => "suggestions", "text"=>"View Suggested Edits", "class" => "button view_edits", "type" => "span", "id" => "edit_$narrative->kNarrative");
}

if(!empty($conditional_buttons)){
	$conditional_bar = create_button_bar($conditional_buttons);
}

?>
<h3>
	Narrative for
	<?=$studentName;?>
</h3>
<form id="narrativeEditor"
	action="<?=base_url();?>index.php/narrative/<?=$action;?>"
	method="post" name="narrativeEditor">

	<?
	$edit_buttons[] = array("selection" => "save_continue", "text" => "Save &amp; Continue", "class" => "button save_continue_narrative", "type" => "span");
	$edit_buttons[] = array("selection" => "save_close", "text" => "Save &amp; Close", "class" => "button save_close_narrative", "type" => "span");
	$edit_buttons[] = array("selection" => "cancel_narrative", "text" => "Cancel", "class" => "button cancel_narrative", "type" => "span");
	if($action == "update" ){
		$edit_buttons[] = array("selection" => "delete_narrative", "text" => "Delete", "class" => "button delete delete_narrative", "type" => "span",
				"enclosure" => array("type" => "span","class"=>"delete-container"));
	}
	
	print create_button_bar($edit_buttons);
	?>

	<input type="hidden" name="target" id="target" value="narrative" /> <input
		type="hidden" name="ajax" id="ajax" value="0" /> <input type="hidden"
		name="kStudent" id="kStudent" value='<?=$student->kStudent;?>' /> <input
		type="hidden" name="kTeach" id="kTeach" value='<?=$kTeach;?>' /> <input
		type="hidden" name="kNarrative" id="kNarrative"
		value='<?=get_value($narrative, 'kNarrative'); ?>' /> <input
		type="hidden" name="action" id="action" value="<?=$action;?>"> <input
		type="hidden" name="status" id="status" value="true" /> <input
		type="hidden" name="originalText" id="originalText" />
	<div id="message" class="message"
		style="font-weight: bold; text-align: center; width: 40%; margin: 8px;">
		<?=get_value($narrative, 'timestamp');?>
	</div>

	<p>
		Teacher/Author: <b><?="$teacherName"; ?> </b> <span id="tracker"></span>
		<br /> <b>Grade In School:</b>
		<?=format_grade($stuGrade);?>
		<input type="hidden" id="stuGrade" name="stuGrade"
			value="<?=$stuGrade;?>" size="5"> &nbsp;<b>Subject:</b><span
			id="subjectMenu"><?=$subjectMenu;?> </span> &nbsp; Current Term:
		<?=get_term_menu('narrTerm', get_value($narrative,'narrTerm', get_current_term()));?>
		Year:
		<?=form_dropdown('narrYear', get_year_list(), $narrYear, "id='narrYear'");?>
		- <input id="yearEnd" type="text" name="yearEnd" readonly
			maxlength="4" size="5" value="<?=$yearEnd; ?>" />
		<?php if($stuGrade >= 5):?>
		<br />Course Grade (middle school only): <input type="text"
			name="narrGrade" id="narrGrade"
			value='<?=get_value($narrative,'narrGrade', $default_grade);?>' size="27">
		<?php endif;?>
	</p>
	<?php
	if($conditional_bar){
		echo $conditional_bar;
	} ?>

	<p>
		<textarea id="narrText" name="narrText" class="tinymce"
			style="width: 99.75%;" rows="19" cols="107">
			<?=get_value($narrative, 'narrText', $narrText);?>
        </textarea>
	</p>
</form>

<script type="text/javascript">
window.setInterval(function(){
	var narrText = $('#narrText').val();
	var action = $("#action").val();

		$("#ajax").val(1);
		var formData = $("#narrativeEditor").serialize();
		var myUrl = base_url + "index.php/narrative/" + action;
		$.ajax({
			url: myUrl,
			type: 'POST',
			data: formData,
			success: function(data){
				var strings = data.split("|");
				if(action == "insert"){
					$("#kNarrative").val(strings[0]);
					$("#action").val("update");
					$("#narrativeEditor").attr("action",base_url + "index.php/narrative/update");
					$(".delete-container").html("<span class='delete button delete_narrative' id='dn_" + strings[0] + "'>Delete</span>");
				}
				$("#message").html("Narrative auto-saved: " + strings[1]).show();
				
			},
			error: function(data){
				$("#message").html("An error occurred. Press 'Save and Continue' to save your work.").show();
			}
		});
		$("#ajax").val(0);
		//saveNarrative();
	
}, 60000);

</script>
