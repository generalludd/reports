<?php defined('BASEPATH') OR exit('No direct script access allowed');
// edit_column.php Chris Dart Feb 3, 2013 7:57:59 PM chrisdart@cerebratorium.com

?>
<div class='header'>
<h3>Assignment: <?php  echo $assignment->assignment;?></h3>
<ul>
<li><label>Category: </label><?php  echo $assignment->category;?></li>
<li><label>Total Points: </label><?php  echo $assignment->points>0?$assignment->points. " Points" :capitalize($assignment->points_type);?></li>
<li><label>Date: </label><?php  echo format_date($assignment->date);?></li>
<?php if($stuGroup = $this->input->cookie("stuGroup")): ?>
<li><label>Student Group: </label><?php  echo $stuGroup;?></li>
<?php endif;?>
</ul>
</div>
<table class='grade-editor'>
	<thead>
		<tr>
			<th class='grade-student'>Student</th>
			<th class='grade-points'>Points</th>
			<th class='grade-status'>Status</th>
			<th class='grade-footnote'>Footnote</th>
			<th class='grade-confirmation'></th>
		</tr>
	</thead>
	<tbody>
		<?php 
		//tabindex is set to allow editors to tab down to the grade point value fields (see below)
		$tabindex = 1;
		foreach($grades as $grade){
			?>
		<tr id="<?php  echo get_value($grade, "kGrade",0);?>">
			<td class='grade-description'><?php  echo format_name($grade->stuNickname,$grade->stuLast);?>
			</td>
			<td class='grade-value'><input type="text"
				id="g_<?php  echo $grade->kAssignment;?>_<?php  echo $grade->kStudent;?>" name="points" size="2"
				class="column-grade assignment-string assignment-field"
				value="<?php  echo get_value($grade,"points");?>" autocomplete="off" tabindex="<?php  echo $tabindex;?>" />
			</td>
			<td class='grade-status'><?php  echo form_dropdown("status",$status, get_value($grade,"status"),sprintf("data-id='%s' data-student='%s' id='status_%s_%s' class='assignment-field'",$grade->kAssignment,$grade->kStudent, $grade->kAssignment,$grade->kStudent));?>
			</td>
			<td class='grade-footnote'><?php  echo form_dropdown("footnote",$footnotes, get_value($grade,"footnote"),
					sprintf("data-id='%s' data-student='%s' id='footnote_%s_%s' class='assignment-field'",$grade->kAssignment, $grade->kStudent, $grade->kAssignment, $grade->kStudent));?>

			</td>
			<td class='grade-button'><span style='margin-left: 5px;'
				id='save_<?php  echo $grade->kAssignment;?>_<?php  echo $grade->kStudent;?>'></span>
			</td>
		</tr>

		<?php 
		//increment the tabindex for the next row item.
		$tabindex++;
} ?>
	</tbody>
</table>
<div class='button-box'>
	<span class='button small close_grade_editor' tabindex="<?php  echo $tabindex;?>">Close</span>
</div>