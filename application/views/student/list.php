<?php
$current_grade = NULL;
$current_teacher = NULL;
$humanities_teacher = NULL;
$default_row_style = array (
		"student-row",
		"row" 
);
?>
<div class='student_list'>


	<?php
	
	foreach ( $students as $student ) :
		$student_style = array (
				"student-name" 
		);
		$stuGrade = get_value ( $student, "stuGrade", get_current_grade ( $student->baseGrade, $student->baseYear, get_current_year () ) );
		$enrolled = "";
		$row_style = $default_row_style;
		if ($student->isEnrolled == 0) {
			// $student_style[] = "disabled";
			$row_style ["tag"] = "disabled";
			$enrolled = "<span>(Not Enrolled)</span>";
			
			if ($student->isGraduate == 1) {
				$row_style ["tag"] = "highlight";
				$enrolled = "(Alumna)";
				if ($student->stuGender == "M") {
					$enrolled = "(Alumnus)";
				}
			}
		}
		$name = format_name ( $student->stuFirst, $student->stuLast, $student->stuNickname );
		if (array_key_exists ( "kTeach", $criteria )) :
			if ($current_teacher != $student->teacherName) :
				?>
	<h3 class='teacher_row'>
		<?="Students of $student->teacherName ($student->teachClass)";?>
	</h3>

	<?
				$current_teacher = $student->teacherName;
			
			
	endif;
		
		
	endif;
		if (array_key_exists ( "humanitiesTeacher", $criteria )) :
			if ($humanities_teacher != $student->humanitiesTeacher) :
				?>
		<h3 class='teacher_row'>
			<?="$student->humanitiesTeacher's Humanities Class"?>
		</h3>
	
		<?
				$humanities_teacher = $student->humanitiesTeacher;
			
			
		endif;
		
		
		endif;
		if ($current_grade != $stuGrade) :
			?>

	<h4 class='grade_row'>
		Grade
		<?= format_grade($stuGrade)?>
	</h4>
	<?
			
			$current_grade = $stuGrade;
		
		
	endif;
		?>
	<div class='<?=implode(" ",$row_style);?>'>
		<div class='<?=implode(" ", $student_style);?>'>
			<a href=<?=site_url("student/view/$student->kStudent");?> class='link'><?="$name";?></a>
			<?=$enrolled;?>
			<? if(get_value($student,"stuEmailPermission") == 1): ?>
			<? echo "&nbsp;" . format_email($student->stuEmail);?>
			<? endif;?>
			<? if(get_value($student,"stuGroup") == "A"|| get_value($student,"stuGroup") == "B"):?>
			<? echo "&nbsp;Group: $student->stuGroup";?>
			<? endif;?>
		</div>
		<?
		$this->load->view("student/navigation",array("student"=>$student, "kStudent"=>$student->kStudent, "style"=>"mini-buttons"));
		?>
	</div>
	<? endforeach; ?>
</div>
