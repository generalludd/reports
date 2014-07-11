<?php

$current_grade = NULL;
$current_teacher = NULL;
$default_row_style = array("student-row","row");
?>
<div class='student_list'>


	<?php

	foreach($students as $student):
	$student_style = array("student-name");
	$stuGrade = get_value($student,"stuGrade",get_current_grade($student->baseGrade, $student->baseYear, get_current_year()));
	$enrolled = "";
	$row_style = $default_row_style;
	if($student->isEnrolled == 0){
		$student_style[] = "disabled";
		$row_style["tag"] = "highlight";
		$enrolled = "<span class=''>(Not Enrolled)</span>";

		if($student->isGraduate == 1){
		    $row_style["tag"] = "highlight";
		    $enrolled = "(Alumna)";
		    if($student->stuGender == "M"){
		        $enrolled = "(Alumnus)";
		    }

		}
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
	if($current_grade != $stuGrade): ?>

	<h4 class='grade_row'>
		Grade
		<?= format_grade($stuGrade)?>
	</h4>
	<?  $current_grade = $stuGrade;
	endif; ?>
	<div class='<?=implode(" ",$row_style);?>'>
		<div class='<?=implode(" ", $student_style);?>'>
			<a href=<?=site_url("student/view/$student->kStudent");?>
				class='link'><?="$name";?> </a>
			<?=$enrolled;?>
			<? if(get_value($student,"stuEmailPermission") == 1): ?>
			<? echo "&nbsp;" . format_email($student->stuEmail);?>
			<? endif;?>
		</div>
		<?
		$buttons = array();
		$buttons[] = array("selection"=>"narrative", "href" => site_url("narrative/student_list/$student->kStudent"), "class" => "button", "text" =>"Narratives");
		$buttons[] = array("selection" => "attendance", "href" => site_url("attendance/search/$student->kStudent"), "class" => "button", "text" => "Attendance" );
		$buttons[] = array("selection" => "support","href" => site_url("support/list_all/$student->kStudent"), "class" => "button", "text" => "Learning Support");
		if($stuGrade >=5) {
			$buttons[] = array("selection" => "report", "href" => site_url("report/get_list/student/$student->kStudent"), "class" => "button","text" => sprintf("%ss",STUDENT_REPORT));
			$buttons[] = array("selection" => "report","href" => site_url("report/create/$student->kStudent"), "text" => sprintf("Add %s",STUDENT_REPORT), "class" => "button new report-add",
					"id" => sprintf("add-report_%s", $student->kStudent));
			$buttons[] = array("selection" => "grade/report_card", "class"=> "button get-student-grades", "id"=> sprintf("gss_%s",$student->kStudent), "text" => "Grades", "type" => "span");

		}
		$options["class"] = "mini-buttons";
		$button_bar = create_button_bar($buttons,$options);
		echo $button_bar;
		?>
	</div>
	<? endforeach; ?>
</div>
