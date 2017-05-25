<?php
/**
 * Report of student benchmarks for every quarter within search parameters
 */
$current_subject = "";
$current_category = "";
$footnote_count = 1;
$footnotes = array();

?>
<?php $this->load->view("student/navigation");?>
<input type="hidden" name="year" id="year" value="<?php echo $year;?>" />
<input type="hidden" name="term" id="term" value="<?php echo $term;?>" />
<input type="hidden" name="quarter" id="quarter" value="<?php echo $quarter;?>" />
<h3>Benchmark Reports for <?php printf("%s, Grade %s", format_name($student->stuFirst, $student->stuLast, $student->stuNickname), $student_grade);?></h3>
<h4><?php printf("%s, %s", $term, $year); ?></h4>
<?php $buttons[] = array("selection"=>"benchmarks", "href"=>site_url("student_benchmark/select/?kStudent=$kStudent&subject=$subject&student_grade=$student_grade&quarter=$quarter&term=$term&year=$year"), "class"=>"button", "text"=>"View Benchmarks");?>
<?php echo create_button_bar($buttons, array("class"=>"small"));?>
<div class="benchmark-legend">
	<?php $this->load->view("benchmark/legend");?>
	</div>
	<p><a href="#" class="benchmark-fill-down" title="This will fill all blank grades with an M, but this is in beta testing, so it may still have bugs">Fill all blank entries with "M" (BETA)</a></p>
<table class="chart list">
<thead>
<tr class="benchmark-header">
<th style="text-align: right; padding-right: 10px;">Quarters
</th>
<?php for($i = 1; $i<= $quarters; $i++):?>
<th class="benchmark-quarters"><?php echo "$i";?></th>
<?php endfor;?>
</tr>
</thead>
<tbody>
<?php foreach($benchmarks as $benchmark):?>
<?php if($benchmark->subject != $current_subject):?>
<tr class="benchmark-header"><td colspan=<?php echo $quarters+1;?>><h3><?php echo $benchmark->subject;?></h3></td></tr>
<?php $current_subject = $benchmark->subject;?>
<?php endif; ?>
<?php if($benchmark->category != $current_category):?>
<tr class="benchmark-header"><td colspan=<?php echo $quarters+1; ?>><?php echo $benchmark->category;?></td></tr>
<?php $current_category = $benchmark->category;?>
<?php endif; ?>
	<tr class="benchmark-row" id="benchmark_<?php echo $benchmark->kBenchmark;?>_<?php echo $kStudent;?>_<?php echo USER_ID;?>">
	<td class="benchmark-label"><?php echo $benchmark->benchmark;?></td>
<?php $q = 1; ?>
	<?php foreach($benchmark->quarters as $grade): ?>
		
		<td class="benchmark-grade">
		<?php if($quarter == $q):?>
			<input type="text" id="g_<?=$benchmark->kBenchmark;?>" name="grade" size="2" class="benchmark-grade benchmark-string" value="<?php echo get_value($grade['grade'],'grade');?>"/>
		<input type="text" id="c_<?=$benchmark->kBenchmark;?>" name="comment" class="benchmark-comment benchmark-string" value="<?=get_value($grade['grade'],"comment","");?>"/>
		
		
		<?php else: ?>
			<?php echo get_value( $grade['grade'], "grade"); ?>
					<?php if(get_value($grade['grade'], "comment")):?>
		<sup><?php echo $footnote_count;?></sup>
		<?php $footnotes[] = array("count"=>$footnote_count, "comment"=>$grade['grade']->comment);?>
		<?php $footnote_count ++; ?>
		
		<?php endif; ?>
		<?php endif; ?>
		


	<?php $q++;?>	
	<?php endforeach; ?>
	<span style='margin-left:5px' id='save_<?=$benchmark->kBenchmark;?>'></span>
			</td>
	</tr>
<?php endforeach;?>
</tbody>
<?php if(!empty($footnotes)):?>
<tfoot>
<?php foreach($footnotes as $footnote):?>

<tr class="benchmark-footnotes">
<th>
<sup><?php echo $footnote['count'];?></sup>
<?php echo $footnote['comment'];?>
</th>
</tr>
<?php endforeach;?>

</tfoot>
<?php endif; ?>
</table>