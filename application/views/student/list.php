<?php

$current_grade = NULL;
$current_teacher = NULL;
?>
<div class='student_list'>


	<?php

	foreach($students as $student):
	$student_style = "studentName";
	$enrolled = "";
	$row_style = "";
	if($student->isEnrolled == 0){
		$student_style = "$student_style highlight-text";
		$row_style = " highlight";
		$enrolled = "(Not Enrolled)";
	}
	$name = format_name($student->stuFirst,$student->stuLast,$student->stuNickname);
	if(get_value($student,"teachFirst",FALSE)):
	if($current_teacher != $student->kTeach):
	?>
	<h3 class='teacher_row'>
		<?="Students of $student->teachFirst $student->teachLast"?>
	</h3>

	<?
	$current_teacher = $student->kTeach;
	endif;
	endif;
	if($current_grade != $student->stuGrade): ?>

	<h4 class='grade_row'>
		Grade
		<?= format_grade($student->stuGrade)?>
	</h4>
	<?  $current_grade = $student->stuGrade;
	endif; ?>
	<div class='student-row row<?=$row_style;?>'>
		<div class='<?=$student_style;?>'>
			<a href=<?=site_url("student/view/$student->kStudent");?>
				class='link'><?="$name";?> </a>
			<?=$enrolled;?>
			<? if(get_value($student,"stuEmailPermission") == 1): ?>
			<? echo "&nbsp;" . format_email($student->stuEmail);?>
			<? endif;?>
		</div>
		<?
		$buttons = array();
		$buttons[] = array("item"=>"student", "href" => site_url("narrative/student_list/$student->kStudent"), "class" => "button", "text" =>"Narratives");
		$buttons[] = array("item" => "attendance", "href" => site_url("attendance/search/$student->kStudent"), "class" => "button", "text" => "Attendance" );
		$buttons[] = array("item" => "support","href" => site_url("support/list_all/$student->kStudent"), "class" => "button", "text" => "Learning Support");
		if($student->stuGrade >=5) {
			$buttons[] = array("item" => "report", "href" => site_url("report/get_list/student/$student->kStudent"), "class" => "button","text" => sprintf("%ss",STUDENT_REPORT));
			$buttons[] = array("item" => "report","href" => site_url("report/create/$student->kStudent"), "text" => sprintf("Add %s",STUDENT_REPORT), "class" => "button new report-add",
					"id" => sprintf("add-report_%s", $student->kStudent));
		}
		$options["class"] = "mini-buttons";
		$button_bar = create_button_bar($buttons,$options);
		echo $button_bar;
		?>
	</div>
	<? endforeach; ?>
</div>
