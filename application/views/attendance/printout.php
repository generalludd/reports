<?php
// a printable report of daily attendance.
?>
<!-- views/attendance/report.php -->
<h4>
<?php echo date("M-d-Y");?>
</h4>
<table class="grid attendance-printout">
	<thead>
		<tr>
		<th>Count</th>
		<th>Class/Grade</th>
		<th>Full-Day Absence</th>
		<th>Half-Day Absence</th>
		<th>Notes</th>
	</thead>
	<tbody>
	<?php $current_class = NULL; ?>
	<?php foreach($records as $record):?>
	<?php $student_name = sprintf("%s %s",format_name($record->stuFirst,$record->stuLast, $record->stuNickname), format_student_group($record->stuGrade, $record->stuGroup)); ?>
		<tr>
		<td class="record record-count"><?php isset($record->count)?printf("%s/%s", get_value($record,"count"), get_value($record,"total")):""; ?></td>
		<td class="record record-class"><?php echo get_value($record,"group"); ?></td>
		<td class="record record-full-day"><?php echo $record->attendType == "Absent"&& !$record->attendLength? $student_name:"";?></td>
		<td class="record record-half-day"><?php echo $record->attendType == "Absent" && $record->attendLength? sprintf( "%s<br/><em>%s</em>",$student_name, $record->attendLengthType): ""; ?></td>
		<td class="record record-notes"><?php echo $record->attendNote;?></td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>