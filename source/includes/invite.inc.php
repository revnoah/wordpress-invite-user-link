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
			'description' => __('Number of users to use a single signup link.'),
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
function invite_user_link_users_menu(): void {
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
function invite_user_link_invite(): void {
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
		$setting['saved'] = isset($setting['default']) ? $setting['default'] : null;
		$output .= invite_user_link_get_formatted_field($setting);
	}
	$output .= invite_user_link_get_table_footer();
	$output .= '<button type="submit">' . __('Invite') . '</button>';
	$output .= invite_user_link_get_form_close();

	$output .= '</div>';

	echo $output;
}

/**
 * Finish signup with variables passed through, verifying field input
 *
 * @param string $slug
 * @param array $fields
 * @return boolean
 */
function invite_user_link_finish_signup(string $slug, array $fields): ?array {
	//get user and invite related to the slug
	global $wpdb;

	//load settings
	$settings = invite_user_link_settings_saved();

	//table setup
	$table_invitations = $wpdb->prefix . 'invite_user_links';
	$table_invitation_users = $wpdb->prefix . 'invite_user_link_users';
	$query = $wpdb->prepare("SELECT * FROM $table_invitations WHERE slug = %s", $slug);
	$invitations = $wpdb->get_results($query);

	//verify invitations
	$verified = invite_user_link_verify_invitation($invitations);
	if (!$verified) {
		$response = [['message' => __('Not verified'), 'field' => 'slug']];

		return $response;
	}

	//validate the field data
	$validation_errors = invite_user_link_validate_signup($fields);
	if (count($validation_errors) > 0) {
		return $validation_errors;
	}

	//user id
	$user = get_userdata($invitations[0]['user_id']);
	$fields['ID'] = $invitations[0]['user_id'];

	//check to see if user can update or if approval is needed
	if ($settings['invite_user_link_require_approval']) {
		$response = [['message' => __('Thank you. We will review your signup info soon. Once accepted, you can log in and use the site.'), 'field' => 'slug']];

		return $response;
	} else {
		//update user and return success message
		$user_id = invite_user_link_update_user($fields);

		if (is_wp_error($user_id)) {
			$response = [['message' => __('There was a problem updating the user'), 'field' => 'slug']];

			return $response;
		} else {
			return null;
		}
	}
}

/**
 * Validate input fields based on plugin settings and basic requirements
 *
 * @param array $fields The fields to be validated
 * @return array An array containing the errors with field name and message
 */
function invite_user_link_validate_signup(array $fields): array {
	//load settings
	$settings = invite_user_link_settings_saved();
	$min_length = 4;
	$errors = [];

	//handle nonce
	if (in_array('_wpnonce', array_keys($fields)) && in_array('slug', array_keys($fields))) {
		wp_verify_nonce($fields['_wpnonce'], 'content-invite-user_'.$fields['slug']);
	}

	//validate fields and get errors
	$errors[] = invite_user_link_validate_email($fields['email'], $settings);
	$errors[] = invite_user_link_validate_name($fields['name'], $settings, $min_length);
	$errors[] = invite_user_link_validate_password($fields['password'], $fields['password2'], $settings, $min_length);
	$errors[] = invite_user_link_validate_slug($fields['slug']);

	return $errors;
}

/**
 * Verify invitation
 *
 * @param array $invitation invitation array
 * @return boolean
 */
function invite_user_link_verify_invitation(array $invitation): bool {
	//load settings
	$settings = invite_user_link_settings_saved();

	//user can not be logged in
	if (is_user_logged_in()) {
		return false;
	}

	//check invitation expiry time
	if ($invitation->expires) {
		$expiry_date = strtotime($invitation->expires);
		if ($expiry_date > time()) {
			return false;
		}
	}

	return true;
}

function invite_user_link_add_invitation(array $invitation): bool {
	global $wpdb;
	
	$table_invitations = $wpdb->prefix . 'invite_user_links';

	//handle slug if passed in
	if (isset($invitation['slug']) && $invitation['slug'] != '') {
		$slug = $invitation['slug'];
	} else {
		$slug = wp_generate_password();
	}

	//format data for insert
	$format = ['%s', '%d', '%d', '%d', '%d'];
	$data = [
		'slug' => $slug,
		'max_invites' => $invitation['invite_user_link_number_users'],
		'require_email_address' => boolval($invitation['invite_user_link_require_email_address']),
		'require_email_verification' => boolval($invitation['invite_user_link_require_email_verification']),
		'require_approval' => boolval($invitation['invite_user_link_require_email_address'])
	];

	//create invitation in table
	$wpdb->insert($table_invitations, $data, $format);

	return false;
}

/**
 * Decrement max_invites
 *
 * @param array $invitation invitation array
 * @return boolean
 */
function invite_user_link_decrement_invitation(array $invitation): bool {
	global $wpdb;

	//check max invites
	if ($invitation->max_invites > 1) {
		$table_invitations = $wpdb->prefix . 'invite_user_links';

		$wpdb->query($wpdb->prepare(
			"UPDATE $table_invitations SET max_invites = max_invites - 1 WHERE ID = %d", 
			$invitation->ID
		));

		return true;
	}

	return false;
}
