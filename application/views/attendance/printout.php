<?php
// a printable report of daily attendance.
?>
<!-- views/attendance/report.php -->
<h4>
<?php echo $date?>
</h4>
<table class="grid attendance-printout">
<thead>
		<tr>
			<th>Count</th>
			<th>Classroom/Grade</th>
			<th>Full-Day Absence</th>
			<th>Half-Day Absence</th>
			<th>Notes</th>
	</thead>
	<tbody>
<?php foreach($lower_school as $record):?>
	<?php $teacher = format_name($record->teachFirst, $record->teachLast);?>
<tr><td><?php printf("%s/%s",  get_value($record,"count"), get_value($record,"total")); ?></td>
<td><?php print $teacher; ?></td>
<td</td>
<td></td>
<td></td>
<td></td>
</tr>
<?php if(!empty($record->attendance)):?>

	<?php foreach($record->attendance as $attendance):?>
	<?php $student_name = format_name($attendance->stuFirst, $attendance->stuLast, $attendance->stuNickname);?>
	<td colspan="2"></td>
	<td class="record record-full-day"><?php echo $attendance->attendType == "Absent"&& !$attendance->attendLength? $student_name:"";?></td>
		<td class="record record-half-day"><?php echo $attendance->attendType == "Absent" && $attendance->attendLength? sprintf( "%s<br/><em>%s</em>",$student_name, $attendance->attendLengthType): ""; ?></td>
		<td class="record record-notes"><?php echo $attendance->attendNote;?></td>
	<?php endforeach;?>
<?php endif;?>
<?php endforeach;?>
<?php foreach($middle_school as $record):?>
<tr>
<td><?php printf("%s/%s",  get_value($record,"count"), get_value($record,"total")); ?></td>
<td colspan="4"><?php echo $record->group;?></td>
</tr>
<?php if(!empty($record->attendance)):?>

	<?php foreach($record->attendance as $attendance):?>
	<?php $student_name = format_name($attendance->stuFirst, $attendance->stuLast, $attendance->stuNickname);?>
	<td></td>
	<td></td>
		<td class="record record-full-day"><?php echo $attendance->attendType == "Absent"&& !$attendance->attendLength? $student_name:"";?></td>
		<td class="record record-half-day"><?php echo $attendance->attendType == "Absent" && $attendance->attendLength? sprintf( "%s<br/><em>%s</em>",$student_name, $attendance->attendLengthType): ""; ?></td>
		<td class="record record-notes"><?php echo $attendance->attendNote;?></td>
	<?php endforeach;?>
<?php endif;?>
<?php endforeach;?>
</tbody>
</table>