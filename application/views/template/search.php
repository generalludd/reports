<?php

$current_year = get_current_year();
?>

<form id="template_search" name="template_search" action="<?=site_url("template/list_templates");?>" method="get">
<input type="hidden" name="kTeach" id="kTeach" value="<?=$kTeach;?>" />
<p>
<label for="subject">Subject: </label>
<?=form_dropdown("subject", $subjects, $subject, "id='subject'");?></p>
<p>
<label for="gradeStart">Grade Range: </label>
<input type="text" id="gradeStart" name="gradeStart" size="2" maxlength="1" value=""/>-
<input type="text" id="gradeEnd" name="gradeEnd" size="2" maxlength="1" value=""/>
</p>
<p>
<label for="term">Term: </label><?=get_term_menu("term", get_current_term(),TRUE);?>
</p>
<p>
<label for="year">School Year: </label><?=form_dropdown("year", $years, $current_year ,"id='year' class='year'"); ?>-
<input type="text" id="yearEnd" name="yearEnd" class='yearEnd' size="5" maxlength="4"
	readonly value="<?=$current_year + 1?>" />
	</p>
	<p>
<label for="include_inactive">Include Inactive Templates: </label>
<input type="checkbox" id="include_inactive" name="include_inactive" value="true"/>    </p>
<p><input type="submit" class="button" value="Find" />

</form>
