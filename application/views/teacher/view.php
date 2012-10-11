<?php

$buttons["year"] = $year;
$buttons["term"] = $term;
$buttons["kTeach"] = $kTeach;
$button_bar =  $this->load->view("teacher/navigation",$buttons, TRUE);

$gradeStart = $teacher->gradeStart;
$gradeEnd = $teacher->gradeEnd;
if($gradeStart == 0){
	$gradeStart = "K";
}
if($gradeEnd == 0){
	$gradeEnd = "K";
}
if($gradeStart == $gradeEnd){
	$gradeRange = $gradeStart;
}else{
	$gradeRange = "$gradeStart-$gradeEnd";
}
?>
<div class='info-box'>
<input
	type="hidden" id="kTeach" name="kTeach"
	value='<? echo $teacher->kTeach; ?>'>
<h3>Info for <?php print "$teacher->teachFirst $teacher->teachLast"; ?></h3>
<?=$button_bar;?>
<div class='content'>
<?
if($kTeach == $this->session->userdata("userID") || $this->session->userdata("dbRole") == 1){

	$edit_buttons[] = array("selection"=>"edit", "class"=>"teacher_edit button edit", "id"=>"et_$kTeach", "text"=>"Edit Info");
	if($this->session->userdata("username") == "administrator"){
		$edit_buttons[] = array("selection" => "edit", "class" => "masquerade button","href" => site_url("/admin/masquerade/$kTeach"), "text" => "Masquerade" );
	}
	$edit_buttons[] = array("selection"=>"auth","type"=>"span","class"=>array("button","password_edit","edit"), "text"=>"Change Password");
	$edit_buttons[] = array("selection"=>"preference", "text" => "Preferences", "href" => site_url("preference/view/$kTeach") );
	
	
	print create_button_bar($edit_buttons);
	
}
?>
<p><label>First Name: </label><? print $teacher->teachFirst; ?></p>
<p><label>Last Name: </label><? print $teacher->teachLast; ?></p>
<p><label>User Account Status: </label> <?php 
if($teacher->status==1){
	print "Enabled";
}else{
	print "Disabled";
}
?></p>
<? //@TODO change the text version of dbRole to pull the menu variable value from the DB instead of hard coding these values ?>
<p><label>Database Role: </label> <?php 
switch($teacher->dbRole){
	case 1:
		print "Administrator/Narrative Reviewer";
		break;
	case 2:
		print "Narrative Author";
		break;
	case 3:
		print "Aide/Support Staff";
		break;
}

?></p>
<?php if($teacher->dbRole == 2):?>
<p><label>Classroom:</label> <? print $teacher->teachClass; ?></p>
<p><label>Grade Range Taught: </label><? print $gradeRange;?></p>
<fieldset><legend>Subjects Taught</legend> <?
$subjectList = array();
foreach($subjects as $subject){
	$subjectList[] =  $subject->subject;
}
echo implode("<br/>", $subjectList);

?></fieldset>

<?php endif;?>
</div>

</div>



