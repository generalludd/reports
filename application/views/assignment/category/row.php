<?php

defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
$year = get_value ( $category, "year", get_current_year () );
$yearEnd = $year + 1;
?>
<tr <?=($action == "insert"?"id='tr-teach_$kTeach'":"");?>>
<td><?php echo form_dropdown("subject",$subjects,get_value($category,"subject"),"class='$action-subject'"); ?>
	<td><input type="text" name="category" class="<?=$action;?>-category"
		id="category_<?=get_value($category,"kCategory","new");?>"
		value="<?=get_value($category,"category");?>" /></td>
	<td><input type="text" name="weight" class="<?=$action;?>-weight"
		id="weight_<?=get_value($category,"kCategory","new");?>"
		value="<?=get_value($category,"weight");?>" size="4" /></td>
	<td><input type="text" name="gradeStart"
		class="<?=$action;?>-gradeStart"
		id="gradeStart_<?=get_value($category,"kCategory","new");?>"
		value="<?=get_value($category,"gradeStart",get_cookie("assignment_grade_start"));?>" size="2" /></td>
	<td><input type="text" name="gradeEnd" class="<?=$action;?>-gradeEnd"
		id="gradeEnd_<?=get_value($category,"kCategory","new");?>"
		value="<?=get_value($category,"gradeEnd", get_cookie("assignment_grade_end"));?>" size="2" /></td>
	<td>
	<?=get_term_menu(sprintf("term_%s",get_value($category,"kCategory","new")), get_value($category,"term",get_current_term()),FALSE,array("classes"=>sprintf("%s-term",$action)));?>

	</td>
	<td>
	<?=form_dropdown("year", get_year_list(FALSE,TRUE), $year ,sprintf("id='year_%s' class='year %s-year'",get_value($category,"kCategory","new"),$action)); ?>
	-<input type="text" name="yearEnd" id="yearEnd"
		readonly value="<?=$yearEnd;?>" size="4" /></td>

	<td><span class="button category-<?=$action;?>"
		id="<?=$action;?>-category_<?=get_value($category,"kCategory",$kTeach,"new");?>"><?=ucfirst($action);?></span>
	</td>
</tr>
