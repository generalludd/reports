<?php
?>
<div class="portlet" id="student-portlet_<?php echo $student->kStudent; ?>">
  <div class="portlet-header"><a href="<?php echo base_url("/student/view/$student->kStudent");?>" target="_blank"><?php print $student->stuLast . ', ' . $student->stuNickname;?></a>
  (<?php echo  format_grade(get_current_grade($student->baseGrade, $student->baseYear, get_current_year()));?>)
  (<?php echo $student->stuGender;?>)
  </div>
</div>
