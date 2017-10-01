<?php  defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class='student_list'>
<?php foreach($students as $student):
	$student_style = "student-name";
	$name = format_name($student->stuFirst,$student->stuLast,$student->stuNickname);
	?>
	<p>
		<span class='link <?php  echo $js_class;?>'
			id='ss_<?php  echo $student->kStudent;?>'><?php  echo "$name";?> </span>
	</p>
<?php endforeach; ?>
</div>
