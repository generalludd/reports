<?php ?>
<div class='info-box'>
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

	$add_narrative_buttons[] = array("selection" => "narrative", "text" => "Add Narrative for $student->stuNickname", "class" => "button new select_narrative_type", "id" => "nn_$student->kStudent");
	echo create_button_bar($add_narrative_buttons);
	$acc_class = "";
	if($accordion == "enable"){
	$acc_class = "accordion";
}
?>
	<div class='<?=$acc_class;?>'>
		<?
		if(count($narratives)>0){
			$thisTerm = "";
			foreach($narratives as $narrative){
				$print_buttons = array();
				$sortTerm = "$narrative->narrTerm $narrative->narrYear";
				if($sortTerm != $thisTerm){
					$schoolYear=$narrative->narrTerm." ". format_schoolyear($narrative->narrYear, $narrative->narrTerm);
					if($thisTerm != ""){
						echo "</table></div>";
					}
					echo "<h4>$schoolYear</h4><div>";
					$print_buttons[] = array("selection"=>"print","text"=>"Preview &amp; Print Report", "href"=> site_url("narrative/print_student_report/$student->kStudent/$narrative->narrTerm/$narrative->narrYear"), "target" =>"_blank");
					if($student->stuGrade > 4){
						$print_buttons[] = array("selection" => "print",
								"text" => "Print Grades",
								"href" => site_url(
						sprintf("grade/report_card?kStudent=%s&year=%s&term=%s&subject=0",$student->kStudent,$narrative->narrYear, $narrative->narrTerm)
					),"target" =>"_blank");
					}
					echo create_button_bar($print_buttons);
					echo "<table class='list'><thead><tr><th><strong>Subject</strong></th><th><strong>Author</strong>";
					echo "</th></th><th><strong>Last Edited</strong></th><th></th><th></th></tr></thead>";
				}
				$info['narrative'] = $narrative;
				$info['studentGrade'] = get_current_grade($student->baseGrade, $student->baseYear, $narrative->narrYear);
				//$info['teacher'] = $this->teacher_model->get_name($narrative->kTeach);
				$info['teacher'] = $narrative->teachFirst . " " . $narrative->teachLast;
				//$info['hasSuggestions'] = $this->suggestion_model->exists($narrative->kNarrative);
				$info['hasSuggestions'] = FALSE;
				$this->load->view('narrative/student_list_body',$info);
				$thisTerm = $sortTerm;

			}
			echo "</table></div>";

		}else{
	echo "<p>There are no reports entered for $studentName</p>";

}

?>
	</div>
</div>
