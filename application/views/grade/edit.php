<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>
<input type="hidden" name="kStudent" id="kStudent" value="<?=$kStudent;?>"/>
<?
foreach($grades as $grade){
	
	?>
	<table>
	<tr>
	<td class='grade-label'><?=$grade->assignment;?></td>
	<td><?=$grade->category;?></td>
	<td><?=$grade->total_points;?></td>
	<td><input type="text" id="g_<?=$grade->kAssignment;?>" name="points" size="2" class="assignment-grade assignment-string" value="<?=get_value($grade,"points");?>"/></td>
	<td><span class='button save_student_grade' id='ssg_<?=$grade->kAssignment;?>'>Save</span></td>
	<td><span style='margin-left:5px' id='save_<?=$grade->kAssignment;?>'></span></td>
</tr>
	
	</table>
	
	<?
} ?>

	<div class='button-box'><span class='button close_grade_editor'>Close</span></div>
