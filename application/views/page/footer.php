<?php if(!isset($print)):
?>
<?php if($this->uri->segment(1) == ""):?>
<div id="ci-version">
<?php  echo "CI Version: " . CI_VERSION;?>
</div>
<div id="app-version">
<?php  echo "App Version: " . APP_VERSION; ?>
</div>
<?php endif; ?>
<?php endif;