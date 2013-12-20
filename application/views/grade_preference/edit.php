<?php defined('BASEPATH') OR exit('No direct script access allowed');
$year = get_value($preference, "school_year",get_current_year());
$pass_fail_check ="";
if(get_value($preference,"pass_fail") == 1){
	$pass_fail_check = "checked";
}
?>
<form name="grade-preference-editor" id="grade-preference-editor" action="<?=site_url("grade_preference/$action");?>" method="post">
<input type="hidden" name= "kStudent" id="kStudent" value="<?=$kStudent;?>"/>
<?=form_dropdown("subject", $subjects, get_value($preference,"subject"), "id='subject'");?><br/>
<input type="text" style="width:5ex" name="school_year" id="school_year" value="<?=$year;?>"/>
-<?=$year + 1;?><br/>
<label for="pass_fail">Is Pass Fail?</label>
<input type="checkbox" name="pass_fail" id="pass_fail" value="1" <?=$pass_fail_check;?>/><br/>
<input type="submit" name="submit" value="<?=ucfirst($action);?>"/>
</form>