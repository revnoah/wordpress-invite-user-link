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
function invite_user_link_get_field($setting): string {
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
 * Get table header with optional header row
 *
 * @param array $rows
 * @return string
 */
function invite_user_link_get_table_header($rows = []): string {
	//open table element
	$output = '<table class="form-table">';

	//output header row if provided
	if (count($rows) > 0) {
		$output .= '<tr>';
		foreach ($rows as $row) {
			$output .= '<th>' . $row . '</th>';
		}
		$output .= '</tr>';
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
	$output .= invite_user_link_get_table_footer();

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
	$output .= ' min="0"';
	$output .= ' value="' . $setting['saved'] 
		. '" />';

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
