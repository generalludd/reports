<?php
$student_link = NULL;
$search_title = "<h3>$title</h3>";
$button_bar = "";
$search_button = "<div class='button-box'><span class='button show_attendance_search'>Modify Search</span></div>";
$add_button = "";
$search_fieldset = "";
if($kStudent){
	$student_name = format_name( $student->stuNickname, $student->stuLast, $student->stuNickname);
	$search_title =  "<h3>$title: $student_name</h3>";
	$button_bar = $this->load->view("student/navigation", $kStudent, TRUE);
	$search_button =  create_button_bar(array(array("text"=>"Refine Search","class"=>"button search show_attendance_search","id"=>"show-attendance-search_$kStudent")));
	$add_button = create_button_bar(array(array("text"=>"Add Attendance","class"=>"button new add_attendance","id"=>"student-add-attendance_$kStudent","Add an attendance record")));
}

if($action == "search"){
	$options = array();
	$startDate = format_date($startDate, "standard");
	$endDate = format_date($endDate, "standard");
	$options[] = "Start Date: <strong>$startDate</strong>";
	$options[] = "End Date: <strong>$endDate</strong>";
	if(!empty($attendType)){
		$options[] = "Attendance Type: <strong>$attendType</strong>";
	}
	if(!empty($attendSubtype)){
		$options[] = "Attendance Subtype: <strong>$attendSubtype</strong>";
	}
	$search_parameters = "<li>" . implode("</li><li>", $options) . "</li>";
	$search_fieldset = "<fieldset class='search_fieldset'>";
	$search_fieldset .= "<legend>Search Parameters</legend><ul>";
	$search_fieldset .= $search_parameters;
	$search_fieldset .= "</ul>$search_button</fieldset>";
}

print $search_title;
print $button_bar;
print $search_fieldset;
if($this->session->userdata("dbRole") == 1):
print $add_button;
endif;

$current_student = NULL;
if($errors == "dup"){
	print "<div class='notice' style='margin:1em 0;padding: .5em;'>It looks like you have tried to enter a duplicate attendance record for $student. Please verify.</div>";
}
if(!empty($attendance)):

?>

<table class='attendance'>
	<thead>
		<tr>
			<th class='a-button'></th>
			<th class='a-date'>Date</th>
			<th class='a-type'>Type</th>
			<th class='a-subtype'>Subtype</th>
			<th class='a-length'>Length</th>
			<th class='a-note'>Notes</th>
		</tr>
	</thead>
	<tbody>
	<?

	foreach($attendance as $item):
	if(!$kStudent){
		if($item->kStudent != $current_student){
			$student_name = format_name($item->stuFirst, $item->stuLast, $item->stuNickname);
			print "<tr><td colspan=6><a class='link' href='" . site_url("student/view/$item->kStudent");
			print "' title='View student info'>$student_name</a>&nbsp;";
			if($this->session->userdata("userID") == 1):
			print "<a class='button new small add_attendance'
				 id='saa_$item->kStudent' title='Add attendance record'>Add</a>";
			endif;
		echo "</td></tr>";

			$current_student = $item->kStudent;
		}
	}
	$attendDate = format_date($item->attendDate, 'standard');
	?>
		<tr>
			<td>
			<? if($this->session->userdata("dbRole") == 1): ?>
			<a class='edit_attendance edit button small'
				id='a_<?=$item->kAttendance;?>' title="Edit">Edit</a>

				<? endif; ?>
				</td>
			<td><?=$attendDate;?></td>
			<td><?=$item->attendType;?></td>
			<td><?=$item->attendSubtype;?></td>
			<td><?=$item->attendLength;?></td>
			<td><?=$item->attendNote;?></td>

		</tr>

		<? endforeach; ?>
	</tbody>
</table>
		<?
		else:
		if($kStudent):
		print "<p>$student_name does not have any attendance entries for the current year";
		if($this->session->userdata("userID") == 1):
			print "&nbsp;<span class='add_attendance button' id='saa_$kStudent'>Add Record</span></p>";
		endif;
		else:
		print "<p>No records for the given search were found.&nbsp;<span class='button show_attendance_search'>Search again</span></p>";
		endif;
		endif;




