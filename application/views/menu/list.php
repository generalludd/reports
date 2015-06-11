<?php defined('BASEPATH') OR exit('No direct script access allowed');
$my_category = NULL;
foreach($categories as $category){
	if($my_category != $category->category){
		$my_category = $category->category;
		$output[] = sprintf("<tr><td colspan='3'><h3>%s</h3></td></tr>",$category->category);
	}
	$output[] = sprintf("<tr><td>%s</td><td>%s<td><td><a class='button edit edit-menu-item' href='%s' id='emi_%s'>Edit</span></td></tr>",$category->label,$category->value,site_url("menu/edit?kMenu=$category->kMenu"),$category->kMenu);

}
$buttons[] = array("selection" => "menu", "text" => "New Menu Item", "class" => array("button","new"), "type"=>"span", "id"=>"add-menu-item","title" => "Add a new menu item for a given category");
echo create_button_bar($buttons);

?>

<table class="list">
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
