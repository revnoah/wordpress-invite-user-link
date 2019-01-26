<?php
/**
 * @package InviteUserLink
 * @version 1.0.0
 */

/*
Plugin Name: Invite User Link
Plugin URI: http://noahjstewart.com/
Description: Plugin to manage and share private photo albums
Author: Noah Stewart
Version: 1.0.0
Author URI: http://noahjstewart.com/
*/

//load required includes
require_once realpath(__DIR__) . '/includes/helpers.inc.php';
require_once realpath(__DIR__) . '/includes/createdb.inc.php';
require_once realpath(__DIR__) . '/includes/admin.inc.php';

//register rewrite hook
register_activation_hook(__FILE__, 'invite_user_link_rewrite_activation');
register_deactivation_hook(__FILE__, 'invite_user_link_rewrite_activation');

/**
 * Handle rewrite rules
 *
 * @return void
 */
function invite_user_link_rewrite_activation(){
	flush_rewrite_rules();
}
