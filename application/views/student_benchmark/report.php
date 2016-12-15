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

<h3>Benchmark Reports for <?php printf("%s, Grade %s", format_name($student->stuFirst, $student->stuLast, $student->stuNickname), $student_grade);?></h3>
<h4><?php printf("%s, %s", $term, $year); ?></h4>
<?php $buttons[] = array("selection"=>"benchmarks", "href"=>site_url("student_benchmark/select/?kStudent=$kStudent&subject=$subject&student_grade=$student_grade&quarter=$quarter&term=$term&year=$year&edit=1"), "class"=>"button edit", "text"=>"Edit");?>
<?php $buttons[] = array("selection"=>"benchmarks", "href"=>"javascript:print();", "class"=>"button print", "text"=>"Print");?>

<?php echo create_button_bar($buttons, array("class"=>"small"));?>
<div class="benchmark-legend">
	<?php $this->load->view("benchmark/legend");?>
	</div>
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
	<tr class="benchmark-row">
	<td ><?php echo $benchmark->benchmark;?></td>

	<?php foreach($benchmark->quarters as $grade): ?>
		
		<td class="benchmark-grade"><?php echo get_value( $grade['grade'], "grade"); ?>
		<?php if(get_value($grade['grade'], "comment")):?>
		<sup><?php echo $footnote_count;?></sup>
		<?php $footnotes[] = array("count"=>$footnote_count, "comment"=>$grade['grade']->comment);?>
		<?php $footnote_count ++; ?>
		
		<?php endif; ?>
		</td>
		
	<?php endforeach; ?>
	</tr>
<?php endforeach;?>
</tbody>
<?php if(!empty($footnotes)):?>
<tfoot>
<?php foreach($footnotes as $footnote):?>

<tr class="benchmark-footnotes">
<td>
<sup><?php echo $footnote['count'];?></sup>
<?php echo $footnote['comment'];?>
</td>
</tr>
<?php endforeach;?>

</tfoot>
<?php endif; ?>
</table>