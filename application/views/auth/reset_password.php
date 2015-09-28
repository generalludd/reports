<?php #reset_password
$output = "";
if($errors):
	if(is_array($errors)){
		foreach($errors as $msg){
			$output =  " -$msg<br/>\n";
		}
	}else{
		$output =  "$errors";
	}
endif;
?>
<div class="login resetter">
<div class="login-title">Password Reset</div>
<form id="password-resetter" name="password-resetter" action="<?=site_url("auth/complete_reset")?>" method="post" >

<div id='password_note' class='notice error-text' style="display:none"><?=$output;?></div>
<input type="hidden" name="resetHash" id="resetHash" value="<?=$resetHash;?>"/>
<input type="hidden" name="kTeach" id="kTeach" value="<?=$kTeach;?>"/>
<div class="reset-fields login-inputs">
<p><label for="new_password">New Password: </label><br/>
<input type="password" id="new_password" name="new_password" required value="" placeholder="new password"/></p>
<p><label for="check_password">Re-enter New Password: </label><br/>
<input type="password" id="check_password" name="check_password" required value="" placeholder="re-enter password"/>
</p>
</div>
<p><input type="submit" name="submit" id="change-password" class="button" style="display:none" value="Reset" /></p>

</form>
</div>
