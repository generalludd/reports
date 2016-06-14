<?php
?>

<form id="narrative_inline_editor" name="narrative_inline_editor"
	action="<?=site_url("narrative/update_inline");?>" method="post">
	<input type="hidden"
	name="kNarrative" id="kNarrative" value='<?="$narrative->kNarrative"; ?>' />
	<input type="hidden"
    name="kTeach" id="kTeach" value='<?="$narrative->kTeach"; ?>' />

<div><textarea id="narrText_<?php echo $narrative->kNarrative;?>" name="narrText" 
	style="width: 99.75%;" rows="19" cols="107"><?=stripslashes($narrative->narrText);?></textarea></div>
<p><span class="button new save_narrative_inline">Save</span></p>
</form>
<script>
tinymce.init({ selector:'textarea#narrText_' + <?php echo $narrative->kNarrative;?>,
	menubar: false,
	min_height:400,
	toolbar: 'bold,italic,|,fullpage,|,cut,copy,paste,pastetext,pasteword,cleanup,code,|,undo,redo,|,bullist,numlist',
	invalid_styles: 'color font-size font-family line-height font-weight',
	plugins: 'fullpage,paste,code',
	invalid_elements: 'div,font,a,html,head,body',
	setup: function (editor) {
	        editor.on('change', function () {
	            editor.save();
	        });
	    },
	    browser_spellcheck: true,
	    contextmenu: false

	});
</script> 