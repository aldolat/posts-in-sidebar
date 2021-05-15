<?php
/**
 * This file contains the tools functions of the plugin
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

/**
 * Get info about the cache.
 *
 * @param  string $widget_id  The ID of the widget.
 * @return array  $cache_info An associative array containing cache information.
 * @since 4.8.4
 */
function pis_get_cache_info( $widget_id ) {
	$cache_info = array();

	// Get transient values.
	$cache_created_timestamp = get_transient( $widget_id . '_created_query_cache' );
	$cache_expires_timestamp = get_transient( 'timeout_' . $widget_id . '_query_cache' );

	// Get the local GMT offset and date/time formats.
	$local_offset    = (int) get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
	$datetime_format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );

	// Add local GMT offset to transients timeout.
	$cache_created_timestamp_localized = $cache_created_timestamp + $local_offset;
	$cache_expires_timestamp_localized = $cache_expires_timestamp + $local_offset;

	// Get date and time of cache creation.
	$cache_created = date( $datetime_format, $cache_created_timestamp_localized );

	// Get how much time passed since creation.
	$cache_passed = human_time_diff( $cache_created_timestamp, time() );

	// Get cache duration time.
	$cache_duration = human_time_diff( $cache_created_timestamp, $cache_expires_timestamp );

	// Get date and time of cache expiration.
	$cache_expires = date( $datetime_format, $cache_expires_timestamp_localized );

	// Get remaining time to next cache update.
	$cache_remaining_time = human_time_diff( $cache_expires_timestamp, time() );

	$cache_info = array(
		'cache_created'        => $cache_created,
		'cache_passed'         => $cache_passed,
		'cache_duration'       => $cache_duration,
		'cache_expires'        => $cache_expires,
		'cache_remaining_time' => $cache_remaining_time,
	);

	return $cache_info;
}

/**
 * Remove extra charachters from a string.
 *
 * This function will maintain the following charachters:
 * a-z, A-Z, 0-9, _
 * removing others.
 *
 * @param string $string  The string to be cleaned.
 * @return string $string The cleaned, lowercased string.
 * @since 4.8.5
 */
function pis_clean_string( $string ) {
	$string = preg_replace( '/([^a-zA-Z0-9_]+)/', '', $string );
	$string = strtolower( $string );

	return $string;
}

/**
 * Get current date and time.
 *
 * @param bool $date Whether the date should be retrived.
 * @param bool $time Whether the time should be retrived.
 * @return string|bool $output The formatted date/time or false if both $date and $time are false.
 *                             Output example: November 10, 2019 10:42:35
 * @since 4.9.0
 */
function pis_get_current_datetime( $date = true, $time = true ) {
	$date_format = apply_filters( 'pis_cf_dateformat', 'Y-m-d' );
	$time_format = apply_filters( 'pis_cf_timeformat', 'H:i' );
	$gmt_offset  = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;

	if ( ! is_bool( $date ) ) {
		$date = false;
	}
	if ( ! is_bool( $time ) ) {
		$time = false;
	}

	if ( $date && ! $time ) {
		$output = date( $date_format, time() + $gmt_offset );
	} elseif ( ! $date && $time ) {
		$output = date( $time_format, time() + $gmt_offset );
	} elseif ( $date && $time ) {
		$output = date( $date_format . ' ' . $time_format, time() + $gmt_offset );
	} else {
		$output = false;
	}

	return $output;
}

/**
 * Get the URL of the current page, without trailing slash.
 *
 * This function works fine both with permalinks activated or not.
 *
 * @return string The URL currently visited.
 * @example https://www.example.com/tag/markup-2 A standard URL.
 * @example https://www.example.com/?tag=markup-2 An URL with a query string.
 *
 * @since 4.10.0
 */
function pis_get_current_url() {
	$url = '';

	if ( isset( $_SERVER['REQUEST_SCHEME'] ) && isset( $_SERVER['HTTP_HOST'] ) && isset( $_SERVER['REQUEST_URI'] ) ) {
		$scheme = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_SCHEME'] ) ) . '://';
		$host   = sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) );
		$uri    = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );
		$url    = untrailingslashit( $scheme . $host . $uri );
	}

	return $url;
}
