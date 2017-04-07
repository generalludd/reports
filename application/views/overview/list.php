<?php

?>
<fieldset class="search_fieldset"><legend>Search Parameters</legend> <?php
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
	echo "<p>Showing all Overviews.</p>";

}

$buttons[] = array("text"=>"Refine Search","class"=>"button dialog","href"=>base_url("overview/search/$kTeach?refine=TRUE"));
echo create_button_bar($buttons);
?>

</fieldset>
<?php print create_button_bar(array(array("text"=>"New Overview","class"=>"button new","href"=>site_url("overview/create/$kTeach"))));?>

<?php if(!empty($overviews)): ?>
<table class="list subject-templates">
        		<thead>
            		<tr>
                		<th></th>
                		<th>Subject</th>
                		<th>Term</th>
                		<th>Grades</th>
                		<th>Status</th>
            		</tr>
        		</thead>
        	<tbody>
<? foreach($overviews as $overview): ?>
		<? $currentTerm = $overview->term . " " . format_schoolyear($overview->year); ?>
		<tr>
    		<td><?=create_button(array("text"=>"Edit","class"=>"button small edit","href"=>site_url("overview/edit/$overview->kOverview")));?></td>
    		<td><strong><?=$overview->subject;?></strong></td>
    		<td><?=$currentTerm?></td>
    		<td><?=format_grade_range($overview->gradeStart, $overview->gradeEnd, TRUE);?> </td>
            <td class="status"><?=$overview->isActive == 0?"Inactive":"Active";?> </td>
       </tr>
<? endforeach;?>
	</tbody>
</table>
<? else: ?>
	<p>There were no results for this search.</p>
<? endif;

