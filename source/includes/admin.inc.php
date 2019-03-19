<?php

/**
 * Define settings fields
 *
 * @return array
 */
function invite_user_link_settings_defaults() {
	$settings = [
		[
			'id' => 'invite_user_link_require_email_address',
			'label' => __('Require Email Address'),
			'description' => __('Require that a user provide a valid email address'),
			'type' => 'boolean',
			'default' => true
		], [
			'id' => 'invite_user_link_require_name',
			'label' => __('Require Name'),
			'description' => __('Require that a user provide a name'),
			'type' => 'boolean',
			'default' => false
		], [
			'id' => 'invite_user_link_require_password',
			'label' => __('Require Email Address'),
			'description' => __('Require that a user set a password'),
			'type' => 'boolean',
			'default' => true
		], [
			'id' => 'invite_user_link_display_code',
			'label' => __('Display Registration Code'),
			'description' => __('Registration code will still be passed through url'),
			'type' => 'boolean',
			'default' => false
		], [
			'id' => 'invite_user_link_require_approval',
			'label' => __('Require Approval'),
			'description' => 
				__('Completed user accounts need to be approved before they can be used.'),
			'type' => 'boolean',
			'default' => false
		]
	];

	return $settings;
}

/**
 * Get settings along with saved value
 *
 * @return array
 */
function invite_user_link_settings_saved(): array {
	$settings_saved = [];
	$settings_defaults = invite_user_link_settings_defaults();

	foreach ($settings_defaults as $setting) {
		$settings_saved[$setting['id']] = get_option($setting['id'], $setting['default']);
	}

	return $settings_saved;
}

/**
 * Define settings fields
 *
 * @return array
 */
function invite_user_link_settings_fields(): array {
	$default_settings = invite_user_link_settings_defaults();

	$settings = [
		'id' => 'invite_user_link',
		'kabob' => 'invite-user-link',
		'label' => __('Invite User Link Defaults'),
		'settings' => $default_settings
	];

	return $settings;
}

/**
 * action admin_menu
 */
add_action('admin_menu', 'invite_user_link_create_menu');

/**
 * Create admin menu item
 *
 * @return void
 */
function invite_user_link_create_menu() {
	$settings = invite_user_link_settings_fields();

	add_submenu_page(
		'options-general.php',
		$settings['label'],
		$settings['label'],
		'administrator',
		__FILE__,
		$settings['id'] . '_admin',
		plugins_url('/images/icon.png', __FILE__)
	);
}

/**
 * action admin_init
 */
add_action('admin_init', 'invite_user_link_settings');

/**
 * Register custom settings
 *
 * @return void
 */
function invite_user_link_settings() {
	$settings = invite_user_link_settings_fields();

	//register settings
	foreach ($settings['settings'] as $setting) {
		register_setting($settings['kabob'] . '-settings-group', $setting['id']);
	}
}

/**
 * Admin settings
 *
 * @return void
 */
function invite_user_link_admin() {
	//load user settings
	$settings = invite_user_link_settings_fields();
	?>
	<div class="wrap">
	<h1><?php echo $settings['label']; ?></h1>

	<form method="post" action="options.php">
		<?php settings_fields($settings['kabob'] . '-settings-group'); ?>
		<?php do_settings_sections($settings['kabob'] . '-settings-group'); ?>
		<?php
			echo invite_user_link_get_table_header();

			foreach ($settings['settings'] as $setting) {
				$setting['saved'] = get_option($setting['id'], $setting['default']);
				echo invite_user_link_get_formatted_field($setting);
			}

			echo invite_user_link_get_table_footer();
			?>

		<?php submit_button(); ?>
	</form>

</div>
<?php
}

/**
 * Display the invitations
 *
 * @param array $users Invited users
 * @return string
 */
function invite_user_link_invited($users) {
	$output = '<table class="table">
		<thead>
		<tr>
		<th scope="col"><input type="checkbox" class="colcheckbox" /></th>
		<th scope="col">Name</th>
		<th scope="col">Login</th>
		<th scope="col">Status</th>
		<th scope="col">Action</th>
		</tr>
		</thead>
		<tbody>';

	foreach($users as $user) {
		$output .= '<tr class="checkboxrow">';
		$output .= '  <th scope="row"><input type="checkbox" name="user[]" value="' . 
			$user->ID . '" /></th>';
		$output .= '  <td>' . $user->display_name . '</td>';
		$output .= '  <td>' . $user->user_login . '</td>';
		$output .= '  <td>' . ucwords($user->status) . '</td>';
		$output .= '  <td><a href="' . 
			site_url('wp-admin/user-edit.php?user_id=2&wp_http_referer=' . 
			home_url($wp->request)) . '" class="btn btn-default btn-sm">Edit</a>';
		$output .= '</tr>';
	}
	$output .= '</tbody>
	</table>';

	return $output;
}
