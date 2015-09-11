<html>
<body>
<h1><?php echo $subject; ?></h1>
<?php if($records): ?>
<h3>The following students were marked absent</h3>
<p>Please verify each record</p>
<?php foreach($records as $record): ?>
	<p><?php echo format_name($record->stuFirst, $record->stuLast, $record->stuNickname);?>
	<a href="<?php echo base_url("attendance/search/$record->kStudent");?>">View or Update</a></p>
	
<?php endforeach;?>
<?php else: ?>
<p><?php $teacherName; ?> has completed attendance with no additional absences.</p>
<?php endif; ?>
</body>
</html>