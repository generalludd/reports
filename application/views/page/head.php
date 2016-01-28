<?php  ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="cache-control" content="no-store" />
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="-1">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?=$title;?></title>
<meta http-equiv="refresh" content = "14400; url=<?php echo site_url("auth/logout");?>">
<link type="text/css" rel="stylesheet" media="all" href="<?=base_url("css/main.css")?>" />
<link type="text/css" rel="stylesheet" media="screen" href="<?=base_url("css/color.css")?>"/>
<link type="text/css" rel="stylesheet" media="screen" href="<?=base_url("css/popup.css")?>" />
<link type="text/css" rel="stylesheet" media="print" href="<?=base_url("css/print.css")?>" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<!-- jquery scripts -->
<script type="text/javascript">
var base_url = '<?=site_url("index.php") . "/";?>';
var root_url = '<?=base_url();?>';
</script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-migrate-1.3.0.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.8.24/jquery-ui.min.js"></script>

<!-- General Script  -->
<script type="text/javascript" src="<?=base_url("js/general.js");?>"></script>

<!-- Rich Text Editor Script -->
<!-- TODO: add this only when on a rich-text editing page -->
<?php if(isset($rich_text)):?>
<script src="//tinymce.cachefly.net/4.3/tinymce.min.js"></script>

<script>tinymce.init({ selector:'textarea.tinymce',
	menubar: false,
	min_height:400,
	toolbar: 'bold,italic,|,fullpage,|,cut,copy,paste,pastetext,pasteword,cleanup,code,|,undo,redo,|,bullist,numlist',
	invalid_styles: 'color font-size font-family line-height font-weight',
	plugins: 'fullpage,paste,code',
	invalid_elements: 'div,font,a,html,head,body',
	setup: function (editor) {
	        editor.on('change', function () {
	            editor.save();
	        });
	    },
	    browser_spellcheck: true,
	    contextmenu: false

	});

</script> 
	
<?php endif; ?>

<!-- TARGET-specific scripts -->
<script type="text/javascript" src="<?=base_url("js/teacher.js");?>"></script>
<script type="text/javascript" src="<?=base_url("js/student.js");?>"></script>
<script type="text/javascript" src="<?=base_url("js/template.js");?>"></script>
<script type="text/javascript" src="<?=base_url("js/benchmark.js");?>"></script>
<script type="text/javascript" src="<?=base_url("js/attendance.js");?>"></script>
<script type="text/javascript" src="<?=base_url("js/support.js");?>"></script>
<script type="text/javascript" src="<?=base_url("js/narrative.js");?>"></script>
<script type="text/javascript" src="<?=base_url("js/password.js");?>"></script>
<script type="text/javascript" src="<?=base_url("js/feedback.js");?>"></script>
<script type="text/javascript" src="<?=base_url("js/assignment.js");?>"></script>
<script type="text/javascript" src="<?=base_url("js/report.js");?>"></script>

<script type="text/javascript" src="<?=base_url("js/legend.js");?>"></script>