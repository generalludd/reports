<?php #special_need_view.inc
print "<h3>Learning Support for $student_name</h3>";
$this->load->view("student/navigation", $kStudent);
if(!$support || get_value($support[0],"year",FALSE) != get_current_year()) { ?>
<fieldset class="support-view">
<legend>No Support Entries</legend>
<p><?=$student_name?> does not have any special support entries for this year.
<? if($this->session->userdata("dbRole") == 1): ?>
<a class="button new" href="<?=site_url("support/create/$kStudent");?>">Add New</a>
<? endif;?>
</p>
	</fieldset>
<? }elseif(!$has_current){ ?>
<fieldset class="support-view">
<legend><?=format_schoolyear(get_current_year());?></legend>
<p><img src="/css/images/notice.png" class="icon"/><?=$student?> does not have any learning support entries for <?=format_schoolyear(get_current_year());?>. 
<? if($this->session->userdata("dbRole") == 1): ?>
<a class="button new" href="<?=site_url("support/create/$kStudent");?>">Add New</a>
<? endif;?>
</p>
</fieldset>
<? }

$data["print"] = FALSE;
foreach($support as $entry){
	$data["entry"] = $entry;
	$this->load->view("support/view", $data);
}
