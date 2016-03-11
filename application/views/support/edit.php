<?php
$meetingChecked = "";
$meeting = get_value ( $support, "meeting" );
if ($meeting == 1) {
	$meetingChecked = "checked";
}

$iepChecked = "";
$hasIEP = get_value ( $support, "hasIEP", 0 );
if ($hasIEP == 1) {
	$iepChecked = "checked";
}

$sppsChecked = "";
$hasSPPS = get_value ( $support, "hasSPPS", 0 );
if ($hasSPPS == 1) {
	$sppsChecked = "checked";
}
$studentName = format_name ( $support->stuFirst, $support->stuLast, $support->stuNickname );
$buttonBar = "";
if (! isset ( $year )) {
	$year = get_value ( $support, "year", get_current_year () );
}
$year_end = $year + 1;
$test_date = get_value ( $support, "testDate" );
$hide_item = "hidden";
$show_item = "";
if ($action == "update") {
	$hide_item = "";
	$show_item = "hidden";
}

$buttons [] = array (
		"selection" => "save-continue",
		"text" => "Save &amp; Continue",
		"class" => "save-continue-support button",
		"type" => "a" 
);
$buttons [] = array (
		"selection" => "save-close",
		"text" => "Save &amp; Close",
		"class" => "save-close-support button",
		"type" => "a" 
);
$buttons [] = array (
		"selection" => "cancel",
		"text" => "Cancel",
		"class" => "button cancel-support-edit",
		"type" => "a" 
);
$buttons [] = array (
		"selection" => "print",
		"text" => "Print",
		"class" => "button $hide_item",
		"id" => "print-support",
		"type" => "a" 
);
$buttons [] = array (
		"selection" => "delete",
		"text" => "Delete",
		"class" => "delete button $hide_item",
		"id" => "delete-support",
		"type" => "a" 
);

$button_box = create_button_bar ( $buttons );
?>
<form name="support-editor" id="support-editor" class="editor" action="<?php echo site_url("support/$action");?>" method="post">


	<?php echo $button_box;?>

	<input type="hidden" name="action" id="action" value="<?php echo $action?>" />
	<input type="hidden" name="ajax" id="ajax" value="" />
	<input type="hidden" name="kStudent" id="kStudent" value="<?php echo get_value($support,"kStudent"); ?>" />
	<input type="hidden" name="kSupport" id="kSupport" value="<?php echo get_value($support,"kSupport"); ?>" />
	<h3>Special Needs Support Summary for <?php echo $studentName;?></h3>
	<div id='message' class='message'></div>
	<p>
		<label for="year">Year for this Documentation:</label> <?php echo form_dropdown("year", $year_list, $year, "id='year' class='yearStart'"); ?>-
		<input id='yearEnd' name='yearEnd' size="5" maxlength="4" value='<?php echo $year_end; ?>' readonly />
	</p>
	<p>
		<input type="checkbox" id="meeting" name="meeting" value="1" <?php echo $meetingChecked;?> /> Fall Meeting Completed for <?php echo format_schoolyear(get_value($support,"year", $year)); ?>
	</p>
	<p>
		<label for="testDate">Date <?php echo $support->stuFirst; ?> was formally tested: </label>
		<input type="date" name="testDate" id="testDate" value="<?php echo $test_date;?>" size="17">
	</p>
	<div class='<?php if(get_cookie("accordion") == "enable"){print "accordion";} ?>' id='needAccordion'>
		<h3>
			<a>Special Need/Diagnosis</a>
		</h3>
		<div>
			<p class="notice">Use this only for description/diagnosis. Please enter meds, Galtier attendance, etc under "Outside Services" section below
				this.</p>
			<textarea name="specialNeed" id="specialNeed" class="tinymce" style="width: 99%" rows="25" cols="91"><?php echo get_value($support,"specialNeed");?></textarea>
		</div>
		<h3 id="iep-section">
			<a>IEP and SPPS Support</a>
		</h3>
		<div>
			<p>
				<label for="hasIEP">Has Active IEP:</label>
				<input type="checkbox" id="hasIEP" name="hasIEP" value="1" <?php echo $iepChecked; ?> />
			</p>
			<p>
				<label for="hasSPPS">Receives SPPS Services</label>
				<input type="checkbox" id="hasSPPS" name="hasSPPS" value="1" <?php echo $sppsChecked; ?> />
			</p>
		</div>
		<h3>
		<a href="#">Accommodations at FSM</a>
		</h3>
		<div>
			<textarea name="modification" id="modification" class="tinymce" style="width: 99%" rows="13" cols="91"><?=get_value($support,"modification"); ?></textarea>
		</div>
		<h3 id="services-section">
			<a>Outside services, Galtier, medications or other treatments</a>
		</h3>
		<div>
			<div class='notice'>i.e. Orton-Gillingham, Galtier, Homework Help, etc. Include start and end date if applicable.</div>
			<textarea name="outsideSupport" id="outsideSupport" rows="15" style="width: 99%" class="tinymce"><?php echo get_value($support, "outsideSupport"); ?></textarea>
		</div>
		<h3 id="attachment-section">
			<a>File Attachments</a>
		</h3>
		<div>
			<div class="alert hidden" id="attachment-content-warning" style="width: 45%">Make sure that any important points covered in attached files
				are also summarized above. This makes it easier for faculty and aides to quickly read a student's profile.</div>
			<p>
				<span class='insert-message notice <?php echo $show_item;?>'>You must save this document before you can attach files.</span>

				<span class="button show-support-file-uploader <?php echo $hide_item;?>">Add a File</span>
			</p>
		
			<? if($support_files): ?>
				<table id="support-file-list" class="files list">
				<thead>
					<tr>
						<th>
							<strong>File Name</strong>
						</th>
						<th>
							<strong>Description</strong>
						</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
						<? foreach($support_files as $file):?>
							<tr id='fr_<?php echo $file->kFile;?>'>
						<td>
							<a href='<?php echo base_url("uploads/$file->file_name");?>' target='_blank'><?php echo $file->file_display_name;?></a>
						</td>
						<td><?php echo $file->file_description;?></td>
						<td>
							<span class='button delete delete-support-file' id='dsf_<?php echo $file->kFile;?>'>Delete</span>
						</td>
					</tr>
						<?endforeach; ?>
					</tbody>
			</table>
			<?php endif; ?>
		</div>


	</div>

</form>

<script type="text/javascript">
$(".show-support-file-uploader").live("mouseover", function(){
	$("#attachment-content-warning").slideDown();
});
	</script>