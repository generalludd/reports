<?php defined('BASEPATH') OR exit('No direct script access allowed');
$year = get_value($assignment,"year",$this->input->cookie("year"));
$term = get_value($assignment,"term",$this->input->cookie("term"));
$gradeStart = get_value($assignment,"gradeStart",$this->input->cookie("assignment_grade_start"));
$gradeEnd = get_value($assignment,"gradeEnd",$this->input->cookie("assignment_grade_end"));
$date = get_value($assignment,"date");
	

?>
<h3><?php echo $title;?></h3>
<form id="edit-assignment" name="edit-assignment" action="<?=site_url("assignment/$action");?>" method="post">
<input type="hidden" name="kTeach" id="kTeach" value="<?=get_value($assignment,"kTeach",$this->session->userdata("userID"));?>"/>
<input type="hidden" name="kAssignment" id="kAssignment" value="<?=get_value($assignment,"kAssignment");?>"/>
<p>
<label for="assignment">Assignment: </label>
<input type="text" name="assignment" id="assignment" value="<?=get_value($assignment,"assignment");?>" required size="25"/>
</p>
<p>
<label for="kCategory">Category: </label>
<span id="cat_span">
<?=form_dropdown("kCategory",$categories,get_value($assignment,"kCategory",get_cookie("kCategory")),"id='kCategory' required");?>
</span>
</p>

<div >Enter zero points for make-up or extra-credit points
<span class='button help' id="Assignment_Zero Points" title="Why would I want to have zero points?">Help</span></div>
<p>
<label for="points">Points: </label>
<input type="text" name="points" id="points" style="width:25px" required value="<?=get_value($assignment,"points");?>"/>
<span id="points-type"></span>
</p>
<? if($action == "insert"): ?>
<p>
		<input type="checkbox" name="prepopulate" id="prepopulate" value="1" <?php echo (get_cookie("prepopulate") == 1 ? "checked":FALSE);?>/>
		<label for="prepopulate">Start every student with total points for this assignment</label>

	</p>
<? endif; ?>
<p>
<label for="date">Date: </label>
<input type="date" name="date" id="date" required value="<?=$date;?>"/>
</p>
<p>
<label for="subject">Subject: </label>
<?=form_dropdown("subject",$subjects,get_value($assignment,"subject"),"id='subject' required");?>
</p>
<p>
<label for="gradeStart">Grade: </label>
<input type="text" id="gradeStart" name="gradeStart" value="<?=$gradeStart; ?>" required size="3"
	maxlength="1"> -<input type="text" id="gradeEnd" name="gradeEnd"
	value="<?=$gradeEnd;?>" size="3"  required maxlength="1"> </p>
<p>	<label for="term">Term:
</label><?=get_term_menu('term', $term);?></p>
<p> <label for="year">Year: </label>
<?=form_dropdown('year',get_year_list(), get_value($assignment,"year",$this->input->cookie("year")), "id='year' class='year'");?>
-<input id="yearEnd" type="text" name="yearEnd" class='yearEnd' readonly
	maxlength="4" size="5" value="<? $yearEnd=$year+1;print $yearEnd; ?>" /></p>
<div class="button-box">
<input type="submit" class="button" value="Save"/>
<? if($action == "update"): ?>
<div class="button delete assignment-delete">Delete</div>
<? endif;?>
</div>

</form>