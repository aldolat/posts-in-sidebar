<?php
/**
 * This file contains the tools functions of the plugin.
 *
 * @package PostsInSidebar
 * @since 1.23
 */

/**
 * Prevent direct access to this file.
 *
 * @since 2.0
 */
if ( ! defined( 'WPINC' ) ) {
	exit( 'No script kiddies please!' );
}

/*
 * Tools functions.
 *******************************************************************************
 */

/**
 * Remove empty keys from an array recursively.
 *
 * @param array   $array      The array to be checked.
 * @param boolean $make_empty If the output is to return as an empty string.
 * @since 1.29
 * @see http://stackoverflow.com/questions/7696548/php-how-to-remove-empty-entries-of-an-array-recursively
 */
function pis_array_remove_empty_keys( $array, $make_empty = false ) {
	if ( ! is_array( $array ) ) {
		return;
	}

	foreach ( $array as $key => $value ) {
		if ( is_array( $value ) ) {
			$array[ $key ] = pis_array_remove_empty_keys( $array[ $key ] );
		}
		if ( empty( $array[ $key ] ) ) {
			unset( $array[ $key ] );
		}
	}

	if ( empty( $array ) && $make_empty ) {
		$array = '';
	}

	return $array;
}

/**
 * Compare a string and an array and return the common elements as a string.
 *
 * @param string $string The string to be compared.
 * @param array  $array  The array to be compared.
 *
 * @return string $output The string containing the common values.
 *
 * @since 3.8.8
 */
function pis_compare_string_to_array( $string = '', $array = array() ) {
	// Convert the string to lowercase.
	$string = strtolower( $string );
	// Remove any space from the string.
	$string = str_replace( ' ', '', $string );
	// Remove any comma at the beginning and at the end of the string.
	$string = trim( $string, ',' );
	// Convert the string into an array.
	$string = explode( ',', $string );

	// Compare the two arrays and return the intersection (the common values).
	$output = array_intersect( $string, $array );
	// Convert the returned array into a string.
	$output = implode( ', ', $output );

	return $output;
}

/**
 * Remove any leading and trailing dash from a string.
 *
 * @param string $string The string to be trimmed.
 * @since 4.1
 */
function pis_remove_dashes( $string = '' ) {
	$string = trim( $string, '-' );
	return $string;
}

/**
 * Normalize entered values making these checks:
 * 1) transform any comma and space (in any number) into one comma + space (, );
 * 2) if $string contains numbers, the numbers can be converted into positive, non-decimal values.
 *
 * @param string $string The string to be checked.
 * @param bool   $absint If $string contains number to be converted into positive non-decimal values.
 * @since 4.7.0
 */
function pis_normalize_values( $string = '', $absint = false ) {
	$string = preg_replace( '([\s,]+)', ', ', $string );
	$string = trim( $string, ', ' );

	if ( $absint ) {
		$string = explode( ', ', $string );
		foreach ( $string as $key => $value ) {
			$string[ $key ] = absint( $value );
		}
		$string = implode( ', ', $string );
	}

	return $string;
}

/**
 * Returns the title of the main post,
 * removing punctuation and lowering the letters.
 *
 * @since 4.7.0
 * @return string $post_title The title of the main post with pluses and lowercase.
 */
function pis_get_post_title() {
	$post_title = get_the_title();

	/*
	 * Remove punctuation.
	 *
	 * We cannot simply use:
	 * $post_title = preg_replace( '/[^a-zA-Z0-9]+/', '+', $post_title );
	 * or
	 * $post_title = preg_replace( '/[^\w|\s]/', '', $post_title );
	 * because preg_replace() will remove characters like Russian and such.
	 */
	$remove_chars = array(
		',',
		';',
		'.',
		':',
		'\'',
		'*',
		'°',
		'@',
		'#',
		'+',
		'"',
		'!',
		'?',
		'–',
		'—',
		'―',
		'(',
		')',
	);
	$post_title   = str_replace( $remove_chars, '', $post_title );
	$post_title   = strtolower( $post_title );
	return $post_title;
}

/**
 * Check post types entered.
 * The function removes any post type that has not been defined.
 *
 * @param string $post_type The post type.
 * @since 4.7.0
 */
function pis_check_post_types( $post_type ) {
	$post_type_wordpress = get_post_types( '', 'names' );
	$post_type           = pis_compare_string_to_array( $post_type, $post_type_wordpress );
	return $post_type;
}

/**
 * Print a multidimensional array.
 *
 * @param array $array The array to be printed.
 * @see https://stackoverflow.com/questions/46343168/how-to-display-values-of-a-multidimensional-associative-array-using-foreach-loop
 * @since 4.7.0
 */
function pis_array2string( $array ) {
	$output = '';
	foreach ( $array as $key => $value ) {
		if ( is_array( $value ) ) {
			$output .= '<ul class="pis-debug-ul" style="margin-bottom: 0;">' . "\n";
			$output .= pis_array2string( $value );
			$output .= '</ul>';
		} else {
			$output .= '<li class="pis-debug-li">' . $key . ': <code>' . esc_html( $value ) . '</code></li>' . "\n";
		}
	}

	return $output;
}
