<?php  
$isAdvisor = get_value($teacher,"isAdvisor",FALSE);
$advisor_checked = "";
if($isAdvisor){
	$advisor_checked = "checked";
}


?>

<form id="teacher_edit" action="<?=site_url("teacher/$action");?>" method="post" name="teacher_edit">
<fieldset><legend>General Info</legend>
<input type="hidden" id="kTeach" name="kTeach" value="<?=get_value($teacher,'kTeach');?>"/>
<p><label for="teachFirst">First Name</label>
<input type="text" id="teachFirst" class="required" name="teachFirst" value="<?=get_value($teacher,'teachFirst');?>"/>
</p><p>
<label for="teachLast">Last Name</label>
<input type="text" id="teachLast" class="required" name="teachLast" value="<?=get_value($teacher,'teachLast');?>"/>
</p>

<?php if($this->session->userdata("dbRole") == 1 || $this->session->userdata("userID") == get_value($teacher,'kTeach',0)):?>
<p>
<label for="username">Username</label>
<input type="text" id="username" class="required" name="username" value="<?=get_value($teacher, 'username');?>"/>
</p>
<?php endif;?>
<p>
<label for="email">Email Address</label>
<input type="text" id="email" class="required" name="email" value="<?=get_value($teacher, 'email');?>" />
</p>

<? if($this->session->userdata("dbRole") == 1 && $this->session->userdata("userID") == ROOT_USER){
		echo "<p><label for='dbRole'>User Role</label>";
		echo form_dropdown('dbRole', $dbRoles, get_value($teacher,'dbRole'), 'id="dbRole"');
		echo "</p><p><label for='status'>User Status</label>";
		echo form_dropdown('status', $userStatus, get_value($teacher,'status'), 'id="status"');
		echo "</p>";
	
}?>

<? if(get_value($teacher,"dbRole",NULL) == 2):?>
<label for="isAdvisor">Is a Middle School Advisor: </label>
<input type="checkbox" name="isAdvisor" id="isAdvisor" value="1" <?=$advisor_checked;?>/>
<?endif;?>
</fieldset>

<?php  if(get_value($teacher, "dbRole",NULL) >= 2):?>
<fieldset><legend>Classroom Information</legend>
<?php if(get_value($teacher,"dbRole",NULL)==2):?>

	<p><label for='teachClass'>Classroom</label> 
	<?=form_dropdown('teachClass', $classrooms, get_value($teacher, 'teachClass'), 'id="teachClass"');?></p>
	<?php endif;?>
	<fieldset><legend>Range of Grades Taught</legend>
<p><label for='gradeStart'>Start Grade</label>
<?=form_dropdown('gradeStart', $grades, get_value($teacher, 'gradeStart'), 'id="gradeStart"');?></p>
	<p><label for='gradeEnd'>End Grade</label>
	
    <?=form_dropdown('gradeEnd', $grades, get_value($teacher, 'gradeEnd'), 'id="gradeEnd"');?></p>
</fieldset>
</fieldset>
<? endif;?>
	<p>
	<input type="submit" class='button save_teacher' value="Save"/></p>
</form>