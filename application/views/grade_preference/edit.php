<?php defined('BASEPATH') OR exit('No direct script access allowed');
$year = get_value($preference, "school_year",get_current_year());
$pass_fail_check ="";
if(get_value($preference,"pass_fail") == 1){
	$pass_fail_check = "checked";
}
?>
<h3><?php echo $title;?></h3>
<form name="grade-preference-editor" id="grade-preference-editor" action="<?=site_url("grade_preference/$action");?>" method="post">
<input type="hidden" name= "kStudent" id="kStudent" value="<?=$kStudent;?>"/>
<p>
<?=form_dropdown("subject", $subjects, get_value($preference,"subject"), "id='subject'");?></p>
<p>
<input type="text" style="width:5ex" name="school_year" id="school_year" value="<?=$year;?>"/>
-<?=$year + 1;?></p>
<p>
<p>
<?php echo get_term_menu("term",TRUE,get_value($preference,"term"));?>
</p>
<label for="pass_fail">Is Pass Fail?</label>
<input type="checkbox" name="pass_fail" id="pass_fail" value="1" <?=$pass_fail_check;?>/></p>
<p>
<input type="submit" name="submit" value="<?=ucfirst($action);?>"/>
</p>
</form>