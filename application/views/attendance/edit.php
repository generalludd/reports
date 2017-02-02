<?php
?>
<h3><?php echo $title; ?></h3>
<form id="attendance-editor" name="attendance-editor" method="post" action="<?php echo base_url("attendance/$action");?>">
<input type="hidden" name="kStudent" value="<?php echo $kStudent;?>"/>
<input type="hidden" name="term" value="<?php echo $term;?>"/>
<input type="hidden" name="year" value="<?php echo $year;?>"/>
<div>
<label for="absent">Absent: </label><input name="absent" type="number" value="<?php echo get_value($attendance,"absent");?>" size="5"/>
&nbsp;
<label for="tardy">Tardy: </label><input name="tardy" type="number" value="<?php echo get_value($attendance,"tardy");?>" size="5"/>
</div>
<?php 
$buttons[] = array("type"=>"pass-through","text"=>"<input type='submit'  value='Update'/>");
echo create_button_bar($buttons);
?>
</form>