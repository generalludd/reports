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
<input
	type="hidden" name="kTeach" id="kTeach" value="<?=$kTeach;?>" />
<?
if(!empty($assignments)){

	/* Get the subject and relevant data from the first row of the assignments */
	$header = $assignments[0];
	$header_string = sprintf("%s<br/>%s<br/>%s",$header->subject,$header->term, format_schoolyear($header->year))
	?>

<h2>
	Grade Chart for
	<?=sprintf("%s by %s %s", $gradeDisplay, $header->teachFirst, $header->teachLast) ;?>
</h2>
<div class="button-box">
	<ul class="button-list">
		<li><span class="button refresh">Refresh Page</span></li>
		<? if($kTeach == $this->session->userdata("userID")): ?>
		<li><span class="button edit assignment_categories_edit">Edit
				Categories</span></li>
		<? endif;?>

		<li><span class="button search-assignments" id="sa_<?=$kTeach;?>"
			title="Search for Current Grade Charts">New Grade Search</span>
		</li>
		<!-- <li><span
				class='button new assignment-create'>Add&nbsp;Assignment</span></li> -->
	</ul>


</div>



<!-- <div  colspan=50 class='assignment-button'><? if($kTeach == $this->session->userdata("userID")):?><span
				class='button new assignment-create'>Add Assignment</span> <? endif; ?>
			</div> -->


<table class='grade-chart'>
	<thead>

		<tr>
			<th><?=$header_string;?>
			</th>
			<th></th>
			<th class='chart-final-grade'>Estimated Final Grade</th>
			<? 
			$total_points = 0;
			foreach($assignments as $assignment){ ?>

			<th id="as_<?=$assignment->kAssignment;?>"
				class="assignment-edit assignment-field"><span
				class='chart-assignment'><?=$assignment->assignment;?> </span><br />
				<span class='chart-category'><?=$assignment->category;?> </span><br />
				<!-- an assignment with 0 points is calculated as a make-up points for assignments -->
				<span class='chart-points'> <?=$assignment->points>0?$assignment->points. " Points" :"Make-Up Points";?>
			</span><br /> <span class='chart-date'><?=format_date($assignment->date,'standard');?>
			</span></th>


			<? 
			//calculated the weighted total points
			$total_points += $assignment->points * $assignment->weight/100;
} ?>
			<th class='assignment-button'><span
				class='button new assignment-create'>Add&nbsp;Assignment</span></th>
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
				$href = site_url(sprintf("grade/report_card?kStudent=%s&year=%s&term=%s&subject=%s&print=true",$grade->kStudent,$this->input->cookie("year"),$this->input->cookie("term"), $header->subject));
				$rows[$grade->kStudent]["button"] = "<td class='student-button'><a class='button' target='_blank' href='$href'>Print</a></td>";
			}
			$points = round($grade->points,2);

			//calculate the weighted grade for this assignment
			$student_points += $grade->points*$grade->weight/100;
			//if the student status for this grade is Abs or Exc display the status instead of the grade
			if(!empty($grade->status)){
				$points = $grade->status;
				//if($grade->status == "Exc"){
				$student_points += $grade->assignment_total*$grade->weight/100;
				//}
			}

			if($grade->points == 0 && $grade->assignment_total == 0){
				$points = "";
			}


			if($grade->footnote){
				$points .= "[$grade->footnote]";
			}
			$rows[$grade->kStudent]["totals"] = $student_points;


			$rows[$grade->kStudent]["grades"][$grade->kAssignment] = sprintf("<td class='grade-points edit' id='sag_%s_%s'>%s</td>",$grade->kAssignment,$grade->kStudent,$points);
		}

		foreach($rows as $row){
			print sprintf("<tr id='sgtr_%s' class='grade-chart-row'>",$row['kStudent']);
			print $row["name"];
			print $row["button"];
			//get the grade as a human-readable percentage
			$final_grade = round(($row["totals"])/$total_points,2)*100;
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
<? }else{ ?>
<p>
	You may need to create categories before creating assignments. <span
		class="button edit assignment_categories_edit">Edit Categories</span>
</p>
<? if($category_count > 0){ ?>
<p>
	You have not entered any assignments or grades for this term. <span
		class='button new assignment-create'>Add Assignment</span>
</p>
<? }
}

