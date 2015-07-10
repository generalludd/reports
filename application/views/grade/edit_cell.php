<?php defined('BASEPATH') OR exit('No direct script access allowed');
$kAssign = $grade->kAssignment;
?>
<input type="hidden" id="kStudent" name="kStudent" value="<?=$grade->kStudent;?>"/>
<input type="hidden" id="kAssignment" name="kAssignment" value="<?=$grade->kAssignment;?>"/>
<input type="text" name="points" id="points_<?=$grade->kAssignment;?>_<?=$grade->kStudent;?>" autocomplete='off' size="2" class="assignment-grade" value="<?=$grade->points;?>" /><br/>
&nbsp;
<? $status_key = sprintf("'status_%s_%s'", $grade->kAssignment, $grade->kStudent);?>
<?=form_dropdown("status",$status, $grade->status,"id=$status_key");?><br/>
&nbsp;
<? $footnote_key = sprintf("'footnote_%s_%s'", $grade->kAssignment, $grade->kStudent);?>
<?=form_dropdown("footnote",$footnotes, $grade->footnote,"id=$footnote_key");?>
<div class="button-box"><span class='link save_cell_grade'>Save</span></div>
