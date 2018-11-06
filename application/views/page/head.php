<?php  ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="cache-control" content="no-store" />
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="-1">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php  echo $title;?></title>
<meta http-equiv="refresh" content = "28800; url=<?php echo site_url("auth/logout");?>">
<link type="text/css" rel="stylesheet" media="all" href="<?php  echo base_url("css/main.css?") . date("U");?>" />
<link type="text/css" rel="stylesheet" media="all" href="<?php  echo base_url("css/navigation.css?") . date("U");?>" />

<link type="text/css" rel="stylesheet" media="screen" href="<?php  echo base_url("css/color.css?") . date("U");?>"/>
<link type="text/css" rel="stylesheet" media="screen" href="<?php  echo base_url("css/popup.css?") . date("U");?>" />

<link type="text/css" rel="stylesheet" media="all" href="<?php  echo base_url("css/print.css?") . date("U")?>" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<!-- add any special style sheets for this page only -->
<?php if(isset($styles) && !empty($styles)):?>
  <?php foreach($styles as $style):?>
        <link  rel="stylesheet" href="<?php echo base_url("css/$style");?>?data=<?php echo date("i");?>"></link>
  <?php endforeach;?>
<?php endif; ?>

<!-- jquery scripts -->
<script type="text/javascript">
var base_url = '<?php  echo site_url("index.php") . "/";?>';
var root_url = '<?php  echo base_url();?>';
</script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-migrate-1.3.0.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.8.24/jquery-ui.min.js"></script>

<!-- General Script  -->
<script type="text/javascript" src="<?php  echo base_url("js/general.js?") . date("U");?>"></script>

<!-- Rich Text Editor Script -->
<!-- TODO: add this only when on a rich-text editing page -->
<?php if(isset($rich_text)):?>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script src="<?php echo base_url("js/tinymce.js");?>"></script>
	
<?php endif; ?>

<!-- TARGET-specific scripts -->
<script type="text/javascript" src="<?php  echo base_url("js/teacher.js");?>?data=<?php echo date("i");?>"></script>
<script type="text/javascript" src="<?php  echo base_url("js/student.js");?>?data=<?php echo date("i");?>"></script>
<script type="text/javascript" src="<?php  echo base_url("js/template.js");?>?data=<?php echo date("i");?>"></script>
<script type="text/javascript" src="<?php  echo base_url("js/benchmark.js");?>?data=<?php echo date("i");?>"></script>
<script type="text/javascript" src="<?php  echo base_url("js/attendance.js");?>?data=<?php echo date("i");?>"></script>
<script type="text/javascript" src="<?php  echo base_url("js/support.js");?>?data=<?php echo date("i");?>"></script>
<script type="text/javascript" src="<?php  echo base_url("js/narrative.js");?>?data=<?php echo date("i");?>"></script>
<script type="text/javascript" src="<?php  echo base_url("js/password.js");?>?data=<?php echo date("i");?>"></script>
<script type="text/javascript" src="<?php  echo base_url("js/feedback.js");?>?data=<?php echo date("i");?>"></script>
<script type="text/javascript" src="<?php  echo base_url("js/assignment.js");?>?data=<?php echo date("i");?>"></script>
<script type="text/javascript" src="<?php  echo base_url("js/report.js");?>?data=<?php echo date("i");?>"></script>

<script type="text/javascript" src="<?php  echo base_url("js/legend.js");?>?data=<?php echo date("i");?>"></script>
<?php if(isset($scripts) && !empty($scripts)):?>
<?php foreach($scripts as $script):?>
<script type="text/javascript" src="<?php echo base_url("js/$script");?>?data=<?php echo date("i");?>"></script>
<?php endforeach;?>
<?php endif; ?>