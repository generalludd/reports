<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<h3><?=$title;?></h3>
<table class="list">
<thead>
<tr>
<th></th>
<th>Grade Start</th>
<th>Grade End</th>
<th>Context</th>
<th>Subjects</th>
</thead>
<tbody>

<?foreach($subjects as $subject):?>
<tr>
	<td><a class="button" href="<?=site_url(sprintf("config/edit_sort?grade_start=%s&grade_end=%s&context=%s",$subject->grade_start,$subject->grade_end,$subject->context));?>">Edit</a>
	<!-- <input type="button" class="button edit edit-subject-sort" id="<?=sprintf("%s_%s_%s",$subject->grade_start,$subject->grade_end,$subject->context);?>" value="Edit"/> -->
	</td>
	<td><?=format_grade($subject->grade_start);?></td>
	<td><?=format_grade($subject->grade_end);?></td>
	<td><?=$subject->context;?></td>
	<td><? $list = explode(",",$subject->subjects);?>
	<ol>
	<? foreach($list as $item):?>
	<li><?=$item;?></li>
	<? endforeach;?>
	</ol>
	</td>
	</tr>
<? endforeach; ?>
</tbody>
</table>