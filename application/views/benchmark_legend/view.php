<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php 	$buttons[] = array("selection" => "benchmark", "text" => "Benchmarks", "class" => "button dialog", "href" => site_url("benchmark/search"));
	$buttons[] = array("selection" => "search", "text" => "Search", "class" => "button dialog", "href" => site_url("benchmark_legend/search"));
	print create_button_bar($buttons, array("id" =>"legend-buttons"));
	
?>
<ul>
<li>
School Year: <?php  echo $legend->term;?>, <?php  echo format_schoolyear($legend->year);?>
</li>
<li>
Subject: <?php  echo $legend->subject;?>
</li>
<li>
Grade Range: <?php  echo format_grade_range($legend->gradeStart, $legend->gradeEnd);?>
</li>
</ul>
<p><strong><?php  echo $legend->title;?></strong></p>
<div><?php  echo $legend->legend;?></div>