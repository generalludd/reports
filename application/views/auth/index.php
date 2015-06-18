<?php #authentication index ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Narrative Reporting System Login</title>
<link href="<?=base_url("/css/main.css");?>" type="text/css" rel="stylesheet" media="all" />
<link href="<?=base_url("/css/color.css");?>" type="text/css" rel="stylesheet" media="all" />

<script type="text/javascript" src="<?=base_url("js/jquery.min.js");?>"></script>
<script type="text/javascript" src="<?=base_url("js/password.js");?>"></script>
</head>
<body class="not-logged-in <?=$this->uri->segment(1);?>">
<div id="main">
<?php 
$this->load->view($target);
?>
</div>
</body>
</html>
