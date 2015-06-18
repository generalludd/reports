<?php defined('BASEPATH') OR exit('No direct script access allowed');
$roles = array(1=>"Administrators",2=>"Editors/Teachers",3=>"Aides");
$gradeStart = 0;

if($this->input->cookie("gradeStart")){
	$gradeStart = $this->input->cookie("gradeStart");
}
$gradeEnd = 8;

if($this->input->cookie("gradeEnd")){
	$gradeEnd = $this->input->cookie("gradeEnd");
}
?>
<form name="teacher_search" id="teacher_search" method="get" action="teacher">
<p>
<label for="showInactive">Show Inactive/Former Staff</label>
<input type="checkbox" name="showInactive" id="showInactive" value="1"/>
</p>
<p>
<label for="showAdmin">Roles: </label><br/>
<?=form_multiselect("role[]",$roles,2,"id='role'");?>
</p>
<!-- <p>
<label for="gradeStart">Grade Range:</label>
<?=form_dropdown("gradeStart",$grades,$gradeStart,"id='gradeStart'");?>
-
<?=form_dropdown("gradeEnd",$grades,$gradeEnd,"id='gradeEnd'");?>
</p> -->
<p>
<input type="submit" class="button" value="Search"/>
</p>
</form>