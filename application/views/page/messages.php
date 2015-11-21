<?php if($this->session->flashdata("notice")):?>
<div class="notice"><?=$this->session->flashdata("notice");?></div>
<?php endif?>
<?php if($this->session->flashdata("message")):?>
<div class="message notice"><?=$this->session->flashdata("message");?></div>
<?php endif?>
<?php if($this->session->flashdata("warning")):?>
<div class="warning notice "><?=$this->session->flashdata("warning");?></div>
<?php endif?>
<?php if($this->session->flashdata("log")):?>
<div class="log notice"><?=$this->session->flashdata("log");?></div>
<?php endif?>