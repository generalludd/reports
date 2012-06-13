<?php defined('BASEPATH') OR exit('No direct script access allowed');?>


<fieldset class="search_fieldset">
	<legend>Search Parameters</legend>
	<?
	if(!empty($options)){

		$keys = array_keys($options);
		$values = array_values($options);

		echo "<ul>";

		for($i = 0; $i < count($options); $i++){

			echo "<li>" . $keys[$i] .": <strong>" . $values[$i]. "</strong></li>";
		}
		echo "</ul>";

	}else{
		echo "<p>Showing all Log Entries.</p>";

	}
	?>

	<div class="button-box">
		<a class="button log_search">Refine Search</a>
	</div>
</fieldset>
<?
$classes = array("table_class"=>"list");
print format_table($logs,$header,$classes);
