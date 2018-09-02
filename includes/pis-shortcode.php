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
 * @example [pissc post_type="page" post_parent_in=4 exclude_current_post=1]
 * @since 3.0
 */
function pis_shortcode( $atts ) {
	$defaults = array(
		// Posts retrieving.
		'post_type'            => 'post',
		'post_type_multiple'   => '',
		'posts_id'             => '',
		'author'               => '',
		'author_in'            => '',
		'posts_by_comments'    => false,
		'cat'                  => '',
		'tag'                  => '',
		'post_parent_in'       => '',
		'post_format'          => '',
		'number'               => get_option( 'posts_per_page' ),
		'orderby'              => 'date',
		'order'                => 'DESC',
		'offset_number'        => '',
		'post_status'          => 'publish',
		'post_meta_key'        => '',
		'post_meta_val'        => '',
		'search'               => null,
		'has_password'         => 'null',
		'post_password'        => '',
		'ignore_sticky'        => false,

		// Taxonomies.
		'relation'             => '',
		'taxonomy_aa'          => '',
		'field_aa'             => '',
		'terms_aa'             => '',
		'operator_aa'          => '',
		'relation_a'           => '',
		'taxonomy_ab'          => '',
		'field_ab'             => '',
		'terms_ab'             => '',
		'operator_ab'          => '',
		'taxonomy_ba'          => '',
		'field_ba'             => '',
		'terms_ba'             => '',
		'operator_ba'          => '',
		'relation_b'           => '',
		'taxonomy_bb'          => '',
		'field_bb'             => '',
		'terms_bb'             => '',
		'operator_bb'          => '',

		// Date query.
		'date_year'            => '',
		'date_month'           => '',
		'date_week'            => '',
		'date_day'             => '',
		'date_hour'            => '',
		'date_minute'          => '',
		'date_second'          => '',
		'date_after_year'      => '',
		'date_after_month'     => '',
		'date_after_day'       => '',
		'date_before_year'     => '',
		'date_before_month'    => '',
		'date_before_day'      => '',
		'date_inclusive'       => false,
		'date_column'          => '',
		'date_after_dyn_num'   => '',
		'date_after_dyn_date'  => '',
		'date_before_dyn_num'  => '',
		'date_before_dyn_date' => '',

		// Meta query.
		'mq_relation'          => '',
		'mq_key_aa'            => '',
		'mq_value_aa'          => '',
		'mq_compare_aa'        => '',
		'mq_type_aa'           => '',
		'mq_relation_a'        => '',
		'mq_key_ab'            => '',
		'mq_value_ab'          => '',
		'mq_compare_ab'        => '',
		'mq_type_ab'           => '',
		'mq_key_ba'            => '',
		'mq_value_ba'          => '',
		'mq_compare_ba'        => '',
		'mq_type_ba'           => '',
		'mq_relation_b'        => '',
		'mq_key_bb'            => '',
		'mq_value_bb'          => '',
		'mq_compare_bb'        => '',
		'mq_type_bb'           => '',

		// Posts exclusion.
		'author_not_in'        => '',
		'exclude_current_post' => false,
		'post_not_in'          => '',
		'cat_not_in'           => '',
		'tag_not_in'           => '',
		'post_parent_not_in'   => '',

		// The title of the post.
		'display_title'        => true,
		'link_on_title'        => true,
		'arrow'                => false,
		'title_length'         => 0,
		'title_length_unit'    => 'words',
		'title_hellipsis'      => true,

		// The featured image of the post.
		'display_image'        => false,
		'image_size'           => 'thumbnail',
		'image_align'          => 'no_change',
		'image_before_title'   => false,
		'image_link'           => '',
		'custom_image_url'     => '',
		'custom_img_no_thumb'  => true,
		'image_link_to_post'   => true,

		// The text of the post.
		'excerpt'              => 'excerpt',
		'exc_length'           => 20,
		'exc_length_unit'      => 'words',
		'the_more'             => esc_html__( 'Read more&hellip;', 'posts-in-sidebar' ),
		'exc_arrow'            => false,

		// Author, date/time and comments.
		'display_author'       => false,
		'author_text'          => esc_html__( 'By', 'posts-in-sidebar' ),
		'linkify_author'       => false,
		'gravatar_display'     => false,
		'gravatar_size'        => 32,
		'gravatar_default'     => '',
		'gravatar_position'    => 'next_author',
		'display_date'         => false,
		'date_text'            => esc_html__( 'Published on', 'posts-in-sidebar' ),
		'linkify_date'         => false,
		'display_time'         => false,
		'display_mod_date'     => false,
		'mod_date_text'        => esc_html__( 'Modified on', 'posts-in-sidebar' ),
		'linkify_mod_date'     => false,
		'display_mod_time'     => false,
		'comments'             => false,
		'comments_text'        => esc_html__( 'Comments:', 'posts-in-sidebar' ),
		'linkify_comments'     => false,
		'utility_sep'          => '|',
		'utility_after_title'  => false,
		'utility_before_title' => false,

		// The categories of the post.
		'categories'           => false,
		'categ_text'           => esc_html__( 'Category:', 'posts-in-sidebar' ),
		'categ_sep'            => ',',
		'categ_before_title'   => false,
		'categ_after_title'    => false,

		// The tags of the post.
		'tags'                 => false,
		'tags_text'            => esc_html__( 'Tags:', 'posts-in-sidebar' ),
		'hashtag'              => '#',
		'tag_sep'              => '',
		'tags_before_title'    => false,
		'tags_after_title'     => false,

		// The custom taxonomies of the post.
		'display_custom_tax'   => false,
		'term_hashtag'         => '',
		'term_sep'             => ',',
		'ctaxs_before_title'   => false,
		'ctaxs_after_title'    => false,

		// The custom field.
		'custom_field_all'     => false,
		'custom_field'         => false,
		'custom_field_txt'     => '',
		'meta'                 => '',
		'custom_field_count'   => '',
		'custom_field_hellip'  => '&hellip;',
		'custom_field_key'     => false,
		'custom_field_sep'     => ':',
		'cf_before_title'      => false,
		'cf_after_title'       => false,

		// The link to the archive.
		'archive_link'         => false,
		'link_to'              => 'category',
		'tax_name'             => '',
		'tax_term_name'        => '',
		// translators: %s is the name of the taxonomy for the archive page link.
		'archive_text'         => esc_html__( 'Display all posts under %s', 'posts-in-sidebar' ),

		// When no posts found.
		'nopost_text'          => esc_html__( 'No posts yet.', 'posts-in-sidebar' ),

		// Styles.
		'margin_unit'          => 'px',
		'intro_margin'         => null,
		'title_margin'         => null,
		'side_image_margin'    => null,
		'bottom_image_margin'  => null,
		'excerpt_margin'       => null,
		'utility_margin'       => null,
		'categories_margin'    => null,
		'tags_margin'          => null,
		'terms_margin'         => null,
		'custom_field_margin'  => null,
		'archive_margin'       => null,
		'noposts_margin'       => null,

		// Extras.
		'list_element'         => 'ul',
		'remove_bullets'       => false,
		'add_wp_post_classes'  => false,

		// Debug.
		'admin_only'           => true,
		'debug_query'          => false,
		'debug_params'         => false,
	);

	$atts = shortcode_atts( $defaults, $atts );

	return do_shortcode( pis_get_posts_in_sidebar( $atts ) );
}
if ( ! shortcode_exists( 'pissc' ) ) {
	add_shortcode( 'pissc', 'pis_shortcode' );
}
