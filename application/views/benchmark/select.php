<?php
?>
<h3>Search for Benchmarks for <?php echo format_name($student->stuFirst, $student->stuLast, $student->stuNickname);?></h3>

<form id="benchmark_select_for_student" name="select_for_student" method="get" action="<?php echo base_url("benchmark/select_student?action=search")?>">
<input type="hidden" name="kStudent" value="<?php echo $student->kStudent;?>"/>
<p>
<label for="grade">Grade Range</label>
<input type="text" name="gradeStart" value="" style="width:2em;"/>-<input type="text" name="gradeEnd" value="" style="width: 2em;"/>
</p>
<p>
<label for="subject">Subject:</label>
<?php echo form_dropdown('subject', $subjects, get_cookie("benchmark_subject") , "id='subject'"); ?>
</p>
<p>
<label for="quarter">Quarter</label>
<?php echo form_dropdown('quarter',array(0=>"",1=>1,2=>2,3=>3,4=>4), get_cookie("benchmark_quarter"),"id='quarter'" );?>
</p>
<p>
<label for="term">Term</label>
<?php echo get_term_menu("term",get_current_term());?>
</p>
<p>
<label for="year">Year</label>
<input type="text" name="year" id="year" value="<?php echo get_current_year();?>" style="width:3em;"/>-
<input type="text" name="yearEnd" id="yearEnd" value="<?php echo get_current_year() + 1;?>" style="width:3em;" readonly/></p>
<p>
<input type="submit" value="Search"/>
</p>
</form>
