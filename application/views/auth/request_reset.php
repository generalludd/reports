<?php #login.inc ?>

<div class="login">
<div class="login-title">Password Reset</div>
<form action="<?php  echo site_url("auth/send_reset"); ?>" method="post"
	name="login_form" id="login_form">

<?php
if($errors):
	if(is_array($errors)){
		foreach($errors as $msg){
			$output =  " -$msg<br/>\n";
		}
	}else{
		$output =  "$errors";
	}
	?>
	<div class="error-text">
<?php print $output; ?>
</div>
<?php
endif;
?>

<div class='login-inputs'>
<p><label for="email">Enter Your Email Address to Reset Your Password</label><br />
<input type="email" name="email" id="email" required placeholder="your_email@fsmn.org"
 class="login-text" /></p>
<p><input type="submit" name="submit" class="button" value="Send" /></p>
</div>
</form>
</div>

