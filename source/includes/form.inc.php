<?php

/**
 * Get formatted field
 *
 * @param array $setting Array element from settings
 * @return string
 */
function invite_user_link_get_formatted_field($setting): string {
	$output = '';

	//get field parts
	$label = invite_user_link_get_label($setting);
	$description = invite_user_link_get_description($setting);
	$field = invite_user_link_get_field($setting);

	//output row
	$output = '<tr valign="top">';
	if($setting['type'] === 'heading') {
		$output .= $field;
	} elseif($setting['type'] === 'boolean') {
		//place checkboxes to the left of the label
		$output .= '<th colspan="2">';
		$output .= $field . $label;
		if($description !== '') {
			$output .= '<br />';
			$output .= $description;
		}
		$output .= '</th>';
	} else {
		//align other fields to column cells
		$output .= '<th>';
		$output .= $label;
		$output .= '</th>';
		$output .= '<td>';
		$output .= $field;
		if($description !== '') {
			$output .= '<br />';
			$output .= $description;
		}
		$output .= '</td>';
	}
	$output .= '</tr>';

	return $output;
}

/**
 * Get field
 *
 * @param array $setting Array element from settings
 * @return string
 */
function invite_user_link_get_field(array $setting): string {
	$output = '';

	if ($setting['type'] === 'heading') {
		$output .= invite_user_link_get_heading($setting);
	} elseif ($setting['type'] === 'boolean') {
		$output .= invite_user_link_get_checkbox($setting);
	} elseif ($setting['type'] === 'date') {
		$output .= invite_user_link_get_date($setting);
	} elseif ($setting['type'] === 'number') {
		$output .= invite_user_link_get_number($setting);
	} elseif ($setting['type'] === 'text') {
		$output .= invite_user_link_get_text($setting);
	}

	return $output;
}

/**
 * Get form open
 *
 * @param string $id          form id
 * @param array  $hidden_vars hidden variables to add to form
 * @param string $action      php route to post the action to
 * @return string
 */
function invite_user_link_get_form_open(
	string $id = '', 
	array $hidden_vars = [], 
	string $action = 'invite-user-link'
	): string {
	$output = '<form method="post" action="' . site_url($action) . '"';
	if ($id !== '') {
		$output .= ' id="' . $id . '"';
	}
	$output .= '>' . "\n";

	//add hidden field for form action
	$output .= '<input type="hidden" name="action" value="' . $id . '" />' . "\n";

	//add custom hidden fields
	if (count($hidden_vars)) {
		foreach($hidden_vars as $key => $hidden_var) {
			$output .= '<input type="hidden" name="' . $key . '" value="' . $hidden_var . '" />' . "\n";
		}
	}

	return $output;
}

/**
 * Get form close
 *
 * @return string
 */
function invite_user_link_get_form_close(): string {
	$output = '</form>';

	return $output;
}

/**
 * Get table header with optional header row
 *
 * @param array $rows optional columns for header row
 * @return string
 */
function invite_user_link_get_table_header(array $rows = []): string {
	//open table element
	$output = '<table class="form-table">';

	//output header row if provided
	if (count($rows) > 0) {
		$output .= '<thead><tr>';
		foreach ($rows as $row) {
			$output .= '<th>' . $row . '</th>';
		}
		$output .= '</tr></thead>';
	}

	return $output;
}

/**
 * Get table footer
 *
 * @return string
 */
function invite_user_link_get_table_footer(): string {
	$output = '</table>';
	
	return $output;
}

/**
 * Get heading
 *
 * @param string $heading Name of heading
 * @return string
 */
function invite_user_link_get_heading($heading): string {
	//close table
	$output = invite_user_link_get_table_footer();

	//display header
	$output .= '<h1>' . $heading['label'] . '</h1>';

	//start new tables
	$output .= invite_user_link_get_table_header();

	return $output;
}

/**
 * Get label
 *
 * @param array $setting Array element from settings
 * @return string
 */
function invite_user_link_get_label($setting): string {
	$output = '<label for="' . $setting['id'] . '">' 
		. $setting['label'] . '</label>';

	return $output;
}

/**
 * Get description
 *
 * @param array $setting Array element from settings
 * @return string
 */
function invite_user_link_get_description($setting): string {
	if (isset($setting['description']) && $setting['description'] != '') {
		return '<small style="font-weight: 400;">' 
			. $setting['description'] 
			. '</small>';
	}

	return '';
}

/**
 * Get Checkbox
 *
 * @param array $setting Array of field settings
 * @return string
 */
function invite_user_link_get_checkbox($setting): string {
	$output = '<input type="checkbox" id="' . $setting['id'] 
	. '" name="' . $setting['id'] . '" ' 
	. ($setting['saved'] === 'on' ? 'checked' : '');
	$output .= invite_user_link_get_data($setting);
	$output .= '/>';

	return $output;
}

/**
 * Get Text Field
 *
 * @param array $setting Array of field settings
 * @return string
 */
function invite_user_link_get_text($setting): string {
	$output = '<input type="text" id="' . $setting['id'] 
		. '" name="' . $setting['id'] 
		. '" class="form-control"';
	$output .= invite_user_link_get_data($setting);
	$output .= ' value="' . $setting['saved'] 
		. '" />';

	return $output;
}

/**
 * Get Number Field
 *
 * @param array $setting Array of field settings
 * @return string
 */
function invite_user_link_get_number($setting): string {
	$output = '<input type="number" id="' . $setting['id'] 
		. '" name="' . $setting['id'] 
		. '" class="form-control"';
	$output .= invite_user_link_get_data($setting);

	//set min option if provided
	if (isset($setting['min'])) {
		$output .= ' min="' . $setting['min'] . '"';
	}

	$output .= ' value="' . $setting['saved'] . '" />';

	return $output;
}

/**
 * Get Text Field
 *
 * @param array $setting Array of field settings
 * @return string
 */
function invite_user_link_get_date($setting): string {
	$output = '<input type="date" id="' . $setting['id'] 
		. '" name="' . $setting['id'] 
		. '" class="form-control"';
	$output .= invite_user_link_get_data($setting);
	$output .= ' value="' . $setting['saved'] 
		. '" />';

	return $output;
}

/**
 * Get data from settings
 *
 * @param array $setting Array of field settings 
 * @return string
 */
function invite_user_link_get_data($setting): string {
	if (!isset($setting['data']) || count($setting['data']) === 0) {
		return '';
	}

	$output = ' data-' . implode(' data-', $setting['data']);

	return $output;
}
