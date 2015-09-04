<?php 

$dbRole = $this->session->userdata("dbRole");
?>
<h2><?php echo $title;?></h2>
<p>Enter the grade range and select the appropriate values as needed.</p>
<form id='attendance-check' name='attendance-check' action='<?=site_url("attendance/check");?>' method='get'>
<p><label for='date'>Date: </label><input type='date' id='date' name='date' value='<?php echo date("Y-m-d");?>' required/></p>
<p><label for='gradeStart'>Grade Range: </label><input type="text" name="gradeStart" id="gradeStart" class="grade" value="" required/>&dash;
<input type="text" name="gradeEnd"  class="grade" id="gradeEnd" value="" required/></p>

<div class="middle-school hidden">
<p class="humanities-teacher"><label for="humanitiesTeacher">Humanities Teacher: </label><?php echo form_dropdown("humanitiesTeacher",$humanities_teachers,$this->session->userdata("userID"));?>
</p>
<p>-OR-</p>
<p><label for="stuGroup">Middle School Student Group: </label><?php echo form_dropdown("stuGroup",$stuGroup);?></p>
</div>

<div class="lower-school hidden">
<p><label for="kTeach">Classroom Teacher or Advisor: </label><?php echo form_dropdown("kTeach",$teachers);?>
</p>
</div>
<p>
<input type="submit" value="Search" class="button"/>
</p>
</form>