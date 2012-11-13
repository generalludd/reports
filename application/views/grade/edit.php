<?php defined('BASEPATH') OR exit('No direct script access allowed');
//use first record for student name
$header_record = $grades[0];
$student_name = format_name($header_record->stuNickname, $header_record->stuLast);
?>
<input
	type="hidden" name="kStudent" id="kStudent" value="<?=$kStudent;?>" />
<input
	type="hidden" name="kTeach" id="kTeach" value="<?=$kTeach;?>" />
	<h5><?=$student_name;?></h5>
<table class='grade-editor'>
	<thead>
		<tr>
			<th class='grade-date'>Date</th>
			<th class='grade-assignment'>Assignment</th>
			<th class='grade-category'>Category</th>
			<th class='grade-total-points'>Total Points</th>
			<th class='grade-points'>Points</th>
			<th class='grade-status'>Status</th>
			<th class='grade-footnote'>Footnote</th>
			<th class='grade-confirmation'></th>
		</tr>
	</thead>
	<tbody>
		<?

		foreach($grades as $grade){

			?>
		<tr>
			<td class='grade-description'><?=format_date($grade->date,"standard");?>
			</td>
			<td class='grade-description'><?=$grade->assignment;?>
			</td>

			<td class='grade-description'><?=$grade->category;?>
			</td>
			<td class='grade-description'><?=$grade->total_points;?>
			</td>
			<td class='grade-value'><input type="text"
				id="g_<?=$grade->kAssignment;?>" name="points" size="2"
				class="assignment-grade assignment-string assignment-field"
				value="<?=get_value($grade,"points");?>" />
			</td>
			<td class='grade-status'><?=form_dropdown("status",$status, get_value($grade,"status"),"id='status_$grade->kAssignment' class='assignment-field'");?>
			</td>
			<td class='grade-footnote'><?=form_dropdown("footnote",$footnotes, get_value($grade,"footnote"),"id='footnote_$grade->kAssignment'  class='assignment-field'");?>
			</td>
			<td class='grade-button'><span style='margin-left: 5px;'
				id='save_<?=$grade->kAssignment;?>'></span>
			</td>
		</tr>


		<?
} ?>
	</tbody>
</table>
<div class='button-box'>
	<span class='button close_grade_editor'>Close</span>
</div>
