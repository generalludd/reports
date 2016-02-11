<?php
?>
<h2><?php echo $title;?></h2>

<form id="edit-config" name="edit-config" action="<?php echo base_url("config/$action");?>" method="post">
<input type="hidden" name="kConfig" value="<?php echo get_value($config, "kConfig");?>"/>
<input type="hidden" name="config_key" value="<?php echo get_value($config,"config_key");?>"/>
<input type="hidden" name="config_description" value="<?php echo get_value($config,"config_description");?>"/>
<p><?php echo $config->config_description;?></p>
<p>
<label for=config_value"><?php echo ucwords(humanize($config->config_key),"_");?></label>&nbsp;
<input type="text" name="config_value" value="<?php echo get_value($config,"config_value");?>"/>
<p>
<p>
<input type="submit" name="submit" value="<?php echo ucfirst($action);?>" class="button edit"/>
</p>


</form>