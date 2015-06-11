<?php defined('BASEPATH') OR exit('No direct script access allowed');

?>

<form id="edit-menu-item" name="edit-menu-item" method="post" action="<?=site_url("menu/$action");?>">

<input type="hidden" name="kMenu" id="kMenu" value="<?=get_value($menu_item,"kMenu");?>"/>
<p>
<label for="category">Category</label>
<? if($action == "update"): ?>
<input type="text" name="category" id="category" readonly value="<?=get_value($menu_item,"category");?>"/>
<? else: ?>
<? echo form_dropdown("category",$categories,"id='category'"); ?>
<? endif; ?>
</p>
<p>
<label for="label">Human-Readable Label</label>&nbsp;
<input type="text" name="label" id="label" value="<?=get_value($menu_item,"label");?>"/></p>
<p>
<label for="value">Computer Value</label>&nbsp;
<input type="text" name="value" id="value" value="<?=get_value($menu_item,"value");?>"/>
</p>
<p>
<input type="submit" name="save" id="save" class="button" value="<?=ucfirst($action);?>"/>
</p>

</form>