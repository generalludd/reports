<?php
$date = date("Y-m-d");
$kTeach = $this->session->userdata("userID");
?>

<h3><?echo $title; ?></h3>
<h4>Be sure to click the "Attendance Complete" button at the bottom when you are done!</h4>
<div class="checklist class-listing">
<?php foreach ($students as $student): ?>
<div id="student-attendance_<?php echo $student->kStudent; ?>" class="checklist row">
			<p class="student-info"><a href="<? echo base_url("attendance/search/$student->kStudent");?>"><?php echo format_name($student->stuNickname,$student->stuLast); ?></a>
			<?php if($student->attendance): ?>
				<span class="highlight">
					<?php print format_attendance ( $student->attendance ); ?>
				</span>
				<?php endif; ?>
				</p>
				<div class="attendance-buttons">
				<?php echo $student->buttons;?>
				</div>
	</div><!-- End student_attendance -->
<?php endforeach; ?>
</div>
<?php echo create_button_bar(array(array("text"=>"Attendance Complete","class"=>"button insert","href"=>base_url("attendance/complete/$date/$kTeach"))));?>