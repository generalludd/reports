<?php #reset_password
if (empty($kTeach) || empty($resetHash)):?>
  <div  class='warning'>Something went wrong. Please try the password reset
    process again.
  </div>
  <?php
  return;
endif;
?>
<div class="login">
  <div class="login-title">Password Reset</div>
  <form id="password-reset" name="password-reset"
        action="<?php echo site_url("auth/complete_reset") ?>" method="post">
    <?php if (!empty($errors)): ?>
      <div id='password_note'
           class='notice error-text hidden'>
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?php echo $error; ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
    <input type="hidden" name="resetHash" id="resetHash"
           value="<?php echo $resetHash; ?>"/>
    <input type="hidden" name="kTeach" id="kTeach"
           value="<?php echo $kTeach; ?>"/>
    <div class="reset-fields login-inputs">
      <p><label for="new_password">New Password: </label><br/>
        <input type="password" id="new_password" name="new_password" required
               value="" placeholder="new password"/></p>
      <p><label for="check_password">Re-enter New Password: </label><br/>
        <input type="password" id="check_password" name="check_password"
               required value="" placeholder="re-enter password"/>
      </p>
    </div>
    <p><input type="submit" name="submit" id="change-password" class="button"
               value="Reset"/></p>

  </form>
</div>
