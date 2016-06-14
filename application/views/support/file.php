<?php
?>
<div style="width: 240px">
	<div class='notice'>Please make sure that as much of the information in this file is also entered in the special needs form fields.</div>
	<form style="width: 90%; margin: 0 auto;" method="post" enctype="multipart/form-data" name="support-file-editor" id="support-file-editor"
		action="<?=site_url("support/attach_file");?>">
		<input type="hidden" name="kSupport" id="kSupport" value="<?=$kSupport?>" />
		<input type="hidden" name="kStudent" id="kStudent" value="<?=$kStudent;?>" />
		<input type="hidden" name="kFile" id="kFile" value="<?=get_value($file,'kFile');?>" />
		<p>
			<label for="file_display_name">Display Name</label><br />
			<input type="text" name="file_display_name" id="file_display_name" value="" />
		</p>
		<p>
			<label for="file_description">Description</label><br />
			<input type="text" name="file_description" id="file_description" value="" />
		</p>
		<p>
			<input type="file" name="userfile" class="" size="20" />
		</p>
		<p>
			<span class="button attach-support-file">Attach</span>
		</p>
	</form>
</div>