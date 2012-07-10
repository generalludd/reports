<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>
<input
	type="hidden" name="kStudent" id="kStudent" value="<?=$kStudent;?>" />
	<table class='grade-editor'>
	<thead>
		<tr>
			<th class='grade-assignment'>Assignment</th>
			<th class='grade-category'>Category</th>
			<th class='grade-total-points'>Total Points</th>
			<th class='grade-points'>Points</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?

foreach($grades as $grade){

	?>

		<tr>
			<td class='grade-description'><?=$grade->assignment;?></td>
			<td class='grade-description'><?=$grade->category;?></td>
			<td class='grade-description'><?=$grade->total_points;?></td>
			<td class='grade-value'><input type="text" id="g_<?=$grade->kAssignment;?>" name="points"
				size="2" class="assignment-grade assignment-string"
				value="<?=get_value($grade,"points");?>" /></td>
			<td class='grade-button'><span class='button save_student_grade'
				id='ssg_<?=$grade->kAssignment;?>'>Save</span></td>
			<td class='grade-button'><span style='margin-left: 5px'
				id='save_<?=$grade->kAssignment;?>'></span></td>
		</tr>


<?
} ?>
	</tbody>
</table>
<div class='button-box'>
	<span class='button close_grade_editor'>Close</span>
</div>
