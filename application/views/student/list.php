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
				class='link'><?="$name";?></a>
				<?=$enrolled;?>
				<? if(get_value($student,"stuEmailPermission") == 1): ?>
				<? echo "&nbsp;" . format_email($student->stuEmail);?>
				<? endif;?>
		</div>
		<div class='button-box'>
		 <a href=<?=site_url("narrative/student_list/$student->kStudent");?> class='button'>Narratives</a>
		<a href=<?=site_url("attendance/search/$student->kStudent");?> class='button'>Attendance</a>
		<a href=<?=site_url("support/list_all/$student->kStudent");?> class='button'>Learning Support</a>
		</div>
	</div>
	<? endforeach; ?>
</div>
