<?php defined('BASEPATH') OR exit('No direct script access allowed');

?>
<table class="list">
<thead>
<tr>
<th>Subject</th>
<th>School Year</th>
<th></th>
</tr>
</thead>
<tbody>
<? foreach($preferences as $preference):?>

<tr>
<td><?=$preference->subject;?></td>
<td><?=format_schoolyear($preference->school_year);?></td>
<td><span class="button edit edit-grade-preference" id="edit-grade-preference_<?=$preference->id;?>">Edit</span></td>
</tr>
<? endforeach;?>
</tbody>
</table>