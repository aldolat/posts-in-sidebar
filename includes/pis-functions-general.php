<?php
/**
 * This file contains the general functions of the plugin.
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
 * General functions.
 *******************************************************************************
 */

/**
 * Return the class for the HTML element.
 *
 * @since 1.9
 *
 * @param  string|array $default One or more classes, defined by plugin's developer, to add to the class list.
 * @param  string|array $class   One or more classes, defined by the user, to add to the class list.
 * @param  boolean      $echo    If the function should echo or not the output. Default true.
 *
 * @return string       $output  HTML formatted list of classes, e.g class="class1 class2".
 */
function pis_class( $default = '', $class = '', $echo = true ) {

	// Define $classes as array.
	$classes = array();

	// If $default is not empty, remove any leading and trailing dot, space, and dash,
	// transform it into an array using internal spaces, and merge it with $classes.
	if ( ! empty( $default ) ) {
		if ( ! is_array( $default ) ) {
			$default = preg_split( '/[\s]+/', trim( $default, ' -' ) );
		}
		$classes = array_merge( $classes, $default );
	}

	// If $class is not empty, remove any leading and trailing space,
	// transform it into an array using internal spaces, and merge it with $classes.
	if ( ! empty( $class ) ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '/[\s]+/', trim( $class, ' -' ) );
		}
		$classes = array_merge( $classes, $class );
	}

	// Remove null or empty or space-only-filled elements from the array.
	foreach ( $classes as $key => $value ) {
		if ( is_null( $value ) || '' === $value || ' ' === $value ) {
			unset( $classes[ $key ] );
		}
	}

	// Sanitize a HTML classname to ensure it only contains valid characters.
	$classes = array_map( 'sanitize_html_class', $classes );
	$classes = array_map( 'pis_remove_dashes', $classes );

	// Convert the array into string and build the final output.
	$classes = 'class="' . implode( ' ', $classes ) . '"';

	if ( true === $echo ) {
		echo apply_filters( 'pis_classes', $classes );
	} else {
		return apply_filters( 'pis_classes', $classes );
	}
}

/**
 * Return the paragraph class with inline style.
 *
 * @since 1.12
 *
 * @param string $margin       The margin of the paragraph.
 * @param string $unit         The unit measure to be used.
 * @param string $class        The default class defined by the plugin's developer.
 * @param string $class_filter The name of the class filter.
 * @return string $output      The class and the inline style.
 * @uses pis_class()
 */
function pis_paragraph( $margin, $unit, $class, $class_filter ) {
	if ( ! is_null( $margin ) ) {
		$style = ' style="margin-bottom: ' . $margin . $unit . ';"';
	} else {
		$style = '';
	}
	$output = pis_class( $class, apply_filters( $class_filter, '' ), false ) . $style;
	return $output;
}

/**
 * Return the given text with paragraph breaks (HTML <br />).
 *
 * @since 1.12
 * @param string $text The text to be checked.
 * @return string $text The checked text with paragraph breaks.
 */
function pis_break_text( $text ) {
	// Convert cross-platform newlines into HTML '<br />'.
	$text = str_replace( array( "\r\n", "\n", "\r" ), '<br />', $text );
	return $text;
}

/**
 * Return the array containing the custom fields of the post.
 *
 * @since 1.12
 * @return array The custom fields of the post.
 */
function pis_meta() {
	global $wpdb;

	$limit = (int) apply_filters( 'pis_postmeta_limit', 30 );

	$sql = "SELECT DISTINCT meta_key
		FROM $wpdb->postmeta
		WHERE meta_key NOT BETWEEN '_' AND '_z'
		HAVING meta_key NOT LIKE %s
		ORDER BY meta_key
		LIMIT %d";

	$keys = $wpdb->get_col( $wpdb->prepare( $sql, $wpdb->esc_like( '_' ) . '%', $limit ) );

	if ( $keys ) {
		natcasesort( $keys );
	}

	return $keys;
}

/**
 * Generate an HTML arrow.
 *
 * @param boolean $pre_space If a space must be prepended before the arrow.
 * @return string $output The HTML arrow.
 * @uses pis_class()
 * @since 1.15
 * @since 4.5.0 Added filter for HTML arrows in title and excerpt.
 */
function pis_arrow( $pre_space = true ) {
	$the_arrow = apply_filters( 'pis_arrow', '&rarr;' );
	if ( is_rtl() ) {
		$the_arrow = '&larr;';
	}

	if ( $pre_space ) {
		$space = '&nbsp;';
	} else {
		$space = '';
	}

	$output = $space . '<span ' . pis_class( 'pis-arrow', apply_filters( 'pis_arrow_class', '' ), false ) . '>' . $the_arrow . '</span>';

	return $output;
}

/**
 * Generate the output for the more and/or the HTML arrow.
 *
 * @param string  $the_more    The text to be displayed for "Continue reading". Default empty.
 * @param boolean $no_the_more If the text for "Continue reading" must be hidden. Default false.
 * @param boolean $exc_arrow   If the arrow must be displayed or not. Default false.
 * @param boolean $echo        If echo the output or return.
 * @param boolean $pre_space   If a space must be prepended.
 *
 * @since 1.15
 * @uses pis_arrow()
 * @return string The HTML arrow linked to the post.
 */
function pis_more_arrow( $the_more = '', $no_the_more = false, $exc_arrow = false, $echo = true, $pre_space = true ) {
	$output = '';
	// If we do not want any "Read more" nor any arrow
	// or the user doesn't want any "Read more" nor any arrow.
	if ( ( true === $no_the_more && false === $exc_arrow ) || ( '' === $the_more && false === $exc_arrow ) ) {
		$output = '';
	} else {
		// Else if we do not want any "Read more" but the user wants an arrow
		// or the user doesn't want the "Read more" but only the arrow.
		if ( ( true === $no_the_more && true === $exc_arrow ) || ( ! $the_more && $exc_arrow ) ) {
			$the_more  = '';
			$the_arrow = pis_arrow( false );
		} elseif ( $the_more && $exc_arrow ) { // The user wants the "Read more" and the arrow.
			$the_arrow = pis_arrow();
		} else { // The user wants the "Read more" but not the arrow.
			$the_arrow = '';
		}
		$output  = '<span ' . pis_class( 'pis-more', apply_filters( 'pis_more_class', '' ), false ) . '>';
		$output .= '<a ' . pis_class( 'pis-more-link', apply_filters( 'pis_more_link_class', '' ), false ) . ' href="' . get_permalink() . '" rel="bookmark">';
		$output .= $the_more . $the_arrow;
		$output .= '</a>';
		$output .= '</span>';
	}

	if ( $pre_space ) {
		$output = ' ' . $output;
	}

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
	}
}

/**
 * Add the custom styles to wp_head hook.
 *
 * @since 1.13
 */
function pis_add_styles_to_head() {
	// Get the options from the database.
	$custom_styles = (array) get_option( 'widget_pis_posts_in_sidebar' );

	// Define $styles as an array.
	$styles = array();

	// Get all the values of "custom_styles" key into $styles.
	foreach ( $custom_styles as $key => $value ) {
		if ( isset( $value['custom_styles'] ) ) {
			$styles[] = $value['custom_styles'];
		}
	}

	/*
	 * Remove any empty elements from the array.
	 *
	 * Invoking array_filter without a callback function
	 * will remove any element with one of these values:
	 *		- false
	 *		- null
	 *		- '' (empty)
	 *
	 * For multidimensional arrays, use pis_array_remove_empty_keys() function.
	 *
	 * @see http://php.net/manual/en/function.array-filter.php#example-5568
	 */
	$styles = array_filter( $styles );

	// Transform the array into a string.
	$styles = implode( "\n", $styles );

	// Print the output if it's not empty.
	if ( $styles ) {
		$output  = "\n\n" . '<!-- Styles generated by Posts in Sidebar plugin -->' . "\n";
		$output .= '<style type="text/css">' . "\n" . $styles . "\n" . '</style>';
		$output .= "\n" . '<!-- / Styles generated by Posts in Sidebar plugin -->' . "\n\n";
		echo $output;
	}
}
add_action( 'wp_head', 'pis_add_styles_to_head' );
