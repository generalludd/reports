<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>

<script>

$("#category-table").on("blur","input[name='weight']",function(){
 var sum = 0;
    $("input[name='weight']").each(function() {
        sum += Number($(this).val());
    });
		$("input.total").val(sum);
		if(sum != 100){
			$("input.total").addClass("highlight");
		}else{
			$("input.total").removeClass("highlight");
		}
		//here calculate the sum of the weights so the user can see if they at 100% or not.
});
</script>
<div class="button-box">
	<ul class="button-list">
		<li><span class="button new small add-category" id="teach_<?=$kTeach;?>">Add Category</span></li>
		<?php if(empty($categories)):?>
		<li><a href="<?php echo site_url("assignment/duplicate_categories?dialog=1");?>" class="button dialog edit small duplicate-categories"
				title="Duplicate categories from a previous term"
			>Duplicate Categories</a></li>
		<?php endif;?>
		</ul>
</div>
<table id="category-table" class="list grid">
	<thead>
		<tr class="first">
			<th colspan=2></th>
			<th colspan=2>Grade</th>
			<th colspan=3></th>
		</tr>
		<tr>
			<th>Category</th>
			<th>Weight</th>
			<th>Start</th>
			<th>End</th>
			<th>Term</th>
			<th>Year</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?
		
		foreach ( $categories as $category ) {
			$data ["category"] = $category;
			$data ["action"] = "update";
			$this->load->view ( "assignment/category/row", $data );
		}
		?>
	</tbody>
	<tfoot>
		<tr>
			<td>Total Weight:</td>
			<td>
	<?php $sum = 0;?>
	<?php foreach($categories as $item):?>
	<?php $sum += $item->weight;?>
	<?php endforeach;?>
	<input type="text" class='total' name="total" value="<?php echo $sum; ?>" size=4 readonly />
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</tfoot>
</table>
<div class="button-box">
	<ul class='button-list'>
		<li><span class="button small refresh">Done</span></li>
	</ul>
</div>
