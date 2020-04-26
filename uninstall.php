<?php
/**
 * Posts in Sidebar Uninstall
 *
 * This file contains the uninstall function of the plugin.
 *
 * @package PostsInSidebar
 * @since 1.0
 */

// Check for the 'WP_UNINSTALL_PLUGIN' constant, before executing.
if ( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit( 'No script kiddies please!' );
}

/**
 * Delete transients and options from the database.
 *
 * Transient created by this plugin have always this string:
 * `pis_transients_`.
 *
 * @since 1.16
 * @since 4.10.3 Modified according to new transients management.
 */
function pis_garbage_collection() {
	// Delete plugin's transients from the database.
	global $wpdb;

	$results = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT option_name FROM $wpdb->options WHERE option_name LIKE %s",
			'%pis_transients_%'
		)
	);

	if ( $results ) {
		foreach ( $results as $key => $transients ) {
			// Ignore transients with `timeout` in the name, since WordPress will delete them by itself.
			if ( false === strpos( $transients->option_name, '_transient_timeout_' ) ) {
				// Remove `_transient_` from name, otherwise WordPress will not find them.
				$transient = str_replace( '_transient_', '', $transients->option_name );
				if ( get_transient( $transient ) ) {
					delete_transient( $transient );
				}
			}
		}
	}

	// Delete plugin's options from the database.
	delete_option( 'widget_pis_posts_in_sidebar' );
}

pis_garbage_collection();

/*
 * "So long, and thanks for all the fish."
 * (Douglas Adams)
 */
