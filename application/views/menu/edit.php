<?php defined('BASEPATH') OR exit('No direct script access allowed');

?>

<form id="edit-menu-item" name="edit-menu-item" method="post" action="<?php  echo site_url("menu/$action");?>">

<input type="hidden" name="kMenu" id="kMenu" value="<?php  echo get_value($menu_item,"kMenu");?>"/>
<p>
<label for="category">Category</label>
<?php if($action == "update"): ?>
<input type="text" name="category" id="category" readonly value="<?php  echo get_value($menu_item,"category");?>"/>
<?php else: ?>
<?php echo form_dropdown("category",$categories,"id='category'"); ?>
<?php endif; ?>
</p>
<p>
<label for="label">Human-Readable Label</label>&nbsp;
<input type="text" name="label" id="label" value="<?php  echo get_value($menu_item,"label");?>"/></p>
<p>
<label for="value">Computer Value</label>&nbsp;
<input type="text" name="value" id="value" value="<?php  echo get_value($menu_item,"value");?>"/>
</p>
<p>
<input type="submit" name="save" id="save" class="button" value="<?php  echo ucfirst($action);?>"/>
</p>

</form>