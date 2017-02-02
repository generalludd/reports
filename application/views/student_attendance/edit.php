<?php #attendance_edit.inc
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
	action="<?=site_url("attendance/$action");?>">
	<input type="hidden"
	name="kStudent" id="kStudent"
	value="<?=get_value($attendance, "kStudent", $kStudent);?>" />
<input type="hidden" name="kAttendance" id="kAttendance"
	value="<?=$kAttendance; ?>" />
<input type="hidden" name="action" id="action" value="<?=$action?>"/>
<p><strong><?=$student;?></strong></p>
<label for="attendType">Type</label>
	<?=form_dropdown("attendType",$attendTypes, get_value($attendance, "attendType"), "id='attendType'");
	?> <label for="attendDate">Date</label><input type="date"
	name="attendDate" id="attendDate" required 
	value="<?=$attendDate;?>" /><br />
<p class='attend_details'><label for="attendSubtype">Subtype</label>
<?=form_dropdown("attendSubtype",$attendSubtypes, get_value($attendance, "attendSubtype"), "id='attendSubtype'");?></p>
<div id="attend-length-notice">Please make sure to identify the length of the absence</div>
<p>
<label for="attendLength">Half-Day </label><input type="checkbox"
	id="attendLength" name="attendLength" value="Half-Day"
	<?=$length_status;?> /><br />
</p>
<p><label for="attendNote">Note:</label><input type="text"
	name="attendNote" id="attendNote" size="50"
	value="<?=get_value($attendance, "attendNote");?>" /></p>
<div class='button-box'>
<input type='submit' class='button' value='Save'/>

<?php if($action == "update"):?>
<span class='delete delete-item button'>Delete</span>
<?php endif ?>

</div>
</form>
