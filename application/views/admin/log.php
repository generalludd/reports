<?php defined('BASEPATH') OR exit('No direct script access allowed');?>


<fieldset class="search_fieldset">
	<legend>Search Parameters</legend>
	<?
	if(!empty($options)){

		$keys = array_keys($options);
		$values = array_values($options);

		echo "<ul>";

		for($i = 0; $i < count($options); $i++){
			if($keys[$i] != "date_range"){
				echo "<li>" . $keys[$i] .": <strong>" . $values[$i]. "</strong></li>";
			}else{
				$time_start = format_date($values[$i]["time_start"],"standard");
				$time_end = format_timestamp($values[$i]["time_end"],FALSE);
				echo "<li>Date Range: <strong>$time_start-$time_end</strong></li>";
			}
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
