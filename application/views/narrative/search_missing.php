<?php
?>
<form id="missing_narratives_search" name="missing_narratives_search" method="get" action="<?=site_url("narrative/show_missing");?>">
<input type="hidden" name="kTeach" id="kTeach" value="<?=$kTeach;?>"/>
<label for="subject">Subject: </label>
<?=form_dropdown("subject", $subjects, $subject, "id='subject'"); ?>
<br/>
<p><label for="gradeStart">Grade Range: </label><?=form_dropdown("gradeStart", $grades, $gradeStart, "id='gradeStart'") . "-" . form_dropdown("gradeEnd", $grades, $gradeEnd, "id='gradeEnd'");?></p>
<input type="submit" class="button" value="Search"/>
</form>
