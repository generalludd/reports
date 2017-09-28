<?php
?>
<style>
.columns{
display: flex;
flex-direction: row;
justify-content: space-evenly; 
}
  .portlet {
    margin: 0 1em 1em 0;
    padding: 0.3em;
  }
  .portlet:hover{
  cursor: move;
  }
  .portlet-header {
    padding: 0.2em 0.3em;
    margin-bottom: 0.5em;
    position: relative;
  }
  .portlet-content {
    padding: 0.4em;
  }
  .portlet-placeholder {
    border: 1px dotted black;
    margin: 0 1em 1em 0;
    height: 50px;
  }</style>
<div class="columns">
<!-- student/class/portlet.php -->
<div class="class" id="students">
<h3>Students</h3>
 <!-- loop through students -->
 <?php foreach($students as $student):?>
  <div class="portlet" id="student-portlet_<?php echo $student->kStudent; ?>">
    <div class="portlet-header"><?php echo format_name($student->stuNickname, $student->stuLast);?></div>
  </div>
  <?php endforeach; ?>
</div>
 <!-- loop through teachers -->
 <?php foreach($teachers as $teacher):?>
<div class="class" id="teacher_<?php echo $teacher->kTeach;?>_<?php echo "humanitiesTeacher";?>">
 <h3><?php echo format_name($teacher->teachFirst, $teacher->teachLast);?></h3>
</div>
<?php endforeach; ?>
</div>