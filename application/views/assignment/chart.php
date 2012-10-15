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
		</li>
		<? endif;?>

		<li><span class="button search-assignments" id="sa_<?=$kTeach;?>"
			title="Search for Current Grade Charts">New Grade Search</span>
		</li>
	</ul>


</div>
<input
	type="hidden" name="kTeach" id="kTeach" value="<?=$kTeach;?>" />


	<div  colspan=50 class='assignment-button'><? if($kTeach == $this->session->userdata("userID")):?><span
				class='button new assignment-create'>Add Assignment</span> <? endif; ?>
			</div>


<table class='grade-chart'>
	<thead>

		<tr>
			<th><?=$header_string;?>
			</th>
			<th></th>
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
			}
			$points = round($grade->points,2);

			//calculate the weighted grade for this assignment
			$student_points += $grade->points*$grade->weight/100;
			//if the student status for this grade is Abs or Exc display the status instead of the grade
			if(!empty($grade->status)){
				$points = $grade->status;
				if($grade->status == "Exc"){
					$student_points += 1;
				}
			}


			if($grade->footnote){
				$points .= "[$grade->footnote]";
			}
			$rows[$grade->kStudent]["totals"] = $student_points;


			$rows[$grade->kStudent]["grades"][$grade->kAssignment] = sprintf("<td class='grade-points edit' id='sag_%s_%s'>%s</td>",$grade->kAssignment,$grade->kStudent,$points);
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
<p>You may need to create categories before creating assignments. <span class="button edit assignment_categories_edit">Edit Categories</span></p>
	<p>You have not entered any assignments or grades for this term. <span class='button new assignment-create'>Add Assignment</span></p>
<? }

