<?php


echo "<tbody><tr><td><strong>$narrative->narrSubject</strong></td>";
echo "<td><a href=". site_url("narrative/teacher_list/$narrative->kTeach") . " class='link'>$teacher</a></td>";
echo "<td>" . format_timestamp($narrative->recModified) . "</td>";
echo "<td><a class='button' href='" . site_url("narrative/view/$narrative->kNarrative"). "'>View/Edit</a>";
if($hasSuggestions){
	echo "&nbsp;<span class='highlight'>Has Suggested Edits</span>";
}
echo "</td></tr></tbody>";


 