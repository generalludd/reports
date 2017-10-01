<?php
?>
<div class="portlet" id="student-portlet_<?php echo $student->kStudent; ?>">
  <div class="portlet-header"><?php echo format_name($student->stuNickname, $student->stuLast);?>
    <?php if(isset($show_ab)): ?>
      (Group: <span class="ab-group"><?php echo $student->stuGroup;?></span>)
    <?php endif; ?>
    <div class="student-details">
      <ul>
        <li>  Grade: <?php echo get_current_grade($student->baseGrade, $student->baseYear, get_current_year());?></li>
      </ul>
    </div>
  </div>
</div>
