<?php
/**
 * Create database tables
 *
 * @return void
 */
function invite_user_link_create_db() {
	global $wpdb;
	$version = get_option( 'householdphotos_version', '1.0.2' );
	$charset_collate = $wpdb->get_charset_collate();
  $table_invitations = $wpdb->prefix . 'invite_user_links';
  $table_invitation_users = $wpdb->prefix . 'invite_user_link_users';
  $table_invitation_user_temp = $wpdb->prefix . 'invite_user_link_user_temp';

	$sql = "CREATE TABLE $table_invitations (
		ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    slug char(32) NOT NULL,
    max_invites int(4) UNSIGNED NOT NULL DEFAULT 1,
    require_email_address tinyint(1) NOT NULL DEFAULT 0,
    require_email_verification tinyint(1) NOT NULL DEFAULT 0,
    require_approval tinyint(1) NOT NULL DEFAULT 0,
    created timestamp DEFAULT CURRENT_TIMESTAMP,
    updated timestamp DEFAULT CURRENT_TIMESTAMP,
    expires timestamp NULL,
		UNIQUE KEY id (ID)
  ) $charset_collate;
  CREATE TABLE $table_invitation_users (
    ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    user_id bigint(20) unsigned NULL,
    invitation_id bigint(20) unsigned NULL,
    status enum('invited', 'activated', 'approved') DEFAULT 'invited',
    created timestamp DEFAULT CURRENT_TIMESTAMP,
		UNIQUE KEY id (ID),
    CONSTRAINT fk_invite_user_links_user_id
      FOREIGN KEY (user_id)
      REFERENCES {$wpdb->prefix}users(ID)
      ON DELETE CASCADE,
    CONSTRAINT fk_invite_user_links_invitation_id
      FOREIGN KEY (invitation_id)
      REFERENCES {$table_invitations}(ID)
      ON DELETE CASCADE    
  ) $charset_collate;
  CREATE TABLE $table_invitation_user_temp (
    ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    invitation_id bigint(20) unsigned NULL,
    serialized_data MEDIUMTEXT NOT NULL DEFAULT 0,
    created timestamp DEFAULT CURRENT_TIMESTAMP,
		UNIQUE KEY id (ID),
    CONSTRAINT fk_invite_user_links_temp_invitation_id
      FOREIGN KEY (invitation_id)
      REFERENCES {$table_invitation_users}(ID)
      ON DELETE CASCADE    
  ) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

