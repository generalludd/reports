<?php
$teacher_ids = array();
foreach($groups as $group){
    $teacher_ids[] = $group->teacherName;
}
?>
<!-- student/class/classroom.php -->

<div class="columns">
    <div class="class" id="students">
        <h3>Students</h3>
        <!-- loop through students -->
      <?php foreach($students as $student):?>
        <?php if(!in_array($student->humanitiesTeacher,$teacher_ids)): ?>
             <?php $this->load->view("student/class/portlet", array("student"=>$student))?>
          <?php endif; ?>
      <?php endforeach; ?>
    </div>
 <!-- loop through teachers -->
 <?php foreach($groups as $group):?>
<div class="class" id="group_<?php echo $group->kTeach;?>_<?php echo $type;?>">
 <h3><?php echo $group->teacherName;?></h3>
    <?php foreach($students as $student):?>
  <?php if($group->teacherName == $student->humanitiesTeacher): ?>
      <?php $this->load->view("student/class/portlet", array("student"=>$student))?>
   <?php endif; ?>
    <?php endforeach; ?>
</div>
<?php endforeach; ?>
</div>