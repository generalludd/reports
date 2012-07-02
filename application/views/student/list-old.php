<?php 

$current_grade = NULL;
$current_teacher = NULL;
?>
<table class='student_list'>
<?php

foreach($students as $student):
	$name = format_name($student->stuFirst,$student->stuLast,$student->stuNickname);
	if(get_value($student,"teachFirst",FALSE)):
        if($current_teacher != $student->kTeach):
?>
	<tr>
		<td colspan="3">
		<h3><?="Students of $student->teachFirst $student->teachLast"?></h3>
		</td>
	</tr>
	<?
	$current_teacher = $student->kTeach;
	   endif;
	endif;
	if($current_grade != $student->stuGrade): ?>
	<tr>
		<td colspan="3">
		<h4>Grade <?= format_grade($student->stuGrade)?></h4>
		</td>
	</tr>
	<?  $current_grade = $student->stuGrade;
	endif; ?>
	<tr>
		<td colspan='3' class='studentName'><strong><?=$name?></strong></td>
	</tr>
	<tr>
		<td class='student_list'><span class='view_narratives button'
			id='n_<?=$student->kStudent?>'>Narratives</span></td>
		<td class='student_list'><span class='list_attendance button'
			id='a_<?=$student->kStudent?>'>Attendance</span></td>
		<td class='student_list'><span class='view_student button'
			id='s_<?=$student->kStudent?>'>Info</span></td>
	</tr>
	<? endforeach; ?>
</table>

