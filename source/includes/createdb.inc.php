<?php
/**
 * Create database tables
 *
 * @return void
 */
function invite_user_link_create_db() {
	global $wpdb;
	$version = get_option( 'householdphotos_version', '1.0.0' );
	$charset_collate = $wpdb->get_charset_collate();
	$table_prefix = $wpdb->prefix;
  $table_invitations = $table_prefix . 'visitor_signup_invitations';
  $table_invitation_users = $table_prefix . 'visitor_signup_invitation_users';

	$sql = "CREATE TABLE $table_invitations (
		ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    hash char(32) NOT NULL,
    max_invites int NOT NULL DEFAULT 1,
    created timestamp DEFAULT CURRENT_TIMESTAMP,
    updated timestamp DEFAULT CURRENT_TIMESTAMP,
    expires timestamp NULL,
		UNIQUE KEY id (ID)
  ) $charset_collate;
  CREATE TABLE $table_invitation_users (
    ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    user_id bigint(20) unsigned NULL,
    invitation_id bigint(20) unsigned NULL,
    status enum(invited', 'activated', 'approved') DEFAULT 'invited',
    created timestamp DEFAULT CURRENT_TIMESTAMP,
		UNIQUE KEY id (ID),
    CONSTRAINT fk_visitor_signup_invitations_user_id
      FOREIGN KEY (user_id)
      REFERENCES {$wpdb->prefix}users(ID)
      ON DELETE CASCADE
    CONSTRAINT fk_visitor_signup_invitations_invitation_id
      FOREIGN KEY (invitation_id)
      REFERENCES {$table_invitations}(ID)
      ON DELETE CASCADE    
  ) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

