<?php $startDate = date("Y-m-d");?>
<html>
<body>
<h1><?php echo $subject; ?></h1>
<?php if($records): ?>
<p>Please verify each record</p>
<?php $attendType = "";?>
<?php foreach($records as $record): ?>
	<?php if($attendType != $record->attendType):?>
		<h4><?php echo $record->attendType;?></h4>
		<?php $attendType = $record->attendType;?>
	<?php endif; ?>
	<p><?php echo format_name($record->stuFirst, $record->stuLast, $record->stuNickname);?> (<?php echo $record->attendOverride?"Present <span style='background-color: yellow;'>(Click the link to delete this attendance record)</span>":$record->attendType;?>)
	<a href="<?php echo base_url("attendance/search/$record->kStudent?startDate=$startDate");?>">View or Update</a></p>
<?php endforeach;?>
<?php else: ?>
<p><?php $teacherName; ?> has completed attendance with no additional absences.</p>
<?php endif; ?>
</body>
</html>