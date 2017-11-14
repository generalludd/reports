<?php
// a printable report of daily attendance.
?>
<!-- views/attendance/report.php -->
<h4>
<?php echo $date . " Printed at " . date("g:m a"); ?>
</h4>
<table class="grid attendance-printout">
	<thead>
		<tr>
			<th>Count</th>
			<th>Classroom/Grade</th>
			<th>Full-Day Absence</th>
			<th>Partial Absence</th>
			<th>Notes</th>
	</thead>
	<tbody>
		<?php foreach($lower_school as $record):?>
			<?php $teacher = format_name($record->teachFirst, $record->teachLast);?>
			<tr class="attendance-group"><td><?php printf("%s/%s",  get_value($record,"count"), get_value($record,"total")); ?></td>
				<td class="group-name"><?php print $teacher; ?></td>
				<td class="break" colspan="4"><?php echo empty($record->attendance)?"<span class='no-absences'>No absences for $teacher's class</span>":""; ?></td>
			</tr>
			<?php if(!empty($record->attendance)):?>
			
				<?php foreach($record->attendance as $attendance):?>
					<tr class="attendance-entries">
						<td colspan="2"></td>
						<?php $student_name = format_name($attendance->stuFirst, $attendance->stuLast, $attendance->stuNickname);?>
						<td class="record record-full-day"><?php echo $attendance->attendType == "Absent"&& !$attendance->attendLength? $student_name:"";?></td>
						<td class="record record-half-day"><?php echo ($attendance->attendType == "Absent" && $attendance->attendLength) ||$attendance->attendType == "Appointment" ? sprintf( "%s<br/><em>%s</em>",$student_name, $attendance->attendLengthType): ""; ?></td>
						<td class="record record-notes"><?php echo $attendance->attendNote;?></td>
					</tr>
				<?php endforeach;?>
			<?php else:?>
					<tr class="attendance-entries">
						<td colspan="2"></td>
						<td colspan="3" class="no-absences">-</td>
					</tr>
			<?php endif;?>
		
		
		<?php endforeach;?>
		<?php foreach($middle_school as $record):?>
			<tr class="attendance-group">
				<td><?php printf("%s/%s",  get_value($record,"count"), get_value($record,"total")); ?></td>
				<td  class="group-name"><?php echo $record->group;?></td>
				<td class="break" colspan="4"><?php echo empty($record->attendance)?"<span class='no-absences'>No absences for $record->group</span>":""; ?></td>
			</tr>
			<?php if(!empty($record->attendance)):?>
				<?php foreach($record->attendance as $attendance):?>
					<tr class="attendance-entries">
						<td colspan="2"></td>
						<?php $student_name = format_name($attendance->stuFirst, $attendance->stuLast, $attendance->stuNickname);?>
						<td class="record record-full-day"><?php echo $attendance->attendType == "Absent"&& !$attendance->attendLength? $student_name:"";?></td>
						<td class="record record-half-day"><?php echo ($attendance->attendType == "Absent" && $attendance->attendLength) ||$attendance->attendType == "Appointment" ? sprintf( "%s<br/><em>%s</em>",$student_name, $attendance->attendLengthType): ""; ?></td>
						<td class="record record-notes"><?php echo $attendance->attendNote;?></td>
					</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr class="attendance-entries">
					<td colspan="2"></td>
					<td colspan="3" class="no-absences">-</td>
				</tr>
			<?php endif;?>
		<?php endforeach;?>
	</tbody>
</table>