<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>
<fieldset class="search_fieldset">
	<legend>Search Parameters</legend>
	<?
	if(!empty($params)){

		$keys = array_keys($params);
		$values = array_values($params);
		echo "<ul>";
		for($i = 0; $i < count($params); $i++){
			if($keys[$i] != "kTeach"){
				echo "<li>" . ucfirst($keys[$i]) .": <strong>";
				if($keys[$i] == "year"){
					echo format_schoolyear($values[$i]);
				}else{
					echo $values[$i];
				}
				echo "</strong></li>";
			}
		}
		echo "</ul>";

	}else{
		echo "<p>Showing all Benchmark Legends.</p>";

	}
	?>

	<div class="button-box">
		<a class="button dialog" href="<?php echo site_url("benchmark_legend/search");?>" id="ts_">Refine Search</a>
	</div>
</fieldset>

<p>
	<a class="button new"
		href="<?=site_url("benchmark_legend/create/$kTeach")?>">New Benchmark
		Legend</a>
</p>
<div>
	<?
	$activeTerm = "";
	if(!empty($legends)){
		foreach($legends as $legend):
		$currentTerm = $legend->term . " " . format_schoolyear($legend->year);
		if($currentTerm != $activeTerm){
			?>
	<h4>
		<?=$currentTerm?>
	</h4>
	<?  $activeTerm = $currentTerm;
		}
		?>
	<p>
		<a href="<?=site_url("benchmark_legend/edit/$legend->kLegend")?>" class="button">Edit</a>
		&nbsp;
		<?="$legend->subject, $currentTerm, " . format_grade_range($legend->gradeStart, $legend->gradeEnd, TRUE);?>

	</p>
	<hr />

	<?
	endforeach;
	}else{
		echo "<p>There were no results for this search.</p>";
}


?>
</div>
