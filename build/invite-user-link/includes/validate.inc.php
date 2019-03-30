<?php

/**
 * Validate email address
 *
 * @param string $input email address
 * @return boolean
 */
function invite_user_link_validate_email(string $email): bool {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Validate two passwords for matching and requirements
 *
 * @param string $password Password
 * @param string $password2 Repeated password
 * @return boolean
 */
function invite_user_link_validate_passwords(string $password, string $password2): bool {
	//start with comparison of two supplied passwords
	if ($password !== $password2) {
		return false;
	}

	if (strlen($password) < 8) {
		return false;
	}
}

/**
 * Validate a string for minimum requirements
 *
 * @param string $input
 * @return boolean
 */
function invite_user_link_validate_string(string $input): bool {
	if (strlen($input) < 8) {
		return false;
	}

	return true;
}
