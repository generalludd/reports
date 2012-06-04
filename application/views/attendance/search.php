<?php ?>
<form id='attendance_search' name='attendance_search' action='<?=site_url("attendance/search");?>' method='post'>
<input type="hidden" value="<?=$kStudent;?>" name="kStudent" id="kStudent"/>
<?php if($kStudent):?>
<p>Search for attendance records for <?=$student;?></p>
<?php endif;?>
<p><label for='startDate'>Start Date: </label><input type='text' class='datefield' id='startDate' name='startDate' value=''/></p>
<p><label for='startDate'>End Date: </label><input type='text' class='datefield' id='endDate' name='endDate' value=''/></p>
<p><label for='attendType'>Type (optional):</label>
<?=form_dropdown("attendType",$attendTypes, NULL, "id='attendType'");?>
</p>
<p>
<label for='attendSubtype'>Subtype (optional):</label>
<?=form_dropdown("attendSubtype",$attendSubtypes, NULL, "id='attendSubtype'");?>
</p>
<p>
<input type="submit" value="Search" class="button"/>
</p>
</form>