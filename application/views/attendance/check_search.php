<?php 

$dbRole = $this->session->userdata("dbRole");

?>

<form id='attendance_search' name='attendance_search' action='<?=site_url("attendance/search");?>' method='get'>
<p><label for='startDate'>Date: </label><input type='text' class='datefield' id='startDate' name='startDate' value='<?php echo date("m/d/Y");?>'/></p>
<p><label for='gradeStart'>Grade Range: </label><input type="text" name="gradeStart" id="gradeStart" class="grade" value=""/>&dash;
<input type="text" name="gradeEnd"  class="grade" id="gradeEnd" value=""/></p>
<p><label for="humanities_teacher">Humanities Teacher: </label><?php echo form_dropdown("humanities_teacher",$humanities_teachers,$this->session->userdata("userID"));?>
</p>
<p><label for="stuGroup">Middle School Student Group: </label><?php echo form_dropdown("stuGroup",$stuGroup);?></p>

<p><label for="kTeach">Classroom Teacher or Advisor: </label><?php echo form_dropdown("kTeach",$teachers);?>
</p>
<p>
<input type="submit" value="Search" class="button"/>
</p>
</form>