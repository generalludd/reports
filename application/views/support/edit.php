<?php

$meetingChecked = "";
$meeting = get_value($support, "meeting");
if($meeting == 1){
	$meetingChecked = "checked";
}

$iepChecked = "";
$hasIEP = get_value($support, "hasIEP", 0);
if($hasIEP == 1 ){
	$iepChecked = "checked";
}

$sppsChecked = "";
$hasSPPS = get_value($support, "hasSPPS", 0);
if($hasSPPS == 1 ){
	$sppsChecked = "checked";
}
$studentName = format_name($support->stuFirst, $support->stuLast, $support->stuNickname);
$buttonBar = "";

$year = get_value($support,"year", get_current_year());
$year_end = $year + 1;
$test_date = get_value($support,"testDate");
if(!empty($test_date)){
	$test_date = format_date($test_date, "standard");
}else{
	$test_date = "";
}
$hide_item = "hidden";
$show_item = "";
if($action == "update"){
	$hide_item = "";
	$show_item = "hidden";
}

$buttons[] = array("selection" => "save-continue", "text" => "Save &amp; Continue","class" => "save-continue-support button", "type" => "span");
$buttons[] = array("selection" => "save-close", "text" => "Save &amp; Close", "class" => "save-close-support button","type" => "span");
$buttons[] = array("selection" => "cancel", "text" =>"Cancel","class" => "button cancel-support-edit", "type" => "span" );
$buttons[] = array("selection" => "print", "text" => "Print", "class" => "button $hide_item", "id"=>"print-support", "type" => "span");
$buttons[] = array("selection" => "delete", "text" => "Delete", "class" => "delete button $hide_item", "id"=>"delete-support", "type" => "span");

$button_box = create_button_bar($buttons, array("id"=>"support-editor-buttons"));
?>
<form id="support-editor" action="<?=site_url("support/$action")?>"
    method="post" name="support-editor">


<?=$button_box;?>

	<input type="hidden" name="action" id="action" value="<?=$action?>"/>
	<input type="hidden" name="ajax" id="ajax" value=""/>
	<input type="hidden" name="kStudent"
	id="kStudent" value="<?=get_value($support,"kStudent"); ?>" /> <input
	type="hidden" name="kSupport" id="kSupport"
	value="<?=get_value($support,"kSupport"); ?>" />
<h3>Special Needs Support Summary for <?=$studentName;?></h3>
<div id='message' class='message'></div>
<label for="year">Year for this Documentation:</label> <?=form_dropdown("year", $year_list, $year, "id='year' class='yearStart'"); ?>-
<input id='yearEnd' name='yearEnd' size="5" maxlength="4"
	value='<?=$year_end; ?>' readonly /><br />
<p><input type="checkbox" id="meeting" name="meeting" value="1"
<?=$meetingChecked;?> /> Fall Meeting Completed for <?=format_schoolyear(get_value($support,"year", $year)); ?></p>
<p>Date <?=$support->stuFirst; ?> was formally tested:<input type="text"
	name="testDate" id="testDate" class="datefield"
	value="<?=$test_date;?>" size="17"></p>
<div class='<?php if(get_cookie("accordion") == "enable"){print "accordion";} ?>' id='needAccordion'>
<h3><a href="#" style="color:#000;text-decoration:none;">Special Need/Diagnosis</a></h3>
<div>
<p class="notice">Please enter meds, Galtier attendance, etc under
"Outside Services" below</p>
<textarea name="specialNeed" id="specialNeed" class="tinymce"
	style="width: 99%" rows="25" cols="91"><?=get_value($support,"specialNeed");?></textarea></div>
<h3 id="iep-section"><a href="#" style="color:#000;text-decoration:none;">IEP and SPPS Support</a></h3>
<div>
<p>Has Active IEP: <input type="checkbox" id="hasIEP" name="hasIEP"
	value="1" <?=$iepChecked; ?> /><br />

Receives SPPS Services <input type="checkbox" id="hasSPPS"
	name="hasSPPS" value="1" <?=$sppsChecked; ?> /></p>
</div>
<h3 id="services-section"><a href="#" style="color:#000;text-decoration:none;">Outside services, Galtier, medications or other
treatments</a></h3>
<div><div class='notice'>(i.e. Orton-Gillingham, Galtier, Homework
Help, etc. Include start and end date if applicable</div> <textarea
	name="outsideSupport" id="outsideSupport" rows="15" style="width: 99%"
	class="tinymce"><?=get_value($support, "outsideSupport"); ?></textarea></div>
<h3 id="modifications-section"><a href="#" style="color:#000;text-decoration:none;">Accommodations at FSM</a></h3>
<div><textarea name="modification" id="modification" class="tinymce"
	style="width: 99%" rows="25" cols="91"><?=get_value($support,"modification"); ?></textarea>
</div>

<h3 id="attachment-section"><a href="#" style="color:#000;text-decoration:none;">File Attachments</a></h3>
<div>
<div class="alert hidden" id="attachment-content-warning" style="width:45%">Make sure that any important points covered in attached files are also summarized above. This makes it easier for faculty and aides to quickly read a student's profile.</div>
<p>
<span class='insert-message notice <?=$show_item;?>'>You must save this document before you can attach files.</span>

<span class="button show-support-file-uploader <?=$hide_item;?>">Add a File</span></p>

<? if($support_files): ?>
<table id="support-file-list" class="files list">
	<thead>
		<tr>
			<th><strong>File Name</strong></th>
			<th><strong>Description</strong></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<? foreach($support_files as $file):?>
	<tr id='fr_$file->kFile'><td><a href='<?=base_url("uploads/$file->file_name");?>' target='_blank'><?=$file->file_display_name;?></a></td>
	<td><?=$file->file_description;?></td>
	<td><span class='button delete delete-support-file' id='dsf_<?=$file->kFile;?>'>Delete</span></td></tr>
	<?endforeach; ?>
	</tbody>
</table>
	<?php endif; ?></div>
</div>

	</form>

	<script type="text/javascript">
$(".show-support-file-uploader").live("mouseover", function(){
	$("#attachment-content-warning").slideDown();
});

	</script>