<?php
/**
 * Posts in Sidebar Uninstall
 *
 * This file contains the uninstall function of the plugin.
 *
 * @package PostsInSidebar
 * @since 1.0
 */

// Check for the 'WP_UNINSTALL_PLUGIN' constant, before executing
if ( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit( 'No script kiddies please!' );
}

/*
 * Delete the transients, if any
 *
 * @since 1.16
 */
$pis_options = (array) get_option( 'widget_pis_posts_in_sidebar' );
foreach ( $pis_options as $key => $value ) {
	if ( get_transient( $value['widget_id'] . '_query_cache' ) ) {
		delete_transient( $value['widget_id'] . '_query_cache' );
	}
}

/*
 * Delete widget's options from the database
 */
delete_option( 'widget_pis_posts_in_sidebar' );
