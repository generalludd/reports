<?php defined('BASEPATH') OR exit('No direct script access allowed');
if(empty($term)){
	$term = get_current_term();
}

if(empty($year)){
	$year = get_current_year();
}

?>

<form style="max-width:370px" id="batch-grade-selector" name="batch-grade-selector" action="<?php  echo site_url("grade/batch_print");?>" method="post">
<input type="hidden" id="kTeach" name="kTeach" value="<?php  echo $kTeach; ?>"/>
<input type="hidden" id="ids" name="ids" value="<?php  echo $ids;?>"/>
<input type="hidden" id="action" name="action" value="print"/>
<!-- <p>
<label for="gradeStart">Grade: </label>
<input type="text" id="gradeStart" name="gradeStart" value="<?php  echo $gradeStart; ?>" size="3"
	maxlength="1"> -<input type="text" id="gradeEnd" name="gradeEnd"
	value="<?php  echo $gradeEnd;?>" size="3" maxlength="1"> </p>
	<p>
	<label for="stuGroup">Student Group (Middle School Specialists Only): </label>
	<?php  echo form_dropdown("stuGroup",array(""=>"","A"=>"A","B"=>"B"),$stuGroup,"id='stuGroup'");?>
	</p>-->
	<p><label for="subject"></label>
	<?php  echo form_dropdown("subject",$subjects);?>
	</p>
<p>	<label for="term">Term:
</label><?php  echo get_term_menu('term', $term);?></p>
<p> <label for="year">Year: </label>
<?php  echo form_dropdown('year',get_year_list(), $year, "id='year' class='year'");?>
-<input id="yearEnd" type="text" name="yearEnd" class='yearEnd' readonly
	maxlength="4" size="5" value="<?php $yearEnd=$year+1;print $yearEnd; ?>" /></p>
	<p>
	<input type="submit" class="button"/>
	</p>
</form>