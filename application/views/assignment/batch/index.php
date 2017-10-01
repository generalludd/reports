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

<h2>Inserting batch assignments for <?php printf("%s, %s, Grade(s): %s",$term, format_schoolyear($year), format_grade_range($gradeStart, $gradeEnd)); ?> </h2>
<?php  echo  create_button_bar($buttons);?>
<p>A minimum of two rows are required.</p>

<form name="batch-insert-assignments" id="batch-insert-assignments" method="post" action="<?php  echo site_url("assignment/insert_batch?subject=$subject");?>">
	<input type="hidden" name="kTeach" value="<?php  echo $kTeach;?>" />
	<input type="hidden" name="year" value="<?php  echo $year;?>" />
	<input type="hidden" name="term" value="<?php  echo $term;?>" />
	<input type="hidden" name="gradeStart" value="<?php  echo $gradeStart;?>" />
	<input type="hidden" name="gradeEnd" value="<?php  echo $gradeEnd;?>" />
	<input type="hidden" name="subject" value="<?php echo $subject;?>"/>

	<table class="grid" id="batch-assignment-table">
		<thead>
			<tr>
				<th>Subject</th>
				<th>Category</th>
				<th>Assignment</th>
				<th>Points</th>
				<th>
					<a title="Give every student the max points available from the start." class="link" href="#max-pts">Max Pts*</a>
				</th>
				<th>Date (mm/dd/yyyy)</th>		
		</thead>
		<tbody>
<?php $this->load->view("assignment/batch/row");?>
<?php $this->load->view("assignment/batch/row");?>

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