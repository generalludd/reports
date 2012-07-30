<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<tr <?=($action == "insert"?"id='tr-teach_$kTeach'":"");?>>
<td>
<input type="text" name="category" class="<?=$action;?>-category" id="category_<?=get_value($category,"kCategory");?>" value="<?=get_value($category,"category");?>" />
</td>
<td>
<input type="text" name="weight" class="<?=$action;?>-weight" id="weight_<?=get_value($category,"kCategory");?>" value="<?=get_value($category,"weight");?>" size="4" />
</td>
<td>
<span class="button category-<?=$action;?>" id="<?=$action;?>-category_<?=get_value($category,"kCategory",$kTeach);?>"><?=ucfirst($action);?></span>
</td>
</tr>