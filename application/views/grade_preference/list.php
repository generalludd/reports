<?php defined('BASEPATH') OR exit('No direct script access allowed');

?>
<table class="list">
<thead>
<tr>
<th>Subject</th>
<th style="width:15ex">School Year</th>
<th>Pass/Fail?</th>
<th></th>
</tr>
</thead>
<tbody>
<? foreach($grade_preferences as $preference):?>

<tr id="grade-preference-row_<?=$preference->id;?>">
<td><?=$preference->subject;?></td>
<td><?=format_schoolyear($preference->school_year);?></td>
<td><? if( $preference->pass_fail == 1):?>
Yes
<? else: ?>
No
<?endif;?>
</td>
<td style="width: 150px;">
<a class="button edit small dialog" href="<?php echo site_url("grade_preference/edit/$preference->id");?>">Edit</a>&nbsp;
<span class="button small delete delete-grade-preference" style="float:none;" id="delete-grade-preference_<?=$preference->id;?>">Delete</span>
</td>
</tr>
<? endforeach;?>
</tbody>
</table>