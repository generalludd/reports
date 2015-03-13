<?php

?>
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

$buttons[] = array("text"=>"Refine Search","class"=>"button template_search","id"=>"ts_$kTeach");
echo create_button_bar($buttons);
?>

</fieldset>
<?=create_button_bar(array(array("text"=>"New Template","class"=>"button new","href"=>site_url("template/create/$kTeach"))));?>

<? if(!empty($templates)): ?>
<table class="list subject-templates">
        		<thead>
            		<tr>
                		<th></th>
                		<th>Subject</th>
                		<th>Term</th>
                		<th>Grades</th>
                		<th>Description</th>
                		<th>Status</th>
            		</tr>
        		</thead>
        	<tbody>
<? foreach($templates as $template): ?>
		<? $currentTerm = $template->term . " " . format_schoolyear($template->year); ?>

		<tr>
    		<td><?=create_button(array("text"=>"Edit","class"=>"button small edit","href"=>site_url("template/edit/$template->kTemplate")));?></td>
    		<td><strong><?=$template->subject;?></strong></td>
    		<td><?=$currentTerm?></td>
    		<td><?=format_grade_range($template->gradeStart, $template->gradeEnd, TRUE);?> </td>
            <td><?=!empty($template->type)?$template->type:"";?></td>
            <td class="status"><?=$template->isActive == 0?"Inactive":"Active";?> </td>
       </tr>
<? endforeach;?>
	</tbody>
</table>
<? else: ?>
	<p>There were no results for this search.</p>
<? endif;

