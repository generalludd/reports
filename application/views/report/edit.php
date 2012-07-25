<?php defined("BASEPATH") OR exit("No direct script access allowed");
/****TEMPORARY VALUES *****/
$kTeach = get_value($report,"kTeach",$this->session->userdata("userID"));
$contact_method = get_value($report,"contact_method");
$contact_date = get_value($report, "contact_date");
if($contact_date){
	$contact_date = format_date($contact_date,"standard");
}
?>
<h3><?=$title;?></h3>
<form id="report-editor" name="report-editor" action="<?=site_url("report/$action");?>" method="POST">
	<input type="hidden" name="kStudent" id="kStudent"  value="<?=$kStudent;?>" />
	<input type="hidden" name="kTeach" id="kTeach" value="<?=$kTeach;?>" />
	<input type="hidden" name="kAdvisor" id="kAdvisor" value="<?=$report->kAdvisor;?>"/>
	<input type="hidden" name="kReport" id="kReport" value="<?=get_value($report,"kReport");?>"/>
	<p id="advisor-name-field">
		<label for="advisor-name">Advisor: </label>
		<?=$advisor;?>
	</p>
	<p id="category-field">
	<label for="category">Category: </label>
		<?=form_dropdown("category",$categories,get_value($report,"category"),"id='category'");?>
	</p>
	<p id="assignment-field">
	<label for="assignment">Assignment:</label><br/>
		<textarea id="assignment" name="assignment" rows="3"><?=get_value($report,"assignment");?></textarea>
	</p>
	<p id="date-field">
	<label for="report_date">Date: </label>
	<input id="report_date" name="report_date" class="datefield" value="<?=format_date(get_value($report,"report_date",date("Y-m-d")),"standard");?>"/>
	</p>
	<p id="comment-field">
	<label for="comment">Comments: </label><br/>
	<textarea id="comment" name="comment" rows="4"><?=get_value($report,"comment");?></textarea>
	</p>
	<fieldset id="parent-communication">
	<legend>Parent Communication</legend>
	<p id="parent-contact-field">
	<label for="parent_contact">Communicated with Parent(s):</label>
	<input type="text" name="parent_contact" id="parent_contact" value="<?=get_value($report,"parent_contact");?>"/>
	</p>
	<p id="contact-method-field">
	<label for="contact_method">Contact Method:</label><br/>
	<? foreach($methods as $method){
		$checked = FALSE;
		if($method->value == $contact_method){
			$checked = TRUE;
		}
		print form_radio("contact_method",$method->value,$checked,"class='radio-column'") .$method->value . "<br/>";
	}?>
	</p>
	<p id="contact-date-field">
	<label for="contact_date">Date: </label>
	<input type="text" name="contact_date" id="contact_date" class="datefield" value="<?=$contact_date;?>"/>
	</p>
</fieldset>
<? if($action == "insert"): ?>
<p><label for="email_advisor">Email Advisor</label>
<input type="checkbox" value=1 name="email_advisor" id="email_advisor" checked/>
</p>
<p><label for="email_student">Email Student</label>
<input type="checkbox" value=1 name="email_student" id="email_student"/>
</p>
<? endif;?>
<div class="button-box">
<input type="submit" value="Save" class="button"/>
<? if($action == "update"):?>
<span class="button delete report_delete">Delete</span>
<?endif;?>
</div>
</form>
