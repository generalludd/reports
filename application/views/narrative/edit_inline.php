<?php
?>

<form id="narrative_inline_editor" name="narrative_inline_editor"
	action="<?php  echo site_url("narrative/update_inline");?>" method="post">
	<input type="hidden"
	name="kNarrative" id="kNarrative" value='<?php  echo "$narrative->kNarrative"; ?>' />
	<input type="hidden"
    name="kTeach" id="kTeach" value='<?php  echo "$narrative->kTeach"; ?>' />

<div><textarea id="narrText_<?php echo $narrative->kNarrative;?>" name="narrText" class="ckeditor auto-save" style="width: 99.75%;" rows="19" cols="107"><?php  echo stripslashes($narrative->narrText);?></textarea></div>
<p><span class="button new save_narrative_inline">Save</span></p>
</form>
