<?php

defined('BASEPATH') or exit('No direct script access allowed');

// row.php Chris Dart Jan 28, 2015 4:24:29 PM chrisdart@cerebratorium.com

?>
<tr class="assignment">
<td>
<?php echo form_dropdown("subject[]",$subjects,$subject,"required");?>
</td>
	<td>
<?php echo form_dropdown("kCategory[]",$categories,"","id='kCategory' required");?>
</td>
	<td><input
		type="text"
		name="assignment[]"
		required
		id="assignment"
		value="" /></td>
	<td><input
		type="text"
		style="width: 25px"
		required
		name="points[]"
		id="points"
		value="" /></td>
	<td><input
		type="checkbox"
		name="prepopulate[]"
		id="prepopulate"
		value="1" /></td>
	<td><input
		type="date"
		name="date[]"
		id="date"
		required
		size="13"
		value="" /></td>
</tr>
