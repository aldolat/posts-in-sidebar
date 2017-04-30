<?php
/**
 * This file contains the core functions of the plugin
 *
 * The main function is pis_get_posts_in_sidebar( $args )
 * that retrieves the posts based on the parameters
 * chosen by the user.
 *
 * The function pis_posts_in_sidebar( $args ) is a simple
 * function to echo the main one.
 *
 * @since 3.8.2
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
 * The core function.
 *
 * @since 1.0
 * @param mixed $args The options for the main function.
 * @return string The HTML output.
 */
function pis_get_posts_in_sidebar( $args ) {
	$defaults = array(
		// The custom container class
		'container_class'     => '',

		// The title of the widget
		'title'               => esc_html__( 'Posts', 'posts-in-sidebar' ),
		'title_link'          => '',
		'intro'               => '',

		// Posts retrieving
		'post_type'           => 'post',    // post, page, attachment, or any custom post type
		'post_type_multiple'  => '',        // A list of post types, comma separated
		'posts_id'            => '',        // Post/Pages IDs, comma separated
		'author'              => '',        // Author nicename
		'author_in'           => '',        // Author IDs
		'cat'                 => '',        // Category slugs, comma separated
		'tag'                 => '',        // Tag slugs, comma separated
		'post_parent_in'      => '',
		'post_format'         => '',
		'number'              => get_option( 'posts_per_page' ),
		'orderby'             => 'date',
		'order'               => 'DESC',
		'offset_number'       => '',
		'post_status'         => 'publish',
		'post_meta_key'       => '',
		'post_meta_val'       => '',
		/*
		 * The 's' (search) parameter must be not declared or must be empty
		 * otherwise it will break sticky posts.
		 */
		'search'              => NULL,
		'ignore_sticky'       => false,
		/*
		 * This is the category of the single post
		 * where we'll get posts from.
		 */
		'get_from_same_cat'   => false,
		'number_same_cat'     => '',
		'title_same_cat'      => '',
		'dont_ignore_params'  => false,
		'sort_categories'  => false,
		/*
		 * This is the author of the single post
		 * where we'll get posts from.
		 */
		'get_from_same_author'=> false,
		'number_same_author'  => '',
		'title_same_author'   => '',
		/*
		 * This is the custom field
		 * to be used when on single post
		 */
		'get_from_custom_fld' => false,
		's_custom_field_key'  => '',
		's_custom_field_tax'  => '',
		'number_custom_field' => '',
		'title_custom_field'  => '',

		// Taxonomies
		'relation'            => '',

		'taxonomy_aa'         => '',
		'field_aa'            => '',
		'terms_aa'            => '',
		'operator_aa'         => '',

		'relation_a'          => '',

		'taxonomy_ab'         => '',
		'field_ab'            => '',
		'terms_ab'            => '',
		'operator_ab'         => '',

		'taxonomy_ba'         => '',
		'field_ba'            => '',
		'terms_ba'            => '',
		'operator_ba'         => '',

		'relation_b'          => '',

		'taxonomy_bb'         => '',
		'field_bb'            => '',
		'terms_bb'            => '',
		'operator_bb'         => '',

		// Date query
		'date_year'           => '',
		'date_month'          => '',
		'date_week'           => '',
		'date_day'            => '',
		'date_hour'           => '',
		'date_minute'         => '',
		'date_second'         => '',
		'date_after_year'     => '',
		'date_after_month'    => '',
		'date_after_day'      => '',
		'date_before_year'    => '',
		'date_before_month'   => '',
		'date_before_day'     => '',
		'date_inclusive'      => false,
		'date_column'         => '',
		'date_after_dyn_num'  => '',
		'date_after_dyn_date' => '',
		'date_before_dyn_num' => '',
		'date_before_dyn_date'=> '',

		// Posts exclusion
		'author_not_in'       => '',
		'exclude_current_post'=> false,
		'post_not_in'         => '',
		'cat_not_in'          => '',        // Category ID, comma separated
		'tag_not_in'          => '',        // Tag ID, comma separated
		'post_parent_not_in'  => '',

		// The title of the post
		'display_title'       => true,
		'link_on_title'       => true,
		'arrow'               => false,

		// The featured image of the post
		'display_image'       => false,
		'image_size'          => 'thumbnail',
		'image_align'         => 'no_change',
		'image_before_title'  => false,
		'image_link'          => '',
		'custom_image_url'    => '',
		'custom_img_no_thumb' => true,
		'image_link_to_post'  => true,

		// The text of the post
		'excerpt'             => 'excerpt', // can be "full_content", "rich_content", "content", "more_excerpt", "excerpt", "none"
		'exc_length'          => 20,        // In words
		'the_more'            => esc_html__( 'Read more&hellip;', 'posts-in-sidebar' ),
		'exc_arrow'           => false,

		// Author, date and comments
		'display_author'      => false,
		'author_text'         => esc_html__( 'By', 'posts-in-sidebar' ),
		'linkify_author'      => false,
		'gravatar_display'    => false,
		'gravatar_size'       => 32,
		'gravatar_default'    => '',
		'gravatar_position'   => 'next_author',
		'display_date'        => false,
		'date_text'           => esc_html__( 'Published on', 'posts-in-sidebar' ),
		'linkify_date'        => false,
		'display_mod_date'    => false,
		'mod_date_text'       => esc_html__( 'Modified on', 'posts-in-sidebar' ),
		'linkify_mod_date'    => false,
		'comments'            => false,
		'comments_text'       => esc_html__( 'Comments:', 'posts-in-sidebar' ),
		'linkify_comments'    => true,
		'utility_sep'         => '|',
		'utility_after_title' => false,
		'utility_before_title'=> false,

		// The categories of the post
		'categories'          => false,
		'categ_text'          => esc_html__( 'Category:', 'posts-in-sidebar' ),
		'categ_sep'           => ',',
		'categ_before_title'  => false,
		'categ_after_title'   => false,

		// The tags of the post
		'tags'                => false,
		'tags_text'           => esc_html__( 'Tags:', 'posts-in-sidebar' ),
		'hashtag'             => '#',
		'tag_sep'             => '',
		'tags_before_title'  => false,
		'tags_after_title'   => false,

		// The custom taxonomies of the post
		'display_custom_tax'  => false,
		'term_hashtag'        => '',
		'term_sep'            => ',',
		'ctaxs_before_title'  => false,
		'ctaxs_after_title'   => false,

		// The custom field
		'custom_field'        => false,
		'custom_field_txt'    => '',
		'meta'                => '',
		'custom_field_count'  => '',  // In characters.
		'custom_field_hellip' => '&hellip;',
		'custom_field_key'    => false,
		'custom_field_sep'    => ':',
		'cf_before_title'     => false,
		'cf_after_title'      => false,

		// The link to the archive
		'archive_link'        => false,
		'link_to'             => 'category',
		'tax_name'            => '',
		'tax_term_name'       => '',
		'archive_text'        => esc_html__( 'Display all posts under %s', 'posts-in-sidebar' ),

		// When no posts found
		'nopost_text'         => esc_html__( 'No posts yet.', 'posts-in-sidebar' ),
		'hide_widget'         => false,

		// Styles
		'margin_unit'         => 'px',
		'intro_margin'        => NULL,
		'title_margin'        => NULL,
		'side_image_margin'   => NULL,
		'bottom_image_margin' => NULL,
		'excerpt_margin'      => NULL,
		'utility_margin'      => NULL,
		'categories_margin'   => NULL,
		'tags_margin'         => NULL,
		'terms_margin'        => NULL,
		'custom_field_margin' => NULL,
		'archive_margin'      => NULL,
		'noposts_margin'      => NULL,
		'custom_styles'       => '',

		// Extras
		'list_element'        => 'ul',
		'remove_bullets'      => false,

		// Cache
		'cached'              => false,
		'cache_time'          => 3600,
		'widget_id'           => '',

		// Debug
		'admin_only'          => true,
		'debug_query'         => false,
		'debug_params'        => false,

	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	/*
	 * Remove empty items from the $args array.
	 * This is performed only to produce a cleaner output if debug is on.
	 *
	 * @since 3.8.6
	 */
	$args = pis_array_remove_empty_keys( $args, true );

	/*
	 * Check if $author or $cat or $tag are equal to 'NULL' (string).
	 * If so, make them empty.
	 * For more informations, see inc/posts-in-sidebar-widget.php, function update().
	 *
	 * @since 1.28
	 */
	if ( 'NULL' == $author ) $author = '';
	if ( 'NULL' == $cat ) $cat = '';
	if ( 'NULL' == $tag ) $tag = '';

	/*
	 * Some params accept only an array.
	 */
	if ( $posts_id            && ! is_array( $posts_id ) )            $posts_id            = explode( ',', $posts_id );            else $posts_id    = array();
	if ( $post_not_in         && ! is_array( $post_not_in ) )         $post_not_in         = explode( ',', $post_not_in );         else $post_not_in = array();
	if ( $cat_not_in          && ! is_array( $cat_not_in ) )          $cat_not_in          = explode( ',', $cat_not_in );          else $cat_not_in  = array();
	if ( $tag_not_in          && ! is_array( $tag_not_in ) )          $tag_not_in          = explode( ',', $tag_not_in );          else $tag_not_in  = array();
	if ( $author_in           && ! is_array( $author_in ) )           $author_in           = explode( ',', $author_in );           else $author_in   = array();
	if ( $author_not_in       && ! is_array( $author_not_in ) )       $author_not_in       = explode( ',', $author_not_in );       else $author_not_in   = array();
	if ( $post_parent_in      && ! is_array( $post_parent_in ) )      $post_parent_in      = explode( ',', $post_parent_in );      else $post_parent_in  = array();
	if ( $post_parent_not_in  && ! is_array( $post_parent_not_in ) )  $post_parent_not_in  = explode( ',', $post_parent_not_in );  else $post_parent_not_in  = array();

	/*
	 * Build $tax_query parameter (if any).
	 * It must be an array of array.
	 *
	 * @since 1.29
	 */
	$tax_query = pis_tax_query( array(
		'relation'    => $relation,
		'taxonomy_aa' => $taxonomy_aa,
		'field_aa'    => $field_aa,
		'terms_aa'    => $terms_aa,
		'operator_aa' => $operator_aa,
		'relation_a'  => $relation_a,
		'taxonomy_ab' => $taxonomy_ab,
		'field_ab'    => $field_ab,
		'terms_ab'    => $terms_ab,
		'operator_ab' => $operator_ab,
		'taxonomy_ba' => $taxonomy_ba,
		'field_ba'    => $field_ba,
		'terms_ba'    => $terms_ba,
		'operator_ba' => $operator_ba,
		'relation_b'  => $relation_b,
		'taxonomy_bb' => $taxonomy_bb,
		'field_bb'    => $field_bb,
		'terms_bb'    => $terms_bb,
		'operator_bb' => $operator_bb,
	) );

	/*
	 * Build the array for date query.
	 * It must be an array of array.
	 *
	 * @since 1.29
	 */
	$date_query = array(
		array(
			'year'      => $date_year,
			'month'     => $date_month,
			'week'      => $date_week,
			'day'       => $date_day,
			'hour'      => $date_hour,
			'minute'    => $date_minute,
			'second'    => $date_second,
			'after'     => array (
				'year'  => $date_after_year,
				'month' => $date_after_month,
				'day'   => $date_after_day,
			),
			'before'    => array (
				'year'  => $date_before_year,
				'month' => $date_before_month,
				'day'   => $date_before_day,
			),
			'inclusive' => $date_inclusive,
			'column'    => $date_column,
		)
	);
	/*
	 * The following function is necessary to make empty the $date_query array if date/time values are empty.
	 * Starting from 3.8.6 this action is performed later, before creating a new WP_Query object.
	 * For this reason it is commented out here starting from 3.8.6 version.
	 */
	// $date_query = pis_array_remove_empty_keys( $date_query, true );

	/*
	 * Get posts published after/before a certain amount of time ago.
	 * In this case we can use an expression like "1 month ago".
	 *
	 * @since 3.8.6
	 */
	if ( $date_after_dyn_num && $date_after_dyn_date ) {
		$date_query[0]['after'] = $date_after_dyn_num . ' ' . $date_after_dyn_date . ' ago';
	}
	if ( $date_before_dyn_num && $date_before_dyn_date ) {
		$date_query[0]['before'] = $date_before_dyn_num . ' ' . $date_before_dyn_date . ' ago';
	}

	/*
	 * If in a single post or in a page, get the ID of the post of the main loop.
	 * This will be used for:
	 * - excluding the current post from the query;
	 * - getting the category of the current post;
	 * - adding the "current-post" CSS class.
	 *
	 * About is_singular() and is_single() functions.
	 * is_singular() => is true when any post type is displayed (regular post, custom post type, page, attachment).
	 * is_single()   => is true when any post type is displayed, except page and attachment.
	 *
	 * @see https://developer.wordpress.org/reference/functions/is_singular/
	 * @see https://developer.wordpress.org/reference/functions/is_single/
	 */
	if ( is_singular() ) {
		$single_post_id = get_the_ID();
	}

	/*
	 * Exclude the current post from the query.
	 * This will be used in case the user does not want to display the same post in the main body and in the sidebar.
	 */
	if ( is_singular() && $exclude_current_post ) {

		/*
		 * First case.
		 * Add the current post ID to the $post_not_in array.
		 */
		if ( ! in_array( $single_post_id, $post_not_in ) ) {
			$post_not_in[] = $single_post_id;
		}

		/*
		 * Second case.
		 * If the user has specified a list of posts to get, the $post_not_in array is ignored by WordPress (see link below).
		 * So let's modify this behaviour.
		 *
		 * @see https://codex.wordpress.org/Class_Reference/WP_Query#Post_.26_Page_Parameters
		 * @since 3.8.1
		 */
		if ( in_array( $single_post_id, $posts_id ) ) {
			$single_post_id_arr = array();
			$single_post_id_arr[] = $single_post_id;
			$posts_id = array_diff( $posts_id, $single_post_id_arr );
		}
	}

	/*
	 * If $post_type is 'attachment', $post_status must be 'inherit'.
	 *
	 * @see https://codex.wordpress.org/Class_Reference/WP_Query#Type_Parameters
	 * @since 1.28
	 */
	if ( 'attachment' == $post_type ) {
		$post_status = 'inherit';
	}

	/*
	 * If $author_in is not empy, make $author empty,
	 * otherwise WordPress will use $author.
	 *
	 * @since 3.0
	 */
	if ( ! empty( $author_in ) ) $author = '';

	/*
	 * Verify if the user wants multiple post types.
	 * If $post_type_multiple is not empty, change $post_type content into an array
	 * with the content of $post_type_multiple.
	 *
	 * @since 3.8.8
	 */
	if ( ! empty( $post_type_multiple ) ) {
		$post_type_multiple = explode( ', ', $post_type_multiple );
		$post_type = (array) $post_type_multiple;
	}

	// Build the array for WP_Query object.
	$params = array(
		'post_type'           => $post_type,   // Uses a string with a single slug or an array of multiple slugs
		'post__in'            => $posts_id,    // Uses ids
		'author_name'         => $author,      // Uses nicenames
		'author__in'          => $author_in,   // Uses ids
		'category_name'       => $cat,         // Uses category slugs
		'tag'                 => $tag,         // Uses tag slugs
		'tax_query'           => $tax_query,   // Uses an array of array
		'date_query'          => $date_query,  // Uses an array of array
		'post_parent__in'     => $post_parent_in,
		'post_format'         => $post_format,
		'posts_per_page'      => $number,
		'orderby'             => $orderby,
		'order'               => $order,
		'author__not_in'      => $author_not_in,
		'post__not_in'        => $post_not_in,        // Uses ids
		'category__not_in'    => $cat_not_in,         // Uses ids
		'tag__not_in'         => $tag_not_in,         // uses ids
		'post_parent__not_in' => $post_parent_not_in, // uses ids
		'offset'              => $offset_number,
		'post_status'         => $post_status,
		'meta_key'            => $post_meta_key,
		'meta_value'          => $post_meta_val,
		's'                   => $search,
		'ignore_sticky_posts' => $ignore_sticky,
	);

	/*
	 * Remove empty items from the $params array.
	 * This is necessary for some parts of WP_Query (like dates)
	 * and will produce a cleaner output if debug is on.
	 *
	 * @since 3.8.6
	 */
	$params = pis_array_remove_empty_keys( $params, true );

	/*
	 * Check if the user wants to display posts from the same category of the single post.
	 * The parameters for excluding posts (like "post__not_in") will be left active.
	 * This will work in single (regular) posts only, not in custom post types.
	 *
	 * The category used will be the first in the array ($post_categories[0]), i.e. the category with the lowest ID.
	 * In the permalink WordPress uses the category with the lowest ID and we want to use this.
	 * On the contrary, if we get the list of posts categories, these are returned by WordPress in an aphabetically ordered array,
	 * where the lowest key ID has not always the category used in the permalink.
	 *
	 * @since 3.2
	 */
	if ( isset( $get_from_same_cat ) && $get_from_same_cat && is_singular( 'post' ) ) {
		// Set the post_type.
		$params['post_type'] = 'post';

		// Set the number of posts
		if ( isset( $number_same_cat ) && ! empty( $number_same_cat ) ) {
			$params['posts_per_page'] = $number_same_cat;
		}

		// Set the category.
		$post_categories = wp_get_post_categories( $single_post_id );
		// Sort the categories of the post in ascending order, so to use the category used by WordPress in the permalink.
		if ( $sort_categories ) {
			sort( $post_categories );
		}
		$the_category = get_category( $post_categories[0] );
		$params['category_name'] = $the_category->slug;

		// Reset other parameters. The user can choose not to reset them.
		if ( ! $dont_ignore_params ) {
			$params['post__in']        = '';
			$params['author_name']     = '';
			$params['author__in']      = '';
			$params['tag']             = '';
			$params['tax_query']       = '';
			$params['date_query']      = '';
			$params['post_parent__in'] = '';
			$params['post_format']     = '';
			$params['meta_key']        = '';
			$params['meta_value']      = '';
		}
	}

	/*
	 * Check if the user wants to display posts from the same author of the single post.
	 * The parameters for excluding posts (like "post__not_in") will be left active.
	 * This will work in single (regular) posts only, not in custom post types.
	 *
	 * @since 3.5
	 */
	if ( isset( $get_from_same_author ) && $get_from_same_author && is_singular( 'post' ) ) {
		// Set the post_type.
		$params['post_type'] = 'post';

		// Set the number of posts
		if ( isset( $number_same_author ) && ! empty( $number_same_author ) ) {
			$params['posts_per_page'] = $number_same_author;
		}

		// Set the authors
		$the_author_id = get_post_field( 'post_author', $single_post_id );
		$params['author__in'] = explode( ',', $the_author_id );

		// Reset other parameters. The user can choose not to reset them.
		if ( ! $dont_ignore_params ) {
			$params['post__in']        = '';
			$params['author_name']     = '';
			$params['category_name']   = '';
			$params['tag']             = '';
			$params['tax_query']       = '';
			$params['date_query']      = '';
			$params['post_parent__in'] = '';
			$params['post_format']     = '';
			$params['meta_key']        = '';
			$params['meta_value']      = '';
		}
	}

	/*
	 * Check if, when on single post, the user wants to display posts from a certain category chosen by the user using custom field.
	 * The parameters for excluding posts (like "post__not_in") will be left active.
	 * This will work in single (regular) posts only, not in custom post types.
	 *
	 * This piece of code will see if the current (main) post has a custom field defined in the widget panel.
	 * If true, the code will change the query parameters for category/tag using the custom field value as taxonomy term.
	 *
	 * @since 3.7
	 */
	if ( isset( $get_from_custom_fld ) && $get_from_custom_fld && is_singular( 'post' ) ) {
		if ( isset( $s_custom_field_key ) && isset( $s_custom_field_tax ) ) {
			$taxonomy_name = get_post_meta( $single_post_id, $s_custom_field_key, true );
			/**
			 * Convert ID of the taxonomy into the relevant slug.
			 *
			 * @since 3.8.3
			 */
			if ( is_numeric( $taxonomy_name ) ) {
				$taxonomy_identity = get_term_by( 'id', $taxonomy_name, $s_custom_field_tax );
				$taxonomy_name = $taxonomy_identity->slug;
			}
			if ( term_exists( $taxonomy_name, $s_custom_field_tax ) && has_term( $taxonomy_name, $s_custom_field_tax, $single_post_id ) ) {
				if ( 'category' == $s_custom_field_tax ) {
					$params['category_name'] = $taxonomy_name;
				} elseif ( 'post_tag' == $s_custom_field_tax ) {
					$params['tag'] = $taxonomy_name;
				}

				// Set the post_type.
				$params['post_type'] = 'post';

				// Set the number of posts
				if ( isset( $number_custom_field ) && ! empty( $number_custom_field ) ) {
					$params['posts_per_page'] = $number_custom_field;
				}

				// Reset other parameters. The user can choose not to reset them.
				if ( ! $dont_ignore_params ) {
					$params['post__in']        = '';
					$params['author_name']     = '';
					$params['author__in']      = '';
					$params['tax_query']       = '';
					$params['date_query']      = '';
					$params['post_parent__in'] = '';
					$params['post_format']     = '';
					$params['meta_key']        = '';
					$params['meta_value']      = '';
				}
			}
		}
	}

	// If the user has chosen a cached version of the widget output...
	if ( $cached ) {
		// Get the cached query
		$pis_query = get_transient( $widget_id . '_query_cache' );
		// If it does not exist, create a new query and cache it for future uses
		if ( ! $pis_query ) {
			$pis_query = new WP_Query( $params );
			set_transient( $widget_id . '_query_cache', $pis_query, $cache_time );
		}
	// ... otherwise serve a not-cached version of the output.
	} else {
		$pis_query = new WP_Query( $params );
	}

	/*
	 * Define the main variable that will concatenate all the output;
	 *
	 * @since 3.0
	 */
	$pis_output = '';

	/* The Loop */
	if ( $pis_query->have_posts() ) : ?><?php
		if ( $intro ) {
			$pis_output = '<p ' . pis_paragraph( $intro_margin, $margin_unit, 'pis-intro', 'pis_intro_class' ) . '>' . pis_break_text( $intro ) . '</p>';
		}

		// When updating from 1.14, the $list_element variable is empty.
		if ( ! $list_element ) $list_element = 'ul';

		if ( $remove_bullets && 'ul' == $list_element ) {
			$bullets_style = ' style="list-style-type:none; margin-left:0; padding-left:0;"';
		} else {
			$bullets_style = '';
		}
		$pis_output .= '<' . $list_element . ' ' . pis_class( 'pis-ul', apply_filters( 'pis_ul_class', '' ), false ) . $bullets_style . '>';

			while ( $pis_query->have_posts() ) : $pis_query->the_post(); ?><?php

				if ( 'private' == get_post_status() && ! current_user_can( 'read_private_posts' ) ) {
					$pis_output .= '';
				} else { ?><?php

					/*
					 * Assign the class 'current-post' if this is the post of the main loop.
					 *
					 * @since 1.6
					 */
					$current_post_class = '';
					if ( is_singular() && $single_post_id == $pis_query->post->ID ) {
						$current_post_class = ' current-post';
					}

					/*
					 * Assign the class 'sticky' if the post is sticky.
					 *
					 * @since 1.25
					 */
					$sticky_class = '';
					 if ( is_sticky() ) {
						$sticky_class = ' sticky';
					}

					/*
					 * Assign the class 'private' if the post is private.
					 *
					 * @since 3.0.1
					 */
					$private_class = '';
					if ( 'private' == get_post_status() ) {
						$private_class = ' private';
					}

					$pis_output .= '<li ' . pis_class( 'pis-li' . $current_post_class . $sticky_class . $private_class, apply_filters( 'pis_li_class', '' ), false ) . '>';

						// Define the containers for single sections to be concatenated later
						$pis_thumbnail_content    = '';
						$pis_title_content        = '';
						$pis_text_content         = '';
						$pis_utility_content      = '';
						$pis_categories_content   = '';
						$pis_tags_content         = '';
						$pis_custom_tax_content   = '';
						$pis_custom_field_content = '';

						/* The thumbnail */
						$pis_thumbnail_content .= pis_the_thumbnail( array(
							'image_align'         => $image_align,
							'side_image_margin'   => $side_image_margin,
							'bottom_image_margin' => $bottom_image_margin,
							'margin_unit'         => $margin_unit,
							'pis_query'           => $pis_query,
							'image_size'          => $image_size,
							'thumb_wrap'          => true,
							'custom_image_url'    => $custom_image_url,
							'custom_img_no_thumb' => $custom_img_no_thumb,
							'post_type'           => $post_type,
							'image_link'          => $image_link,
							'image_link_to_post'  => $image_link_to_post,
						) );

						/* The title */
						$pis_title_content .= pis_the_title( array(
							'title_margin'      => $title_margin,
							'margin_unit'       => $margin_unit,
							'gravatar_display'  => $gravatar_display,
							'gravatar_position' => $gravatar_position,
							'gravatar_author'   => get_the_author_meta( 'ID' ),
							'gravatar_size'     => $gravatar_size,
							'gravatar_default'  => $gravatar_default,
							'link_on_title'     => $link_on_title,
							'arrow'             => $arrow,
						) );

						/*
						 * The post content.
						 * The content of the post (i.e. the text and/or the post thumbnail)
						 * should be displayed if one of these conditions are true:
						 * - The $post_type is an attachment;
						 * - The post has a thumbnail or a custom image;
						 * - The $excerpt variable is different from 'none'.
						 */
						if ( 'attachment' == $post_type || ( $display_image && ( has_post_thumbnail() || $custom_image_url ) ) || 'none' != $excerpt ) :
							// Prepare the variable $pis_the_text to contain the text of the post
							$pis_the_text = pis_the_text( array(
								'excerpt'    => $excerpt,
								'pis_query'  => $pis_query,
								'exc_length' => $exc_length,
								'the_more'   => $the_more,
								'exc_arrow'  => $exc_arrow,
							) );

							// If the the text of the post is empty or the user does not want to display the image, hide the HTML p tag
							if ( ! empty( $pis_the_text ) || $display_image )
							$pis_text_content .= '<p ' . pis_paragraph( $excerpt_margin, $margin_unit, 'pis-excerpt', 'pis_excerpt_class' ) . '>';

								if ( $display_image && ! $image_before_title ) {
									/* The thumbnail */
									if ( 'attachment' == $post_type || has_post_thumbnail() || $custom_image_url ) {
										$pis_text_content .= pis_the_thumbnail( array(
											'image_align'         => $image_align,
											'side_image_margin'   => $side_image_margin,
											'bottom_image_margin' => $bottom_image_margin,
											'margin_unit'         => $margin_unit,
											'pis_query'           => $pis_query,
											'image_size'          => $image_size,
											'thumb_wrap'          => false,
											'custom_image_url'    => $custom_image_url,
											'custom_img_no_thumb' => $custom_img_no_thumb,
											'post_type'           => $post_type,
											'image_link'          => $image_link,
											'image_link_to_post'  => $image_link_to_post,
										) );
									} // Close if has_post_thumbnail
								}
								// Close if $display_image

								/* The Gravatar */
								if ( $gravatar_display && 'next_post' == $gravatar_position ) {
									$pis_text_content .= pis_get_gravatar( array(
										'author'  => get_the_author_meta( 'ID' ),
										'size'    => $gravatar_size,
										'default' => $gravatar_default
									) );
								}
								// Close The Gravatar

								/* The text */
								$pis_text_content .= $pis_the_text;

							if ( ! empty( $pis_the_text ) || $display_image )
							$pis_text_content .= '</p>';
						endif;
						// Close the post content

						/* The author, the date and the comments */
						$pis_utility_content .= pis_utility_section( array(
							'display_author'    => $display_author,
							'display_date'      => $display_date,
							'display_mod_date'  => $display_mod_date,
							'comments'          => $comments,
							'utility_margin'    => $utility_margin,
							'margin_unit'       => $margin_unit,
							'author_text'       => $author_text,
							'linkify_author'    => $linkify_author,
							'utility_sep'       => $utility_sep,
							'date_text'         => $date_text,
							'linkify_date'      => $linkify_date,
							'mod_date_text'     => $mod_date_text,
							'linkify_mod_date'  => $linkify_mod_date,
							'comments_text'     => $comments_text,
							'pis_post_id'       => $pis_query->post->ID,
							'link_to_comments'  => $linkify_comments,
							'gravatar_display'  => $gravatar_display,
							'gravatar_position' => $gravatar_position,
							'gravatar_author'   => get_the_author_meta( 'ID' ),
							'gravatar_size'     => $gravatar_size,
							'gravatar_default'  => $gravatar_default,
						) );

						/* The categories */
						$pis_categories_content .= pis_the_categories( array(
							'post_id'           => $pis_query->post->ID,
							'categ_sep'         => $categ_sep,
							'categories_margin' => $categories_margin,
							'margin_unit'       => $margin_unit,
							'categ_text'        => $categ_text,
						) );

						/* The tags */
						$pis_tags_content .= pis_the_tags( array(
							'post_id'     => $pis_query->post->ID,
							'hashtag'     => $hashtag,
							'tag_sep'     => $tag_sep,
							'tags_margin' => $tags_margin,
							'margin_unit' => $margin_unit,
							'tags_text'   => $tags_text,
						) );

						/* The custom taxonomies */
						$pis_custom_tax_content .= pis_custom_taxonomies_terms_links( array(
							'postID'       => $pis_query->post->ID,
							'term_hashtag' => $term_hashtag,
							'term_sep'     => $term_sep,
							'terms_margin' => $terms_margin,
							'margin_unit'  => $margin_unit,
						) );

						/* The post meta */
						$pis_custom_field_content .= pis_custom_field( array(
							'post_id'             => $pis_query->post->ID,
							'meta'                => $meta,
							'custom_field_txt'    => $custom_field_txt,
							'custom_field_key'    => $custom_field_key,
							'custom_field_sep'    => $custom_field_sep,
							'custom_field_count'  => $custom_field_count,
							'custom_field_hellip' => $custom_field_hellip,
							'custom_field_margin' => $custom_field_margin,
							'margin_unit'         => $margin_unit,
						) );

						// Concatenate the variables
						if ( $display_image && $image_before_title )                                $pis_output .= $pis_thumbnail_content;
						if ( $utility_before_title )                                                $pis_output .= $pis_utility_content;
						if ( $categories && $categ_before_title )                                   $pis_output .= $pis_categories_content;
						if ( $tags && $tags_before_title )                                          $pis_output .= $pis_tags_content;
						if ( $display_custom_tax && $ctaxs_before_title )                           $pis_output .= $pis_custom_tax_content;
						if ( $custom_field && $cf_before_title )                                    $pis_output .= $pis_custom_field_content;

						if ( $display_title )                                                       $pis_output .= $pis_title_content;

						if ( $utility_after_title )                                                 $pis_output .= $pis_utility_content;
						if ( $categories && $categ_after_title )                                    $pis_output .= $pis_categories_content;
						if ( $tags && $tags_after_title )                                           $pis_output .= $pis_tags_content;
						if ( $display_custom_tax && $ctaxs_after_title )                            $pis_output .= $pis_custom_tax_content;
						if ( $custom_field && $cf_after_title )                                     $pis_output .= $pis_custom_field_content;

						if ( ! post_password_required() )                                           $pis_output .= $pis_text_content;

						if ( ! $utility_before_title && ! $utility_after_title )                    $pis_output .= $pis_utility_content;
						if ( $categories && ! $categ_before_title && ! $categ_after_title )         $pis_output .= $pis_categories_content;
						if ( $tags && ! $tags_before_title && ! $tags_after_title )                 $pis_output .= $pis_tags_content;
						if ( $display_custom_tax && ! $ctaxs_before_title && ! $ctaxs_after_title ) $pis_output .= $pis_custom_tax_content;
						if ( $custom_field && ! $cf_before_title && ! $cf_after_title )             $pis_output .= $pis_custom_field_content;

					$pis_output .= '</li>';
					// Close li

				}
				// Close if private and current user can't read private posts.

			endwhile;
			// Close while

		$pis_output .= '</' . $list_element . '>';

		/* The link to the entire archive */
		if ( $archive_link ) {
			$pis_output .= pis_archive_link( array(
				'link_to'        => $link_to,
				'tax_name'       => $tax_name,
				'tax_term_name'  => $tax_term_name,
				'archive_text'   => $archive_text,
				'archive_margin' => $archive_margin,
				'margin_unit'    => $margin_unit
			) );
		} ?><?php
	// If we have no posts yet
	else :

		if ( $nopost_text ) {
			$pis_output .= '<p ' . pis_paragraph( $noposts_margin, $margin_unit, 'pis-noposts noposts', 'pis_noposts_class' ) . '>';
				$pis_output .= $nopost_text;
			$pis_output .= '</p>';
		}
		if ( $hide_widget ) {
			$pis_output .= '<style type="text/css">#' . $widget_id . ' { display: none; }</style>';
		}

	endif;

	/* Debugging */
	$pis_output .= pis_debug( array(
		'admin_only'         => $admin_only,           // bool   If display debug informations to admin only.
		'debug_query'        => $debug_query,          // bool   If display the parameters for the query.
		'debug_params'       => $debug_params,         // bool   If display the complete set of parameters of the widget.
		'params'             => $params,               // array  The parameters for the query.
		'args'               => $args,                 // array  The complete set of parameters of the widget.
		'cached'             => $cached,               // bool   If the cache is active.
	) );

	// Prints the version of Posts in Sidebar and if the cache is active
	$pis_output .= pis_generated( $cached );

	// Reset the custom query
	wp_reset_postdata();

	return $pis_output;
}


/**
 * The main function to echo the output.
 *
 * @uses get_pis_posts_in_sidebar()
 * @param mixed $args The options for the main function.
 * @since 3.0
 */
function pis_posts_in_sidebar( $args ) {
	echo pis_get_posts_in_sidebar( $args );
}
