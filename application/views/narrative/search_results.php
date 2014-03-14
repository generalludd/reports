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
if($count > 0):
	foreach($narratives as $narrative):
		$student = format_name($narrative->stuFirst, $narrative->stuLast, $narrative->stuNickname);
		$text = str_replace($replace, "<span class='highlight'>$replace</span>", $narrative->narrText);
		$edit_buttons = array();
		$edit_buttons[] = array("selection"=>"view","text"=>"View","href"=> site_url("narrative/view/$narrative->kNarrative"));
		$edit_buttons[] = array("selection" => "message", "type" => "span", "class" => "text","text" => sprintf("(Last edited on %s " , format_timestamp($narrative->recModified) ), "id" => "time_$narrative->kNarrative");
		?>
		<h3><?=$narrative->narrSubject;?> Narrative for <?=$student;?></h3>
		<?=create_button_bar($edit_buttons);?>
		<div><?=$text; ?></div>
	<? endforeach; ?>
<? else: ?>
	 <p>No Narratives were Changed</p>
<? endif;

