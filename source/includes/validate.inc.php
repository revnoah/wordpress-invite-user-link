<?php

/**
 * Validate email address
 *
 * @param string $email
 * @param array|null $settings
 * @param boolean $unique
 * @return array
 */
function invite_user_link_validate_email(string $email, ?array $settings, bool $unique = true): array {
	$errors = [];

	//load settings if not supplied
	$settings = invite_user_link_get_settings($settings);

	if ($settings['invite_user_link_require_email_address']) {
		//ensure that this is a valid email address
		if(!is_email($email)) {
			$errors[] = [
				'field' => 'email',
				'message' => __('You must provide a valid email address')
			];
		}

		//TODO: query users to see if email is used already

	
	} 

	return $errors;
}

/**
 * Validate two passwords for matching and requirements
 *
 * @param string $password Password
 * @param string $password2 Repeated password
 * @param array|null $settings
 * @return array
 */
function invite_user_link_validate_passwords(string $password, string $password2, ?array $settings, int $min_length = 4): array {
	$errors = [];

	//load settings if not supplied
	$settings = invite_user_link_get_settings($settings);

	if ($settings['invite_user_link_require_password'] && strlen($password) < $min_length) {
		$errors[] = [
			'field' => 'password',
			'message' => __('You must provide a password with at least ' . $min_length . ' characters')
		];
	}	

	if ($settings['invite_user_link_require_password'] && $password != $password2) {
		$errors[] = [
			'field' => 'password2',
			'message' => __('You must type the same password twice')
		];
	}	

	return $errors;
}

/**
 * Validate name
 *
 * @param string $name
 * @param array|null $settings
 * @param integer $min_length
 * @return array
 */
function invite_user_link_validate_name(string $name, ?array $settings, int $min_length = 4, bool $unique = true): array {
	$errors = [];

	//load settings if not supplied
	$settings = invite_user_link_get_settings($settings);

	if ($settings['invite_user_link_require_name'] 
		&& strlen($name) < $min_length) {
		$errors[] = [
			'field' => 'name',
			'message' => __('You must provide a name with at least ' . $min_length . ' characters')
		];
	}	

	//TODO: handle unique names

	return $errors;
}

/**
 * Validate slug
 *
 * @param string $slug
 * @param boolean $available
 * @return array
 */
function invite_user_link_validate_slug(string $slug, bool $available = true): array {
	$errors = [];

	if ($slug == '') {
		$errors[] = [
			'field' => 'slug',
			'message' => __('You need to provide an invitation code')
		];
	}	

	//TODO: query slugs to ensure there are open spots

	return $errors;
}

/**
 * Validate a string for minimum requirements
 *
 * @param string $input
 * @param string $field_name
 * @return array
 */
function invite_user_link_validate_string(string $input, string $field_name, int $min_length = 4): array {
	$errors = [];

	if (strlen($input) < $min_length) {
		$errors[] = [
			'field' => $field_name,
			'message' => __('Text must be at least ' . $min_length . ' characters')
		];
	}	

	return $errors;
}
