<?php
?>
<div class="portlet" id="student-portlet_<?php echo $student->kStudent; ?>">
  <div class="portlet-header"><?php echo format_name($student->stuNickname, $student->stuLast);?> 
  (<?php echo  format_grade(get_current_grade($student->baseGrade, $student->baseYear, get_current_year()));?>)
  (<?php echo $student->stuGender;?>)
  </div>
</div>
