<?php #special_need_view.inc
$current_year = get_current_year();
if($this->session->userdata("dbRole")<3){
	$current_year = get_current_year()+1;
}
?>
<h3><?php echo $title; ?></h3>
<?php 
$this->load->view("student/navigation", $kStudent);
if(!$support || get_value($support[0],"year",FALSE) != format_schoolyear($current_year) ): ?>
<fieldset class="support-view">
<legend>No Support Entries for <?php echo format_schoolyear($current_year);?></legend>
<p><?=$student_name?> does not have any special support entries for <?php echo format_schoolyear($current_year);?>.
<? if($this->session->userdata("dbRole") == 1): ?>
<a class="button new" href="<?=site_url("support/create/$kStudent");?>">Add New</a>
<? endif;?>
</p>
	</fieldset>
<? elseif(!$has_current): ?>
<fieldset class="support-view">
<legend><?=format_schoolyear(get_current_year());?></legend>
<p><img src="/css/images/notice.png" class="icon"/><?=$student?> does not have any learning support entries for <?=format_schoolyear($current_year);?>. 
<? if($this->session->userdata("dbRole") == 1): ?>
<a class="button new" href="<?=site_url("support/create/$kStudent");?>">Add New</a>
<? endif;?>
</p>
</fieldset>
<? endif;

$data["print"] = FALSE;
foreach($support as $entry){
	$data["entry"] = $entry;
	$this->load->view("support/view", $data);
}
