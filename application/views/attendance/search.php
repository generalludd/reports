<h2><?php echo $title; ?></h2>
<form id='attendance_search' name='attendance_search' action='<?=site_url("attendance/search/$kStudent");?>' method='get'>
<p><label for='startDate'>Start Date: </label><input type='date' id='startDate' name='startDate' value='<?php echo date("Y-m-j");?>'/></p>
<p><label for='startDate'>End Date: </label><input type='date' id='endDate' name='endDate' value=''/></p>
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