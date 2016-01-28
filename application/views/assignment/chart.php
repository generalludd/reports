<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
$table = array ();
$assignment_count = 0;
$gradeDisplay = sprintf ( "Grade %s", $gradeStart );

if ($gradeStart != $gradeEnd) {
	$gradeDisplay = sprintf ( "Grades %s/%s", $gradeStart, $gradeEnd );
}
if ($stuGroup) {
	$gradeDisplay = sprintf ( "%s %s", $gradeDisplay, $stuGroup );
}

$buttons [] = array (
		"text" => "Refresh Page",
		"class" => "button refresh",
		"selection" => "grades" 
);
$buttons [] = array (
		"text" => "New Grade Search",
		"href" => site_url ( "assignment/search/$kTeach" ),
		"class" => "button dialog",
		"selection" => "grades" 
);
if ($kTeach == $this->session->userdata ( "userID" )) {
	$buttons [] = array (
			"text" => "Edit Categories",
			"class" => "button edit dialog",
			"selection" => "grades",
			"href" => site_url ( "assignment/edit_categories/$kTeach?year=$year&term=$term&gradeStart=$gradeStart&gradeEnd=$gradeEnd" ) 
	);
}
if($category_count > 0){
	
$buttons [] = array (
		"text" => "Add Assignment",
		"href" => site_url ( "assignment/create/$kTeach" ),
		"class" => "button dialog new" 
);
$buttons [] = array (
		"text" => "Batch Add Assignments",
		"selection" => "",
		"title" => "Insert batches of assignments at once (Beta!)",
		"href" => site_url ( "assignment/create_batch?kTeach=$kTeach&term=$term&year=$year&gradeStart=$gradeStart&gradeEnd=$gradeEnd" ),
		"class" => "button new dialog"
);
}
$buttons [] = array (
		"text" => "Batch Print Grades",
		"title" => "Print report cards for this subject for all the selected students",
		"class" => "button print batch-print-grades",
		"href" => "#" 
);
?>
<input type="hidden" name="kTeach" id="kTeach" value="<?=$kTeach;?>" />
<?
if (! empty ( $assignments )) {
	
	/*
	 * Get the subject and relevant data from the first row of the assignments
	 */
	$header = $assignments [0];
	$header_string = sprintf ( "%s<br/>%s<br/>%s", $header->subject, $header->term, format_schoolyear ( $header->year ) );
	$classes = array (
			"grade-chart" 
	);
	if (($header->year >= get_current_year () && $header->term == get_current_term () || $header->year >= get_current_year () && $header->term == "Year-End")) {
		$classes [] = "editable";
	} else {
		$classes [] = "locked";
	}
	?>
<h2>
	Grade Chart for
	<?=sprintf("%s, %s, %s by %s %s", $gradeDisplay, $header->term, format_schoolyear($header->year), $header->teachFirst, $header->teachLast) ;?>
</h2>
<? if($this->input->get("date_start")):?>
<h3>
	<label>Date Range: </label>
<?=sprintf("%s-%s",$this->input->get("date_start"), $this->input->get("date_end"));?>
</h3>
<? elseif(count($assignments > 10)): ?>
<div class="alert">
	You can reduce the number of assignments displayed by entering a date range when you run your grade search.<br />This can speed up editing when
	you are dealing with large numbers of assignments.
</div>
<? endif;?>
 <?=create_button_bar($buttons);?>
<table class='<?=implode(" ",$classes);?>'>
	<thead>
		<tr class="first">
			<th colspan='2'><?=$header_string;?>
			</th>
			<th></th>
			<th></th>
			<th class='chart-final-grade'>Final Grade</th>
			<?
	
	$assignment_keys = array ();
	foreach ( $assignments as $assignment ) :
		?>

			<th id="as_<?=$assignment->kAssignment;?>" class="assignment-field buttons">
				<div>
					<a class="dialog edit small button" id="ase_<?=$assignment->kAssignment;?>"
						href="<?php echo site_url("assignment/edit/$assignment->kAssignment");?>" title="Edit this assignment"
					>Edit</a>
					<span class="chart-assignment"><?=$assignment->assignment;?></span>
					<br />
					<span class='chart-category'><?=$assignment->category;?> </span>
					<br />
					<!-- an assignment with 0 points is calculated as a make-up points for assignments -->
					<span class='chart-points'> <?=$assignment->points>0?$assignment->points. " Points" : capitalize($assignment->points_type);?>
			</span>
					<br />
					<span class='chart-date'><?=format_date($assignment->date);?>
			</span>
				</div>
			<? $assignment_keys[] = $assignment->kAssignment;?>
			</th>
			<? endforeach; ?>
			<th class='assignment-button'>
			<?php echo create_button(array("text"=>"Add Assignment","href"=>site_url("assignment/create/$kTeach"),"class"=>"button dialog new") )?>
			</th>
		</tr>
		<tr class="second">
			<th colspan=5></th>
		<? foreach($assignment_keys as $key):?>
		<th class="buttons">
				<a href="<?php echo site_url("grade/edit_column/$key");?>" class='dialog small button' id='ace_<?=$key;?>'>Edit Grades</a>
			</th>
		<? endforeach;?>
		</tr>
	</thead>
	<tbody>
		<?
	
	if (! empty ( $grades )) {
		
		$current_student = FALSE;
		foreach ( $grades as $grade ) {
			if ($current_student != $grade->kStudent) {
				$rows [$grade->kStudent] ["name"] = sprintf("<td class='student-name'><a href='%s' title='View %s&rsquo;s student info' class='student'>%s</a></td>",base_url("/student/view/$grade->kStudent"),format_name($grade->stuNickname, $grade->stuLast),format_name($grade->stuNickname, $grade->stuLast));
				$rows [$grade->kStudent] ["name_string"] = format_name ( $grade->stuNickname, $grade->stuLast );
				$rows [$grade->kStudent] ["delete"] = sprintf ( "<td class='grade-delete-row'><span class='student delete button' id='dgr_%s_%s_%s' title='Delete the entire row'>Delete</span></td>", $grade->kStudent, $header->term, $header->year );
				$rows [$grade->kStudent] ["kStudent"] = $grade->kStudent;
				$current_student = $grade->kStudent;
				$student_points = 0;
				
				$href = site_url ( sprintf ( "grade/report_card?kStudent=%s&year=%s&term=%s&subject=%s&print=true", $grade->kStudent, $this->input->cookie ( "year" ), $this->input->cookie ( "term" ), $header->subject ) );
				$rows [$grade->kStudent] ["edit-button"] = sprintf ( "<td class='student-button'>%s</td>", create_button ( array (
						"text" => "Edit",
						"href" => site_url ( "grade/edit/$grade->kStudent/$grade->kTeach" ),
						"class" => array (
								"button",
								"edit",
								"dialog" 
						) 
				) ) );
				$rows [$grade->kStudent] ["button"] = "<td class='student-button'><a class='button print' target='_blank' href='$href'>Print</a></td>";
			} // end if current_student
			
			$points = $grade->points;
			$rows [$grade->kStudent] ["grade"] = array ();
			
			// if the student status for this grade is Abs or Exc display the
			// status instead of the grade
			
			if ($grade->points == 0 && $grade->assignment_total == 0) {
				$points = "";
			} // end if points
			
			if ($grade->status) {
				$points = $grade->status;
			}
			
			if ($grade->footnote) {
				$points .= "[$grade->footnote]";
			} // end if footnote
			
			$rows [$grade->kStudent] ["totals"] = calculate_final_grade ( $grade->final_grade );
			
			$rows [$grade->kStudent] ["grades"] [$grade->kAssignment] = sprintf ( "<td class='grade-points edit' id='sag_%s_%s'  title='%s'>%s</td>", $grade->kAssignment, $grade->kStudent, format_name ( $grade->stuNickname, $grade->stuLast ), $points );
		} // end foreach grades
		
		foreach ( $rows as $row ) {
			print sprintf ( "<tr id='sgtr_%s' title='%s' class='grade-chart-row'>", $row ['kStudent'], $row ['name_string'] );
			print $row ["delete"];
			print $row ["name"];
			print $row ["edit-button"];
			print $row ["button"];
			print sprintf ( "<td>%s (%s%s)</td>", calculate_letter_grade ( $row ['totals'] ), $row ['totals'], "%" );
			print implode ( "", $row ["grades"] );
			print "</tr>";
		} // end foreach rows
	} // end if $grades
	?>
	</tbody>
</table>
<div class='button-box'>
	<span class='button new show-student-selector'>Add Student</span>
</div>
<? }else{ ?>

	<?=$category_count == 0?"<p style='padding-bottom: 1em;'>You may need to create categories before creating assignments.</p>":""; ?>
	<?=create_button_bar($buttons);?>
<? if($category_count > 0){ ?>
<p>You have not entered any assignments or grades for this term.</p>
<?
	} // end if category_count
} //end if assignments


