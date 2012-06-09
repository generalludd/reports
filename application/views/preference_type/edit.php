<?php defined('BASEPATH') OR exit('No direct script access allowed');
$readonly = FALSE;
if($preference){
	$readonly = "readonly";
}
?>

<form id="preference_type_edit" name="preference_type_edit" method="post" action="<?=site_url("preference_type/$action");?>">
<p>
<label for="type">Type (letters, numbers, underscores, hyphens): </label>
<input type="text" name="type" id="type" class="alnum" value="<?=get_value($preference, "type");?>" <?=$readonly;?>/>
</p>
<p>
<label for="name">Name: </label>
<input type="text" name="name" id="name" value="<?=get_value($preference, "name");?>"/>
</p>
<p>
<label for="description">Description:</label><br/>
<textarea name="description" id="description" style="width:95%;" rows="5"><?=get_value($preference, "description");?></textarea>
</p>
<p>
<label for="options">Options:(comma separated list, no spaces between items)</label>
<input type="text" name="options" id="options" value="<?=get_value($preference, "options");?>" <?=$readonly;?>/>
</p>
<p>
<label for="format">Format: </label>
<?
if($readonly):?>
<input type="text" <?=$readonly;?> id="format" name="format" value="<?=get_value($preference, "format");?>"/>
<?else:
echo form_dropdown("format", $formats, get_value($preference, "format"), "id='format' $readonly" );
endif;?>
</p>
<p>
<label for="sort_order">Sort Order: </label>
<input type="text" size="3" name="sort_order" id="sort_order" value="<?=get_value($preference, "sort_order");?>"/>
</p>
<p>
<input type="submit" class="button" id="save_preference_type" value="Save"/>
</p>

</form>