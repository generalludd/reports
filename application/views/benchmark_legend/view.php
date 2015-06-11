<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>

<? 	$buttons[] = array("selection" => "benchmark", "text" => "Benchmarks", "class" => "button dialog", "href" => site_url("benchmark/search"));
	$buttons[] = array("selection" => "search", "text" => "Search", "class" => "button dialog", "href" => site_url("benchmark_legend/search"));
	print create_button_bar($buttons, array("id" =>"legend-buttons"));
	
?>
<ul>
<li>
Teacher: <?=$teacher; ?>
</li>
<li>
School Year: <?=$legend->term;?>, <?=format_schoolyear($legend->year);?>
</li>
<li>
Subject: <?=$legend->subject;?>
</li>
<li>
Grade Range: <?=format_grade_range($legend->gradeStart, $legend->gradeEnd);?>
</li>
</ul>
<p><strong><?=$legend->title;?></strong></p>
<div><?=$legend->legend;?></div>