<form action="<?php esc_url( admin_url('index.php') ); ?>" method="post">
  <div class="form-group">
    <label for="slug"><?php __('Registration Code') ?></label>
    <input type="text" name="slug" class="form-control" 
      value="<?php echo get_query_var('slug'); ?>" />
  </div>
  <div class="form-group">
    <label for="name"><?php __('Name') ?></label>
    <input type="text" name="name" class="form-control"
      value="<?php echo get_query_var('name'); ?>" />
  </div>
  <div class="form-group">
    <label for="name"><?php __('Email') ?></label>
    <input type="email" name="email" class="form-control" 
      value="<?php echo get_query_var('email'); ?>" />
  </div>
  <div class="form-group">
    <label for="name"><?php __('Password') ?></label>
    <input type="password" name="password" class="form-control" />
  </div>
  <div class="form-group">
    <label for="name"><?php __('Repeat Password') ?></label>
    <input type="password" name="password2" class="form-control" />
  </div>
  <div class="form-group">
    <button type="submit" class="btn btn-primary"><?php __('Sign Up') ?></button>
  </div>
</form>
