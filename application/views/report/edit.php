<?php defined("BASEPATH") OR exit("No direct script access allowed");
$contact_method = get_value($report,"contact_method");
$contact_date = get_value($report, "contact_date");
if($contact_date){
	$contact_date = format_date($contact_date);
}
?>


<div id="orange-slip" class="half-page">
<form id="report-editor" name="report-editor"
	action="<?php  echo site_url("report/$action");?>" method="POST">
	<input type="hidden" name="kStudent" id="kStudent"
		value="<?php  echo $kStudent;?>" />
		
		<input type="hidden"
		name="kAdvisor" id="kAdvisor" value="<?php  echo $report->kAdvisor;?>" /> <input
		type="hidden" name="kReport" id="kReport"
		value="<?php  echo get_value($report,"kReport");?>" />
		<?php if($is_teacher): ?>
		<input type="hidden" name="kTeach"
		id="kTeach" value="<?php  echo $kTeach;?>" />
		<?php else: ?>
		<label for="kTeach"><?php  echo sprintf("Submitting %s on Behalf of:",STUDENT_REPORT);?></label>
		<?php  echo form_dropdown("kTeach",$teachers,get_value($report,"kTeach",$kTeach),"id='kTeach'");?>
		<?php endif;?>
	<p id="student-name-field">
		<label for="student-name">Student: </label>
		<?php  echo $student;?>
	</p>
	<p id="advisor-name-field">
		<label for="advisor-name">Advisor: </label>
		<?php  echo $advisor;?>
	</p>
	<p id="date-field">
		<label for="report_date">Date: </label> <input type="date" id="report_date"
			name="report_date"
			value="<?php  echo get_value($report,"report_date",date("Y-m-d"));?>" />
	</p>
	<?php if($action == "insert"): ?>
		<label for="email_advisor">Email Advisor</label> <input
			type="checkbox" value=1 name="email_advisor" id="email_advisor"
			checked />&nbsp;|&nbsp;
		<label for="email_student">Email Student</label> <input
			type="checkbox" value=1 name="email_student" id="email_student" />

			<?php endif;?>
	<?php if($this->session->userdata("userID") == get_value($report,"kAdvisor")): ?>
	<p id="is_read-field">
	<!-- automatically check as read when the teacher opens the editor -->
	<label for="is_read">Is Read:</label>
	<input type="checkbox" name="is_read" id="is_read" checked value="1"/>
	</p>
	<p id="rank-field">
		<label for="Rank">Rank:</label>
		<?php  echo form_dropdown("rank",$ranks,get_value($report,"rank",0),"id='rank'");?>

	</p>
	<?php else: ?>
	<input type="hidden" name="rank" id="rank" value="unread" />

	<?php endif;?>
	<p id="category-field">
		<label for="category">Category: </label>
		<?php  echo form_dropdown("category",$categories,get_value($report,"category"),"id='category'");?>
	</p>
	<?php 
	$checked = "";
	if(get_value($report,"assignment_status")){
		$checked = "checked";
		
	}
	$display = "none";
	if(get_value($report,"category") == "Missing Homework"){
		$display = "block";
	}
	?>
	<p id="assignment-status-field" style="margin-left:1em; display: <?php  echo $display;?>;">
	    <label for="assignment_status">Turned in Late:</label>
	   <input type="checkbox" name="assignment_status" id="assignment_status" <?php  echo $checked;?> value="1"/>
	</p>
	<p id="assignment-field">
		<label for="assignment">Assignment:</label><br />
		<textarea id="assignment" name="assignment" rows="3"><?php  echo get_value($report,"assignment");?></textarea>
	</p>

	<p id="comment-field">
		<label for="comment">Comments: </label><br />
		<textarea id="comment" name="comment" rows="4"><?php  echo get_value($report,"comment");?></textarea>
	</p>
	<fieldset id="parent-communication">
		<legend>Parent Communication</legend>
		<p id="parent-contact-field">
			<label for="parent_contact">Communicated with Parent(s):</label> <input
				type="text" name="parent_contact" id="parent_contact"
				value="<?php  echo get_value($report,"parent_contact");?>" />
		</p>
		<p id="contact-method-field">
			<label for="contact_method">Contact Method:</label><br />
			<?php foreach($methods as $method){
				$checked = FALSE;
				if($method->value == $contact_method){
					$checked = TRUE;
				}
				print form_radio("contact_method",$method->value,$checked,"class='radio-column'") .$method->value . "<br/>";
	}?>
		</p>
		<p id="contact-date-field">
			<label for="contact_date">Date: </label> <input type="date"
				name="contact_date" id="contact_date"
				value="<?php  echo $contact_date;?>" />
		</p>
	</fieldset>

	<div class="button-box">
		<input type="submit" value="Save" class="button" />
		<?php if($action == "update"):?>
		<span class="button delete report_delete">Delete</span>
		<?php endif;?>
	</div>
</form>
</div>