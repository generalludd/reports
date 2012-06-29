<?php defined('BASEPATH') OR exit('No direct script access allowed');
$table = array();
?>
<table class='grade-chart'>
	<thead>
		<tr>
			<th></th>
			<? foreach($assignments as $assignment){ ?>

			<th id="as_<?=$assignment->kAssignment;?>" class="assignment-edit"><?=$assignment->assignment;?><br />
				<?=$assignment->category;?><br /> <?=$assignment->points;?> Points</th>


			<? } ?>
		</tr>
	</thead>
	<tbody>
		<? $current_student = FALSE; ?>
		<? foreach($assignments as $grade){ 
			if($current_student != $grade->kStudent){
				$rows[$grade->kStudent]["name"] = "<td><span class='student'>$grade->stuNickname $grade->stuLast</span></td>";
				$current_student = $grade->kStudent;
			}
			$rows[$grade->kStudent]["grades"][$grade->kAssignment] = "<td><input type='text' id='sag_" . $grade->kAssignment . "_" . $grade->kStudent . " name='grade' value='$grade->points' size='3' /></td>";

		}
		
		foreach($rows as $row){
			print "<tr>";
			print $row["name"];
			print implode("",$row["grades"]);
			print "</tr>";
		}

		?>

		<tr>
			<td><span class='button new grade_chart_add_student'>Add Student</span>
			</td>
		</tr>
	</tbody>
</table>


