<?php
// narrative_view.inc
$narrYear = $narrative->narrYear;
$stuGrade = get_current_grade ( $narrative->baseGrade, $narrative->baseYear, $narrYear );
$report_grade = $narrative->narrGrade;
if ($letter_grade) {
	$report_grade = $letter_grade;
}
?><h3><?php print "Grade $stuGrade, $narrative->narrTerm $narrative->narrYear";?></h3><p>	<a href="<?=site_url("narrative/student_list/$narrative->kStudent");?>" class='button small'>Back to Narratives</a></p><p>	<span class='highlight'>Last Modified: <?=format_timestamp( $narrative->recModified); ?> by <?="$recModifier->teachFirst $recModifier->teachLast"; ?></span></p><p>Written by: <?=$teacher;?></p> <? //if($hasSuggestions): ?><!-- <p> --><!-- 	<span class="highlight">Has Suggested Edits</span> --><!-- </p> --> <? //endif; ?><!-- middle schoolers get letter grades in some classes --><? if($stuGrade >= 6 && $report_grade && $narrative->narrSubject !="Humanities"): ?><p>Report Grade: <?=$report_grade; ?></p><? endif; ?><?php


$buttons [] = array (
		"text" => "Edit Original",
		"href" => site_url ( "narrative/edit/$narrative->kNarrative" ),
		"class" => "button edit small",
		"id" => "n_$narrative->kNarrative"
); // ,
// if ($benchmarks_available) {
// 	$buttons [] = array (
// 			"text" => "Edit Benchmarks",
// 			"href" => site_url ( "benchmark/edit_for_student/$narrative->kNarrative" ),
// 			"class" => "button dialog small"
// 	);
// }
if ($backups) {
	$buttons [] = array (
			"text" => "Show Backups",
			"href" => site_url ( "narrative/list_backups/$narrative->kNarrative" ),
			"class" =>"button small"
	);
}
print create_button_bar ( $buttons );
?><div class="narrText">
<?php if($narrative->includeOverview && !empty($overview)):?><h4><?php print $narrative->narrSubject; ?> Overview</h4>
<?php print stripslashes($overview[0]->overview);?>
<h4><?php printf("%s's Progress", $narrative->stuNickname);?></h4><?php endif;?><?=stripslashes($narrative->narrText);?></div><?

// if (! empty ( $benchmarks )) {

// 	$this->load->view ( "benchmark/chart", array (
// 			"benchmarks" => $benchmarks,
// 			"legend" => $legend));// }
?>