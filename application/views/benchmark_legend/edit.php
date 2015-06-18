<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?

$year = get_current_year();
$term = get_current_term();
?>
<form id="benchmark_legend" action="<?=site_url("benchmark_legend/$action");?>" method="post" name="benchmark_legend">
 <input type="hidden" id="kLegend" name="kLegend" value="<?=get_value($legend, "kLegend");?>" />
 <input type="hidden" id="kTeach" name="kTeach" value="<?=get_value($legend, "kTeach");?>"/>
<p><label for="gradeStart">Grade Range: </label>
<input type="text" id="gradeStart" name="gradeStart" value="<?=get_value($legend, "gradeStart"); ?>" size="3"
maxlength="1" required/> -<input type="text" id="gradeEnd" name="gradeEnd"
value="<?=get_value($legend, "gradeStart"); ?>" size="3" maxlength="1" required/>
<label for="term">Term:
</label><?=get_term_menu('term', get_value($legend, "term", $term));?>
<label for="year">Year: </label>
<?=form_dropdown('year',get_year_list(), get_value($legend, "year", $year), "id='year' class='year'");?>
-<input id="yearEnd" type="text" name="yearEnd" class='yearEnd' readonly
	maxlength="4" size="5" value="<? $yearEnd=$year+1;print $yearEnd; ?>" /></p>

<label for="subject">Subject:</label><?=form_dropdown('subject',$subjects,get_value($legend, "subject"),"id='subject' required");?>
<p><label for="title">Title:</label><input type="text"
	id="title" name="title" required value="<?=get_value($legend,"title");?>" /></p>

<p><label for="legend">Legend:</label><br />
<textarea id='legend'  name='legend' class="tinymce" style="width: 100%;height:100px"><?=get_value($legend, "legend");?></textarea></p>

<p><input type="submit" class='save_legend button' value="Save" /></p>
</form>
