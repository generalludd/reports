<?php defined('BASEPATH') OR exit('No direct script access allowed');

if($emails):
foreach($emails as $email):?>
	
<p><?=$email->smtp_user;?> <span class="button edit email_edit" id="ee_<?=$email->kEmail;?>">Edit</span></p>
	
<? endforeach;
	else:?>
	
<p>There were no emails entered in the system. 

<?
endif;

?>
<p>
<a href="<?=site_url("email/create");?>" class="button new email_create">New Email</a>
</p>