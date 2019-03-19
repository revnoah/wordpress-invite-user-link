<?php
/**
 * @package InviteUserLink
 * @version 1.0.1
 */

/*
Plugin Name: Invite User Link
Plugin URI: http://noahjstewart.com/
Description: Plugin to create placeholder users and signup links for the users to finish adding their info
Author: Noah Stewart
Version: 1.0.1
Author URI: http://noahjstewart.com/
*/

//define constants for plugin
define("PLUGIN_DIR", __DIR__);
define("PAGENAME", 'invite-user-link');

//load required includes
require_once realpath(__DIR__) . '/includes/helpers.inc.php';
require_once realpath(__DIR__) . '/includes/createdb.inc.php';
require_once realpath(__DIR__) . '/includes/validate.inc.php';
require_once realpath(__DIR__) . '/includes/form.inc.php';
require_once realpath(__DIR__) . '/includes/admin.inc.php';
require_once realpath(__DIR__) . '/includes/invite.inc.php';
require_once realpath(__DIR__) . '/includes/permissions.inc.php';


add_action( 'init', 'invite_user_link_rewrite_add_rewrites' );

/**
 * Add rewrites
 *
 * @return void
 */
function invite_user_link_rewrite_add_rewrites() {
  add_rewrite_rule(
	  '^' . PAGENAME . '/?([^/]*)',
	  'index.php?pagename=' . PAGENAME . '&slug=$matches[1]',
	  'top'
  );
}

//TODO: review and possibly remove this function and filter
//created: invite_user_link_locate_template
/**
 * Redirect page template
 *
 * @param [type] $template
 * @return void
 */
function invite_user_link_redirect_page_template($template) {
	$template_name = 'page- ' . PAGENAME . '.php';

	if ($template_name == basename($template)) {
		$template = PLUGIN_DIR . '/templates/' . $template_name;
	}

	return $template;
}

/**
 * add page template filter
 */
add_filter ('page_template', 'invite_user_link_redirect_page_template');

/*
add_action( 'init', 'wpse26388_rewrites_init' );
function wpse26388_rewrites_init(){
	add_rewrite_rule(
		'properties/([0-9]+)/?$',
		'index.php?pagename=properties&property_id=$matches[1]',
		'top' );
}

add_filter( 'query_vars', 'wpse26388_query_vars' );
function wpse26388_query_vars( $query_vars ){
	$query_vars[] = 'property_id';
	return $query_vars;
}
*/

/**
 * Activation/deactivation hooks
 */
register_activation_hook( __FILE__, 'invite_user_link_rewrite_activation' );
register_activation_hook( __FILE__, 'invite_user_link_create_db' );
register_deactivation_hook( __FILE__, 'invite_user_link_rewrite_activation' );

/**
 * Apply rewrite rules
 *
 * @return void
 */
function invite_user_link_rewrite_activation() {
	invite_user_link_rewrite_add_rewrites();
	flush_rewrite_rules();
}

//filter for query vars passed to index.php
add_filter('query_vars', 'invite_user_link_query_vars');

/**
 * Handle query params
 *
 * @param array $vars Query vars
 * @return array
 */
function invite_user_link_query_vars($vars) {
  $vars[] = 'slug';

  return $vars;
}

// load custom template, generate image and redirect based on query vars
add_action('template_redirect', 'invite_user_link_catch_vars');

/**
 * Core page functionality
 *
 * @return void
 */
function invite_user_link_catch_vars() {
	global $wpdb, $wp_query;
	$current_user = wp_get_current_user();
	$template_file = '';
	session_start();

	//current user is logged in, redirect
	if ($current_user->ID > 0 && true == false) {
		//TODO: handle logged in user with message and redirect
		echo 'User is logged in';

		return;
	}

	$pagename = get_query_var('pagename');
	$slug = get_query_var('slug');

	if ($pagename !== PAGENAME) {
		return;
	}

	//TODO: handle multiple routes, actions or pagenames
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		//get defined keys from post and finish signup with data
		$keys = ['name', 'email', 'password', 'password2'];
		$account = invite_user_link_get_request_vars($keys, 'POST');

		//finish signup with supplied fields
		invite_user_link_finish_signup($slug, $account);
	} else {
		//load variables to pass into template
		global $settings;
		$settings = invite_user_link_settings_saved();

		//$template_path = invite_user_link_redirect_page_template($template_file);		
		$template_name = 'page-' . PAGENAME . '.php';
		$template_path = invite_user_link_locate_template([$template_name], true);
	}
}

