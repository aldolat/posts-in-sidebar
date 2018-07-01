<?php
/**
 * This file contains the shortcode for the plugin
 *
 * @since 3.0
 */

/**
 * Create the shortcode.
 *
 * @example [pissc post_type="page" post_parent_in=4 exclude_current_post=1]
 * @since 3.0
 */
function pis_shortcode( $atts ) {
	extract( shortcode_atts( array(
		// The custom container class
		//'container_class'     => '', /* For widget only */

		// The title of the widget
		//'title'               => esc_html__( 'Posts', 'posts-in-sidebar' ), /* For widget only */
		//'title_link'          => '', /* For widget only */
		//'intro'               => '', /* For widget only */

		// Posts retrieving
		'post_type'           => 'post',    // post, page, attachment, or any custom post type
		'post_type_multiple'  => '',        // A list of post types, comma separated
		'posts_id'            => '',        // Post/Pages IDs, comma separated
		'author'              => '',        // Author nicename
		'author_in'           => '',        // Author IDs
		'posts_by_comments'   => false,     // Boolean. An array of post IDs will be used
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
		'has_password'        => 'null', // Fake content that will be converted later into real null/true/false.
		'post_password'       => '',
		'ignore_sticky'       => false,
		/*
		 * This is the category of the single post
		 * where we'll get posts from.
		 */
		//'get_from_same_cat'   => false, /* For widget only */
		//'number_same_cat'     => '',    /* For widget only */
		//'title_same_cat'      => '',    /* For widget only */
		//'sort_categories'     => false, /* For widget only */
		/*
		 * This is the tag of the single post
		 * where we'll get posts from.
		 */
		//'get_from_same_tag'      => false, /* For widget only */
		//'number_same_tag'        => '',    /* For widget only */
		//'title_same_tag'         => '',    /* For widget only */
		//'sort_tags'              => false, /* For widget only */
		/*
		 * This is the author of the single post
		 * where we'll get posts from.
		 */
		//'get_from_same_author'=> false, /* For widget only */
		//'number_same_author'  => '',    /* For widget only */
		//'title_same_author'   => '',    /* For widget only */
		/*
		 * This is the custom field
		 * to be used when on single post
		 */
		//'get_from_custom_fld' => false, /* For widget only */
		//'s_custom_field_key'  => '',    /* For widget only */
		//'s_custom_field_tax'  => '',    /* For widget only */
		//'number_custom_field' => '',    /* For widget only */
		//'title_custom_field'  => '',    /* For widget only */
		/*
		 * Do not ignore other parameters when changing query on single posts.
		 */
		//'dont_ignore_params'  => false, /* For widget only */

		/*
		 * Get posts from the current category page.
		 */
		//'get_from_cat_page' => false, /* For widget only */
		//'number_cat_page'   => '', /* For widget only */
		//'offset_cat_page'   => '', /* For widget only */
		//'title_cat_page'    => '', /* For widget only */
		/*
		 * Get posts from the current tag page.
		 */
		//'get_from_tag_page' => false, /* For widget only */
		//'number_tag_page'   => '', /* For widget only */
		//'offset_tag_page'   => '', /* For widget only */
		//'title_tag_page'    => '', /* For widget only */
		/*
		 * Get posts from the current author page.
		 */
		//'get_from_author_page' => false, /* For widget only */
		//'number_author_page'   => '', /* For widget only */
		//'offset_author_page'   => '', /* For widget only */
		//'title_author_page'    => '', /* For widget only */
		/*
		 * Do not ignore other parameters when changing query on archive pages.
		 */
		//'dont_ignore_params_page'  => false, /* For widget only */

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

		// Meta query
		'mq_relation'         => '',
		'mq_key_aa'           => '',
		'mq_value_aa'         => '',
		'mq_compare_aa'       => '',
		'mq_type_aa'          => '',
		'mq_relation_a'       => '',
		'mq_key_ab'           => '',
		'mq_value_ab'         => '',
		'mq_compare_ab'       => '',
		'mq_type_ab'          => '',
		'mq_key_ba'           => '',
		'mq_value_ba'         => '',
		'mq_compare_ba'       => '',
		'mq_type_ba'          => '',
		'mq_relation_b'       => '',
		'mq_key_bb'           => '',
		'mq_value_bb'         => '',
		'mq_compare_bb'       => '',
		'mq_type_bb'          => '',

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
		'title_length'        => 0,
		'title_length_unit'   => 'words',
		'title_hellipsis'     => true,

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
		'exc_length'          => 20,
		'exc_length_unit'     => 'words',
		'the_more'            => esc_html__( 'Read more&hellip;', 'posts-in-sidebar' ),
		'exc_arrow'           => false,

		// Author, date/time and comments
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
		'display_time'        => false,
		'display_mod_date'    => false,
		'mod_date_text'       => esc_html__( 'Modified on', 'posts-in-sidebar' ),
		'linkify_mod_date'    => false,
		'display_mod_time'    => false,
		'comments'            => false,
		'comments_text'       => esc_html__( 'Comments:', 'posts-in-sidebar' ),
		'linkify_comments'    => false,
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
		'custom_field_all'    => false,
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
		//'hide_widget'         => false, /* For widget only */

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
		//'custom_styles'       => '', /* For widget only */

		// Extras
		'list_element'        => 'ul',
		'remove_bullets'      => false,
		'add_wp_post_classes' => false,

		// Cache
		//'cached'              => false, /* For widget only */
		//'cache_time'          => 3600,  /* For widget only */
		//'widget_id'           => '',    /* For widget only */

		// Debug
		'admin_only'          => true,
		'debug_query'         => false,
		'debug_params'        => false,
	), $atts ) );

	return do_shortcode( pis_get_posts_in_sidebar( $atts ) );
}
if ( ! shortcode_exists( 'pissc' ) ) {
	add_shortcode('pissc', 'pis_shortcode');
}
