<?php
?>
<h2><?php echo $title;?></h2>
<p class="notice">Except for the description, the values must be all lowercase and contain only letters, numbers and underscores</p>
<form id="edit-config" name="edit-config" action="<?php echo base_url("config/$action");?>" method="post">
<input type="hidden" name="kConfig" value="<?php echo get_value($config, "kConfig");?>"/>
<p>
<label for="config_group" style="display:block">Group: lowercase and underscores only</label>
<input type="text" name="config_group" value="<?php echo get_value($config,"config_group");?>"/></p>
<p>
<label for="config_key" style="display:block">Key: lowercase and underscores only</label>
<input type="text" name="config_key" value="<?php echo get_value($config,"config_key");?>"/></p>
<p>
<label for="config_value" style="display:block">Value: lowercase and underscores only</label>
<input type="text" name="config_value" value="<?php echo get_value($config,"config_value");?>"/>
</p>
<p>
<label for="config_description" style="display:block">Description:</label>
<input type="text" name="config_description" value="<?php echo get_value($config,"config_description");?>"/></p>
<p>
<input type="submit" name="submit" value="<?php echo ucfirst($action);?>" class="button edit"/>
</p>
</form>