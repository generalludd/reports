<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="button-box">
	<span class="button new add-category" id="teach_<?=$kTeach;?>">Add
		Category</span>
</div>

<table id="category-table">
	<thead>
		<tr>
			<th>Category</th>
			<th>Weight</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<? foreach($categories as $category){
			$data["category"] = $category;
			$data["action"] = "update";
			$this->load->view("assignment/category_row",$data);
		}?>
	</tbody>
</table>
<div class="button-box">
	<span class="button refresh">Done</span>
</div>
