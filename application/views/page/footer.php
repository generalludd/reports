<?php if(!isset($print)):
?>
<?php if($this->uri->segment(1) == ""):?>
<div id="ci-version">
<?="CI Version: " . CI_VERSION;?>
</div>
<div id="app-version">
<?="App Version: " . APP_VERSION; ?>
</div>
<?php endif; ?>
<?endif;