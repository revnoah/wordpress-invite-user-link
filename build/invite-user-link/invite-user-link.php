<?php
/**
 * @package InviteUserLink
 * @version 1.0.1
 */

/*
Plugin Name: Invite User Link
Plugin URI: http://noahjstewart.com/
Description: Plugin to manage and share private photo albums
Author: Noah Stewart
Version: 1.0.1
Author URI: http://noahjstewart.com/
*/

//load required includes
require_once realpath(__DIR__) . '/includes/helpers.inc.php';
require_once realpath(__DIR__) . '/includes/createdb.inc.php';
require_once realpath(__DIR__) . '/includes/form.inc.php';
require_once realpath(__DIR__) . '/includes/admin.inc.php';
require_once realpath(__DIR__) . '/includes/invite.inc.php';
require_once realpath(__DIR__) . '/includes/permissions.inc.php';

//register rewrite hook
register_activation_hook(__FILE__, 'invite_user_link_rewrite_activation');
register_activation_hook(__FILE__, 'invite_user_link_create_db');
register_deactivation_hook(__FILE__, 'invite_user_link_rewrite_activation');

/**
 * Handle rewrite rules
 *
 * @return void
 */
function invite_user_link_rewrite_activation() {
  invite_user_link_rewrite_add_rewrites();
	flush_rewrite_rules();
}

/**
 * Load rewrite
 */
add_action('init', 'invite_user_link_rewrite_add_rewrites');

/**
 * Set new rewrite rules for invites
 *
 * @return void
 */
function invite_user_link_rewrite_add_rewrites() {
	add_rewrite_rule(
		'^invite/?([^/]*)',
		'index.php?pagename=invite&code=$matches[1]',
		'top'
	);
}

/**
 * filter for query vars passed to index.php
 */
add_filter('query_vars', 'invite_user_link_query_vars');

/**
 * Handle query params
 *
 * @param array $vars Query vars
 * @return array
 */
function invite_user_link_query_vars($vars) {
	$vars[] = 'code';

	return $vars;
}
  
/**
 * Add endpoint
 */
add_action('init', 'invite_user_link_add_endpoints');

/**
 * Manage endpoints
 *
 * @return void
 */
function invite_user_link_add_endpoints() {
	add_rewrite_endpoint('invite', EP_PAGES);
}

// load custom template, generate image and redirect based on query vars
add_action('template_redirect', 'invite_user_link_catch_vars');
function invite_user_link_catch_vars() {
	global $wpdb, $wp_query;
	session_start();

	$pagename = get_query_var('pagename');
	$code = get_query_var('code');

	if ($pagename !== 'invite') {
		return;
	}

	echo 'Invite!!';
}
