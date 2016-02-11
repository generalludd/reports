<?php
?>
<h2><?php echo $title;?></h2>

<form id="edit-config" name="edit-config" action="<?php echo base_url("config/$action");?>" method="post">
<input type="hidden" name="kConfig" value="<?php echo get_value($config, "kConfig");?>"/>
<input type="hidden" name="config_group" value=<?php echo get_value($config,"config_group");?>"/>
<input type="hidden" name="config_key" value="<?php echo get_value($config,"config_key");?>"/>
<p style="max-width:380px;font-weight: bold;"><?php echo get_value($config,"config_description");?></p>
<p>
<label for=config_value"><?php echo ucwords(humanize(get_value($config,"config_key")),"_");?></label>&nbsp;
<input type="text" name="config_value" value="<?php echo get_value($config,"config_value");?>"/>
<p>
<p>
<label for="config_description" style="display:block; padding-bottom: 1em">Description</label>
<textarea name="config_description" style="min-width: 380px;min-height:2em">
<?php echo get_value($config,"config_description");?>
</textarea>
</p>
<p>
<input type="submit" name="submit" value="<?php echo ucfirst($action);?>" class="button edit" />
</p>


</form>