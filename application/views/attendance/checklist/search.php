<?php 

$dbRole = $this->session->userdata("dbRole");

?>

<form id='attendance_search' name='attendance_search' action='<?=site_url("attendance/check");?>' method='get'>
<p><label for='date'>Date: </label><input type='text' class='datefield' id='date' name='date' value='<?php echo date("m/d/Y");?>'/></p>
<p><label for='gradeStart'>Grade Range: </label><input type="text" name="gradeStart" id="gradeStart" class="grade" value=""/>&dash;
<input type="text" name="gradeEnd"  class="grade" id="gradeEnd" value=""/></p>
<p><label for="humanitiesTeacher">Humanities Teacher: </label><?php echo form_dropdown("humanitiesTeacher",$humanities_teachers,$this->session->userdata("userID"));?>
</p>
<p><label for="stuGroup">Middle School Student Group: </label><?php echo form_dropdown("stuGroup",$stuGroup);?></p>

<p><label for="kTeach">Classroom Teacher or Advisor: </label><?php echo form_dropdown("kTeach",$teachers), $this->session->userdata('userID');?>
</p>
<p>
<input type="submit" value="Search" class="button"/>
</p>
</form>