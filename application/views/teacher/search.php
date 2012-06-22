<?php defined('BASEPATH') OR exit('No direct script access allowed');
$roles = array(1=>"Administrators",2=>"Editors/Teachers",3=>"Aides");
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
<p>
<label for="gradeStart">Grade Range:</label>
<?=form_dropdown("gradeStart",$grades,$this->session->userdata("gradeStart"),"id='gradeStart'");?>
-
<?=form_dropdown("gradeEnd",$grades,$this->session->userdata("gradeEnd"),"id='gradeEnd'");?>
</p>
<p>
<input type="submit" class="button" value="search"/>
</p>
</form>