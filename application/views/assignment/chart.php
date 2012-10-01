<?php defined('BASEPATH') OR exit('No direct script access allowed');
$table = array();
$assignment_count = 0;
$gradeDisplay = sprintf("Grade %s",$gradeStart);

if($gradeStart != $gradeEnd){
	$gradeDisplay = sprintf("Grades %s/%s",$gradeStart,$gradeEnd);
}
if($stuGroup){
	$gradeDisplay = sprintf("%s %s",$gradeDisplay, $stuGroup);
}
?>
<h2>
	Grade Chart for
	<?=$gradeDisplay;?>
	[BETA]
</h2>
<div class="button-box">
	<ul class="button-list">
		<li><span class="button refresh">Refresh Page</span></li>
		<li><span class="button edit assignment_categories_edit">Edit
				Categories</span></li>
	</ul>


</div>
<input
	type="hidden" name="kTeach" id="kTeach" value="<?=$kTeach;?>" />

<? if(!empty($assignments)){

	/* Get the subject of the first row of the assignments */
	$header = $assignments[0];
	?>

<table class='grade-chart'>
	<thead>
		<tr>
			<th><?=$header->subject;?><br /> <?="$header->term<br/>" . format_schoolyear($header->year);?>
			</th>
			<th></th>
			<? foreach($assignments as $assignment){ ?>

			<th id="as_<?=$assignment->kAssignment;?>"
				class="assignment-edit assignment-field"><span
				class='chart-assignment'><?=$assignment->assignment;?> </span><br />
				<span class='chart-category'><?=$assignment->category;?> </span><br />
				<span class='chart-points'> <?=$assignment->points;?> Points
			</span><br /> <span class='chart-date'><?=format_date($assignment->date,'standard');?>
			</span></th>


			<? 
			$assignment_count++;
} ?>
			<th class='assignment-button'><span
				class='button new assignment-create'>Add Assignment</span>
			</th>
		</tr>
	</thead>
	<tbody>
		<? if(!empty($grades)){ ?>
		
		<? $current_student = FALSE; ?>
		<? foreach($grades as $grade){ 
			if($current_student != $grade->kStudent){
				$rows[$grade->kStudent]["name"] = "<td class='student-name'><span class='student edit_student_grades' id='eg_$grade->kStudent'>$grade->stuNickname $grade->stuLast</span></td>";
				$rows[$grade->kStudent]["kStudent"] = $grade->kStudent;
				$current_student = $grade->kStudent;
				$student_points = 0;
				//$rows[$grade->kStudent]["test"] = $this->grade->get_totals($grade->kStudent,$this->session->userdata("term"),$this->session->userdata("year"),$this->session->userdata("userID") );
				

			}
			$points = round($grade->points,1);
			$student_points += $grade->points/$grade->assignment_total; //*$grade->weight/100;
			//if the student status for this grade is Abs or Exc display the status instead of the grade
			if(!empty($grade->status)){
				$points = $grade->status;
			}
			if($grade->footnote){
				$points .= "[$grade->footnote]";
			}
			$rows[$grade->kStudent]["totals"] = $student_points;
				

			$rows[$grade->kStudent]["grades"][$grade->kAssignment] = sprintf("<td class='grade-points edit' id='sag_%s" . "_%s'>$points</td>",$grade->kAssignment,$grade->kStudent);
		}
		
		foreach($rows as $row){
			print sprintf("<tr id='sgtr_%s'>",$row['kStudent']);
			print $row["name"];
			/*$i=0;
			$sum = 0;
			foreach($row["test"] as $test){
				$sum += $test->category_average;
				$i++;
			}
			$final_grade = round($sum/$i,2) * 100;
			*/
			//get the grade as a human-readable percentage
			$final_grade = round(($row["totals"])/$assignment_count,2)*100;
			//$final_grade = $row["totals"];
			print sprintf("<td>%s (%s%s)</td>",calculate_letter_grade($final_grade),$final_grade,"%");
			
			print implode("",$row["grades"]);
			print "</tr>";
		}
		
		}
		?>
	</tbody>
</table>
<div class='button-box'>
	<span class='button new show-student-selector'>Add Student</span>
</div>
<? }else{
	print "<p>You have not entered any assignments or grades for this term. <span class='button new assignment-create'>Add Assignment</span></p>";
}
?>
<p class="notice">Please Note: Grade totals do not yet reflect the grade
	weights. In fact, they are a complete mess, as you can see! Don't
	worry, this is just a calculation error and does not reflect problems
	with the data.</p>
