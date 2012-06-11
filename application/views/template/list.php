<?php #template/list
?>
<div class='info-box'>
<h2>Showing Subject Templates for <?=$teacher;?></h2>
<fieldset class="search_fieldset"><legend>Search Parameters</legend> <?
if(!empty($options)){

	if(array_key_exists("where", $options)){
	$keys = array_keys($options["where"]);
	$values = array_values($options["where"]);
	echo "<ul>";

	for($i = 0; $i < count($options["where"]); $i++){
		echo "<li>" . ucfirst($keys[$i]) .": <strong>";
		if($keys[$i] == "year"){
			echo format_schoolyear($values[$i]);
		}else{
			echo $values[$i];
		}
		echo "</strong></li>";
	}
	if(array_key_exists("grade_range", $options)){
		$gradeStart = $options["grade_range"]["gradeStart"];
		$gradeEnd = $options["grade_range"]["gradeEnd"];
		if($gradeStart == $gradeEnd){
			echo "<li>Grade: <strong>$gradeStart</strong></li>";
		}else{
			echo "<li>Grade Range: <strong>$gradeStart-$gradeEnd</strong></li>";
		}
	}
	echo "</ul>";
}
	
}else{
	echo "<p>Showing all Templates.</p>";

}
?>

<div class="button-box"><a class="button template_search"
	id="ts_<?=$kTeach?>">Refine Search</a></div>
</fieldset>
<p><a class="button new" href="<?=site_url("template/create/$kTeach")?>">New Template</a></p>

<?
$activeTerm = "";
if(!empty($templates)){
	foreach($templates as $template):
		$currentTerm = $template->term . " " . format_schoolyear($template->year);
		if($currentTerm != $activeTerm){
			?>
<h4><?=$currentTerm?></h4>
			<?  $activeTerm = $currentTerm;
		}
		?>
<p><a href="<?=site_url("template/edit/$template->kTemplate")?>" class="button">Edit</a>
&nbsp;<?="<strong>$template->subject</strong>, $currentTerm," . format_grade_range($template->gradeStart, $template->gradeEnd, TRUE);?>
<?
if(!empty($template->type)){
	echo " type: $template->type ";
}
if($template->isActive == 0){
	echo "<span class='highlight' style='padding:2px;'>Inactive Template</span>";
}
?>
</p>
<hr />

<?
	endforeach;
}else{
	echo "<p>There were no results for this search.</p>";
}

?>
</div>
