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
	<table>
	<tr>
	<td class='benchmark-label'><?=$benchmark->benchmark;?></td>
	<td><input type="text" id="g_<?=$benchmark->kBenchmark;?>" name="grade" size="2" class="benchmark-grade benchmark-string" value="<?=$benchmark->grade;?>"/></td>
	<td><input type="text" id="c_<?=$benchmark->kBenchmark;?>" name="comment" class="benchmark-comment benchmark-string" value="<?=get_value($benchmark,"comment","");?>"/></td>
	<td><span class='button save_student_benchmark' id='ssb_<?=$benchmark->kBenchmark;?>'>Save</span></td>
	<td><span style='margin-left:5px' id='save_<?=$benchmark->kBenchmark;?>'></span></td>
</tr>
	
	</table>
	<?
}