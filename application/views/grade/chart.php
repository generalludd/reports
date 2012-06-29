<?php defined('BASEPATH') OR exit('No direct script access allowed');

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
		<? $current_student = 0; ?>
		<? foreach($assignments as $grade){ 
if($current_student != $grade->kStudent){ 
	if($current_student != 0){
		
		echo "</tr>";
	}
	$current_student = $grade->kStudent;
		echo "<tr>";
		echo "<td>$grade->stuNickname $grade->stuLast</td>";
		 } ?>
			<td><input type="text"
				id="sag_<?=$grade->kAssignment;?>_<?=$grade->kStudent;?> name="
				grade" value="<?=$grade->points;?>" size="3" />
			</td>
		
<? } ?>

<tr><td><span class='button new grade_chart_add_student'>Add Student</span></td></tr>
	</tbody>
</table>
