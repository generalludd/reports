<?php
#apply templates to a batch of students for the  subject,term, year, grade range. 
?>
<table class="list">
<thead>
<tr>
<th>Student</th><th>Grade</th><th></th>
</tr>
</thead>
<tbody>

<? foreach($students as $student):
	$has_narrative = $this->narrative_model->has_narrative($student->kStudent, $kTeach, $subject, $narrTerm, $narrYear);
	if(!$has_narrative):
		$student_name = format_name($student->stuFirst, $student->stuLast, $student->stuNickname);
		?>
		<tr class="student-row">
		<td><a href="<?=site_url("student/view/$student->kStudent");?>" title="view student info"><?=$student_name;?></a></td>
		<td><?=format_grade($student->baseGrade + get_current_year() - $student->baseYear);?></td>
		<td class="apply-template-button">
		<a href="<?php echo site_url("narrative/apply_template");?>" class="button apply_template new" id="an_<?php echo $student->kStudent;?>_<?php echo $template->kTemplate;?>">Apply Template</a>
		</td>
		</tr>
<?
	endif;
	
endforeach;
?>
</tbody>
</table>
<script type="text/javascript">
$(".apply_template").live("click",function(e){
	e.preventDefault(); 
	me = $(this);
	my_id = this.id.split("_");
	my_student = my_id[1];
	my_template = my_id[2];
	my_url = $(this).attr("href");
	form_data = {
		ajax: 1,
		kStudent: my_student,
		kTemplate: my_template			
	};
	//console.log(form_data);
	
	$.ajax({
		type: "post",
		url: my_url,
		dataType: "json",
		data: form_data,
		success: function(data){
			target_url = base_url + "narrative/edit/" + data.kNarrative;
			$(me).addClass("edit").removeClass("new").removeClass("apply_template").html("Edit Narrative");
			
			me.attr("href", target_url);
		},
		error: function(data){
			console.log("FAIL");
			
		}
		});
});

</script>