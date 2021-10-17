<?php
/**
 * This file contains the general functions of the plugin
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
 * Returns the default options.
 *
 * @since 4.8.1
 * @return array $defaults The default options.
 */
function pis_get_defaults() {
	$defaults = array(
		// The custom container class.
		'container_class'             => '',

		// The title of the widget.
		'title'                       => esc_html__( 'Posts', 'posts-in-sidebar' ),
		'title_link'                  => '',
		'intro'                       => '',

		// Posts retrieving.
		'post_type'                   => 'post', // post, page, attachment, or any custom post type.
		'post_type_multiple'          => '',     // A list of post types, comma separated.
		'posts_id'                    => '',     // Post/Pages IDs, comma separated.
		'author'                      => '',     // Author nicename.
		'author_in'                   => '',     // Author IDs.
		'posts_by_comments'           => false,  // Boolean. An array of post IDs will be used.
		'cat'                         => '',     // Category slugs, comma separated.
		'tag'                         => '',     // Tag slugs, comma separated.
		'post_parent_in'              => '',
		'post_format'                 => '',
		'number'                      => get_option( 'posts_per_page' ),
		'orderby'                     => 'date',
		'order'                       => 'DESC',
		'offset_number'               => '',
		'post_status'                 => 'publish',
		'post_meta_key'               => '',
		'post_meta_val'               => '',

		/*
		 * The 's' (search) parameter must be not declared or must be empty
		 * otherwise it will break sticky posts.
		 */
		'search'                      => null,
		'has_password'                => 'null', // Fake content that will be converted later into real null/true/false.
		'post_password'               => '',
		'ignore_sticky'               => false,

		/*
		 * This is the category of the single post
		 * where we'll get posts from.
		 */
		'get_from_same_cat'           => false,
		'number_same_cat'             => '',
		'title_same_cat'              => '',
		'sort_categories'             => false,
		'yoast_main_cat'              => false,
		'orderby_same_cat'            => 'date',
		'order_same_cat'              => 'DESC',
		'offset_same_cat'             => '',
		'search_same_cat'             => false,
		'post_type_same_cat'          => 'post',
		'ptm_sc'                      => '',

		/*
		 * This is the tag of the single post
		 * where we'll get posts from.
		 */
		'get_from_same_tag'           => false,
		'number_same_tag'             => '',
		'title_same_tag'              => '',
		'sort_tags'                   => false,
		'orderby_same_tag'            => 'date',
		'order_same_tag'              => 'DESC',
		'offset_same_tag'             => '',
		'search_same_tag'             => false,
		'post_type_same_tag'          => 'post',
		'ptm_st'                      => '',

		/*
		 * This is the author of the single post
		 * where we'll get posts from.
		 */
		'get_from_same_author'        => false,
		'number_same_author'          => '',
		'title_same_author'           => '',
		'orderby_same_author'         => 'date',
		'order_same_author'           => 'DESC',
		'offset_same_author'          => '',
		'search_same_author'          => false,
		'post_type_same_author'       => 'post',
		'ptm_sa'                      => '',

		/*
		 * This is the custom field
		 * to be used when on single post
		 */
		'get_from_custom_fld'         => false,
		's_custom_field_key'          => '',
		's_custom_field_tax'          => '',
		'number_custom_field'         => '',
		'title_custom_field'          => '',
		'orderby_custom_fld'          => 'date',
		'order_custom_fld'            => 'DESC',
		'offset_custom_fld'           => '',
		'search_same_cf'              => false,
		'post_type_same_cf'           => 'post',
		'ptm_scf'                     => '',

		/*
		 * This is the post format of the single post
		 * where we'll get posts from.
		 */
		'get_from_same_post_format'   => false,
		'number_same_post_format'     => '',
		'title_same_post_format'      => '',
		'orderby_same_post_format'    => 'date',
		'order_same_post_format'      => 'DESC',
		'offset_same_post_format'     => '',
		'search_same_post_format'     => false,
		'post_type_same_post_format'  => 'post',
		'ptm_spf'                     => '',

		/*
		 * Do not ignore other parameters when changing query on single posts.
		 */
		'dont_ignore_params'          => false,

		/*
		 * Get posts from the current category page.
		 */
		'get_from_cat_page'           => false,
		'number_cat_page'             => '',
		'offset_cat_page'             => '',
		'title_cat_page'              => '',
		'orderby_cat_page'            => 'date',
		'order_cat_page'              => 'DESC',
		'post_type_cat_page'          => 'post',
		'ptm_scp'                     => '',

		/*
		 * Get posts from the current tag page.
		 */
		'get_from_tag_page'           => false,
		'number_tag_page'             => '',
		'offset_tag_page'             => '',
		'title_tag_page'              => '',
		'orderby_tag_page'            => 'date',
		'order_tag_page'              => 'DESC',
		'post_type_tag_page'          => 'post',
		'ptm_stp'                     => '',

		/*
		 * Get posts from the current author page.
		 */
		'get_from_author_page'        => false,
		'number_author_page'          => '',
		'offset_author_page'          => '',
		'title_author_page'           => '',
		'orderby_author_page'         => 'date',
		'order_author_page'           => 'DESC',
		'post_type_author_page'       => 'post',
		'ptm_sap'                     => '',

		/*
		 * Get posts from the current post format page.
		 */
		'get_from_post_format_page'   => false,
		'number_post_format_page'     => '',
		'offset_post_format_page'     => '',
		'title_post_format_page'      => '',
		'orderby_post_format_page'    => 'date',
		'order_post_format_page'      => 'DESC',
		'post_type_post_format_page'  => 'post',
		'ptm_spfp'                    => '',

		/*
		 * Do not ignore other parameters when changing query on archive pages.
		 */
		'dont_ignore_params_page'     => false,

		// Taxonomies.
		'relation'                    => '',
		'taxonomy_aa'                 => '',
		'field_aa'                    => '',
		'terms_aa'                    => '',
		'operator_aa'                 => '',
		'relation_a'                  => '',
		'taxonomy_ab'                 => '',
		'field_ab'                    => '',
		'terms_ab'                    => '',
		'operator_ab'                 => '',
		'taxonomy_ba'                 => '',
		'field_ba'                    => '',
		'terms_ba'                    => '',
		'operator_ba'                 => '',
		'relation_b'                  => '',
		'taxonomy_bb'                 => '',
		'field_bb'                    => '',
		'terms_bb'                    => '',
		'operator_bb'                 => '',

		// Date query.
		'date_year'                   => '',
		'date_month'                  => '',
		'date_week'                   => '',
		'date_day'                    => '',
		'date_hour'                   => '',
		'date_minute'                 => '',
		'date_second'                 => '',
		'date_after_year'             => '',
		'date_after_month'            => '',
		'date_after_day'              => '',
		'date_before_year'            => '',
		'date_before_month'           => '',
		'date_before_day'             => '',
		'date_inclusive'              => false,
		'date_column'                 => '',
		'date_after_dyn_num'          => '',
		'date_after_dyn_date'         => '',
		'date_before_dyn_num'         => '',
		'date_before_dyn_date'        => '',

		// Meta query.
		'mq_relation'                 => '',
		'mq_key_aa'                   => '',
		'mq_value_aa'                 => '',
		'mq_compare_aa'               => '',
		'mq_type_aa'                  => '',
		'mq_relation_a'               => '',
		'mq_key_ab'                   => '',
		'mq_value_ab'                 => '',
		'mq_compare_ab'               => '',
		'mq_type_ab'                  => '',
		'mq_key_ba'                   => '',
		'mq_value_ba'                 => '',
		'mq_compare_ba'               => '',
		'mq_type_ba'                  => '',
		'mq_relation_b'               => '',
		'mq_key_bb'                   => '',
		'mq_value_bb'                 => '',
		'mq_compare_bb'               => '',
		'mq_type_bb'                  => '',

		// Get posts from user login name.
		'get_from_username'           => false,
		'use_categories'              => false,

		/*
		 * Do not ignore other parameters when changing query using current user login name.
		 */
		'dont_ignore_params_username' => false,

		// Posts exclusion.
		'author_not_in'               => '',
		'exclude_current_post'        => false,
		'post_not_in'                 => '',
		'cat_not_in'                  => '', // Category ID, comma separated.
		'tag_not_in'                  => '', // Tag ID, comma separated.
		'post_parent_not_in'          => '',

		// The title of the post.
		'display_title'               => true,
		'link_on_title'               => true,
		'arrow'                       => false,
		'title_length'                => 0,
		'title_length_unit'           => 'words',
		'title_hellipsis'             => true,
		'html_title_type_of'          => 'p',

		// The featured image of the post.
		'display_image'               => false,
		'image_size'                  => 'thumbnail',
		'image_align'                 => 'no_change',
		'image_before_title'          => false,
		'image_link'                  => '',
		'custom_image_url'            => '',
		'custom_img_no_thumb'         => true,
		'image_link_to_post'          => true,

		// The text of the post.
		'excerpt'                     => 'excerpt',
		'exc_length'                  => 20,
		'exc_length_unit'             => 'words',
		'the_more'                    => esc_html__( 'Read more&hellip;', 'posts-in-sidebar' ),
		'exc_arrow'                   => false,

		// Author, date/time and comments.
		'display_author'              => false,
		'author_text'                 => esc_html__( 'By', 'posts-in-sidebar' ),
		'linkify_author'              => false,
		'gravatar_display'            => false,
		'gravatar_size'               => 32,
		'gravatar_default'            => '',
		'gravatar_position'           => 'next_author',
		'display_date'                => false,
		'date_text'                   => esc_html__( 'Published on', 'posts-in-sidebar' ),
		'linkify_date'                => false,
		'date_format'                 => '',
		'display_time'                => false,
		'time_format'                 => '',
		'display_mod_date'            => false,
		'mod_date_text'               => esc_html__( 'Modified on', 'posts-in-sidebar' ),
		'linkify_mod_date'            => false,
		'date_mod_format'             => '',
		'display_mod_time'            => false,
		'time_mod_format'             => '',
		'comments'                    => false,
		'comments_text'               => esc_html__( 'Comments:', 'posts-in-sidebar' ),
		'linkify_comments'            => false,
		'display_comm_num_only'       => false,
		'hide_zero_comments'          => false,
		'utility_sep'                 => '|',
		'utility_after_title'         => false,
		'utility_before_title'        => false,

		// The categories of the post.
		'categories'                  => false,
		'categ_text'                  => esc_html__( 'Category:', 'posts-in-sidebar' ),
		'categ_sep'                   => ',',
		'categ_before_title'          => false,
		'categ_after_title'           => false,

		// The tags of the post.
		'tags'                        => false,
		'tags_text'                   => esc_html__( 'Tags:', 'posts-in-sidebar' ),
		'hashtag'                     => '#',
		'tag_sep'                     => '',
		'tags_before_title'           => false,
		'tags_after_title'            => false,

		// The custom taxonomies of the post.
		'display_custom_tax'          => false,
		'term_hashtag'                => '',
		'term_sep'                    => ',',
		'ctaxs_before_title'          => false,
		'ctaxs_after_title'           => false,

		// The custom field.
		'custom_field_all'            => false,
		'custom_field'                => false,
		'custom_field_txt'            => '',
		'meta'                        => '',
		'custom_field_count'          => '', // In characters.
		'custom_field_hellip'         => '&hellip;',
		'custom_field_key'            => false,
		'custom_field_sep'            => ':',
		'cf_before_title'             => false,
		'cf_after_title'              => false,

		// The link to the archive.
		'archive_link'                => false,
		'link_to'                     => 'category',
		'tax_name'                    => '',
		'tax_term_name'               => '',
		'auto_term_name'              => false,
		// translators: %s is the name of the taxonomy for the archive page link.
		'archive_text'                => esc_html__( 'Display all posts under %s', 'posts-in-sidebar' ),

		// When no posts found.
		'nopost_text'                 => esc_html__( 'No posts yet.', 'posts-in-sidebar' ),
		'hide_widget'                 => false,

		// Styles.
		'margin_unit'                 => 'px',
		'intro_margin'                => null,
		'title_margin'                => null,
		'side_image_margin'           => null,
		'bottom_image_margin'         => null,
		'excerpt_margin'              => null,
		'utility_margin'              => null,
		'categories_margin'           => null,
		'tags_margin'                 => null,
		'terms_margin'                => null,
		'custom_field_margin'         => null,
		'archive_margin'              => null,
		'noposts_margin'              => null,
		'custom_styles'               => '',

		// Extras.
		'list_element'                => 'ul',
		'remove_bullets'              => false,
		'add_wp_post_classes'         => false,

		// Cache.
		'cached'                      => false,
		'cache_time'                  => 3600,
		'widget_id'                   => '',

		// Debug.
		'admin_only'                  => true,
		'debug_query'                 => false,
		'debug_params'                => false,
		'debug_post_id'               => false,

		'shortcode_id'                => '',
	);

	return $defaults;
}

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

	// If $default is not empty, remove any leading and trailing space and dash,
	// transform it into an array using internal spaces, and merge it with $classes.
	if ( ! empty( $default ) ) {
		if ( ! is_array( $default ) ) {
			$default = preg_split( '/[\s]+/', trim( $default, ' -' ) );
		}
		$classes = array_merge( $classes, $default );
	}

	// If $class is not empty, remove any leading and trailing space and dash,
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

	$keys = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT DISTINCT meta_key
			FROM $wpdb->postmeta
			WHERE meta_key NOT BETWEEN '_' AND '_z'
			HAVING meta_key NOT LIKE %s
			ORDER BY meta_key
			LIMIT %d",
			$wpdb->esc_like( '_' ) . '%',
			$limit
		)
	);

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
	if ( 0 === $exc_arrow ) {
		$exc_arrow = false;
	}
	if ( 1 === $exc_arrow ) {
		$exc_arrow = true;
	}
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

/**
 * Chage the widget title.
 *
 * @param array $instance The array containing the widget options.
 * @return string $instance['title'] The changed widget title.
 * @since 4.7.7
 * @since 4.15.0 Added compatibility with Yoast SEO plugin.
 */
function pis_change_widget_title( $instance ) {
	// If $instance is not array, stop the function.
	if ( ! is_array( $instance ) ) {
		return;
	}

	/*
	 * Change the widget title if the user wants a different title in single posts (for same category).
	 *
	 * @since 3.2
	 */
	if ( $instance['get_from_same_cat'] && ! empty( $instance['title_same_cat'] ) && is_single() ) {
		if ( $instance['yoast_main_cat'] && function_exists( 'yoast_get_primary_term_id' ) && false !== yoast_get_primary_term_id( 'category', get_the_ID() ) ) {
			$the_category = explode( ',', yoast_get_primary_term_id( 'category', get_the_ID() ) );
		} else {
			$the_category = wp_get_post_categories( get_the_ID() );
		}
		if ( $the_category ) {
			if ( $instance['sort_categories'] ) {
				sort( $the_category );
			}
			$the_category      = get_category( $the_category[0] );
			$the_category_name = $the_category->name;
			$instance['title'] = $instance['title_same_cat'];
			$instance['title'] = str_replace( '%s', $the_category_name, $instance['title'] );
		}
	}

	/*
	 * Change the widget title if the user wants a different title in single posts (for same tag).
	 *
	 * @since 4.3.0
	 */
	if ( $instance['get_from_same_tag'] && ! empty( $instance['title_same_tag'] ) && is_single() ) {
		$the_tag = wp_get_post_tags( get_the_ID() );
		if ( $the_tag ) {
			if ( $instance['sort_tags'] ) {
				sort( $the_tag );
			}
			$the_tag           = get_tag( $the_tag[0] );
			$the_tag_name      = $the_tag->name;
			$instance['title'] = $instance['title_same_tag'];
			$instance['title'] = str_replace( '%s', $the_tag_name, $instance['title'] );
		}
	}

	/*
	 * Change the widget title if the user wants a different title in single posts (for same author).
	 *
	 * @since 3.5
	 */
	if ( $instance['get_from_same_author'] && ! empty( $instance['title_same_author'] ) && is_single() ) {
		$instance['title'] = $instance['title_same_author'];
		$post_author_id    = get_post_field( 'post_author', get_the_ID() );
		$the_author_name   = get_the_author_meta( 'display_name', $post_author_id );
		$instance['title'] = str_replace( '%s', $the_author_name, $instance['title'] );
	}

	/*
	 * Change the widget title if the user wants a different title
	 * in single posts (for same category/tag using custom fields).
	 *
	 * @since 3.7
	 */
	if ( $instance['get_from_custom_fld'] &&
		isset( $instance['s_custom_field_key'] ) &&
		isset( $instance['s_custom_field_tax'] ) &&
		! empty( $instance['title_custom_field'] ) &&
		is_single() ) {

		// Get the custom field value of the custom field key.
		$the_term_slug = get_post_meta( get_the_ID(), $instance['s_custom_field_key'], true );
		// The functions term_exists() and has_term() seem to work only on slugs (a text value),
		// so let's figure out if the custom field value is a numeric value.
		// If it's numeric...
		if ( is_numeric( $the_term_slug ) ) {
			// ... let's get the term's array of characteristics using its ID...
			$the_term = get_term_by( 'id', $the_term_slug, $instance['s_custom_field_tax'], 'OBJECT' );
			// ... and then the slug.
			$the_term_slug = $the_term->slug;
			// In the meantime get the term's name.
			$the_term_name = $the_term->name;
		} else {
			// If the custom field value is a text value, let's get the term's array of characteristics using its slug...
			$the_term = get_term_by( 'slug', $the_term_slug, $instance['s_custom_field_tax'], 'OBJECT' );
			// ... and then the term's name.
			$the_term_name = $the_term->name;
		}
		// If the term exists and the current post of the main query has this term...
		if ( term_exists( $the_term_slug, $instance['s_custom_field_tax'] ) && has_term( $the_term_slug, $instance['s_custom_field_tax'], get_the_ID() ) ) {
			// ... change the title as required by the user.
			$instance['title'] = $instance['title_custom_field'];
			// Also change the %s into the term name, if required.
			$instance['title'] = str_replace( '%s', wp_strip_all_tags( $the_term_name ), $instance['title'] );
		}
	}

	/*
	 * Change the widget title if the user wants a different title in single posts (for same post format).
	 *
	 * @since 4.8.0
	 */
	if ( $instance['get_from_same_post_format'] && ! empty( $instance['title_same_post_format'] ) && is_single() ) {
		$instance['title'] = $instance['title_same_post_format'];
		$post_format       = get_post_format( get_the_ID() );
		$post_format_name  = get_post_format_string( $post_format );
		$instance['title'] = str_replace( '%s', $post_format_name, $instance['title'] );
	}

	/*
	 * Change the widget title if the user wants a different title in archive page (for same category).
	 *
	 * @since 4.6
	 */
	if ( $instance['get_from_cat_page'] && ! empty( $instance['title_cat_page'] ) && is_category() ) {
		$current_archive_category = get_queried_object();
		$the_category_name        = $current_archive_category->name;
		$instance['title']        = $instance['title_cat_page'];
		$instance['title']        = str_replace( '%s', $the_category_name, $instance['title'] );
	}

	/*
	 * Change the widget title if the user wants a different title in archive page (for same tag).
	 *
	 * @since 4.6
	 */
	if ( $instance['get_from_tag_page'] && ! empty( $instance['title_tag_page'] ) && is_tag() ) {
		$the_tag           = get_queried_object();
		$the_tag_name      = $the_tag->name;
		$instance['title'] = $instance['title_tag_page'];
		$instance['title'] = str_replace( '%s', $the_tag_name, $instance['title'] );
	}

	/*
	 * Change the widget title if the user wants a different title in archive page (for same author).
	 *
	 * @since 4.6
	 */
	if ( $instance['get_from_author_page'] && ! empty( $instance['title_author_page'] ) && is_author() ) {
		$the_author        = get_queried_object();
		$the_author_name   = $the_author->display_name;
		$instance['title'] = $instance['title_author_page'];
		$instance['title'] = str_replace( '%s', $the_author_name, $instance['title'] );
	}

	/*
	 * Change the widget title if the user wants a different title in archive page (for same post format).
	 *
	 * @since 4.8.0
	 */
	if ( $instance['get_from_post_format_page'] && ! empty( $instance['title_post_format_page'] ) && is_tax( 'post_format' ) ) {
		$instance['title'] = $instance['title_post_format_page'];
		$post_format       = get_post_format( get_the_ID() );
		$post_format_name  = get_post_format_string( $post_format );
		$instance['title'] = str_replace( '%s', $post_format_name, $instance['title'] );
	}

	return $instance['title'];
}
