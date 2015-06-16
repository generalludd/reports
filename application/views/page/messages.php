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