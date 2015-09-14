<?php
$student_link = NULL;
$search_title = "<h3>$title</h3>";
$button_bar = "";
// $search_button = "<div class='button-box'><a class='button dialog' href='>Modify Search</span></div>";
$search_button = create_button_bar ( array (
		array (
				"text" => "Refine Search",
				"href" => site_url ( "attendance/show_search/$kStudent" ),
				"class" => "button search dialog" 
		) 
) );

$add_button = "";
$search_fieldset = "";

if ($action == "search") {
	$options = array ();
	$startDate = format_date ( $startDate, "standard" );
	$endDate = format_date ( $endDate, "standard" );
	$options [] = "Start Date: <strong>$startDate</strong>";
	$options [] = "End Date: <strong>$endDate</strong>";
	if (! empty ( $attendType )) {
		$options [] = "Attendance Type: <strong>$attendType</strong>";
	}
	if (! empty ( $attendSubtype )) {
		$options [] = "Attendance Subtype: <strong>$attendSubtype</strong>";
	}
	$search_parameters = "<li>" . implode ( "</li><li>", $options ) . "</li>";
	$search_fieldset = "<fieldset class='search_fieldset no-print'>";
	$search_fieldset .= "<legend>Search Parameters</legend><ul>";
	$search_fieldset .= $search_parameters;
	$search_fieldset .= "</ul>$search_button</fieldset>";
}

print $search_title;
print $button_bar;
print $search_fieldset;
if ($this->session->userdata ( "dbRole" ) == 1) :
	print $add_button;


endif;

$current_student = NULL;
if (! empty ( $attendance )) :
	
	?>

<table class='attendance'>
	<thead>
		<tr>
			<th class='a-button no-print'></th>
			<th class='a-name'>Name</th>
			<th class='a-grade'>Grade</th>
			<th class='a-class'>Class</th>
			<th class='a-type'>Type</th>
			<th class='a-subtype'>Subtype</th>
			<th class='a-length'>Length</th>
			<th class='a-note'>Notes</th>
		</tr>
	</thead>
	<tbody>
	<?
	$current_class = "";?>
	<?php foreach ( $attendance as $item ) :?>
		<?php $buttons = array (); ?>
		<?php $student_class = format_classroom ( $item->teachClass, $item->stuGrade, $item->stuGroup ); ?>
	<?php	if ($student_class != $current_class) :?>
		<?php  $current_class = $student_class; ?>
		
	<?php endif; //current class label ?>
		<?php if($item->kStudent != $current_student) :
			?>
			<?php $student_name = format_name ( $item->stuNickname, $item->stuLast, $item->stuNickname ); ?>
		<?php 	$current_student = $item->kStudent;?>
		<?php endif;?>
		
	<?php $attendDate = format_date ( $item->attendDate, 'standard' );?>
	<td class='no-print'>
			<? if($this->session->userdata("dbRole") == 1): //@TODO Clean this Up!?>
<?php $buttons[]= array("text"=>"Edit","href"=> site_url("attendance/edit/$item->kAttendance"), "class"=>"dialog edit small button","id"=> "a_$item->kAttendance");?>
			
			<?php echo create_button_bar($buttons); ?>
				
				<? endif; ?>
				</td>
		<td style="white-space: nowrap;"><a href="<?php echo site_url("student/view/$item->kStudent");?>" title="view details for <?php echo $student_name; ?>"><?php echo $student_name;?></a></td>
		<td><?php echo format_grade($item->stuGrade);?></td>
		<td><?php echo format_classroom($item->teachClass, $item->stuGrade, $item->stuGroup);?></td>
		<td><?=$item->attendType;?></td>
		<td><?=$item->attendSubtype;?></td>
		<td><?=$item->attendLength;?></td>
		<td><?=$item->attendNote;?></td>

		</tr>

		<? endforeach; ?>
	</tbody>
</table>


 <?
else :
	if ($kStudent) :
		print "<p>$student_name does not have any attendance entries for the current year";
		
		print $add_button;
	 

	else :
		print "<p>No records for the given search were found.&nbsp;<span class='button show_attendance_search'>Search again</span></p>";
	

	endif;
endif;




