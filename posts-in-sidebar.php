<?php
/**
 * Plugin Name: Posts in Sidebar
 * Plugin URI: https://dev.aldolat.it/projects/posts-in-sidebar/
 * Description: Publish a list of posts in your sidebar
 * Version: 4.7.0
 * Author: Aldo Latino
 * Author URI: https://www.aldolat.it/
 * Text Domain: posts-in-sidebar
 * Domain Path: /languages/
 * License: GPLv3 or later
 *
 * @package PostsInSidebar
 */

/*
Copyright (C) 2009, 2018  Aldo Latino  (email : aldolat@gmail.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * Prevent direct access to this file.
 *
 * @since 2.0
 */
if ( ! defined( 'WPINC' ) ) {
	exit( 'No script kiddies please!' );
}

/**
 * Launch Posts in Sidebar.
 *
 * @since 1.27
 */
add_action( 'plugins_loaded', 'pis_setup' );

/**
 * Setup Posts in Sidebar.
 *
 * @since 1.27
 */
function pis_setup() {

	/**
	 * Define the version of the plugin.
	 */
	define( 'PIS_VERSION', '4.7.0' );

	/**
	 * Make plugin available for i18n.
	 * Translations must be archived in the /languages/ directory.
	 * The name of each translation file must be, for example:
	 *
	 * ITALIAN:
	 * posts-in-sidebar-it_IT.po
	 * posts-in-sidebar-it_IT.mo
	 *
	 * GERMAN:
	 * posts-in-sidebar-de_DE.po
	 * posts-in-sidebar-de_DE.po
	 *
	 * and so on.
	 *
	 * @since 0.1
	 */
	load_plugin_textdomain( 'posts-in-sidebar', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	/*
	 * Load the plugin's main function.
	 *
	 * @since 3.8.1
	 */
	require_once plugin_dir_path( __FILE__ ) . 'includes/pis-core.php';

	/*
	 * Load the plugin's functions.
	 *
	 * @since 1.23
	 */
	require_once plugin_dir_path( __FILE__ ) . 'includes/pis-functions.php';

	/*
	 * Load Posts in Sidebar's widgets.
	 */
	add_action( 'widgets_init', 'pis_load_widgets' );

	/*
	 * Load the shortcode.
	 *
	 * @since 3.0
	 */
	require_once plugin_dir_path( __FILE__ ) . 'includes/pis-shortcode.php';

	/*
	 * Load the script.
	 *
	 * @since 1.29
	 */
	add_action( 'admin_enqueue_scripts', 'pis_load_scripts' );

	/*
	 * Add links to plugins list line.
	 *
	 * @since 3.1
	 */
	add_filter( 'plugin_row_meta', 'pis_add_links', 10, 2 );
}

/**
 * Load the Javascript file.
 * The file will be loaded only in the widgets admin page.
 *
 * @param string $hook The page where to load scripts.
 * @since 1.29
 */
function pis_load_scripts( $hook ) {
	if ( 'widgets.php' !== $hook ) {
		return;
	}

	// Register and enqueue the JS file.
	wp_register_script( 'pis_js', plugins_url( 'assets/pis-admin.js', __FILE__ ), array( 'jquery' ), PIS_VERSION, false );
	wp_enqueue_script( 'pis_js' );

	// Register and enqueue the CSS file.
	wp_register_style( 'pis_style', plugins_url( 'assets/pis-admin.css', __FILE__ ), array(), PIS_VERSION, 'all' );
	wp_enqueue_style( 'pis_style' );
}


/**
 * Register the widget
 *
 * @since 1.0
 */
function pis_load_widgets() {

	/**
	 * Load the widget's form functions.
	 *
	 * @since 1.12
	 */
	require_once plugin_dir_path( __FILE__ ) . 'includes/pis-widget-form-functions.php';

	/**
	 * Load the widget's PHP file.
	 *
	 * @since 1.1
	 */
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pis-posts-in-sidebar.php';

	register_widget( 'PIS_Posts_In_Sidebar' );
}

/**
 * Add links to plugins list line.
 *
 * @param array  $links The array containing links.
 * @param string $file  The path to this plugin file.
 * @since 3.1
 */
function pis_add_links( $links, $file ) {
	if ( plugin_basename( __FILE__ ) !== $file ) {
		$rate_url = 'https://wordpress.org/support/plugin/' . basename( dirname( __FILE__ ) ) . '/reviews/#new-post';
		$links[]  = '<a target="_blank" href="' . $rate_url . '" title="' . esc_html__( 'Click here to rate and review this plugin on WordPress.org', 'posts-in-sidebar' ) . '">' . esc_html__( 'Rate this plugin', 'posts-in-sidebar' ) . '</a>';
	}
	return $links;
}

/*
 * CODE IS POETRY
 */
