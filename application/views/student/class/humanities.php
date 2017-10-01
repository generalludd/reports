<?php
?>

<div class="columns">
<!-- student/class/portlet.php -->
<div class="class" id="students">
<h3>Students</h3>
 <!-- loop through students -->
 <?php foreach($students as $student):?>
   <?php if(!get_value($student,"stuGroup")):?>
     <?php $this->load->view("student/class/portlet", array("student"=>$student))?>

     <?php endif;?>
  <?php endforeach; ?>
</div>
 <!-- loop through teachers -->
 <?php foreach($groups as $group):?>
<div class="class" id="group_<?php echo $group->kTeach;?>_<?php echo $type;?>">
 <h3><?php echo $group->teacherName;?></h3>
  <?php foreach($students as $student):?>
      <?php $this->load->view("student/class/portlet", array("student"=>$student))?>

  <?php endforeach; ?>
</div>
<?php endforeach; ?>
</div>