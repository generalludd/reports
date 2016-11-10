<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php if(isset($standalone)):?>
<?php $this->load->view("student/navigation");?>
<h3><?php echo $title; ?></h3>
<?php $buttons[] = array("selection"=>"benchmarks", "href"=>site_url("benchmark/edit_student/$kStudent?subject=$subject&student_grade=$student_grade&quarter=$quarter&term=$term&year=$year"), "class"=>"button edit dialog", "text"=>"Edit Benchmarks");?>
<?php echo create_button_bar($buttons);?>
<?php endif; ?>
<? if($legend): ?>
<div class='legend'>
<p><strong><?=$legend->title;?></strong></p>
<div><?=$legend->legend;?></div>
<? endif; ?>
<?
if(count($benchmarks)>0 && $benchmarks != 0){
	print "<div class='section'>";
	print "<table class='chart'>";
	$i=1;
	$currentCategory = "";
	$comments = array();
	$currentQuarter = 0;
	foreach($benchmarks as $benchmark){
		
		if($benchmark->quarter != $currentQuarter){
			$currentQuarter = $benchmark->quarter;
			print "<tr><td colspan=2><strong>Quarter $benchmark->quarter</strong></td></tr>";
		}
		echo "<tr>";
		if($benchmark->category!=$currentCategory){
			$currentCategory=$benchmark->category;
			print "<td><strong>$benchmark->category</strong></td>";
		}else{
			print "<td></td>";
		}
		$mark="";
		
		if(strlen($benchmark->comment)>0){
			$comment = "<sup>$i</sup><span class='footnote'>$benchmark->comment</span>";
			$mark = "<sup>$i</sup>";
			$i++;
			$comments[] = $comment;
		}
		print "<td>$benchmark->benchmark</td><td>$benchmark->grade$mark</td>";

	}
	print "</tr></table>";
	if($comments){
		print implode("<br/>", $comments);
	}
	print "</div>";
}

?>
</div>