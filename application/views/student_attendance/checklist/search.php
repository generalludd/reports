<?php 

$dbRole = $this->session->userdata("dbRole");
//does the teacher only teach two grade ranges or fewer?
$lowerschool = $teacher->gradeEnd - $teacher->gradeStart <=1?TRUE:FALSE ;
$cookie_day = "";//sprintf("%s-", date("D"));
$gradeStart = get_cookie($cookie_day."gradeStart");
if(!$gradeStart){
	$gradeStart = format_grade($teacher->gradeStart);
}

$gradeEnd = get_cookie($cookie_day."gradeEnd");
if(!$gradeEnd){
	$gradeEnd = format_grade($teacher->gradeEnd);
}

?>
<h2><?php echo $title;?></h2>
<p>Enter the grade range and select the appropriate values as needed.</p>
<form id='attendance-check' name='attendance-check' action='<?=site_url("attendance/check");?>' method='get'>
<p><label for='date'>Date: </label><input type='date' id='date' name='date' value='<?php echo date("Y-m-d");?>' required/></p>
<p><label for='gradeStart'>Grade Range: </label><input type="text" name="gradeStart" id="gradeStart" class="grade" value="<?php echo $gradeStart; ?>" required/>-
<input type="text" name="gradeEnd"  class="grade" id="gradeEnd" value="<?php echo $gradeEnd;?>" required/></p>
<?php $css_class = !get_cookie($cookie_day . "kTeach") && (get_cookie($cookie_day . "humanitiesTeacher") || get_cookie($cookie_day. "stuGroup"))?"visible":"hidden";?>
<div class="middle-school <?php echo $css_class;?>">
<p class="humanities-teacher"><label for="humanitiesTeacher">Humanities Teacher: </label><?php echo form_dropdown("humanitiesTeacher",$humanities_teachers,get_cookie($cookie_day . "humanitiesTeacher"),"id='humanitiesTeacher'");?>
</p>
<p>-OR-</p>
<p><label for="stuGroup">Middle School Student Group: </label><?php echo form_dropdown("stuGroup",$stuGroup,get_cookie($cookie_day . "stuGroup"),"id='stuGroup'");?></p>
</div>
<?php $css_class = get_cookie($cookie_day. "kTeach" ) || ($lowerschool && $teacher->gradeStart < 5 && $teacher->gradeStart !=0) ?"visible":"hidden";?>
<?php $kTeach = get_cookie($cookie_day. "kTeach");?>
<?php $kTeach = $kTeach?$kTeach:$this->session->userdata("userID");?>
<div class="lower-school <?php echo $css_class;?>">
<p><label for="kTeach">Classroom Teacher or Advisor: </label><span id="kTeach-wrapper"><?php echo form_dropdown("kTeach",$teachers,get_cookie($cookie_day . "kTeach"),"id='kTeach'");?></span>
</p>
</div>
<p>
<input type="submit" value="Search" class="button"/>
</p>
</form>