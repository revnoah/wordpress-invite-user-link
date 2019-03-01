<?php

/**
 * Define settings fields
 *
 * @return array
 */
function invite_user_link_invite_fields() {
	$default_settings = invite_user_link_settings_defaults();

	$settings = [
		'id' => 'invite_user_link_invite',
		'kabob' => 'invite-user-link-invite',
		'label' => __('Invite User'),
		'settings' => [[
			'id' => 'invite_user_link_number_users',
			'label' => __('Number Of Users To Create'),
			'description' => __('Number of users to use a single link.'),
			'type' => 'number',
			'default' => '1',
			'min' => '1'
		], [
			'id' => 'invite_user_link_expiry_date',
			'label' => __('Expiry Date'),
			'description' => __('Date to cancel user accounts. Leave blank for none.'),
			'type' => 'date',
			'default' => ''
		], [
			'id' => 'invite_user_link_number_users',
			'label' => __('Hours Until Expiry'),
			'description' => __('The number of hours until a user account 
				is cancelled. Set zero for none.'),
			'type' => 'number',
			'default' => '0',
			'min' => '0'
		], [
			'id' => 'invite_user_link_heading_options',
			'label' => __('Account Approval'),
			'description' => __('Defaults defined in the plugin settings'),
			'type' => 'heading'
		]],
		'hidden_vars' => []
	];

	$settings['settings'] = array_merge($settings['settings'], $default_settings);

	return $settings;
}


/**
 * add user menu
 */
add_action('admin_menu', 'invite_user_link_users_menu');

/**
 * Invite user or users
 *
 * @return void
 */
function invite_user_link_users_menu() {
	$settings = invite_user_link_invite_fields();

	add_users_page(
		$settings['label'], 
		$settings['label'], 
		'read', 
		$settings['kabob'], 
		'invite_user_link_invite'
	);
}

/**
 * Invite user
 *
 * @return void
 */
function invite_user_link_invite() {
	//load user settings
	$hidden_vars = [
		'code' => 'code'
	];
	$settings = invite_user_link_invite_fields();
	$action = bloginfo('url') . '/invite-user-link/';

	$output = '<div class="wrap">';
	$output .= '<h1>' . $settings['label'] . '</h1>';

	$output .= invite_user_link_get_form_open(
		$settings['id'], 
		$settings['hidden_vars']
	);
	$output .= invite_user_link_get_table_header();
	foreach ($settings['settings'] as $setting) {
		$setting['saved'] = $setting['default'];
		$output .= invite_user_link_get_formatted_field($setting);
	}
	$output .= invite_user_link_get_table_footer();
	$output .= '<button type="submit">' . __('Invite') . '</button>';
	$output .= invite_user_link_get_form_close();

	$output .= '</div>';

	echo $output;
}
