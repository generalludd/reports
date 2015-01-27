<?php defined('BASEPATH') OR exit('No direct script access allowed');
if(empty($term)){
	$term = get_current_term();
}

if(empty($year)){
	$year = get_current_year();
}

?>

<form style="max-width:370px" id="chart-search" name="chart-search" action="<?=site_url("assignment/chart");?>" method="get">
<input type="hidden" id="kTeach" name="kTeach" value="<?=$kTeach; ?>"/>
<p>
<label for="gradeStart">Grade: </label>
<input type="text" id="gradeStart" name="gradeStart" value="<?=$gradeStart; ?>" size="3"
	maxlength="1"> -<input type="text" id="gradeEnd" name="gradeEnd"
	value="<?=$gradeEnd;?>" size="3" maxlength="1"> </p>
	<p>
	<label for="stuGroup">Student Group (Middle School Specialists Only): </label>
	<?=form_dropdown("stuGroup",array(""=>"","A"=>"A","B"=>"B"),$stuGroup,"id='stuGroup'");?>
	</p>
<p>	<label for="term">Term:
</label><?=get_term_menu('term', $term);?></p>
<p> <label for="year">Year: </label>
<?=form_dropdown('year',get_year_list(), $year, "id='year' class='year'");?>
-<input id="yearEnd" type="text" name="yearEnd" class='yearEnd' readonly
	maxlength="4" size="5" value="<? $yearEnd=$year+1;print $yearEnd; ?>" /></p>
	<div class="message">Setting a date range can speed up editing when you are dealing with large numbers of assignments.</div>
	<p><label for="date_range">Date Range</label><input type="text" id="date_start" name="date_start" class="datefield" value="<?=get_cookie("assignment_date_start");?>"/>-
	<input type="text" name="date_end" id="date_end" class="datefield" value="<?=get_cookie("assignment_date_end");?>"/>
	</p>
	<p>
	<label for="student_sort_order">Sort Students by</label>
	<?=form_dropdown("student_sort_order",array("stuFirst"=>"First Name","stuLast"=>"Last Name"),get_cookie("student_sort_order"),"id='student_sort_order'");?>
	</p>
	<p>
	<input type="submit" class="button"/>
	</p>
</form>