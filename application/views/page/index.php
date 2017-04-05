<?php

if (isset ( $print ) && $print == TRUE) {
	$print = TRUE;
} else {
	$print = FALSE;
}
if (! isset ( $body_classes )) {
	$body_classes = array (
			"not-front" 
	);
}
$body_classes [] = "browser";
$body_classes [] = $this->uri->segment ( 1 );
?>
<!DOCTYPE html>
<html>
<head>
<? $this->load->view('page/head');?>
</head>
<body class="<?=implode(" ",$body_classes);?>">
	<div id="page">
<?php if(!$print): ?>
<div id='header'>

<? if($_SERVER['HTTP_HOST'] == "reports"): ?>
<div id="site-name">
				WARNING: THIS IS THE STAGING SERVER. CHANGES MADE HERE ARE IMAGINARY!</div>
<? else: ?>
<div id='site-name'>Friends School Student Information System</div>
<? endif;?>
<div id="top-nav">
				<div id='utility'><? $this->load->view('page/utility');?></div>

			</div>
		</div>
<?php endif; ?>
<?php $this->load->view("page/messages");?>
<div id="main">
<nav id='navigation'>
<?php  $this->load->view('page/navigation'); ?>
</nav>
			<!-- content -->
			<div id="content">
				<h1 id="page-title"><?php echo $title;?></h1>
<?php $this->load->view ( $target ); ?>
</div>
			<!-- end content -->
			<div id="sidebar"></div>
			<!-- end sidebar -->
		</div>
		<div id='search_list'></div>
		<div id="footer"><?php $this->load->view('page/footer');?>
</div>
	</div>
</body>
</html>
