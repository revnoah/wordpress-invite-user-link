<?php

//form fields are displayed dynamically
global $settings;

?>
<form action="<?php echo site_url('invite-user-link'); ?>" method="post">
  <input type="hidden" name="pagename" value="invite-user-link" />
  <?php
  if ($settings['invite_user_link_require_name']):
  ?>
  <div class="form-group">
    <label for="name"><?php _e('Name') ?></label><br />
    <input type="text" id="name" name="name" class="form-control"
      value="<?php echo get_query_var('name'); ?>" />
    <p class="text-muted"><small><?php _e('Your name as display to other website users'); ?></small></p>  
  </div>
  <?php
  endif;
  if ($settings['invite_user_link_require_email_address']):
  ?>
  <div class="form-group">
    <label for="email"><?php _e('Email') ?></label><br />
    <input type="email" id="email" name="email" class="form-control" 
      value="<?php echo get_query_var('email'); ?>" /><br />
    <p class="text-muted"><small><?php _e('Your email address is used to maintain your password'); ?></small></p>  
  </div>
  <?php
  endif;
  if ($settings['invite_user_link_require_password']):
  ?>
  <div class="form-group">
    <label for="password"><?php _e('Password') ?></label><br />
    <input type="password" id="password" name="password" class="form-control" /><br />
    <label for="password2"><?php _e('Repeat Password') ?></label><br />
    <input type="password" id="password2" name="password2" class="form-control" /><br />
    <p class="text-muted"><small><?php _e('Choose a unique password. You can always reset if you forget.'); ?></small></p>  
  </div>
  <?php
  endif;
  if ($settings['invite_user_link_display_code']):
  ?>
  <div class="form-group">
    <label for="slug"><?php _e('Registration Code') ?></label><br />
    <input type="text" name="slug" class="form-control" 
      value="<?php echo get_query_var('slug'); ?>" /><br />
    <p class="text-muted"><small><?php _e('Choose a unique password. You can always reset if you forget.'); ?></small></p>  
  </div>
  <?php
  else:
    echo '<input type="hidden" name="slug" value="' . get_query_var('slug') . '" />';
  endif;
  ?>
  <div class="form-group">
    <button type="submit" class="btn btn-primary"><?php _e('Continue') ?></button>
  </div>
</form>
