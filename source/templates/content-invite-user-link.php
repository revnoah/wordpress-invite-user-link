<form action="<?php echo site_url('invite-user-link'); ?>" method="post">
  <input type="hidden" name="pagename" value="invite-user-link" />
  <table class="form-table">
    <tbody>
      <tr>
        <td><label for="slug"><?php _e('Registration Code') ?></label></td>
        <td><input type="text" name="slug" class="form-control" 
          value="<?php echo get_query_var('slug'); ?>" /></td>
      </tr>
      <tr>
        <td><label for="name"><?php _e('Name') ?></label></td>
        <td><input type="text" id="name" name="name" class="form-control"
          value="<?php echo get_query_var('name'); ?>" /></td>
      </tr>
      <tr>
        <td><label for="email"><?php _e('Email') ?></label></td>
        <td><input type="email" id="email" name="email" class="form-control" 
          value="<?php echo get_query_var('email'); ?>" /></td>
      </tr>
      <tr>
        <td><label for="password"><?php _e('Password') ?></label></td>
        <td><input type="password" id="password" name="password" class="form-control" /></td>
      </tr>
      <tr>
        <td><label for="password2"><?php _e('Repeat Password') ?></label></td>
        <td><input type="password" id="password2" name="password2" class="form-control" /></td>
      </tr>
      <tr>
        <td colspan="2">
          <button type="submit" class="btn btn-primary"><?php _e('Sign Up') ?></button>
        </td>
       </tr>
    </tbody>
  </table>
</form>
