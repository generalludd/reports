<h2><?php echo $title; ?></h2>
<form id='attendance_search' name='attendance_search' action='<?php  echo site_url("attendance/search/$kStudent");?>' method='get'>
<p><label for='startDate'>Start Date: </label><input type='date' id='startDate' name='startDate' value='<?php echo $refine?get_cookie("attendance-startDate"):date("Y-m-j");?>'/></p>
<p><label for='startDate'>End Date: </label><input type='date' id='endDate' name='endDate' value='<?php echo $refine?get_cookie("attendance-endDate"):date("Y-m-j");?>'/></p>
<p><label for='attendType'>Type (optional):</label>
<?php  echo form_dropdown("attendType",$attendTypes,  $refine?get_cookie("attendance-attendType"):NULL, "id='attendType'");?>
</p>
<p>
<label for='attendSubtype'>Subtype (optional):</label>
<?php  echo form_dropdown("attendSubtype",$attendSubtypes, $refine?get_cookie("attendance-attendSubtype"):NULL, "id='attendSubtype'");?>
</p>
<p>
<input type="submit" value="Search" class="button"/>
</p>
</form>
<script type="text/javascript">
$("#startDate").on("change",function(event){
	$("#endDate").val($("#startDate").val());
});
</script>