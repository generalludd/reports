<?php if($this->session->flashdata("notice")):?>
<div class="notice"><?php  echo $this->session->flashdata("notice");?></div>
<?php endif?>
<?php if($this->session->flashdata("message")):?>
<div class="message notice"><?php  echo $this->session->flashdata("message");?></div>
<?php endif?>
<?php if($this->session->flashdata("warning")):?>
<div class="warning notice"><?php  echo $this->session->flashdata("warning");?></div>
<?php endif?>
<?php if($this->session->flashdata("log")):?>
<div class="log notice"><?php  echo $this->session->flashdata("log");?></div>
<?php endif?>