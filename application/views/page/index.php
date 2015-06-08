<?php if(isset($print) && $print == TRUE){
	$print = TRUE;
}else{
	$print = FALSE;
}
if(!isset($body_classes)){
	$body_classes = array("not-front");
}
$body_classes[] = "browser";
$body_classes[] = $this->uri->segment(1);
?>
<!DOCTYPE html">
<html>
<head>
<? $this->load->view('page/head');?>
</head>
<body class="<?=implode(" ",$body_classes);?>">
<div id="page">
<?php if(!$print): ?>
<div id='header'>

<? if($_SERVER['HTTP_HOST'] == "reports"): ?>
<div id="page-title">WARNING: THIS IS THE STAGING SERVER. CHANGES MADE HERE ARE IMAGINARY!</div>
<? else: ?>
<div id='page-title'>Friends School Student Information System</div>
<? endif;?>
<nav id="top-nav">
<div id='utility'><? $this->load->view('page/utility');?></div>
<div id='navigation'>
<?  $this->load->view('page/navigation'); ?>
</div>
</nav>
</div>
<?php endif; ?>

<!-- main -->

<div id="main">
<?php if($this->session->flashdata("notice")):?>
<div class="notice"><?=$this->session->flashdata("notice");?>
</div>
<?php endif?>
<?php if($this->session->flashdata("message")):?>
<div class="message"><?=$this->session->flashdata("message");?></div>
<?php endif?>
<?php if($this->session->flashdata("warning")):?>
<div class="warning"><?=$this->session->flashdata("warning");?></div>
<?php endif?>
<!-- content -->
<div id="content"><?
$this->load->view($target);
?></div>
<!-- end content -->
<div id="sidebar"></div>
<!-- end sidebar --></div>
<div id='search_list'></div>
<div id="footer"><?$this->load->view('page/footer');?>
</div>
</div>
</body>
</html>
