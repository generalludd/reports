<?php
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
			echo "<h4>$student</h4>";
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
		$edit_buttons[] = array("item"=>"view","text"=>"View","href"=> site_url("narrative/view/$narrative->kNarrative"), "title"=>"$narrSummary");
		$edit_buttons[] = array("item" =>"edit_inline","text"=>"Edit Inline","class" =>"button edit edit_narrative_inline", "id" => "enil_$narrative->kNarrative", "title" => "Edit this narrative here" );
		$edit_buttons[] = array("item" => "message", "type" => "span", "class" => "text","text" => "(Last edited on " . format_timestamp($narrative->recModified) . ")", "id" => "time_$narrative->kNarrative");
		echo create_button_bar($edit_buttons);
		if($narrative->narrGrade){
			echo "<p>Grade: $narrative->narrGrade</p>";
		}
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
<a href="<?=site_url("template/list_templates/?kTeach=$kTeach");?>" class="button">Edit Templates</a> 
	<a href="<?=site_url("");?>" class='button'>Search for Students</a></p>

	<?
}
?>
<p>
<a class='button missing_narrative_search' id='mns_<?=$kTeach;?>'>View
Missing Narratives</a>
</p>
