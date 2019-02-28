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

/**
 * Get template part from user theme falling back to plugin folder
 *
 * @param string $template_name   Name of content template partial
 * @param string $template_folder Name of folder to search
 * @return void
 */
function invite_user_link_get_template_part(string $template_name, string $template_folder = 'template_parts'): void {
	//plugin dir is defined in root file
	$plugin_dir = plugin_dir_path(PLUGIN_DIR);

	$new_template = locate_template($template_name);
	if ($new_template == '' && $template_name != '') {

		echo 'template is set';

		load_template($filename);
		get_template_part($template_folder . '/content', $template_name);
		//include plugin_dir_path( __FILE__ ) . 'templates/' . $template_name;
		//exit;
	} elseif($new_template !== '') {

		echo 'loading from plugins';

		//filename of plugin template part
		$filename = $plugin_dir . 'template_parts/content-' . $template_name . '.php';
		load_template($filename);
		//include $new_template;
		//exit;
	}
}