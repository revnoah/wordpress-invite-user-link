<?php

/**
 * Get permissions to view invite statement
 *
 * @param array  $invite invite statement object
 * @param string $slug Unique slug
 * @return boolean
 */
function invite_user_link_get_permissions($slug) {
	global $wpdb;

	$current_user = wp_get_current_user();
	if (!session_id()) {
		session_start();
	}

	if ($slug !== '' && $_SESSION['slug'] == $slug) {
		return true;
	}

	$sql = "SELECT * FROM {$wpdb->prefix}invite_user_link_users AS iulu 
		INNER JOIN {$wpdb->prefix}invite_user_links iul ON iul.invitation_id = iulu.ID
		WHERE user_id = %d";

	$invite = $wpdb->get_row(
		$wpdb->prepare($sql, $current_user->ID)
	);

	/*
	echo 'checked user ' , $current_user->ID;
	print_r($invite);
	die;
	*/

	$sql = "SELECT * FROM {$wpdb->prefix}invite_user_links WHERE slug = %s";
	$invite = $wpdb->get_row(
		$wpdb->prepare($sql, $slug)
	);

	return false;
}

/**
 * Add permission to view last image created by referencing the slug
 *
 * @param string $slug Unique string md5 encoded
 * @return void
 */
function invite_user_link_add_permission($slug) {
	if (!session_id()) {
		session_start();
	}

	$_SESSION['slug'] = $slug;        
}
