<?php
/**
 * Posts in Sidebar Uninstall
 *
 * @package PostsInSidebar
 * @since 1.0
 */

// Check for the 'WP_UNINSTALL_PLUGIN' constant, before executing
if( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

/*
 * Delete the transients, if any
 */
$pis_options = (array) get_option( 'widget_pis_posts_in_sidebar' );

$pis_widgets = array();
foreach ( $pis_options as $key => $value ) {
	$pis_widgets[] = $value['widget_id'];
}
$pis_widgets = array_filter($pis_widgets);

foreach ( $pis_widgets as $pis_widget ) {
	if ( get_transient( $pis_widget . '_query_cache' ) ) {
		delete_transient( $pis_widget . '_query_cache' );
	}
}


/*
 * Delete widget's options from the database
 */
delete_option( 'widget_pis_posts_in_sidebar' );
