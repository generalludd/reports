<?php #attendance_edit.inc
$length_status = "";
$attendLength = get_value($attendance, "attendLength","");
if($attendLength == "Half-Day"){
	$length_status = "checked=checked";
}

$attendDate = date("Y-m-j");
if(get_value($attendance,"attendDate",FALSE)){
	$attendDate = get_value($attendance,"attendDate");
}

$appointment_placeholder = "You must a time arriving, leaving, or other details for appointments";
if(get_value($attendance,"attendType")== "Appointment"){
	$placeholder = "You must a time arriving, leaving, or other details for appointments";
}
?>
<form name="attendanceEditor" id="attendanceEditor" method="post"
	action="<?php  echo site_url("attendance/$action");?>">
	<input type="hidden"
	name="kStudent" id="kStudent"
	value="<?php  echo get_value($attendance, "kStudent", $kStudent);?>" />
		<input type="hidden" name="redirect" value="<?php echo isset($redirect)?$redirect:"";?>"/>
	
<input type="hidden" name="kAttendance" id="kAttendance"
	value="<?php  echo $kAttendance; ?>" />
<input type="hidden" name="action" id="action" value="<?php  echo $action?>"/>
<p><strong><?php  echo $student;?></strong></p>
<label for="attendType">Type</label>
	<?php  echo form_dropdown("attendType",$attendTypes, get_value($attendance, "attendType"), "id='attendType'");
	?> <label for="attendDate">Date</label><input type="date"
	name="attendDate" id="attendDate" required 
	value="<?php  echo $attendDate;?>" /><br />
<p class='attend_details'><label for="attendSubtype">Subtype</label>
<?php  echo form_dropdown("attendSubtype",$attendSubtypes, get_value($attendance, "attendSubtype"), "id='attendSubtype'");?></p>
<div id="attend-length-notice">Please make sure to identify the length of the absence and add notes as needed.</div>
<p>
<label for="attendLength">Half-Day </label><input type="checkbox"
	id="attendLength" name="attendLength" value="Half-Day"
	<?php  echo $length_status;?> /><br />
</p>
<p class='half-day-type'>
<label for="attendLengthType">Arriving Late or Departing Early?</label>
<?php echo form_dropdown("attendLengthType",$length_types,get_value($attendance,"attendLengthType"),"id='attendLengthType'");?>
</p>
<p><label for="attendNote">Note:</label><input type="text"
	name="attendNote" id="attendNote" size="50"
	value="<?php  echo get_value($attendance, "attendNote");?>" <?php echo get_value($attendance,"attendType") || get_value($attendance,"attendLengthType")?"required":"";?> placeholder="Enter arrival/departure times or other notes here" /></p>
<div class='button-box'>
<input type='submit' class='button' value='Save'/>

<?php if($action == "update"):?>
<span class='delete delete-item button'>Delete</span>
<?php endif ?>

</div>
</form>
<script type="text/javascript">
$("#attendType,#attendLengthType").on("change",function(){
	my_value = $(this).val();
	console.log(my_value);
	if(my_value == "Appointment" || my_value == "early-dismissal" || my_value == "late-arrival"){
		$("#attendNote").prop("required",true);
	}else{
		$("#attendNote").prop("required",false);
	}
});
</script>>