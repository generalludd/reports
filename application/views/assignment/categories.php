<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>
<table id="category-table">
	<thead>
		<tr>
			<th>Category</th>
			<th>Weight</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<? foreach($categories as $category):?>
		<tr>
			<td>
				<input type="text" name="category" id="category_<?=$category->kCategory;?>" value="<?=$category->category;?>" />
			</td>
			<td>
				<input type="text" name="weight" id="weight_<?=$category->kCategory;?>" value="<?=$category->weight;?>" width="4" />
			</td>
			<td>
				<span class="button save-category" id="save-category_<?=$category->kCategory;?>">Save</span>
			</td>
		</tr>
		<? endforeach;?>
	</tbody>
</table>
<div class="button-box">
<span class="button add-category" id="teach_<?=$kTeach;?>">Add Category</span>
</div>
