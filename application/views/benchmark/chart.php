<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
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
	foreach($benchmarks as $benchmark){
		echo "<tr>";
		if($benchmark->category!=$currentCategory){
			$currentCategory=$benchmark->category;
			print "<td><b>$benchmark->category</b></td>";
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