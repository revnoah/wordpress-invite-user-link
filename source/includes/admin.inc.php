<?php

/**
 * Define settings fields
 *
 * @return array
 */
function invite_user_link_settings_fields() {
	$settings = [
		'id' => 'invite_user_link',
		'kabob' => 'invite-user-link',
		'label' => __('Invite User Link'),
		'settings' => [[
			'id' => 'invite_user_link_invite_path',
			'label' => __('Path'),
			'type' => 'string',
			'default' => ''
		], [
			'id' => 'invite_user_link_require_email_address',
			'label' => __('Require Email Address'),
			'type' => 'boolean',
			'default' => true
		], [
			'id' => 'invite_user_link_require_email_verification',
			'label' => __('Require Email Verification'),
			'type' => 'boolean',
			'default' => false
		], [
			'id' => 'invite_user_link_require_approval',
			'label' => __('Require Approval'),
			'type' => 'boolean',
			'default' => false
		]]
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
		register_setting($settings['kabob'] . '-settings-group', $setting->id);
	}
}

/**
 * Admin settings
 *
 * @return void
 */
function invite_user_link_admin() {
	//load admin plugin
	//$plugin_path = plugins_url('js/admin.js', __FILE__);
	//wp_enqueue_script('admin-js', $plugin_path, array('jquery'));

	//load user settings
	$settings = invite_user_link_settings_fields();
	
	?>
	<div class="wrap">
	<h1><?php echo $settings['label']; ?></h1>

	<form method="post" action="options.php">
		<?php settings_fields($settings['kabob'] . '-settings-group'); ?>
		<?php do_settings_sections($settings['kabob'] . '-settings-group'); ?>

		<table class="form-table">
			<?php
			foreach ($settings['settings'] as $setting) {
				$setting['saved'] = get_option($setting['id'], $setting['default']);
				?>
				<tr valign="top">
					<th scope="row"><?php echo invite_user_link_get_label($setting); ?></th>
					<td>
					<?php echo invite_user_link_get_field($setting); ?>
					</td>
				</tr>
			<?php
			}
			?>
		</table>

		<?php submit_button(); ?>
	</form>

</div>
<?php
}

/**
 * Get field
 *
 * @param array $setting Array element from settings
 * @return string
 */
function invite_user_link_get_field($setting) {
	$output = '';

	if ($setting['type'] === 'boolean') {
		$output .= '<input type="checkbox" id="' . $setting['id'] 
			. '" name="' . $setting['id'] . '" ' 
			. ($setting['saved'] === 'on' ? 'checked' : '') 
			. '/>';
		//$output .= '<label for="' . $setting['id'] . '">'
		// . $setting['label'] . '</label>';
	} else {
		//$output .= '<label for="' . $setting['id'] . '">' 
		//. $setting['label'] . '</label>';
		$output .= '<input type="text" id="' . $setting['id'] 
			. '" name="' . $setting['id'] 
			. '" class="form-control" value="' . $setting['saved'] 
			. '" />';
	}
	if ($setting['description'] != '') {
		$output .= '<br /><small>' . $setting['description'] . '</small>';
	}

	return $output;
}

/**
 * Get label
 *
 * @param array $setting Array element from settings
 * @return string
 */
function invite_user_link_get_label($setting) {
	$output = '<label for="' . $setting['id'] . '">' . $setting['label'] . '</label>';

	return $output;
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

/*
function update_album_users($album_id) {
	global $pwdb;
	$user_ids = $_POST['user'];
	$action = $_POST['submit'];

	switch($action) {
		case 'add':
			_add_album_users($user_ids, $album_id);
			break;
		case 'remove':
			_remove_album_users($user_ids, $album_id);
			break;
	}
}

function _add_album_users($user_ids, $album_id) {
	global $wpdb;

	foreach($user_ids as $user_id) {
		$wpdb->insert(
			$wpdb->prefix . 'hhp_album_user',
			[
				'user_id' => $user_id,
				'album_id' => $album_id
			]
		);
	}
}

function _remove_album_users($user_ids, $album_id) {
	global $wpdb;

	foreach($user_ids as $user_id) {
		$wpdb->get_results($wpdb->prepare(
			"DELETE FROM {$wpdb->prefix}hhp_album_user
			WHERE album_id = %d AND user_id = %d", $album_id, $user_id)
		);
	}
}
*/