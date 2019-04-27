<?php
/**
 * This file contains the shortcode for the plugin
 *
 * @package PostsInSidebar
 * @since 3.0
 */

/**
 * Create the shortcode.
 *
 * @param array $atts The options for the main function.
 * @example [pissc number=3 ignore_sticky=1]
 * @example [pissc post_type="page" post_parent_in=2 exclude_current_post=1]
 * @since 3.0
 */
function pis_shortcode( $atts ) {
	$atts = shortcode_atts( pis_get_defaults(), $atts );

	return do_shortcode( pis_get_posts_in_sidebar( $atts ) );
}
if ( ! shortcode_exists( 'pissc' ) ) {
	add_shortcode( 'pissc', 'pis_shortcode' );
}
