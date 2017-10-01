<?php
$current_subject = "";
$current_category = "";

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
<input type="hidden" name="year" value="<?php echo $year;?>" />
<input type="hidden" name="term" value="<?php echo $term;?>" />
<input type="hidden" name="quarter" value="<?php echo $quarter;?>" />
<table>
	<tr id="benchmark_<?php echo $benchmark->kBenchmark;?>_<?php echo $kStudent;?>_<?php echo $kTeach;?>">
	<td class='benchmark-label'><?php  echo $benchmark->benchmark;?></td>
	<td><input type="text" id="g_<?php  echo $benchmark->kBenchmark;?>" name="grade" size="2" class="benchmark-grade benchmark-string" value="<?php  echo $benchmark->grade;?>"/></td>
	<td><input type="text" id="c_<?php  echo $benchmark->kBenchmark;?>" name="comment" class="benchmark-comment benchmark-string" value="<?php  echo get_value($benchmark,"comment","");?>"/></td>
	<td><span class='button save_student_benchmark' id='ssb_<?php  echo $benchmark->kBenchmark;?>'>Save</span></td>
	<td><span style='margin-left:5px' id='save_<?php  echo $benchmark->kBenchmark;?>'></span></td>
</tr>
	
	</table>
	<?php 
}