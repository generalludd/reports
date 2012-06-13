<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>
<form id="log_search" name="log_search" action="<?=site_url("auth/show_log");?>" method="get">
<p>
<label for="username">Users: </label>
<?=form_dropdown("username",$users,NULL,"id='username'");?>
</p>
<p>
<label for="action">Action: </label><br/>
<input type="radio" name="action"  value="login" />Login<br/>
<input type="radio" name="action"  value="logout"/>Logout
</p>
<p>
<input type="submit" class="button" value="search"/>
</p>

</form>


