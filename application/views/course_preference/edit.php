<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>
<h3><?php echo $title;?></h3>
<form name="course-preference-editor" id="course-preference-editor" action="<?php  echo site_url("course_preference/$action");?>" method="post">
<input type="hidden" name= "kStudent" id="kStudent" value="<?php  echo $kStudent;?>"/>
<p>
<?php  echo form_dropdown("subject", $subjects, get_value($preference,"subject"), "id='subject'");?></p>
<p>
<input type="text" style="width:5ex" name="school_year" id="school_year" value="<?php  echo $year;?>"/>
-<?php  echo $year + 1;?></p>
<p>
<label for="preference">Preference</label>
<?php echo form_dropdown("preference",$course_preferences,get_value($preference,"preference"));?>
</p>
<p>
<input type="submit" name="submit" class="button create" value="<?php  echo ucfirst($action);?>"/>
</p>
</form>