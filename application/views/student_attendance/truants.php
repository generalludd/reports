<?php
?>
<h2><?php echo $title;?></h2>
<table class="list table table-responsive">
<thead>
<tr>
<th>Student</th>
<th>Absences from <?php printf("%s to %s", $start_date, $end_date);?></th>
</tr>
</thead>
<tbody>
<?php foreach($truants as $truant):?>
<tr>
<td><?php echo format_name($truant->stuNickname,$truant->stuLast);?></td>
<td><?php echo $truant->total;?></td>
</tr>

<?php endforeach;?>
</tbody>
</table>