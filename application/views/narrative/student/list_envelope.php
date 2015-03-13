<?php ?>

	<form id='narrative_add' name='narrative_add'
		action='"<?= site_url("narrative/$action")?>"' method='post'>
		<input type='hidden' id='kStudent' name='kStudent'
			value='<?=$student->kStudent;?>' /> <input type='hidden' id='userID'
			name='userID' value='<?=$userID;?>' /> <input type='hidden'
			id='userRole' name='userRole' value='<?=$userRole;?>' /> <input
			type='hidden' id='defaultTerm' name='defaultTerm'
			value='<?=$defaultTerm;?>' /> <input type='hidden' id='defaultYear'
			name='defaultYear' value='<?=$defaultYear;?>' />
	</form>

	<h3>
		Reports for
		<?=$studentName;?>
	</h3>

	<? $data["kStudent"] = $student->kStudent;
	$this->load->view("student/navigation", $data);

	$add_narrative_buttons[] = array("selection" => "narrative", "text" => "Add Narrative for $student->stuNickname", "class" => "button new select_narrative_type small", "id" => "nn_$student->kStudent");
	echo create_button_bar($add_narrative_buttons);

?>
	<div class='student-narrative-list'>
<? if(count($reports)>0){
foreach($reports as $report): ?>
<div class="report-row">
<? foreach($report as $term){
print $term;
}?></div>
<? endforeach;
}else{
    echo "<p>There are no reports entered for $studentName</p>";
}?>

	</div>

