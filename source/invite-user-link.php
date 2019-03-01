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
define("PLUGIN_DIR", __FILE__);

//load required includes
require_once realpath(__DIR__) . '/includes/helpers.inc.php';
require_once realpath(__DIR__) . '/includes/createdb.inc.php';
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
	  '^invite-user-link/?([^/]*)',
	  'index.php?pagename=invite-user-link&code=$matches[1]',
	  'top'
  );
}

/*
function invite_user_link_redirect_page_template ($template) {
	if ('my-custom-template.php' == basename ($template))
		$template = WP_PLUGIN_DIR . '/mypluginname/my-custom-template.php';
	return $template;
}
add_filter ('page_template', 'invite_user_link_redirect_page_template');
*/

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
  $vars[] = 'code';

  return $vars;
}

add_action('init', 'invite_user_link_add_endpoints');

/**
 * Manage endpoints
 *
 * @return void
 */
function invite_user_link_add_endpoints() {
	add_rewrite_endpoint('vision-statement', EP_PAGES);
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
	if ($current_user->ID > 0) {
		//TODO: handle logged in user with message and redirect
		echo 'User is logged in';

		return;
	}

	$pagename = get_query_var('pagename');
	$code = get_query_var('code');
	$action = isset($_GET['action']) ? $_GET['action'] : '';
	$name = isset($_POST['name']) ? $_POST['name'] : '';
	$email = isset($_POST['email']) ? $_POST['email'] : '';
	$password = isset($_POST['password']) ? $_POST['password'] : '';
	$password2 = isset($_POST['password2']) ? $_POST['password2'] : '';

	if ($pagename !== 'invite-user-link') {
		return;
	}

	if ($code !== '') {
		echo $code;

		$template_file = 'page-invite-user-link.php';
	}

	$new_template = locate_template($template_file);
	if($new_template == '' && $template_file != '') {
		include plugin_dir_path( __FILE__ ) . 'templates/' . $template_file;
		exit;
	} elseif($new_template !== '') {
		include $new_template;
		exit;
	}
}

