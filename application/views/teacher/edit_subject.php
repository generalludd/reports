<?php
?>
<div id="new_subject">
<form id="subject_editor" name="subject_editor" method="POST" action="<?=site_url("teacher/insert_subject");?>">
<label for="subject">Subject:&nbsp;</label>
<?=form_dropdown("subject", $subjects,"", "id='subject'");?>
&nbsp;
<label for="gradeStart">Grade Range:&nbsp;</label>
<?=form_dropdown("subGradeStart", $grades, $gradeStart, "id='subGradeStart'"); ?>
-
<?=form_dropdown("subGradeEnd", $grades, $gradeEnd, "id='subGradeEnd'"); ?>
&nbsp;<span class='button insert_subject small'>Add</span>
</form>
</div>