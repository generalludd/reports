<?php defined('BASEPATH') OR exit('No direct script access allowed');
$my_category = NULL;
foreach($categories as $category){
	if($my_category != $category->category){
		$my_category = $category->category;
		$output[] = sprintf("<tr><td colspan='3'><h3>%s</h3></td></tr>",$category->category);
	}
	$output[] = sprintf("<tr><td>%s</td><td>%s<td><td><a class='button edit edit-menu-item' href='%s' id='emi_%s'>Edit</span></td></tr>",$category->label,$category->value,base_url("menu/edit?kMenu=$category->kMenu"),$category->kMenu);

}
?>
<table>
	<thead>
		<tr>
			<th>Human Label</th>
			<th>Computer Value</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?
		echo implode("\r",$output);
		?>

	</tbody>
</table>
