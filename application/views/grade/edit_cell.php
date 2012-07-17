<?php defined('BASEPATH') OR exit('No direct script access allowed');
$kAssign = $grade->kAssignment;
?>
<input type="hidden" id="kStudent" name="kStudent" value="<?=$grade->kStudent;?>"/>
<input type="hidden" id="kAssignment" name="kAssignment" value="<?=$grade->kAssignment;?>"/>
<input type="text" id="points" name="points" size="2" class="assignment-grade" value="<?=$grade->points;?>" /><br/>
&nbsp;
<?=form_dropdown("status",$status, $grade->status,"id='status'");?><br/>
&nbsp;
<?=form_dropdown("footnote",$footnotes, $grade->footnote,"id='footnote'");?>
<div class="button-box"><span class='button save_cell_grade'>Save</span></div>
