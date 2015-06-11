<?php
$studentName = format_name($student->stuFirst, $student->stuLast, $student->stuNickname, 'highlight');
$teacherName = format_name($teacher->teachFirst, $teacher->teachLast);
$stuGrade = get_value($narrative, 'stuGrade', FALSE);

if(!$stuGrade){
	$stuGrade = get_current_grade($student->baseGrade, $student->baseYear);
}
$yearEnd = $narrative->narrYear + 1;

$conditional_buttons = array();
$conditional_bar = FALSE;
if($hasNeeds){
	$conditional_buttons[] = array("selection" => "support", "text" => "Show Learning Support", "class" => "button show_support", "type" => "span", "id" => "need_$hasNeeds->kSupport");
}

if(!empty($conditional_buttons)){
	$conditional_bar = create_button_bar($conditional_buttons);
}

?>
<h3>
	Narrative Suggestion Edits for
	<?=$studentName;?>
</h3>
<form id="narrativeEditor"
	action="<?=site_url("suggestion/$action");?>"
	method="post" name="narrativeEditor">

	<?
	$edit_buttons[] = array("selection" => "save_continue", "text" => "Save &amp; Continue Suggestions", "class" => "button save_continue_suggestion", "type" => "span");
	$edit_buttons[] = array("selection" => "save_close", "text" => "Save &amp; Close Suggestions", "class" => "button save_close_suggestion", "type" => "span");
	$edit_buttons[] = array("selection" => "cancel_narrative", "text" => "Cancel Suggestions", "class" => "button cancel_suggestion", "type" => "span");
	if($action == "update" ){
		$edit_buttons[] = array("selection" => "delete_narrative", "text" => "Delete Suggestions", "class" => "button delete delete_narrative", "type" => "span",
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
		type="hidden" name="action" id="action" value="<?=$action;?>">
	<div id="message" class="message"
		style="font-weight: bold; text-align: center; width: 40%; margin: 8px;">
		<?=get_value($narrative, 'timestamp');?>
	</div>

	<p>
		Teacher/Author: <b><?="$teacherName"; ?> </b> <span id="tracker"></span>
		<br /> <b>Grade In School:</b>
		<?=format_grade($stuGrade);?>
		<input type="hidden" id="stuGrade" name="stuGrade" value="<?=$stuGrade;?>" size="5">
		&nbsp;<b>Subject:</b>
			<input id="narrSubject" type="text" name="narrSubject" readonly value="<?=$narrative->narrSubject?>"/>
			&nbsp;Current Term:
		<?=get_value($narrative,'narrTerm');?>
		Year:
		<input id="narrYear" type="text" name="narrYear" readonly
			maxlength="4" size="5" value="<?=$narrative->narrYear?>"/>
		- <input id="yearEnd" type="text" name="yearEnd" readonly
			maxlength="4" size="5" value="<?=$yearEnd; ?>" />

	</p>
	<?php
	if($conditional_bar){
		echo $conditional_bar;
	} ?>

	<p>
		<textarea id="narrText" name="narrText" class="tinymce"
			style="width: 99.75%;" rows="19" cols="107">
			<?=get_value($narrative, 'narrText');?>
        </textarea>
	</p>
</form>

<script type="text/javascript">
/*window.setInterval(function(){
	var narrText = $('#narrText').val();
	var action = $("#action").val();

		$("#ajax").val(1);
		var formData = $("#narrativeEditor").serialize();
		var myUrl = base_url + "narrative/" + action;
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
				$("#message").html("Narrative auto-saved: " + strings[1]).show();

			},
			error: function(data){
				$("#message").html("An error occurred. Press 'Save and Continue' to save your work.").show();
			}
		});
		$("#ajax").val(0);
		//saveNarrative();

}, 60000);
*/
</script>
