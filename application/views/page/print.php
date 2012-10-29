<?php $print = TRUE;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<? $this->load->view('page/head');?>
</head>
<body class='print'>
<!-- main -->
<div id="main"><!-- content -->
<div id="content"><? 
$this->load->view($target);
?></div>
<!-- end content -->
<div id="sidebar">
</div>
<!-- end sidebar --> 
</div>

<div id="footer">
<?// $this->load->view('/template/footer');?>
</div>
</body>
</html>