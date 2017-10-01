<?php
?>
<fieldset class="search_fieldset"><legend>Search Parameters</legend>
<ul>
<li>
Term: <strong><?php  echo "$narrTerm " . format_schoolyear($narrYear);?></strong>
</li>
<li>
Subject: <strong><?php  echo $subject;?></strong>
</li>
<li>
<?php if($gradeStart == $gradeEnd){
	echo "Grade: <strong>" . format_grade($gradeStart) . "</strong>";
}else{
    echo "Grades: <strong>". format_grade($gradeStart) . " to " . format_grade($gradeEnd). "</strong>";
}
?>
</li>
</ul>
<div class="button-box">
<a class="button dialog" href="<?php echo site_url("narrative/search_missing/$kTeach");?>">Refine Search</a></div>
</fieldset>
<table class="list">
<thead>
<tr>
<th>Student</th><th>Grade</th><th></th>
</tr>
</thead>
<tbody>

<?php foreach($students as $student){
	$has_narrative = $this->narrative_model->has_narrative($student->kStudent, $kTeach, $subject, $narrTerm, $narrYear);
	if(!$has_narrative){
		$student_name = format_name($student->stuFirst, $student->stuLast, $student->stuNickname);
		?>
		<tr>
		<td><a href="<?php  echo site_url("student/view/$student->kStudent");?>" title="view student info"><?php  echo $student_name;?></a></td>
		<td><?php  echo format_grade($student->baseGrade + get_current_year() - $student->baseYear);?></td>
		<td>
		<a href="<?php echo site_url("narrative/select_type?kStudent=$student->kStudent");?>" class="button dialog new" id="an_<?php  echo $student->kStudent;?>">Add Narrative</a>
		</td>
		</tr>
<?php 
	}
	
}
?>
</tbody>
</table>