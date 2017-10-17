<?php #support/list
$current_year = get_current_year();
if($this->session->userdata("dbRole")<3){
	$current_year = get_current_year();
}
?>
<?php 
$this->load->view("student/navigation", $kStudent);?>


<?php if( $has_current == $current_year): ?>
	

<p><?php  echo $student_name?> already has a support entry for <?php echo format_schoolyear($current_year);?>.
<?php if($this->session->userdata("dbRole") == 1): ?>
<?php $next_year = $current_year + 1;?>
<a class="button new small" href="<?php  echo site_url("support/create/$kStudent/$next_year");?>">Add for Next Year</a>
<?php endif;?>
</p>
<?php elseif(!$support || $has_current != $current_year): ?>
<fieldset class="support-view">
<legend><?php  echo format_schoolyear(get_current_year());?></legend>
<p><?php  echo $student_name;?> does not have any learning support entries for <?php  echo format_schoolyear($current_year);?>.
<?php if($this->session->userdata("dbRole") < 3): ?>
<a class="button new small" href="<?php  echo site_url("support/create/$kStudent");?>">Add New</a>
<?php endif;?>
</p>
</fieldset>
<?php endif;

$data["print"] = FALSE;
foreach($support as $entry){
	$data["entry"] = $entry;
	$this->load->view("support/view", $data);
}
