<?php
$teacher = "";
foreach($teachers as $teacher): ?>
<?php $i = 0; //iterate to make sure there are at least 20 rows for each teacher column?>
<div class="column class-listing column-3">
<div class="classroom header row">
<?php echo $teacher->teachClass; ?>
</div>
<?php foreach($teacher->students as $student):?>
<?php $i++; ?>
<div class="row">
<?php echo sprintf("<a href='%s'>%s</a>",base_url("student/view/$student->kStudent"), format_name($student->stuNickname, $student->stuLast)); ?>
<?php echo sprintf("&nbsp;(%s)",format_grade($student->stuGrade));?>
</div>
<?php endforeach;?>
<?php while ($i < 18):?>
<div class="row empty">&nbsp;
</div>
<?php $i++; ?>
<?php endwhile; ?>
</div>
<?php endforeach; ?>