<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<h3><?php  echo $title;?></h3>
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

<?php foreach($subjects as $subject):?>
<tr>
	<td><a class="button" href="<?php  echo site_url(sprintf("config/edit_sort?grade_start=%s&grade_end=%s&context=%s",$subject->grade_start,$subject->grade_end,$subject->context));?>">Edit</a>
	<!-- <input type="button" class="button edit edit-subject-sort" id="<?php  echo sprintf("%s_%s_%s",$subject->grade_start,$subject->grade_end,$subject->context);?>" value="Edit"/> -->
	</td>
	<td><?php  echo format_grade($subject->grade_start);?></td>
	<td><?php  echo format_grade($subject->grade_end);?></td>
	<td><?php  echo $subject->context;?></td>
	<td><?php $list = explode(",",$subject->subjects);?>
	<ol>
	<?php foreach($list as $item):?>
	<li><?php  echo $item;?></li>
	<?php endforeach;?>
	</ol>
	</td>
	</tr>
<?php endforeach; ?>
</tbody>
</table>