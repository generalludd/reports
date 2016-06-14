<?php

$data["year"] = $year;
$data["term"] = $term;
$data["kTeach"] = $kTeach;
$button_bar =  $this->load->view("teacher/navigation",$data, TRUE);

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
<input
	type="hidden" id="kTeach" name="kTeach"
	value='<? echo $teacher->kTeach; ?>'>
<h3>Info for <?php print "$teacher->teachFirst $teacher->teachLast"; ?></h3>
<?=$button_bar;?>
<div class='content inner'>
<?

$userID = $this->session->userdata("userID");
if($kTeach == $userID || $userID == ROOT_USER){

	$edit_buttons[] = array("selection"=>"edit", "class"=>"button edit dialog","href"=>site_url("teacher/edit?kTeach=$kTeach"), "text"=>"Edit Info");

	$edit_buttons[] = array("selection"=>"auth","class"=>array("button","dialog","edit"),"href"=>site_url("user/edit_password?kTeach=$kTeach"), "text"=>"Change Password");
	$edit_buttons[] = array("selection"=>"preference", "text" => "Preferences", "href" => site_url("preference/view/$kTeach") );
	if($userID == ROOT_USER && $kTeach != $userID){
		$edit_buttons[] = array("selection" => "edit", "class" => "masquerade button","href" => site_url("/admin/masquerade/$kTeach"), "text" => "Masquerade" );
	}

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
<?php  if(get_value($teacher, "dbRole",NULL) == 2):?>
<p><label>Classroom:</label> <? print $teacher->teachClass; ?></p>
<p><label>Grade Range Taught: </label><? print $gradeRange;?></p>


	<h4>Subjects Taught</h4>
	<p>This information is used to generate any menus indicating the subjects
	<? if($userID == $teacher->kTeach){
		print " you teach.";
	}else{
		print get_value($teacher, 'teachFirst'). " teaches.";
	}?></p>
<?
$this->load->view("teacher/subject_list");
echo "<p><span class='add_subject button small new' id='t_$teacher->kTeach'>Add a Subject</span></p>";

endif;?>
</div>



