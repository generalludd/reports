<?php
$date = date("Y-m-d");
$kTeach = $this->session->userdata("userID");
?>

<h3><?echo $title; ?></h3>
<div class="checklist class-listing">
<?php foreach ($students as $student): ?>
<div id="student-attendance_<?php echo $student->kStudent; ?>" class="checklist row">
			<p class="student-info"><?php echo format_name($student->stuNickname,$student->stuLast); ?>
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
<?php echo create_button_bar(array(array("text"=>"Attendance Complete","class"=>"button dialog insert","href"=>base_url("attendance/complete/$date/$kTeach"))));?>