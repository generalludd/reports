<?php defined('BASEPATH') OR exit('No direct script access allowed');


?>

<form id="email_edit" name="email_edit" method="post" action="<?=site_url("email/$action");?>">
<p>
<label for="default">Default Email: </label>
<?=form_checkbox("default",get_value($email,"default",TRUE), TRUE);?>
</p>
<p>
<label for="mailpath">Mail Path: </label>
<input type="text" name="mailpath" id="mailpath" class="required" value="<?=get_value($email,"mailpath","/usr/sbin/sendmail");?>"/>
</p>
<p>
<label for="protocol">Protocol: </label>
<input type="text" name="protocol" id="protocol" class="required" value="<?=get_value($email,"protocol","smtp");?>"/>
</p>
<p>
<label for="smtp_host">SMTP Host: </label>
<input type="text" name="smtp_host" id="smtp_host" class="required" value="<?=get_value($email,"smtp_host","ssl://smtp.gmail.com");?>"/>
</p>
<p>
<label for="smtp_auth">SMTP Authorization: </label>
<?=form_checkbox("smtp_auth",get_value($email,"smtp_auth",TRUE), TRUE);?>
</p>
<p>
<label for="smtp_port">SMTP Port: </label>
<input type="text" name="smtp_port" id="smtp_port" value="<?=get_value($email,"smtp_port",465);?>"/>
</p>
<p>
<label for="smtp_user">SMTP User: </label>
<input type="text" name="smtp_user" id="smtp_user" value="<?=get_value($email, "smtp_user");?>"/>
</p>
<p>
<label for="smtp_pass">SMTP Password: </label>
<input type="text" name="smtp_pass" id="smtp_pass" value="<?=get_value($email, "smtp_pass");?>"/>
</p>
<p>
<label for="newline">New Line Code: </label>
<input type="text" name="newline" id="newline" value="<?=get_value($email,"newline", "\r\n");?>"/>
</p>
<p>
<label for="charset">Character Set Encoding: </label>
<input type="text" name="charset" id="charset" value="<?=get_value($email,"charset","utf-8");?>"/>
</p>
<p>
<input type="submit" value="Save" class="button"/>
</p>
</form>