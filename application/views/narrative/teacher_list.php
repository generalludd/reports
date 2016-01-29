<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<fieldset class="search_fieldset">
<legend>Search Parameters</legend>
<?
if(!empty($options)){

	$keys = array_keys($options);
	$values = array_values($options);

	echo "<ul>";

	for($i = 0; $i < count($options); $i++){
		$key = $keys[$i];
		$value = $values[$i];
		switch($key){
			case "kTeach":
				echo "<li>Teacher: <strong>$teacher</strong></li>";
				break;
			case "gradeStart":
							echo "<li>Starting Grade: <strong>$value</strong></li>";
				break;
			case "gradeEnd":
				echo "<li>Ending Grade: <strong>$value</strong></li>";
				break;
			case "narrSubject":
				echo "<li>Subject: <strong>$value</strong></li>";
				break;
			case "narrTerm":
				echo "<li>Term: <strong>$value</strong></li>";
				break;
			case "narrYear":
				echo "<li>School Year: <strong>" . format_schoolyear($value) . "</strong></li>";
				break;
			default:
				echo "<li>" .ucfirst($key) . "<strong>$value</strong></li>";
		}
	}

		echo "</ul>";

}else{
echo "<p>Showing all Users.</p>";

}

$buttons[] = array("text"=>"Refine Search","href"=>site_url("narrative/search_teacher_narratives/$kTeach"),"class"=>"button dialog");
print create_button_bar($buttons);
?>	
	</fieldset>
<?
if(!empty($narratives)){
	$thisTerm="";
	$thisStudent="";
	$thisGrade="";
	foreach($narratives as $narrative){

		$student = format_name($narrative->stuFirst, $narrative->stuLast, $narrative->stuNickname);
		$sortTerm = "$narrative->narrTerm $narrative->narrYear";
		if($thisGrade != $narrative->currentGrade){
			$displayGrade = format_grade($narrative->currentGrade);
			echo "<h2>Grade: $displayGrade</h2>";
			$thisGrade = $narrative->currentGrade;
		}
		if($sortTerm != $thisTerm){
			echo "<h3>$narrative->narrTerm $narrative->narrYear</h3>";
			$thisTerm = $sortTerm;
		}
		if($student != $thisStudent){
			echo "<h4 id='student-text_$narrative->kNarrative'>$student</h4>";
			$thisStudent = $student;
			if($sortTerm == $thisTerm){
				$thisTerm="";
			}
		}
		$edit_buttons = array();
		$narrSplit = str_split(strip_tags($narrative->narrText),100);
		$narrSummary = $narrSplit[0];
		$narrText = stripslashes($narrative->narrText);
		echo "<p><b>$narrative->narrSubject</b></p>";
		$edit_buttons[] = array("selection"=>"view","text"=>"View","href"=> site_url("narrative/view/$narrative->kNarrative"), "title"=>"$narrSummary","class"=>"button small");
		$edit_buttons[] = array("selection" =>"edit_inline","text"=>"Edit Inline","class" =>"button edit small edit_narrative_inline", "id" => "enil_$narrative->kNarrative", "title" => "Edit this narrative here" );

		if($narrative->stuGrade >= 5){
			$button_type = "new";
			$button_text = "Add Grade";
			if(!empty($narrative->narrGrade)){
				$button_type = "edit";
				$button_text = "Edit Grade";
			}
			//$edit_buttons[] = array("selection" => "narrGrde", "type" => "span", "class" => "text","text" => "Grade: <span class='narr_grade' style='font-weight:bold' id='ngtext_$narrative->kNarrative'>$narrative->narrGrade</span>" );
			//$edit_buttons[] = array("selection" =>"edit_grade","text" => $button_text, "type" => "span", "class" => "button $button_type grade_edit", "id" => "ngedit_$narrative->kNarrative");
		}
		$edit_buttons[] = array("selection" => "message", "type" => "span", "class" => "text","text" => "(Last edited on " . format_timestamp($narrative->recModified) . " by $narrative->teachFirst $narrative->teachLast)", "id" => "time_$narrative->kNarrative");

		echo create_button_bar($edit_buttons);
		echo  "<div id='text_$narrative->kNarrative'>$narrText</div>";
		$thisTerm = $sortTerm;

	}
}else{

	if($this->session->userdata("userID") == $kTeach){
		echo "<p>You have ";
	}else{
		echo "<p>$teacher has ";
	}

	echo "not written any reports yet for this term.</p>";
	?>
<p>
<a href="<?=site_url("template/list_templates/?kTeach=$kTeach");?>" class="button small">Edit Templates</a>
	<a href="<?=site_url("");?>" class='button small'>Search for Students</a></p>

	<?
}
?>
<p>
<a class='button small dialog' href="<?php echo site_url("narrative/search_missing/$kTeach");?>">View
Missing Narratives</a>
</p>
