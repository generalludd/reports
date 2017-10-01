<?php
?>
<div id="new_subject">
<form id="subject_editor" name="subject_editor" method="POST" action="<?php  echo site_url("teacher/insert_subject");?>">
<label for="subject">Subject:&nbsp;</label>
<?php  echo form_dropdown("subject", $subjects,"", "id='subject'");?>
&nbsp;
<label for="gradeStart">Grade Range:&nbsp;</label>
<?php  echo form_dropdown("subGradeStart", $grades, $gradeStart, "id='subGradeStart'"); ?>
-
<?php  echo form_dropdown("subGradeEnd", $grades, $gradeEnd, "id='subGradeEnd'"); ?>
&nbsp;<span class='button insert_subject small'>Add</span>
</form>
</div>