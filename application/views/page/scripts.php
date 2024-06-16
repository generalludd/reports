<?php if(!empty($scripts)):?>
<?php foreach($scripts as $script):?>
<script type="text/javascript" src="<?php echo base_url("js/$script");?>"></script>
<?php endforeach;?>
<?php endif; ?>
