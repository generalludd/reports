<?php
$student_link = NULL;
$button_bar = "";
// $search_button = "<div class='button-box'><a class='button dialog' href='>Modify Search</span></div>";
$search_buttons [] = array (
		"text" => "Refine Search",
		"href" => site_url ( "attendance/show_search/$kStudent?refine=1" ),
		"class" => "button search dialog" 
);
if ($startDate == $endDate) {
	
	$search_buttons [] = array (
			"text" => "Print Daily Report",
			"href" => site_url ( "attendance/printout?date=$startDate" ),
			"class" => "button print" 
	);
}
$add_button = "";
$search_fieldset = "";
if ($action == "search") {
	$options = array ();
	$options [] = sprintf ( "Date Range: <strong>%s</strong>", format_date_range ( $startDate, $endDate ) );
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
	
	$search_fieldset .= "</ul>";
	$search_fieldset .= create_button_bar($search_buttons) . "</fieldset>";
	
}

print $button_bar;
print $search_fieldset;
if ($this->session->userdata ( "dbRole" ) == 1) :
	print $add_button;





endif;

$current_student = NULL;
if (! empty ( $attendance )) :
	
	?>
	<?php if($kStudent):?>
<!-- attendance/list.php -->
<?php 	print create_button_bar(array(array("selection"=>"attendance","href"=>site_url("attendance/create/$student->kStudent?redirect=" .$_SERVER['QUERY_STRING']),"class"=>"button dialog new","text"=>"Add Attendance")));?>
<h4>Attendance Summary for <?php echo format_schoolyear(get_current_year());?></h4>
<p>
	Days Absent: <strong><?php echo $summary['absent'];?></strong>, Days Tardy: <strong><?php echo $summary['tardy'];?></strong>
</p>
<?php endif; ?>
<div class="rows">

<?php if(!empty($unmarked)):?>
<div class="unmarked">
		<h3>Students Missing from Attendance</h3>
<?php $this->load->view("attendance/checklist/list",array("students"=>$unmarked,"unmarked"=>TRUE));?>
</div>
<?php endif; ?>
<table class='attendance' style="order: -1">
		<thead>
			<tr>
				<th class='a-button no-print'></th>
				<th class='a-name'>Name</th>
			<?php if($kStudent):?>
			<th class='a-date'>Date</th>
			<?php endif;?>
			<th class='a-grade'>Grade</th>
				<th class='a-class'>Class</th>
				<th class='a-type'>Type</th>
				<th class='a-subtype'>Subtype</th>
				<th class='a-length'>Length</th>
				<th class='a-note'>Notes</th>
			</tr>
		</thead>
		<tbody>
	<?php
	$current_class = "";
	?>
	<?php foreach ( $attendance as $item ) :?>
	<tr>

		<?php $buttons = array (); ?>
		<?php $student_class = format_classroom ( $item->teachClass, $item->stuGrade, $item->stuGroup ); ?>
	<?php	if ($student_class != $current_class) :?>
		<?php  $current_class = $student_class; ?>
		
	<?php endif; //current class label ?>
		<?php
		
		if ($item->kStudent != $current_student) :
			?>
			<?php $student_name = format_name ( $item->stuNickname, $item->stuLast, $item->stuNickname ); ?>
		<?php 	$current_student = $item->kStudent;?>
		<?php endif;?>
		
	<?php $attendDate = format_date ( $item->attendDate, 'standard' );?>
	<td class='no-print'>
			<?php if($this->session->userdata("dbRole") == 1): //@TODO Clean this Up!?>
<?php $buttons[]= array("text"=>"Edit","href"=> site_url("attendance/edit/$item->kAttendance?redirect=" . uri_query()), "class"=>"dialog edit small button","id"=> "a_$item->kAttendance");?>
			
			<?php echo create_button_bar($buttons); ?>
				
				<?php endif; ?>
				</td>
				<td style="white-space: nowrap;">
					<a href="<?php echo site_url("student/view/$item->kStudent");?>" title="view details for <?php echo $student_name; ?>"><?php echo $student_name;?></a>
				</td>
		<?php if($kStudent):?>
		<td><?php echo format_date($item->attendDate);?></td>
		<?php endif; ?>
		<td><?php echo format_grade($item->stuGrade);?></td>
				<td><?php echo format_classroom($item->teachClass, $item->stuGrade, $item->stuGroup);?></td>
				<td><?php  echo $item->attendType;?></td>
				<td><?php  echo $item->attendSubtype;?></td>
				<td><?php  echo $item->attendLength;?></td>
				<td><?php  echo $item->attendNote;?><?php echo $item->attendOverride?" <span class='highlight'>($item->teachFirst $item->teachLast has marked this student as present during daily attendance. Please delete accordingly.)</span>":"";?></td>

			</tr>

		<?php endforeach; ?>
	</tbody>
	</table>


</div>


<?phpelse :
	

	if ($kStudent) :
		?>
<p>
	<a href="<?php echo site_url("student/view/$kStudent");?>" title="View student's record"><?php echo format_name($student->stuNickname, $student->stuLast)?></a>
	does not have any attendance entries for the selected search
</p>

<?php echo $add_button; ?>
	 

	<?php else : ?>
<p>No records for the given search were found</p>


<?php endif; ?>
<?php endif;?>


