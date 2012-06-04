<?php
?>
<h2>Showing Search &amp; Replace Results</h2>
<fieldset class="search_fieldset"><legend>Search Parameters</legend>
<ul>
<li>
Term: <strong><?="$narrTerm " . format_schoolyear($narrYear);?></strong>
</li>
<li>
Teacher: <strong><?="$teacher->teachFirst $teacher->teachLast";?></strong>
</li>
<li>
<? if($gradeStart == $gradeEnd){
	echo "Grade: <strong>" . format_grade($gradeStart) . "</strong>";
}else{
    echo "Grades: </strong>". format_grade($gradeStart) . " to " . format_grade($gradeEnd). "</strong>";
}
?>
</li>
</ul>
<div class="button-box">
<a href="<?=site_url("narrative/search");?>" class="button">Search &amp; Replace</a></div>
</fieldset>
<?
if($count > 0){
	foreach($narratives as $narrative){
		$student = format_name($narrative->stuFirst, $narrative->stuLast, $narrative->stuNickname);
		$text = str_replace($replace, "<span class='highlight'>$replace</span>", $narrative->narrText);
		$viewButton = "<a href='". site_url("narrative/view/$narrative->kNarrative") ."' class='button'>";
		$viewButton .= "View</a>";
		print "<h3>Narrative for $student $viewButton</h3>";
		print "<div>$text</div>";
	}
}else{
	echo "No Narratives were Changed";
}

