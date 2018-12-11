<?php defined('BASEPATH') OR exit('No direct script access allowed');
$kAssign = $grade->kAssignment;
?>
<input type="hidden" id="kStudent" name="kStudent" value="<?php echo $grade->kStudent; ?>"/>
<input type="hidden" id="kAssignment" name="kAssignment" value="<?php echo $grade->kAssignment; ?>"/>
<input type="text" name="points" id="points_<?php echo $grade->kAssignment; ?>_<?php echo $grade->kStudent; ?>"
       autocomplete='off' size="2" class="assignment-grade" value="<?php echo $grade->points; ?>"/><br/>
&nbsp;
<?php $status_key = sprintf("'status_%s_%s'", $grade->kAssignment, $grade->kStudent); ?>
<?php echo form_dropdown("status", $status, $grade->status, "data-id='$grade->kAssignment' data-student='$grade->kStudent' id=$status_key"); ?>
<br/>
&nbsp;
<?php $footnote_key = sprintf("'footnote_%s_%s'", $grade->kAssignment, $grade->kStudent); ?>
<?php echo form_dropdown("footnote", $footnotes, $grade->footnote, "data-id='$grade->kAssignment' data-student='$grade->kStudent' id=$footnote_key"); ?>
<div class="button-box"><span class='link save_cell_grade'>Save</span></div>
