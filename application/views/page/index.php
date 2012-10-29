<?php if(isset($print) && $print == TRUE){
	$print = TRUE;
}else{
	$print = FALSE;
}
$body_class = $this->uri->segment(1);
if($this->uri->segment(1) == ""){
	$body_class = "front";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<? $this->load->view('page/head');?>
</head>
<body class="browser <?=$body_class;?>">
<div id="page">
<?php if(!$print): ?>
<div id='header'>

<? if($_SERVER['HTTP_HOST'] == "test.server.fsmn"): ?>
<div id="page-title" class="alert">WARNING: THIS IS THE STAGING SERVER. CHANGES MADE HERE ARE IMAGINARY!</div>
<? else: ?>
<div id='page-title'>Friends School Student Information System</div>
<? endif;?>
<div id='utility'><? $this->load->view('page/utility');?></div>
<div id='navigation'>
<?  $this->load->view('page/navigation'); ?>
</div>
</div>
<?php endif; ?>
<div id="alert" class="message"><?=$this->session->userdata("notice");
?></div>
<?=$this->session->set_userdata("notice",NULL);?>

<!-- main -->

<div id="main"><!-- content -->
<div id="content"><? 
$this->load->view($target);
?></div>
<!-- end content -->
<div id="sidebar"></div>
<!-- end sidebar --></div>
<div id='search_list'></div>
<div id="footer"><?$this->load->view('page/footer');?></div>
</div>
</body>
</html>
