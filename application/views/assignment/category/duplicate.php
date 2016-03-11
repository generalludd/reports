<?php
//Allow users to duplicate categories from a previous year/term

?>
<h2><?php echo $title;?></h2>
<p>Select the term, year and grade range for categories you want to duplicate</p>

<form name="category-duplicate" id="category-duplicator" method="get" action="<?php echo base_url("assignment/duplicate_categories");?>">
<input type="hidden" name="kTeach" value="<?php echo USER_ID;?>"/>
<input type="hidden" name="duplicate" value="1"/>

<p class="category row">
<label for="sourceYear">Source Year: </label>
	<?php echo form_dropdown("sourceYear", get_year_list(), get_current_year()); ?>
	</p>
	<p class = "category row">
	<label for="sourceTerm">Source Term: </label>
	<?php echo get_term_menu("sourceTerm",get_current_term());?>
	</p>
<p class="category row">
<label for="gradeStart">Grade Range</label>
<input type="text" name="gradeStart"
		id="gradeStart"
		value="" size="2" required/>-<input type="text" name="gradeEnd" 
		value="" size="2" required/>
</p>
<p class="category row">
<label for="sourceYear">Target Year: </label>
	<?php echo form_dropdown("year", get_year_list(FALSE,TRUE), get_current_year()); ?>-
<input type="text" name="yearEnd" id="yearEnd"
		readonly value="" size="4" />
</p>
	<p class = "category row">
	<label for="sourceTerm">Target Term: </label>
	<?php echo get_term_menu("term",get_current_term());?>
	</p>
<p class="category row">
<input type="submit" class="edit" value="Duplicate"/>
</p>
</form>