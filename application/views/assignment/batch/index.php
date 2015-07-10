<?php

defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

// batch_insert.php Chris Dart Jan 28, 2015 4:23:12 PM chrisdart@cerebratorium.com

$buttons [] = array (
		"text" => "New Row",
		"class" => "button new small add-batch-assignment-row",
		"id" => "row-add_$kTeach",
		"title" => "Add a new row of assignments",
		"selection" => "" 
);

?>

<h2>Inserting batch assignments for <? printf("%s, %s, Grade(s): %s",$term, format_schoolyear($year), format_grade_range($gradeStart, $gradeEnd)); ?> </h2>
<?= create_button_bar($buttons);?>
<p>A minimum of two rows are required.</p>

<form name="batch-insert-assignments" id="batch-insert-assignments" method="post" action="<?=site_url("assignment/insert_batch");?>">
	<input type="hidden" name="kTeach" value="<?=$kTeach;?>" />
	<input type="hidden" name="year" value="<?=$year;?>" />
	<input type="hidden" name="term" value="<?=$term;?>" />
	<input type="hidden" name="gradeStart" value="<?=$gradeStart;?>" />
	<input type="hidden" name="gradeEnd" value="<?=$gradeEnd;?>" />

	<table class="grid" id="batch-assignment-table">
		<thead>
			<tr>
				<th>Category</th>
				<th>Assignment</th>
				<th>Points</th>
				<th>
					<a title="Give every student the max points available from the start." class="link" href="#max-pts">Max Pts*</a>
				</th>
				<th>Date (mm/dd/yyyy)</th>
				<th>Subject</th>
		
		</thead>
		<tbody>
<? $this->load->view("assignment/batch/row");?>
<? $this->load->view("assignment/batch/row");?>

</tbody>
	</table>
	<p>
		<sup>*</sup>Give every student the max points available from the start.
	</p>

	<p>
		<a id="#max-pts"></a>
		<input type="submit" class="button new small" value="Insert Batch" />
	</p>
</form>