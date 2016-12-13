<?php
$current_subject = "";
$current_category = "";
$this->load->view("student/navigation");
?>
<input type="hidden" name="year" id="year" value="<?php echo $year;?>" />
<input type="hidden" name="term" id="term" value="<?php echo $term;?>" />
<input type="hidden" name="quarter" id="quarter" value="<?php echo $quarter;?>" />
<h3><?php echo $title;?></h3>
<h4><?php echo format_quarter($year, $term, $quarter);?> </h4>
<?php $buttons[] = array("selection"=>"benchmarks", "href"=>site_url("student_benchmark/select/?kStudent=$kStudent&subject=$subject&student_grade=$student_grade&quarter=$quarter&term=$term&year=$year"), "class"=>"button print", "text"=>"Print Benchmarks");?>
<?php echo create_button_bar($buttons);?>

<?php $this->load->view("benchmark/legend");?>
<?php 
foreach($benchmarks as $benchmark){
	if($benchmark->subject != $current_subject){
		echo "<h3>$benchmark->subject</h3>";
		$current_subject = $benchmark->subject;
	}
	if($benchmark->category != $current_category){
		echo "<h4>$benchmark->category</h4>";
		$current_category = $benchmark->category;
	}
	?>

<table>
	<tr id="benchmark_<?php echo $benchmark->kBenchmark;?>_<?php echo $kStudent;?>_<?php echo USER_ID;?>">
	<td class='benchmark-label'><?=$benchmark->benchmark;?></td>
	<td><input type="text" id="g_<?=$benchmark->kBenchmark;?>" name="grade" size="2" class="benchmark-grade benchmark-string" value="<?=$benchmark->grade;?>"/></td>
	<td><input type="text" id="c_<?=$benchmark->kBenchmark;?>" name="comment" class="benchmark-comment benchmark-string" value="<?=get_value($benchmark,"comment","");?>"/></td>
	<td><span style='margin-left:5px' id='save_<?=$benchmark->kBenchmark;?>'></span></td>
</tr>
	
	</table>
	<?
}