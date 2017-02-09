<?php
?>
<h3>Search for Benchmarks for <?php echo format_name($student->stuFirst, $student->stuLast, $student->stuNickname);?></h3>

<form id="benchmark_select_for_student" name="select_for_student" method="get" action="<?php echo base_url("student_benchmark/select")?>">
<input type="hidden" name="kStudent" value="<?php echo $student->kStudent;?>"/>
<p>
<label for="grade">Grade in School</label>
<input type="text" name="student_grade" value="<?php echo $student_grade; ?>" style="width:2em;"/>
</p>
<p>
<label for="subject">Subject:</label>
<?php echo form_dropdown('subject', $subjects, $refine?get_cookie("benchmark_subject"):"" , "id='subject'"); ?>
</p>
<p>
<label for="year">Year</label>
<input type="text" name="year" id="year" value="<?php echo get_current_year();?>" style="width:3em;"/>-
<input type="text" name="yearEnd" id="yearEnd" value="<?php echo get_current_year() + 1;?>" style="width:3em;" readonly/></p>
<p>
<label for="term">Term</label>
<?php echo get_term_menu("term",get_current_term());?>
</p>
<p>
<label for="quarter">Quarter</label>
<?php echo form_dropdown('quarter',array(1=>1,2=>2,3=>3,4=>4), $refine?get_cookie("benchmark_quarter"):"","id='quarter'" );?>
</p>
<p>
<input type="submit" value="Search"/>
</p>
</form>
<script type="text/javascript">
$("#benchmark_select_for_student #quarter").change(function(){
console.log($(this).val());
my_val = $(this).val();
if(my_val == 1 || my_val == 2){
	$("#benchmark_select_for_student #term").val("Mid-Year");
}else{
	$("#benchmark_select_for_student #term").val("Year-End");	
}
});
$("#benchmark_select_for_student #term").change(function(){
	console.log($(this).val());
	my_val = $(this).val();
	if(my_val == "Mid-Year"){
		$("#benchmark_select_for_student #quarter").val(1);
	}else{
		$("#benchmark_select_for_student #quarter").val(3);	
	}
	});
</script>