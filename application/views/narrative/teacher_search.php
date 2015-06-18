<?php defined('BASEPATH') OR exit('No direct script access allowed');

?>
<h3><?php echo $title;?></h3>
<form id="teacher_narratives_search" name="teacher_narratives_search" method="get" action="<?=site_url("narrative/teacher_list");?>">
<p>
<label for="kTeach">Teacher:</label>
<?=form_dropdown("kTeach",$teachers,$kTeach,"id='kTeach'");?>
</p>
<p>
<label for="subject">Subject: </label>
<span id="subject_menu"><?=form_dropdown("subject", $subjects, $subject, "id='subject'"); ?></span>
</p>
<p><label for="gradeStart">Grade Range: </label>
<span id="grade_range"><?=form_dropdown("gradeStart", $grades, $gradeStart, "id='gradeStart'") . "-" . form_dropdown("gradeEnd", $grades, $gradeEnd, "id='gradeEnd'");?>
</span>
</p>
<p>
<label for="narrTerm">Term:</label>
<?=get_term_menu('narrTerm', $narrTerm);?> </p>
<p>
<label for="narrYear">Year: </label>
<?=form_dropdown('narrYear',get_year_list(), $narrYear, "id='narrYear' class='year'");?>
-<input id="yearEnd" type="text" name="yearEnd" class='yearEnd' readonly
	maxlength="4" size="5" value="<? $yearEnd=$narrYear+1;print $yearEnd; ?>" /></p>
<input type="submit" class="button" value="Search"/>
</form>