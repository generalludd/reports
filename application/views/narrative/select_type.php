<?php #narrative_type_selector.inc ?>
<h3><?php echo $title;?></h3>
<div id="narrative_process">
<form id="type_selector" action="<?=site_url("template/show_selector");?>" method="POST"
	name="type_selector"><input type="hidden" name="target"
	value="template"> <input type="hidden" name="action" id="action"
	value="select_template" /> <input type="hidden" id="kStudent"
	name="kStudent" value="<? echo $kStudent; ?>" /> <input type="hidden"
	id="kTeach" name="kTeach" value="<? echo $kTeach; ?>" />
<p><label for="subject">Subject:</label><?=form_dropdown('subject',$subjects,'',"id='subject'");?></p>
<p><label for="term">Term: </label><?=$term_menu; ?></p>
<p><!--<label for='year'>Year: </label>--> <?=form_dropdown('year',$year_list,$currentYear, "id='year'"); ?>-
<input type="text" name="yearEnd" id="yearEnd"
	value="<? $yearEnd=$currentYear+1;print $yearEnd; ?>" readonly size="5" /></p>

<p><input type="submit" class='button small select_template' value="Continue"/></p>
</form>
</div>