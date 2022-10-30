<?php
$current_grade = NULL;
$current_teacher = NULL;
$humanities_teacher = NULL;
$default_row_style = array (
		"student-row",
		"row" 
);
if(empty($criteria)){
    $criteria = [];
}
?>
<!-- student/list.php -->
<div class='student_list'>


<?php

foreach ( $students as $student ) :
	
	$student_style = array (
			"student-name" 
	);
	
	$stuGrade = get_value ( $student, "stuGrade", get_current_grade ( $student->baseGrade, $student->baseYear, get_current_year () ) );
	$enrolled = "";
	$row_style = $default_row_style;
	if ($student->isEnrolled == 0) :
		$row_style ["tag"] = "disabled";
		$enrolled = "<span>(Not Enrolled)</span>";
		
		if ($student->isGraduate == 1) :
			$row_style ["tag"] = "highlight";
			$enrolled = "(Alumn)";
		
				
			endif;
	
		endif;
	$name = format_name ( $student->stuFirst, $student->stuLast, $student->stuNickname );
	if (array_key_exists ( "kTeach", $criteria )) :
		if ($current_teacher != $student->teacherName) :
			?>
				<!-- teacher_row -->
	<h3 class='teacher_row'>
		<?php echo "Students of $student->teacherName ($student->teachClass)";?>
	</h3>

	<?php $current_teacher = $student->teacherName; ?>
	<?php endif;?>
			
	<?php endif; ?>
	<?php 	if (array_key_exists ( "humanitiesTeacher", $criteria )) : ?>
	<?php 		if ($humanities_teacher != $student->humanitiesTeacher) : ?>
				
		<h3 class='teacher_row'>
			<?php echo "$student->humanitiesTeacher's Humanities Class"?>
		</h3>
	
		<?php
			$humanities_teacher = $student->humanitiesTeacher;
			?>
			
			
		<?php endif; ?>
		
		
		<?php endif; ?>
		<?php if ($current_grade != $stuGrade && array_key_exists("grouping",$criteria)): ?>

	<h4 class='grade_row'>
		Grade
		<?php echo format_grade($stuGrade)?>
	</h4>
	<?php	$current_grade = $stuGrade; ?>
		
		
	<?php endif;?>
	<div class='<?php  echo implode(" ",$row_style);?>'>
		<div class='<?php  echo implode(" ", $student_style);?>'>
			<a href=<?php  echo site_url("student/view/$student->kStudent");?> class='link'><?php  echo "$name";?></a>
			<?php  echo $enrolled;?>
			<?php if(get_value($student,"stuEmailPermission") == 1): ?>
			<?php echo "&nbsp;" . format_email($student->stuEmail);?>
			<?php endif;?>
						<?php if(!array_key_exists("grouping",$criteria)):?>
			Grade: <?php echo $stuGrade;?>
			<?php endif;?>
			<?php if(get_value($student,"stuGroup") == "A"|| get_value($student,"stuGroup") == "B"):?>
			<?php echo "&nbsp;Group: $student->stuGroup";?>
			<?php endif;?>

		</div>
		<?php 
	$this->load->view ( "student/navigation", array (
			"student" => $student,
			"kStudent" => $student->kStudent,
			"style" => "mini-buttons" 
	) );
	?>
	</div>
	<?php endforeach; ?>
</div>
