<?php
 ?>
<h3><?php echo $title;?></h3>
<form id="benchmarkSearch" action="<?php  echo site_url("benchmark/teacher_list");?>" method="get" name="benchmarkSearch">
	<p>
		<input type="hidden" name="kTeach" id="kTeach" value="<?php  echo $kTeach;?>" />
		<label for="subject">Subject:</label>
<?php  echo form_dropdown('subject', $subject_list, get_cookie("benchmark_subject"), "id='subject'"); ?>
    </p>
	<p>
		<label for="term">Year:</label>
    <input type="text" name="year" id="year" size="5" maxlength="4" class="year" value="<?php  echo $yearStart; ?>" />
		-
		<input type="text" id="yearEnd" class='yearEnd' readonly value="<?php  echo $yearEnd; ?>" size="5" />
	</p>
	<p>
		Grade Range:
		<input type="text" name="gradeStart" class="gradeStart" size="3" maxlength="1" value="<?php  echo $gradeStart?>" />
		-
		<input type="text" name="gradeEnd" size="3" maxlength="1" class="gradeEnd" value="<?php  echo $gradeEnd;?>" />
	</p>
	<p>
		<input type="submit" class="button" id="continue_2" value="continue" />
	</p>
</form>
