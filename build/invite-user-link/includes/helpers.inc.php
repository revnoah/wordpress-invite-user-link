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
