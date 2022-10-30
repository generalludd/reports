<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h3><?php  echo $title;?></h3>
<?php
$nav_buttons[] = array("selection" => "student", "text" => "Student Info", "class" => "button info", "href"=>site_url("student/view/$report->kStudent"));
//$nav_buttons[] = array("selection" => "report","text"=> sprintf("List %ss",STUDENT_REPORT),"class" =>"button","href" => site_url("report/get_list/student/$report->kStudent"));
print create_button_bar($nav_buttons);

$edit_buttons[] = array("selection" => "report", "text" => "Edit Report", "class" => "button edit report-edit","id"=>"re_$report->kReport", "href"=>site_url("report/edit/$report->kReport"));
print create_button_bar($edit_buttons);
?>
	<div  class='field' id="date-field">
		<label>Date: </label>
		<?php  echo format_date($report->report_date);?>
	</div>
	<div class='field' id="advisor-name-field">
		<label for="advisor-name">Advisor: </label>
		<?php  echo $advisor;?>
	</div>
	<div class='field' id='teacher-name-field'>
	<label>Submitted by: </label><?php  echo format_name($report->teachFirst,$report->teachLast);?></div>
	<div class='field' id="category-field">
	<label>Category: </label>
		<?php  echo $report->category;?>
	</div>
	<?php if($report->category == "Missing Homework"):?>
	<div class="field" id="assignment-status-field">
	<?php if($report->assignment_status == 1): ?>
	<span style="margin-left:1em"><strong>Turned In Late</strong></span>
	<?php endif;?>
	</div>
	<?php endif;?>
	<div class='field' id="assignment-field">
	<label>Assignment:</label>
		<?php  echo $report->assignment;?>
	</div>


	<div class='field' id="comment-field">
	<label>Comments: </label>
	<?php  echo $report->comment;?>
	</div>
	<div id="parent-communication">
	<div class='field' id="parent-contact-field">
	<label>Communicated with Parent(s):</label>
	<?php  echo $report->parent_contact;?>
	</div>
	<div class='field' id="contact-method-field">
	<label>Contact Method: </label>
	<?php  echo $report->contact_method;?>
	</div>
	<div class='field' id="contact-date-field">
	<label for="contact_date">Date: </label>
	<?php if($report->contact_date){
		print format_date($report->contact_date);
	}?>
	</div>
</div>