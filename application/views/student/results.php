<?php
$grades = "All Grades";
if(array_key_exists("grades", $criteria)){
	foreach($criteria["grades"] as $grade){
		$grade_list[] = format_grade_text($grade);
	}
	$grades = grammatical_implode(", ", $grade_list);
}

?>
<div class='info-box'>
<fieldset class='search_fieldset'><legend>Showing Student Search Results</legend>

<ul>
	<li>Year: <strong><?=format_schoolyear($criteria["year"]);?></strong></li>
	<li>Grades: <strong><?=$grades?></strong></li>
	<?
	if(array_key_exists("hasNeeds", $criteria)){
		print "<li><strong>Showing only students with additional support requirements</strong></li>";
	}

	if(array_key_exists("includeFormerStudents", $criteria)){
		echo "<li><strong>Including former students</strong></li>";
	}
	?>
	<li>Found Count: <?=count($students);?></li>
</ul>
<div class='button-box'>
<a href="<?=site_url("/");?>" class="button" title="Modify Search">Modify
Search</a>
<a href="<?=$_SERVER['REQUEST_URI']. "&export=true";?>" class="button" title="Export">Export List</a>
</div>
</fieldset>
</div>
<div class="page-list">
	<?
	$this->load->view("student/list"); ?>
</div>