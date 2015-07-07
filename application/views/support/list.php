<?php #special_need_view.inc
$current_year = get_current_year();
if($this->session->userdata("dbRole")<3){
	$current_year = get_current_year();
}
?>
<h3><?php echo $title; ?></h3>
<?php 
$this->load->view("student/navigation", $kStudent);
if( $has_current == $current_year): ?>
<fieldset class="support-view">
<legend>Support Entries for <?php echo format_schoolyear($current_year);?></legend>
<p><?=$student_name?> alread yas a support entry for <?php echo format_schoolyear($current_year);?>.
<? if($this->session->userdata("dbRole") == 1): ?>
<?php $next_year = $current_year + 1;?>
<a class="button new small" href="<?=site_url("support/create/$kStudent/$next_year");?>">Add for Next Year</a>
<? endif;?>
</p>
	</fieldset>
<? elseif(!$support || $has_current != $current_year): ?>
<fieldset class="support-view">
<legend><?=format_schoolyear(get_current_year());?></legend>
<p><?=$student_name;?> does not have any learning support entries for <?=format_schoolyear($current_year);?>. 
<? if($this->session->userdata("dbRole") == 1): ?>
<a class="button new small" href="<?=site_url("support/create/$kStudent");?>">Add New</a>
<? endif;?>
</p>
</fieldset>
<? endif;

$data["print"] = FALSE;
foreach($support as $entry){
	$data["entry"] = $entry;
	$this->load->view("support/view", $data);
}
