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
		$today = date ( "Y-m-d" );
		if ($today < MID_YEAR) {
			$start_date = YEAR_START;
		} else {
			$start_date = MID_YEAR;
		}
		$buttons = array ();
		$buttons [] = array (
				"selection" => "narrative",
				"href" => site_url ( "narrative/student_list/$student->kStudent" ),
				"class" => "button",
				"text" => "Narratives" 
		);
		$buttons [] = array (
				"selection" => "attendance",
				"href" => site_url ( "attendance/search/$student->kStudent?startDate=$start_date&endDate=$today" ),
				"class" => "button",
				"text" => "Attendance" 
		);
		$buttons [] = array (
				"selection" => "attendance",
				"href" => site_url ( "attendance/create/$student->kStudent" ),
				"class" => "button dialog new",
				"text" => "Add Attendance" 
		);
		$buttons [] = array (
				"selection" => "support",
				"href" => site_url ( "support/list_all/$student->kStudent" ),
				"class" => "button",
				"text" => "Learning Support" 
		);
		if ($stuGrade >= 5) {
			$buttons [] = array (
					"selection" => "report",
					"href" => site_url ( "report/get_list/student/$student->kStudent" ),
					"class" => "button",
					"text" => sprintf ( "%ss", STUDENT_REPORT ) 
			);
			$buttons [] = array (
					"selection" => "report",
					"href" => site_url ( "report/create/$student->kStudent" ),
					"text" => sprintf ( "Add %s", STUDENT_REPORT ),
					"class" => "button new dialog",
					"id" => sprintf ( "add-report_%s", $student->kStudent ) 
			);
			$buttons [] = array (
					"selection" => "grade/report_card",
					"class" => "button dialog",
					"id" => sprintf ( "gss_%s", $student->kStudent ),
					"text" => "Grades",
					"href" => site_url ( "grade/select_report_card/$student->kStudent" ) 
			);
		}
		$options ["class"] = "mini-buttons";
		$button_bar = create_button_bar ( $buttons, $options );
		echo $button_bar;
		?>
	</div>
	<? endforeach; ?>
</div>
