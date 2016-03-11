<?php //@TODO fix the flow here to be more properly coded. 
$current_class = FALSE;
foreach($students as $student): ?>
<?php $i = 0; //iterate to make sure there are at least 20 rows for each teacher column?>
<?php if($student->teachClass != $current_class):?>
<?php if($current_class):?>
</div>
<?php endif; ?>
<div class="column class-listing column-3">
<div class="classroom header row">
<?php echo $student->teachClass; ?>
<?php $current_class = $student->teachClass; ?>
</div>
<?php endif; ?>

<div class="row">
<?php echo sprintf("<a href='%s'>%s</a>",base_url("student/view/$student->kStudent"), format_name($student->stuNickname, $student->stuLast)); ?>
<?php echo sprintf("&nbsp;(%s)",format_grade($student->stuGrade));?>
</div>

<?php endforeach; ?>