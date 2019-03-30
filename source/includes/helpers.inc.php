<?php

/**
 * Load CSS template file, if present in template directory
 *
 * @param string $template_name css file to look for, load in theme folder
 * @return void
 */
function invite_user_link_load_css($template_name) {
	$template = locate_template($template_name . '.css', false);
	if ($template) {
		wp_enqueue_style(
			$template_name, 
			get_template_directory_uri() . '/' . $template_name . '.css'
		);
	}
}

/**
 * Get template part from user theme falling back to plugin folder
 *
 * @param string $template_name   Name of content template partial
 * @param string $prefix File prefix of template
 * @return string
 */
function invite_user_link_get_template_part(string $template_slug, string $prefix = 'page'): string {
	$template_name = $prefix . '-' . $template_slug . '.php';

	$new_template = invite_user_link_locate_template([$template_name], true, false);

	return $new_template;
}

/**
 * Locate template that searches within template folder
 *
 * @param array $template_names array of template names 
 * @param boolean $load load the template
 * @param boolean $require_once require once when loading template
 * @return string
 */
function invite_user_link_locate_template(
		array $template_names, 
		bool $load = false, 
		bool $require_once = true 
	):string {
    $located = '';

	//loop through templates
	foreach ((array)$template_names as $template_name) {
		if (!$template_name) {
			continue;
		}

		//default template locations with additional template folders
		$template_locations = [
			STYLESHEETPATH . '/' . $template_name,
			STYLESHEETPATH . '/templates/' . $template_name,
			STYLESHEETPATH . '/template_parts/' . $template_name,
			TEMPLATEPATH . '/' . $template_name,
			TEMPLATEPATH . '/templates/' . $template_name,
			TEMPLATEPATH . '/template_parts/' . $template_name,
			ABSPATH . WPINC . '/theme-compat/' . $template_name,
			PLUGIN_DIR . '/templates/' . $template_name,
			PLUGIN_DIR . '/template_parts/' . $template_name
		];

		//loop through template locations
		foreach(array_unique($template_locations) as $template_location) {
			if (file_exists($template_location)) {
				$located = str_replace(ABSPATH, '', $template_location);
				break;
			}
		}
	}

	//load template
	if ($load && $located != '') {
		load_template($located, $require_once);
    }

    return $located;
}

/**
 * Get request vars from whitelist array of keys
 *
 * @param array $keys Whitelist array of keys
 * @param string $method Method, defaults to POST
 * @return array
 */
function invite_user_link_get_request_vars(array $keys, string $method = 'POST'): array {
	$array = [];

	//loop through keys and get the fields we've whitelisted
	foreach ($keys as $key) {
		if (strtoupper($method) == 'POST' && isset($_POST[$key]) && $_POST[$key] != '') {
			$array[$key] = sanitize_text_field($_POST[$key]);
		} elseif (strtoupper($method) == 'GET' && isset($_GET[$key]) && $_GET[$key] != '') {
			$array[$key] = sanitize_text_field($_GET[$key]);
		}
 	}

	return $array;
}

/**
 * Update user
 *
 * @param array $fields
 * @return boolean
 */
function invite_user_link_update_user(array $fields): bool {
	$user = [];

	//update email address
	if ($fields['email']) {
		$user['user_email'] = $fields['email'];
	}

	//update user login name
	if ($fields['username']) {
		$user['user_login'] = $fields['username'];
	}

	//update names
	if ($fields['name']) {
		$user['display_name'] = $fields['name'];
		if (stristr($fields['name'], ' ')) {
			$field_parts = explode(' ', $fields['name']);
			$user['last_name'] = array_pop($field_parts);
			$user['first_name'] = implode(' ', $field_parts);
		}
	}

	//no fields to update
	if (count($user) == 0) {
		return false;
	}

	//update user with hashed password
	if ($fields['password']) {
		invite_user_link_set_hash_password($fields['password'], $fields['ID']);
	}

	return wp_update_user($fields);
}

/**
 * Set password
 *
 * @param string $hash_password
 * @param integer $user_id
 * @return void
 */
function invite_user_link_set_hash_password(string $hash_password, int $user_id): void {
	global $wpdb;
	
	$wpdb->update(
		$wpdb->users,
		array(
			'user_pass'           => $hash_password,
			'user_activation_key' => '',
		),
		array('ID' => $user_id)
	);
	wp_cache_delete($user_id, 'users');
}
