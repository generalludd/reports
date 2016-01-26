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
//@TODO condense the following 7 lines of code into three/four
if($hasNeeds){
	$conditional_buttons[] = array("selection" => "support", "text" => "Show Learning Support", "class" => "button show_support small", "type" => "span", "id" => "need_$hasNeeds->kSupport");
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
	action="<?=site_url("narrative/$action");?>"
	method="post" name="narrativeEditor">

	<?
	$edit_buttons[] = array("selection" => "save_continue", "text" => "Save &amp; Continue", "class" => "button save_continue_narrative", "type" => "span");
	$edit_buttons[] = array("type"=>"pass-through","text"=>"<input type='submit' class='button save_close_narrative' value='Save & Close'/>" );
	$edit_buttons[] = array("selection" => "cancel_narrative", "text" => "Cancel", "class" => "button cancel_narrative", "type" => "span");
	if($action == "update" ){
		$edit_buttons[] = array("selection" => "delete_narrative", "text" => "Delete", "class" => "button delete delete_narrative", "type" => "span",
				"enclosure" => array("type" => "span","class"=>"delete-container"));
	}

	print create_button_bar($edit_buttons,array("id"=>"editing-buttons"));
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
		</p><p> <b>Grade In School:</b>
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
		</p>
		<!-- @TODO Put calculated final grade here.  -->
		<p>Course Grade (middle school only):
			<? if($this->input->cookie("submits_report_card") && $this->input->cookie("submits_report_card") == "yes" && ! get_value($narrative,"narrGrade",FALSE)): ?>
			<span id="course_grade"><?=$default_grade;?></span>&nbsp;<a class='button small override-narrative-grade' href="#" title="override-narrative-grade allows a teacher who usually provides student grades to override the grade under special circumstances
			">Override</a>
			
			<? else: ?>
				<span id="course_grade"><input type="text" name="narrGrade" id="narrGrade" value='<?=get_value($narrative,'narrGrade', $default_grade);?>' size="27"></span>
			<?php endif;?>
		<?php endif;?>
	</p>
	<?php
	if($conditional_bar){
		echo $conditional_bar;
	} ?>

		<textarea id="narrText" name="narrText" class="tinymce"
			style="width: 99.75%;" rows="19" cols="107">
			<?=get_value($narrative, 'narrText', $narrText);?>
        </textarea>
	</p>
</form>

<script type="text/javascript">
window.setInterval(function(){
	save_continue_narrative();
}, 60000);

</script>

