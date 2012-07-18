<?php defined('BASEPATH') OR exit('No direct script access allowed');
$year = get_value($assignment,"year",$this->session->userdata('year'));
$term = get_value($assignment,"term",$this->session->userdata('term'));
$gradeStart = get_value($assignment,"gradeStart",$this->session->userdata('gradeStart'));
$gradeEnd = get_value($assignment,"gradeEnd",$this->session->userdata('gradeEnd'));
$date = "";
if($date = get_value($assignment,"date")){
	$date = format_date($date,"standard");
}
?>
<form id="edit-assignment" name="edit-assignment" action="<?=base_url("assignment/$action");?>" method="post">
<input type="hidden" name="kTeach" id="kTeach" value="<?=get_value($assignment,"kTeach",$this->session->userdata("userID"));?>"/>
<input type="hidden" name="kAssignment" id="kAssignment" value="<?=get_value($assignment,"kAssignment");?>"/>
<p>
<label for="assignment">Assignment: </label>
<input type="text" name="assignment" id="assignment" value="<?=get_value($assignment,"assignment");?>" size="25"/>
</p>
<p>
<label for="category">Category: </label>
<span id="cat_span">
<?=form_dropdown("category",$categories,get_value($assignment,"category"),"id='category'");?>
</span>
</p>
<p>
<label for="points">Points: </label>
<input type="text" name="points" id="points" value="<?=get_value($assignment,"points");?>"/>
</p>
<p>
<label for="date">Date: </label>
<input type="text" name="date" id="date" class="datefield" value="<?=$date;?>"/>
</p>
<p>
<label for="subject">Subject: </label>
<?=form_dropdown("subject",$subjects,get_value($assignment,"subject"),"id='subject'");?>
</p>
<p>
<label for="gradeStart">Grade: </label>
<input type="text" id="gradeStart" name="gradeStart" value="<?=$gradeStart; ?>" size="3"
	maxlength="1"> -<input type="text" id="gradeEnd" name="gradeEnd"
	value="<?=$gradeEnd;?>" size="3" maxlength="1"> </p>
<p>	<label for="term">Term:
</label><?=get_term_menu('term', $term);?></p>
<p> <label for="year">Year: </label>
<?=form_dropdown('year',get_year_list(), get_value($assignment,"year",$this->session->userdata('year')), "id='year' class='searchYear'");?>
-<input id="yearEnd" type="text" name="yearEnd" class='yearEnd' readonly
	maxlength="4" size="5" value="<? $yearEnd=$year+1;print $yearEnd; ?>" /></p>
<p>
<input type="submit" class="button" value="Save"/>
</p>

</form>