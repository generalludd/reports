<?php

$lower_school = implode("\r", create_grade_checklist(0, 4,"grades" ));
$middle_school = implode("\r", create_grade_checklist(5,8,"grades"));
?>
<form id="class-search" action="<?php site_url("student/edit_classes");?>" method="get">
<p>
<label for="year">School Year</label><br/>
<?php echo form_dropdown('year', $yearList, $currentYear,"id='year' class='year'"); ?>
			- <input type="text" id='yearEnd' name="yearEnd" size="5"
				maxlength="4" readonly value="<?php echo $currentYear + 1; ?>" />
<p>
<label for="type">Class Grouping</label><br/><?php echo form_dropdown("type", array("humanitiesTeacher"=>"Humanities","classroom"=>"Classroom","advisory"=>"Advisory","ab"=>"MS A/B Groups"));?>
</p>
<p>
<label for="grades">Grades
</label>
<div class="columns">
<ul class="rows">
<?php echo $lower_school;?>
</ul>
<ul class="rows">
<?php echo $middle_school;?>
</ul>
</div>
</p>
<p>
<input type="submit" class="button"/>
</p>
</form>