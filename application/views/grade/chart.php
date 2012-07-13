<?php defined('BASEPATH') OR exit('No direct script access allowed');
$table = array();
?>
<input
	type="hidden" name="kTeach" id="kTeach" value="<?=$kTeach;?>" />
	<input type="hidden" name="term" id="term" value="<?=$term;?>"/>
	<input type="hidden" name="year" id="year" value="<?=$year;?>"/>
	<? if(!empty($assignments)){?>
<table class='grade-chart'>
	<thead>
		<tr>
			<th>Grade Chart<br/>
			<?="$term, $year";?><br/>
			Subject?</th>
			<? foreach($assignments as $assignment){ ?>

			<th id="as_<?=$assignment->kAssignment;?>" class="assignment-edit"><span class='chart-assignment'><?=$assignment->assignment;?></span><br />
				<span class='chart-category'><?=$assignment->category;?></span><br /><span class='chart-points'> <?=$assignment->points;?> Points</span><br/>
				<span class='chart-date'><?=format_date($assignment->date,'standard');?></span></th>


			<? } ?>
			<th><span class='button new assignment-create'>Add Assignment</span></th>
		</tr>
	</thead>
	<tbody>
	<? if(!empty($grades)){ ?>
		<? $current_student = FALSE; ?>
		<? foreach($grades as $grade){ 
			if($current_student != $grade->kStudent){
				$rows[$grade->kStudent]["name"] = "<td class='student-name'><span class='student edit_student_grades' id='eg_$grade->kStudent'>$grade->stuNickname $grade->stuLast</span></td>";
				$current_student = $grade->kStudent;
			}
			$rows[$grade->kStudent]["grades"][$grade->kAssignment] = "<td class='grade-points edit' id='sag_$grade->kAssignment" . "_$grade->kStudent'>$grade->points</td>";
		}

		foreach($rows as $row){
			print "<tr>";
			print $row["name"];
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
	print "<p>You have not entered any assignments for this term. <span class='button new assignment-create'>Add Assignment</span></p>";
}