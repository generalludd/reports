<?php #student_attendance/edit.php
$length_status = "";
$attendLength = get_value($attendance, "attendLength","");
if($attendLength == 1){
	$length_status = "checked";
}

$attendDate = date("Y-m-j");
if(get_value($attendance,"attendDate",FALSE)){
	$attendDate = get_value($attendance,"attendDate");
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
<div id="attend-length-notice">Please make sure to identify the length of the absence</div>
<p>
<label for="attendLength">Half-Day </label><input type="checkbox"
	id="attendLength" name="attendLength" value="Half-Day"
	<?php  echo $length_status;?> /><br />
</p>
<p><label for="attendNote">Note:</label><input type="text"
	name="attendNote" id="attendNote" size="50"
	value="<?php  echo get_value($attendance, "attendNote");?>" /></p>
<div class='button-box'>
<input type='submit' class='button' value='Save'/>

<?php if($action == "update"):?>
<span class='delete delete-item button'>Delete</span>
<?php endif ?>

</div>
</form>
