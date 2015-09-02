<?php
?>

<h3><?echo $title; ?></h3>
<table class="list">
	<thead>

	</thead>
	<tbody>
<?php foreach ($students as $student): ?>
<tr id="student-attendance_<?php echo $student->kStudent; ?>">
			<td><?php echo format_name($student->stuNickname,$student->stuLast); ?></td>
			<td><?php if($student->attendance): ?>
				<span class="highlight">
					<?php print format_attendance ( $student->attendance ); ?>
				</span>
				<?php endif; ?>
			</td>
			<td>
				<a class="button">Present</a>
				&nbsp;
				<a class="button">Absent</a>
			</td>

		</tr>
<?php endforeach; ?>
</tbody>

</table>
