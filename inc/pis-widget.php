<?php
/**
 * This file contains the functions for the widget
 *
 * @since 1.0
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
 * Create the widget.
 *
 * @package PostsInSidebar
 * @since 1.0
 */

class PIS_Posts_In_Sidebar extends WP_Widget {

	/**
	 * Create the widget's base settings.
	 * Uses PHP5 constructor method.
	 *
	 * @see https://developer.wordpress.org/reference/classes/wp_widget/__construct/
	 * @since 1.0
	 */
	public function __construct() {

		/* Widget settings. */
		$widget_ops = array(
			'classname'   => 'posts-in-sidebar',
			'description' => __( 'Display a list of posts in a widget', 'posts-in-sidebar' ),
		);

		/* Widget control settings. */
		$control_ops = array(
			'width'   => 600,
			'id_base' => 'pis_posts_in_sidebar',
		);

		/* The PHP5 widget contructor */
		parent::__construct(
			'pis_posts_in_sidebar',
			__( 'Posts in Sidebar', 'posts-in-sidebar' ),
			$widget_ops,
			$control_ops
		);
	}

	/**
	 * Display the content of the widget in the front-end.
	 *
	 * @param array $args
	 * @param array $instance
	 * @since 1.0
	 */
	public function widget( $args, $instance ) {
		/*
		 * Extract $args array keys into single variables.
		 * Some of these are:
		 * 		$args['before_widget']
		 * 		$args['after_widget']
		 * 		$args['before_title']
		 * 		$args['after_title']
		 *
		 * @since 1.0
		 */
		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );

		/*
		* Change the widget title if the user wants a different title in single posts (for same category).
		*
		* @since 3.2
		*/
		if ( isset( $instance['get_from_same_cat'] ) && $instance['get_from_same_cat'] && isset( $instance['title_same_cat'] ) && ! empty( $instance['title_same_cat'] ) && is_singular( 'post' ) ) {
			$title = $instance['title_same_cat'];
			$the_category = get_the_category( get_the_ID() );
			$the_category_name = $the_category[0]->name;
			$title = str_replace( '%s', $the_category_name, $title );
		}

		/*
		* Change the widget title if the user wants a different title in single posts (for same author).
		*
		* @since 3.5
		*/
		if ( isset( $instance['get_from_same_author'] ) && $instance['get_from_same_author'] && isset( $instance['title_same_author'] ) && ! empty( $instance['title_same_author'] ) && is_singular( 'post' ) ) {
			$title = $instance['title_same_author'];
			$post_author_id = get_post_field( 'post_author', get_the_ID() );
			$the_author_name = get_the_author_meta( 'display_name', $post_author_id );
			$title = str_replace( '%s', $the_author_name, $title );
		}

		/*
		* Change the widget title if the user wants a different title in single posts (for for same category/tag using custom fields).
		*
		* @since 3.7
		*/
		if ( isset( $instance['get_from_custom_fld'] ) &&
			 $instance['get_from_custom_fld'] &&
			 isset( $instance['s_custom_field_key'] ) &&
			 isset( $instance['s_custom_field_tax'] )  &&
			 isset( $instance['title_custom_field'] ) &&
			 ! empty( $instance['title_custom_field'] ) &&
			 is_singular( 'post' ) ) {

			$taxonomy_name = get_post_meta( get_the_ID(), $instance['s_custom_field_key'], true );
			if ( term_exists( $taxonomy_name, $instance['s_custom_field_tax'] ) && has_term( $taxonomy_name, $instance['s_custom_field_tax'], get_the_ID() ) ) {
				$title = $instance['title_custom_field'];
				$the_category = get_term_by( 'slug', $taxonomy_name, $instance['s_custom_field_tax'], 'OBJECT' );
				$the_category_name = $the_category->name;
				$title = str_replace( '%s', strip_tags( $the_category_name ), $title );
			}
		}

		echo '<!-- Start Posts in Sidebar - ' . $widget_id . ' -->';

		echo $before_widget;

		// Add a new container if the "Container Class" is not empty
		if ( isset( $instance['container_class'] ) && ! empty( $instance['container_class'] ) ) {
			echo '<div class="' . $instance['container_class'] . '">';
		}

		if ( $title && isset( $instance['title_link'] ) && ! empty( $instance['title_link'] ) ) {
			echo $before_title . '<a class="pis-title-link" href="' . esc_url( $instance['title_link'] ) . '">' . $title . '</a>' . $after_title;
		} elseif ( $title ) {
			echo $before_title . $title . $after_title;
		}

		/*
		 * Check for non-existent values in the database.
		 * This avoids PHP notices.
		 */
		if ( ! isset( $instance['intro'] ) )                $instance['intro']                = '';
		if ( ! isset( $instance['post_type'] ) )            $instance['post_type']            = 'post';
		if ( ! isset( $instance['posts_id'] ) )             $instance['posts_id']             = '';
		if ( ! isset( $instance['author_in'] ) )            $instance['author_in']            = '';
		if ( ! isset( $instance['post_parent_in'] ) )       $instance['post_parent_in']       = '';
		if ( ! isset( $instance['post_format'] ) )          $instance['post_format']          = '';
		if ( ! isset( $instance['search'] ) )               $instance['search']               = NULL;
		if ( ! isset( $instance['get_from_same_cat'] ) )    $instance['get_from_same_cat']    = false;
		if ( ! isset( $instance['number_same_cat'] ) )      $instance['number_same_cat']      = '';
		if ( ! isset( $instance['title_same_cat'] ) )       $instance['title_same_cat']       = '';
		if ( ! isset( $instance['get_from_same_author'] ) ) $instance['get_from_same_author'] = false;
		if ( ! isset( $instance['number_same_author'] ) )   $instance['number_same_author']   = '';
		if ( ! isset( $instance['title_same_author'] ) )    $instance['title_same_author']    = '';
		if ( ! isset( $instance['get_from_custom_fld'] ) )  $instance['get_from_custom_fld']  = false;
		if ( ! isset( $instance['s_custom_field_key'] ) )   $instance['s_custom_field_key']   = '';
		if ( ! isset( $instance['s_custom_field_tax'] ) )   $instance['s_custom_field_tax']   = '';
		if ( ! isset( $instance['number_custom_field'] ) )  $instance['number_custom_field']  = '';
		if ( ! isset( $instance['title_custom_field'] ) )   $instance['title_custom_field']   = '';
		if ( ! isset( $instance['relation'] ) )             $instance['relation']             = '';
		if ( ! isset( $instance['taxonomy_aa'] ) )          $instance['taxonomy_aa']          = '';
		if ( ! isset( $instance['field_aa'] ) )             $instance['field_aa']             = 'slug';
		if ( ! isset( $instance['terms_aa'] ) )             $instance['terms_aa']             = '';
		if ( ! isset( $instance['operator_aa'] ) )          $instance['operator_aa']          = 'IN';
		if ( ! isset( $instance['relation_a'] ) )           $instance['relation_a']           = '';
		if ( ! isset( $instance['taxonomy_ab'] ) )          $instance['taxonomy_ab']          = '';
		if ( ! isset( $instance['field_ab'] ) )             $instance['field_ab']             = 'slug';
		if ( ! isset( $instance['terms_ab'] ) )             $instance['terms_ab']             = '';
		if ( ! isset( $instance['operator_ab'] ) )          $instance['operator_ab']          = 'IN';
		if ( ! isset( $instance['taxonomy_ba'] ) )          $instance['taxonomy_ba']          = '';
		if ( ! isset( $instance['field_ba'] ) )             $instance['field_ba']             = 'slug';
		if ( ! isset( $instance['terms_ba'] ) )             $instance['terms_ba']             = '';
		if ( ! isset( $instance['operator_ba'] ) )          $instance['operator_ba']          = 'IN';
		if ( ! isset( $instance['relation_b'] ) )           $instance['relation_b']           = '';
		if ( ! isset( $instance['taxonomy_bb'] ) )          $instance['taxonomy_bb']          = '';
		if ( ! isset( $instance['field_bb'] ) )             $instance['field_bb']             = 'slug';
		if ( ! isset( $instance['terms_bb'] ) )             $instance['terms_bb']             = '';
		if ( ! isset( $instance['operator_bb'] ) )          $instance['operator_bb']          = 'IN';
		if ( ! isset( $instance['date_year'] ) )            $instance['date_year']            = '';
		if ( ! isset( $instance['date_month'] ) )           $instance['date_month']           = '';
		if ( ! isset( $instance['date_week'] ) )            $instance['date_week']            = '';
		if ( ! isset( $instance['date_day'] ) )             $instance['date_day']             = '';
		if ( ! isset( $instance['date_hour'] ) )            $instance['date_hour']            = '';
		if ( ! isset( $instance['date_minute'] ) )          $instance['date_minute']          = '';
		if ( ! isset( $instance['date_second'] ) )          $instance['date_second']          = '';
		if ( ! isset( $instance['date_after_year'] ) )      $instance['date_after_year']      = '';
		if ( ! isset( $instance['date_after_month'] ) )     $instance['date_after_month']     = '';
		if ( ! isset( $instance['date_after_day'] ) )       $instance['date_after_day']       = '';
		if ( ! isset( $instance['date_before_year'] ) )     $instance['date_before_year']     = '';
		if ( ! isset( $instance['date_before_month'] ) )    $instance['date_before_month']    = '';
		if ( ! isset( $instance['date_before_day'] ) )      $instance['date_before_day']      = '';
		if ( ! isset( $instance['date_inclusive'] ) )       $instance['date_inclusive']       = false;
		if ( ! isset( $instance['date_column'] ) )          $instance['date_column']          = '';
		if ( ! isset( $instance['author_not_in'] ) )        $instance['author_not_in']        = '';
		if ( ! isset( $instance['exclude_current_post'] ) ) $instance['exclude_current_post'] = false;
		if ( ! isset( $instance['post_not_in'] ) )          $instance['post_not_in']          = '';
		if ( ! isset( $instance['cat_not_in'] ) )           $instance['cat_not_in']           = '';
		if ( ! isset( $instance['tag_not_in'] ) )           $instance['tag_not_in']           = '';
		if ( ! isset( $instance['post_parent_not_in'] ) )   $instance['post_parent_not_in']   = '';
		if ( ! isset( $instance['image_align'] ) )          $instance['image_align']          = 'no_change';
		if ( ! isset( $instance['image_before_title'] ) )   $instance['image_before_title']   = false;
		if ( ! isset( $instance['image_link'] ) )           $instance['image_link']           = '';
		if ( ! isset( $instance['custom_image_url'] ) )     $instance['custom_image_url']     = '';
		if ( ! isset( $instance['custom_img_no_thumb'] ) )  $instance['custom_img_no_thumb']  = true;
		if ( ! isset( $instance['image_link_to_post'] ) )   $instance['image_link_to_post']   = true;
		if ( ! isset( $instance['the_more'] ) )             $instance['the_more']             = __( 'Read more&hellip;', 'posts-in-sidebar' );
		if ( ! isset( $instance['display_author'] ) )       $instance['display_author']       = false;
		if ( ! isset( $instance['author_text'] ) )          $instance['author_text']          = __( 'By', 'posts-in-sidebar' );
		if ( ! isset( $instance['linkify_author'] ) )       $instance['linkify_author']       = false;
		if ( ! isset( $instance['gravatar_display'] ) )     $instance['gravatar_display']     = false;
		if ( ! isset( $instance['gravatar_size'] ) )        $instance['gravatar_size']        = 32;
		if ( ! isset( $instance['gravatar_default'] ) )     $instance['gravatar_default']     = '';
		if ( ! isset( $instance['gravatar_position'] ) )    $instance['gravatar_position']    = 'next_author';
		if ( ! isset( $instance['date_text'] ) )            $instance['date_text']            = __( 'Published on', 'posts-in-sidebar' );
		if ( ! isset( $instance['linkify_date'] ) )         $instance['linkify_date']         = false;
		if ( ! isset( $instance['display_mod_date'] ) )     $instance['display_mod_date']     = false;
		if ( ! isset( $instance['mod_date_text'] ) )        $instance['mod_date_text']        = __( 'Modified on', 'posts-in-sidebar' );
		if ( ! isset( $instance['linkify_mod_date'] ) )     $instance['linkify_mod_date']     = false;
		if ( ! isset( $instance['comments_text'] ) )        $instance['comments_text']        = __( 'Comments:', 'posts-in-sidebar' );
		if ( ! isset( $instance['linkify_comments'] ) )     $instance['linkify_comments']     = true;
		if ( ! isset( $instance['utility_sep'] ) )          $instance['utility_sep']          = '|';
		if ( ! isset( $instance['utility_after_title'] ) )  $instance['utility_after_title']  = false;
		if ( ! isset( $instance['categories'] ) )           $instance['categories']           = false;
		if ( ! isset( $instance['categ_text'] ) )           $instance['categ_text']           = __( 'Category:', 'posts-in-sidebar' );
		if ( ! isset( $instance['categ_sep'] ) )            $instance['categ_sep']            = ',';
		if ( ! isset( $instance['tags'] ) )                 $instance['tags']                 = false;
		if ( ! isset( $instance['tags_text'] ) )            $instance['tags_text']            = __( 'Tags:', 'posts-in-sidebar' );
		if ( ! isset( $instance['hashtag'] ) )              $instance['hashtag']              = '#';
		if ( ! isset( $instance['tag_sep'] ) )              $instance['tag_sep']              = '';
		if ( ! isset( $instance['display_custom_tax'] ) )   $instance['display_custom_tax']   = false;
		if ( ! isset( $instance['term_hashtag'] ) )         $instance['term_hashtag']         = '';
		if ( ! isset( $instance['term_sep'] ) )             $instance['term_sep']             = ',';
		if ( ! isset( $instance['custom_field'] ) )         $instance['custom_field']         = false;
		if ( ! isset( $instance['custom_field_txt'] ) )     $instance['custom_field_txt']     = '';
		if ( ! isset( $instance['meta'] ) )                 $instance['meta']                 = '';
		if ( ! isset( $instance['custom_field_count'] ) )   $instance['custom_field_count']   = '';
		if ( ! isset( $instance['custom_field_hellip'] ) )  $instance['custom_field_hellip']  = '&hellip;';
		if ( ! isset( $instance['custom_field_key'] ) )     $instance['custom_field_key']     = false;
		if ( ! isset( $instance['custom_field_sep'] ) )     $instance['custom_field_sep']     = '';
		if ( ! isset( $instance['tax_name'] ) )             $instance['tax_name']             = '';
		if ( ! isset( $instance['tax_term_name'] ) )        $instance['tax_term_name']        = '';
		if ( ! isset( $instance['nopost_text'] ) )          $instance['nopost_text']          = __( 'No posts yet.', 'posts-in-sidebar' );
		if ( ! isset( $instance['hide_widget'] ) )          $instance['hide_widget']          = false;
		if ( ! isset( $instance['margin_unit'] ) )          $instance['margin_unit']          = 'px';
		if ( ! isset( $instance['intro_margin'] ) )         $instance['intro_margin']         = NULL;
		if ( ! isset( $instance['title_margin'] ) )         $instance['title_margin']         = NULL;
		if ( ! isset( $instance['side_image_margin'] ) )    $instance['side_image_margin']    = NULL;
		if ( ! isset( $instance['bottom_image_margin'] ) )  $instance['bottom_image_margin']  = NULL;
		if ( ! isset( $instance['excerpt_margin'] ) )       $instance['excerpt_margin']       = NULL;
		if ( ! isset( $instance['utility_margin'] ) )       $instance['utility_margin']       = NULL;
		if ( ! isset( $instance['categories_margin'] ) )    $instance['categories_margin']    = NULL;
		if ( ! isset( $instance['tags_margin'] ) )          $instance['tags_margin']          = NULL;
		if ( ! isset( $instance['terms_margin'] ) )         $instance['terms_margin']         = NULL;
		if ( ! isset( $instance['custom_field_margin'] ) )  $instance['custom_field_margin']  = NULL;
		if ( ! isset( $instance['archive_margin'] ) )       $instance['archive_margin']       = NULL;
		if ( ! isset( $instance['noposts_margin'] ) )       $instance['noposts_margin']       = NULL;
		if ( ! isset( $instance['custom_styles'] ) )        $instance['custom_styles']        = '';
		if ( ! isset( $instance['list_element'] ) )         $instance['list_element']         = 'ul';
		if ( ! isset( $instance['remove_bullets'] ) )       $instance['remove_bullets']       = false;
		if ( ! isset( $instance['cached'] ) )               $instance['cached']               = false;
		if ( ! isset( $instance['cache_time'] ) )           $instance['cache_time']           = 3600;
		if ( ! isset( $instance['debug_query'] ) )          $instance['debug_query']          = false;
		if ( ! isset( $instance['debug_params'] ) )         $instance['debug_params']         = false;
		if ( ! isset( $instance['debug_query_number'] ) )   $instance['debug_query_number']   = false;

		/*
		 * Execute the main function in the front-end.
		 * Some parameters are passed only for the debugging list.
		 */
		pis_posts_in_sidebar( array(
			// The custom container class
			'container_class'     => $instance['container_class'],

			// The title of the widget
			'title'               => $instance['title'],
			'title_link'          => $instance['title_link'],
			'intro'               => $instance['intro'],

			// Posts retrieving
			'post_type'           => $instance['post_type'],
			'posts_id'            => $instance['posts_id'],
			'author'              => $instance['author'],
			'author_in'           => $instance['author_in'],
			'cat'                 => $instance['cat'],
			'tag'                 => $instance['tag'],
			'post_parent_in'      => $instance['post_parent_in'],
			'post_format'         => $instance['post_format'],
			'number'              => $instance['number'],
			'orderby'             => $instance['orderby'],
			'order'               => $instance['order'],
			'offset_number'       => $instance['offset_number'],
			'post_status'         => $instance['post_status'],
			'post_meta_key'       => $instance['post_meta_key'],
			'post_meta_val'       => $instance['post_meta_val'],
			'search'              => $instance['search'],
			'ignore_sticky'       => $instance['ignore_sticky'],
			'get_from_same_cat'   => $instance['get_from_same_cat'],
			'number_same_cat'     => $instance['number_same_cat'],
			'title_same_cat'      => $instance['title_same_cat'],
			'get_from_same_author'=> $instance['get_from_same_author'],
			'number_same_author'  => $instance['number_same_author'],
			'title_same_author'   => $instance['title_same_author'],
			'get_from_custom_fld' => $instance['get_from_custom_fld'],
			's_custom_field_key'  => $instance['s_custom_field_key'],
			's_custom_field_tax'  => $instance['s_custom_field_tax'],
			'number_custom_field' => $instance['number_custom_field'],
			'title_custom_field'  => $instance['title_custom_field'],

			// Taxonomies
			'relation'            => $instance['relation'],

			'taxonomy_aa'         => $instance['taxonomy_aa'],
			'field_aa'            => $instance['field_aa'],
			'terms_aa'            => $instance['terms_aa'],
			'operator_aa'         => $instance['operator_aa'],

			'relation_a'          => $instance['relation_a'],

			'taxonomy_ab'         => $instance['taxonomy_ab'],
			'field_ab'            => $instance['field_ab'],
			'terms_ab'            => $instance['terms_ab'],
			'operator_ab'         => $instance['operator_ab'],

			'taxonomy_ba'         => $instance['taxonomy_ba'],
			'field_ba'            => $instance['field_ba'],
			'terms_ba'            => $instance['terms_ba'],
			'operator_ba'         => $instance['operator_ba'],

			'relation_b'          => $instance['relation_b'],

			'taxonomy_bb'         => $instance['taxonomy_bb'],
			'field_bb'            => $instance['field_bb'],
			'terms_bb'            => $instance['terms_bb'],
			'operator_bb'         => $instance['operator_bb'],

			// Date query
			'date_year'           => $instance['date_year'],
			'date_month'          => $instance['date_month'],
			'date_week'           => $instance['date_week'],
			'date_day'            => $instance['date_day'],
			'date_hour'           => $instance['date_hour'],
			'date_minute'         => $instance['date_minute'],
			'date_second'         => $instance['date_second'],
			'date_after_year'     => $instance['date_after_year'],
			'date_after_month'    => $instance['date_after_month'],
			'date_after_day'      => $instance['date_after_day'],
			'date_before_year'    => $instance['date_before_year'],
			'date_before_month'   => $instance['date_before_month'],
			'date_before_day'     => $instance['date_before_day'],
			'date_inclusive'      => $instance['date_inclusive'],
			'date_column'         => $instance['date_column'],

			// Posts exclusion
			'author_not_in'       => $instance['author_not_in'],
			'exclude_current_post'=> $instance['exclude_current_post'],
			'post_not_in'         => $instance['post_not_in'],
			'cat_not_in'          => $instance['cat_not_in'],
			'tag_not_in'          => $instance['tag_not_in'],
			'post_parent_not_in'  => $instance['post_parent_not_in'],

			// The title of the post
			'display_title'       => $instance['display_title'],
			'link_on_title'       => $instance['link_on_title'],
			'arrow'               => $instance['arrow'],

			// The featured image of the post
			'display_image'       => $instance['display_image'],
			'image_size'          => $instance['image_size'],
			'image_align'         => $instance['image_align'],
			'image_before_title'  => $instance['image_before_title'],
			'image_link'          => $instance['image_link'],
			'custom_image_url'    => $instance['custom_image_url'],
			'custom_img_no_thumb' => $instance['custom_img_no_thumb'],
			'image_link_to_post'  => $instance['image_link_to_post'],

			// The text of the post
			'excerpt'             => $instance['excerpt'],
			'exc_length'          => $instance['exc_length'],
			'the_more'            => $instance['the_more'],
			'exc_arrow'           => $instance['exc_arrow'],

			// Author, date and comments
			'display_author'      => $instance['display_author'],
			'author_text'         => $instance['author_text'],
			'linkify_author'      => $instance['linkify_author'],
			'gravatar_display'    => $instance['gravatar_display'],
			'gravatar_size'       => $instance['gravatar_size'],
			'gravatar_default'    => $instance['gravatar_default'],
			'gravatar_position'   => $instance['gravatar_position'],
			'display_date'        => $instance['display_date'],
			'date_text'           => $instance['date_text'],
			'linkify_date'        => $instance['linkify_date'],
			'display_mod_date'    => $instance['display_mod_date'],
			'mod_date_text'       => $instance['mod_date_text'],
			'linkify_mod_date'    => $instance['linkify_mod_date'],
			'comments'            => $instance['comments'],
			'comments_text'       => $instance['comments_text'],
			'linkify_comments'    => $instance['linkify_comments'],
			'utility_sep'         => $instance['utility_sep'],
			'utility_after_title' => $instance['utility_after_title'],

			// The categories of the post
			'categories'          => $instance['categories'],
			'categ_text'          => $instance['categ_text'],
			'categ_sep'           => $instance['categ_sep'],

			// The tags of the post
			'tags'                => $instance['tags'],
			'tags_text'           => $instance['tags_text'],
			'hashtag'             => $instance['hashtag'],
			'tag_sep'             => $instance['tag_sep'],

			// The custom taxonomies of the post
			'display_custom_tax'  => $instance['display_custom_tax'],
			'term_hashtag'        => $instance['term_hashtag'],
			'term_sep'            => $instance['term_sep'],

			// The custom field
			'custom_field'        => $instance['custom_field'],
			'custom_field_txt'    => $instance['custom_field_txt'],
			'meta'                => $instance['meta'],
			'custom_field_count'  => $instance['custom_field_count'],
			'custom_field_hellip' => $instance['custom_field_hellip'],
			'custom_field_key'    => $instance['custom_field_key'],
			'custom_field_sep'    => $instance['custom_field_sep'],

			// The link to the archive
			'archive_link'        => $instance['archive_link'],
			'link_to'             => $instance['link_to'],
			'tax_name'            => $instance['tax_name'],
			'tax_term_name'       => $instance['tax_term_name'],
			'archive_text'        => $instance['archive_text'],

			// Text when no posts found
			'nopost_text'         => $instance['nopost_text'],
			'hide_widget'         => $instance['hide_widget'],

			// Styles
			'margin_unit'         => $instance['margin_unit'],
			'intro_margin'        => $instance['intro_margin'],
			'title_margin'        => $instance['title_margin'],
			'side_image_margin'   => $instance['side_image_margin'],
			'bottom_image_margin' => $instance['bottom_image_margin'],
			'excerpt_margin'      => $instance['excerpt_margin'],
			'utility_margin'      => $instance['utility_margin'],
			'categories_margin'   => $instance['categories_margin'],
			'tags_margin'         => $instance['tags_margin'],
			'terms_margin'        => $instance['terms_margin'],
			'custom_field_margin' => $instance['custom_field_margin'],
			'archive_margin'      => $instance['archive_margin'],
			'noposts_margin'      => $instance['noposts_margin'],
			'custom_styles'       => $instance['custom_styles'],

			// Extras
			'list_element'        => $instance['list_element'],
			'remove_bullets'      => $instance['remove_bullets'],

			// Cache
			'cached'              => $instance['cached'],
			'cache_time'          => $instance['cache_time'],
			/*
			 *
			 * The following 'widget_id' variable will be used in the main function
			 * to check if a cached version of the query already exists
			 * for every instance of the widget.
			 */
			'widget_id'           => $this->id, // $this->id is the id of the widget instance.

			// Debug
			'debug_query'         => $instance['debug_query'],
			'debug_params'        => $instance['debug_params'],
			'debug_query_number'  => $instance['debug_query_number'],
		) );

		if ( isset( $instance['container_class'] ) && ! empty( $instance['container_class'] ) ) {
			echo '</div>';
		}

		echo $after_widget;

		echo '<!-- End Posts in Sidebar - ' . $widget_id . ' -->';
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// The title of the widget
		$instance['title']      = strip_tags( $new_instance['title'] );
		$instance['title_link'] = esc_url( strip_tags( $new_instance['title_link'] ) );
		$allowed_html = array(
			'a' => array(
				'href'  => array(),
				'title' => array(),
			),
			'em' => array(),
			'strong' => array(),
		);
		$instance['intro']               = wp_kses( $new_instance['intro'], $allowed_html );

		// Posts retrieving
		$instance['post_type']           = $new_instance['post_type'];
		$instance['posts_id']            = strip_tags( $new_instance['posts_id'] );
			if ( 0 == $instance['posts_id'] ) $instance['posts_id'] = '';
			/*
			 * For historical reasons (for example, see version 1.18 of this plugin),
			 * the variables $author, $cat, and $tag could have a value of 'NULL' (as string, not the costant NULL).
			 * This means that in the database we could have this value, so that WordPress will search, for example,
			 * for posts by author with 'NULL' nicename. We have to convert this wrong value into an empty value.
			 * This conversion should be safe because $author, $cat, and $tag must be all lowercase
			 * (according to WordPress slugs management) and, for example, a 'NULL' (uppercase) author nicename couldn't exist.
			 *
			 * @since 1.28
			 */
		$instance['author']              = $new_instance['author'];
			if ( 'NULL' == $instance['author'] ) $instance['author'] = '';
		$instance['author_in']           = strip_tags( $new_instance['author_in'] );
			// Make $author empty if $author_in is not empty.
			if ( ! empty( $instance['author_in'] ) ) $instance['author'] = '';
		$instance['cat']                 = strip_tags( $new_instance['cat'] );
			if ( 'NULL' == $instance['cat'] ) $instance['cat'] = '';
		$instance['tag']                 = strip_tags( $new_instance['tag'] );
			if ( 'NULL' == $instance['tag'] ) $instance['tag'] = '';
		$instance['post_parent_in']      = strip_tags( $new_instance['post_parent_in'] );
		$instance['post_format']         = $new_instance['post_format'];
		$instance['number']              = intval( strip_tags( $new_instance['number'] ) );
			if ( 0 == $instance['number'] || ! is_numeric( $instance['number'] ) ) $instance['number'] = get_option( 'posts_per_page' );
		$instance['orderby']             = $new_instance['orderby'];
		$instance['order']               = $new_instance['order'];
		$instance['offset_number']       = absint( strip_tags( $new_instance['offset_number'] ) );
			if ( 0 == $instance['offset_number'] || ! is_numeric( $instance['offset_number'] ) ) $instance['offset_number'] = '';
		$instance['post_status']         = $new_instance['post_status'];
		$instance['post_meta_key']       = strip_tags( $new_instance['post_meta_key'] );
		$instance['post_meta_val']       = strip_tags( $new_instance['post_meta_val'] );
		$instance['search']              = strip_tags( $new_instance['search'] );
			if ( '' == $instance['search'] ) $instance['search'] = NULL;
		$instance['ignore_sticky']       = isset( $new_instance['ignore_sticky'] ) ? 1 : 0;
		$instance['get_from_same_cat']   = isset( $new_instance['get_from_same_cat'] ) ? 1 : 0;
		$instance['number_same_cat']     = intval( strip_tags( $new_instance['number_same_cat'] ) );
			if ( 0 == $instance['number_same_cat'] || ! is_numeric( $instance['number_same_cat'] ) ) $instance['number_same_cat'] = '';
		$instance['title_same_cat']      = strip_tags( $new_instance['title_same_cat'] );
		$instance['get_from_same_author']= isset( $new_instance['get_from_same_author'] ) ? 1 : 0;
		$instance['number_same_author']  = intval( strip_tags( $new_instance['number_same_author'] ) );
			if ( 0 == $instance['number_same_author'] || ! is_numeric( $instance['number_same_author'] ) ) $instance['number_same_author'] = '';
		$instance['title_same_author']   = strip_tags( $new_instance['title_same_author'] );
		$instance['get_from_custom_fld'] = isset( $new_instance['get_from_custom_fld'] ) ? 1 : 0;
		$instance['s_custom_field_key']  = strip_tags( $new_instance['s_custom_field_key'] );
		$instance['s_custom_field_tax']  = $new_instance['s_custom_field_tax'];
		$instance['number_custom_field'] = intval( strip_tags( $new_instance['number_custom_field'] ) );
			if ( 0 == $instance['number_custom_field'] || ! is_numeric( $instance['number_custom_field'] ) ) $instance['number_custom_field'] = '';
		$instance['title_custom_field']  = strip_tags( $new_instance['title_custom_field'] );

		// Taxonomies
		$instance['relation']            = $new_instance['relation'];

		$instance['taxonomy_aa']         = strip_tags( $new_instance['taxonomy_aa'] );
		$instance['field_aa']            = $new_instance['field_aa'];
		$instance['terms_aa']            = strip_tags( $new_instance['terms_aa'] );
		$instance['operator_aa']         = $new_instance['operator_aa'];

		$instance['relation_a']          = $new_instance['relation_a'];

		$instance['taxonomy_ab']         = strip_tags( $new_instance['taxonomy_ab'] );
		$instance['field_ab']            = $new_instance['field_ab'];
		$instance['terms_ab']            = strip_tags( $new_instance['terms_ab'] );
		$instance['operator_ab']         = $new_instance['operator_ab'];

		$instance['taxonomy_ba']         = strip_tags( $new_instance['taxonomy_ba'] );
		$instance['field_ba']            = $new_instance['field_ba'];
		$instance['terms_ba']            = strip_tags( $new_instance['terms_ba'] );
		$instance['operator_ba']         = $new_instance['operator_ba'];

		$instance['relation_b']          = $new_instance['relation_b'];

		$instance['taxonomy_bb']         = strip_tags( $new_instance['taxonomy_bb'] );
		$instance['field_bb']            = $new_instance['field_bb'];
		$instance['terms_bb']            = strip_tags( $new_instance['terms_bb'] );
		$instance['operator_bb']         = $new_instance['operator_bb'];

		// Date query
		$instance['date_year']           = strip_tags( $new_instance['date_year'] );
			if ( ! is_numeric( $instance['date_year'] ) ) $instance['date_year'] = '';
		$instance['date_month']          = strip_tags( $new_instance['date_month'] );
			if ( 1 > $instance['date_month'] || 12 < $instance['date_month'] || ! is_numeric( $instance['date_month'] ) ) $instance['date_month'] = '';
		$instance['date_week']           = strip_tags( $new_instance['date_week'] );
			if ( 0 > $instance['date_week'] || 53 < $instance['date_week'] || ! is_numeric( $instance['date_week'] ) ) $instance['date_week'] = '';
		$instance['date_day']            = strip_tags( $new_instance['date_day'] );
			if ( 1 > $instance['date_day'] || 31 < $instance['date_day'] || ! is_numeric( $instance['date_day'] ) ) $instance['date_day'] = '';
		$instance['date_hour']           = strip_tags( $new_instance['date_hour'] );
			if ( 0 > $instance['date_hour'] || 23 < $instance['date_hour'] || ! is_numeric( $instance['date_hour'] ) ) $instance['date_hour'] = '';
		$instance['date_minute']         = strip_tags( $new_instance['date_minute'] );
			if ( 0 > $instance['date_minute'] || 59 < $instance['date_minute'] || ! is_numeric( $instance['date_minute'] ) ) $instance['date_minute'] = '';
		$instance['date_second']         = strip_tags( $new_instance['date_second'] );
			if ( 0 > $instance['date_second'] || 59 < $instance['date_second'] || ! is_numeric( $instance['date_second'] ) ) $instance['date_second'] = '';
		$instance['date_after_year']     = strip_tags( $new_instance['date_after_year'] );
			if ( ! is_numeric( $instance['date_after_year'] ) ) $instance['date_after_year'] = '';
		$instance['date_after_month']    = strip_tags( $new_instance['date_after_month'] );
			if ( 1 > $instance['date_after_month'] || 12 < $instance['date_after_month'] || ! is_numeric( $instance['date_after_month'] ) ) $instance['date_after_month'] = '';
		$instance['date_after_day']      = strip_tags( $new_instance['date_after_day'] );
			if ( 1 > $instance['date_after_day'] || 31 < $instance['date_after_day'] || ! is_numeric( $instance['date_after_day'] ) ) $instance['date_after_day'] = '';
		$instance['date_before_year']    = strip_tags( $new_instance['date_before_year'] );
			if ( ! is_numeric( $instance['date_before_year'] ) ) $instance['date_before_year'] = '';
		$instance['date_before_month']   = strip_tags( $new_instance['date_before_month'] );
			if ( 1 > $instance['date_before_month'] || 12 < $instance['date_before_month'] || ! is_numeric( $instance['date_before_month'] ) ) $instance['date_before_month'] = '';
		$instance['date_before_day']     = strip_tags( $new_instance['date_before_day'] );
			if ( 1 > $instance['date_before_day'] || 31 < $instance['date_before_day'] || ! is_numeric( $instance['date_before_day'] ) ) $instance['date_before_day'] = '';
		$instance['date_inclusive']      = isset( $new_instance['date_inclusive'] ) ? 1 : 0 ;
		$instance['date_column']         = $new_instance['date_column'];

		// Posts exclusion
		$instance['author_not_in']       = strip_tags( $new_instance['author_not_in'] );
		$instance['exclude_current_post']= isset( $new_instance['exclude_current_post'] ) ? 1 : 0 ;
		$instance['post_not_in']         = strip_tags( $new_instance['post_not_in'] );
		$instance['cat_not_in']          = strip_tags( $new_instance['cat_not_in'] );
		$instance['tag_not_in']          = strip_tags( $new_instance['tag_not_in'] );
		$instance['post_parent_not_in']  = strip_tags( $new_instance['post_parent_not_in'] );

		// The title of the post
		$instance['display_title']       = isset( $new_instance['display_title'] ) ? 1 : 0;
		$instance['link_on_title']       = isset( $new_instance['link_on_title'] ) ? 1 : 0;
		$instance['arrow']               = isset( $new_instance['arrow'] ) ? 1 : 0;

		// The featured image of the post
		$instance['display_image']       = isset( $new_instance['display_image'] ) ? 1 : 0;
		$instance['image_size']          = $new_instance['image_size'];
		$instance['image_align']         = $new_instance['image_align'];
		$instance['image_before_title']  = isset( $new_instance['image_before_title'] ) ? 1 : 0;
		$instance['image_link']          = esc_url( strip_tags( $new_instance['image_link'] ) );
		$instance['custom_image_url']    = esc_url( strip_tags( $new_instance['custom_image_url'] ) );
		$instance['custom_img_no_thumb'] = isset( $new_instance['custom_img_no_thumb'] ) ? 1 : 0;
		$instance['image_link_to_post']  = isset( $new_instance['image_link_to_post'] ) ? 1 : 0;

		// The text of the post
		$instance['excerpt']             = $new_instance['excerpt'];
		$instance['exc_length']          = absint( strip_tags( $new_instance['exc_length'] ) );
			if ( '' == $instance['exc_length'] || ! is_numeric( $instance['exc_length'] ) ) $instance['exc_length'] = 20;
		$instance['the_more']            = strip_tags( $new_instance['the_more'] );
			if ( strpos( $instance['the_more'], '...' ) ) $instance['the_more'] = str_replace( '...', '&hellip;', $instance['the_more'] );
		$instance['exc_arrow']           = isset( $new_instance['exc_arrow'] ) ? 1 : 0;

		// Author, date and comments
		$instance['display_author']      = isset( $new_instance['display_author'] ) ? 1 : 0;
		$instance['author_text']         = strip_tags( $new_instance['author_text'] );
		$instance['linkify_author']      = isset( $new_instance['linkify_author'] ) ? 1 : 0;
		$instance['gravatar_display']    = isset( $new_instance['gravatar_display'] ) ? 1 : 0;
		$instance['gravatar_size']       = strip_tags( $new_instance['gravatar_size'] );
		$instance['gravatar_default']    = esc_url( $new_instance['gravatar_default'] );
		$instance['gravatar_position']   = $new_instance['gravatar_position'];
		$instance['display_date']        = isset( $new_instance['display_date'] ) ? 1 : 0;
		$instance['date_text']           = strip_tags( $new_instance['date_text'] );
		$instance['linkify_date']        = isset( $new_instance['linkify_date'] ) ? 1 : 0;
		$instance['display_mod_date']    = isset( $new_instance['display_mod_date'] ) ? 1 : 0;
		$instance['mod_date_text']       = strip_tags( $new_instance['mod_date_text'] );
		$instance['linkify_mod_date']    = isset( $new_instance['linkify_mod_date'] ) ? 1 : 0;
		$instance['comments']            = isset( $new_instance['comments'] ) ? 1 : 0;
		$instance['comments_text']       = strip_tags( $new_instance['comments_text'] );
		$instance['linkify_comments']    = isset( $new_instance['linkify_comments'] ) ? 1 : 0;
		$instance['utility_sep']         = strip_tags( $new_instance['utility_sep'] );
		$instance['utility_after_title'] = isset( $new_instance['utility_after_title'] ) ? 1 : 0;

		// The categories of the post
		$instance['categories']          = isset( $new_instance['categories'] ) ? 1 : 0;
		$instance['categ_text']          = strip_tags( $new_instance['categ_text'] );
		$instance['categ_sep']           = strip_tags( $new_instance['categ_sep'] );

		// The tags of the post
		$instance['tags']                = isset( $new_instance['tags'] ) ? 1 : 0;
		$instance['tags_text']           = strip_tags( $new_instance['tags_text'] );
		$instance['hashtag']             = strip_tags( $new_instance['hashtag'] );
		$instance['tag_sep']             = strip_tags( $new_instance['tag_sep'] );

		// The custom taxonomies of the post
		$instance['display_custom_tax']  = isset( $new_instance['display_custom_tax'] ) ? 1 : 0;
		$instance['term_hashtag']        = strip_tags( $new_instance['term_hashtag'] );
		$instance['term_sep']            = strip_tags( $new_instance['term_sep'] );

		// The custom field
		$instance['custom_field']        = isset( $new_instance['custom_field'] ) ? 1 : 0;
		$instance['custom_field_txt']    = strip_tags( $new_instance['custom_field_txt'] );
		$instance['meta']                = strip_tags( $new_instance['meta'] );
		$instance['custom_field_count']  = strip_tags( $new_instance['custom_field_count'] );
			if ( 0 >= $instance['custom_field_count'] || ! is_numeric( $instance['custom_field_count'] ) ) $instance['custom_field_count'] = '';
		$instance['custom_field_hellip'] = strip_tags( $new_instance['custom_field_hellip'] );
		$instance['custom_field_key']    = isset( $new_instance['custom_field_key'] ) ? 1 : 0;
		$instance['custom_field_sep']    = strip_tags( $new_instance['custom_field_sep'] );

		// The link to the archive
		$instance['archive_link']        = isset( $new_instance['archive_link'] ) ? 1 : 0;
		$instance['link_to']             = $new_instance['link_to'];
		$instance['tax_name']            = strip_tags( $new_instance['tax_name'] );
		$instance['tax_term_name']       = strip_tags( $new_instance['tax_term_name'] );
		$instance['archive_text']        = strip_tags( $new_instance['archive_text'] );

		// Text when no posts found
		$instance['nopost_text']         = strip_tags( $new_instance['nopost_text'] );
		$instance['hide_widget']         = isset( $new_instance['hide_widget'] ) ? 1 : 0;

		// Styles
		$instance['margin_unit']         = $new_instance['margin_unit'];
		$instance['intro_margin']        = strip_tags( $new_instance['intro_margin'] );
			if ( ! is_numeric( $instance['intro_margin'] ) ) $instance['intro_margin'] = NULL;
		$instance['title_margin']        = strip_tags( $new_instance['title_margin'] );
			if ( ! is_numeric( $instance['title_margin'] ) ) $instance['title_margin'] = NULL;
		$instance['side_image_margin']   = $new_instance['side_image_margin'];
			if ( ! is_numeric( $instance['side_image_margin'] ) ) $instance['side_image_margin'] = NULL;
		$instance['bottom_image_margin'] = $new_instance['bottom_image_margin'];
			if ( ! is_numeric( $instance['bottom_image_margin'] ) ) $instance['bottom_image_margin'] = NULL;
		$instance['excerpt_margin']      = strip_tags( $new_instance['excerpt_margin'] );
			if ( ! is_numeric( $instance['excerpt_margin'] ) ) $instance['excerpt_margin'] = NULL;
		$instance['utility_margin']      = strip_tags( $new_instance['utility_margin'] );
			if ( ! is_numeric( $instance['utility_margin'] ) ) $instance['utility_margin'] = NULL;
		$instance['categories_margin']   = strip_tags( $new_instance['categories_margin'] );
			if ( ! is_numeric( $instance['categories_margin'] ) ) $instance['categories_margin'] = NULL;
		$instance['tags_margin']         = strip_tags( $new_instance['tags_margin'] );
			if ( ! is_numeric( $instance['tags_margin'] ) ) $instance['tags_margin'] = NULL;
		$instance['terms_margin']        = strip_tags( $new_instance['terms_margin'] );
			if ( ! is_numeric( $instance['terms_margin'] ) ) $instance['terms_margin'] = NULL;
		$instance['custom_field_margin'] = strip_tags( $new_instance['custom_field_margin'] );
			if ( ! is_numeric( $instance['custom_field_margin'] ) ) $instance['custom_field_margin'] = NULL;
		$instance['archive_margin']      = strip_tags( $new_instance['archive_margin'] );
			if ( ! is_numeric( $instance['archive_margin'] ) ) $instance['archive_margin'] = NULL;
		$instance['noposts_margin']      = strip_tags( $new_instance['noposts_margin'] );
			if ( ! is_numeric( $instance['noposts_margin'] ) ) $instance['noposts_margin'] = NULL;
		$instance['custom_styles']       = strip_tags( $new_instance['custom_styles'] );

		// Extras
		$instance['container_class']     = sanitize_html_class( $new_instance['container_class'] );
		$instance['list_element']        = $new_instance['list_element'];
		$instance['remove_bullets']      = isset( $new_instance['remove_bullets'] ) ? 1 : 0;

		// Cache
		$instance['cached']              = isset( $new_instance['cached'] ) ? 1 : 0;
		$instance['cache_time']          = strip_tags( $new_instance['cache_time'] );
			// If cache time is not a numeric value OR is 0, then reset cache. Also set cache time to 3600 if cache is active.
			if ( ! is_numeric( $new_instance['cache_time'] ) || 0 == $new_instance['cache_time'] ) {
				delete_transient( $this->id . '_query_cache' );
				if ( $instance['cached'] ) {
					$instance['cache_time'] = 3600;
				} else {
					$instance['cache_time'] = '';
				}
			}
		// This option is stored only for uninstall purposes. See uninstall.php for further information.
		$instance['widget_id']           = $this->id;

		// Debug
		$instance['debug_query']         = isset( $new_instance['debug_query'] ) ? 1 : 0;
		$instance['debug_params']        = isset( $new_instance['debug_params'] ) ? 1 : 0;
		$instance['debug_query_number']  = isset( $new_instance['debug_query_number'] ) ? 1 : 0;

		return $instance;
	}

	/**
	 * Display the options form on admin.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 * @since 1.0
	 */
	public function form( $instance ) {
		$defaults = array(
			// The title of the widget
			'title'               => __( 'Posts', 'posts-in-sidebar' ),
			'title_link'          => '',
			'intro'               => '',

			// Posts retrieving
			'post_type'           => 'post',
			'posts_id'            => '',
			'author'              => '',
			'author_in'           => '',
			'cat'                 => '',
			'tag'                 => '',
			'post_parent_in'      => '',
			'post_format'         => '',
			'number'              => get_option( 'posts_per_page' ),
			'orderby'             => 'date',
			'order'               => 'DESC',
			'offset_number'       => '',
			'post_status'         => 'publish',
			'post_meta_key'       => '',
			'post_meta_val'       => '',
			'search'              => NULL,
			'ignore_sticky'       => false,
			'get_from_same_cat'   => false,
			'number_same_cat'     => '',
			'title_same_cat'      => '',
			'get_from_same_author'=> false,
			'number_same_author'  => '',
			'title_same_author'   => '',
			'get_from_custom_fld' => false,
			's_custom_field_key'  => '',
			's_custom_field_tax'  => '',
			'number_custom_field' => '',
			'title_custom_field'  => '',

			// Taxonomies
			'relation'            => '',

			'taxonomy_aa'         => '',
			'field_aa'            => 'slug',
			'terms_aa'            => '',
			'operator_aa'         => 'IN',

			'relation_a'          => '',

			'taxonomy_ab'         => '',
			'field_ab'            => 'slug',
			'terms_ab'            => '',
			'operator_ab'         => 'IN',

			'taxonomy_ba'         => '',
			'field_ba'            => 'slug',
			'terms_ba'            => '',
			'operator_ba'         => 'IN',

			'relation_b'          => '',

			'taxonomy_bb'         => '',
			'field_bb'            => 'slug',
			'terms_bb'            => '',
			'operator_bb'         => 'IN',

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

			// Posts exclusion
			'author_not_in'       => '',
			'exclude_current_post'=> false,
			'post_not_in'         => '',
			'cat_not_in'          => '',
			'tag_not_in'          => '',
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
			'excerpt'             => 'excerpt',
			'exc_length'          => 20,
			'the_more'            => __( 'Read more&hellip;', 'posts-in-sidebar' ),
			'exc_arrow'           => false,

			// Author, date and comments
			'display_author'      => false,
			'author_text'         => __( 'By', 'posts-in-sidebar' ),
			'linkify_author'      => false,
			'gravatar_display'    => false,
			'gravatar_size'       => 32,
			'gravatar_default'    => '',
			'gravatar_position'   => 'next_author',
			'display_date'        => false,
			'date_text'           => __( 'Published on', 'posts-in-sidebar' ),
			'linkify_date'        => false,
			'display_mod_date'    => false,
			'mod_date_text'       => __( 'Modified on', 'posts-in-sidebar' ),
			'linkify_mod_date'    => false,
			'comments'            => false,
			'comments_text'       => __( 'Comments:', 'posts-in-sidebar' ),
			'linkify_comments'    => true,
			'utility_sep'         => '|',
			'utility_after_title' => false,

			// The categories of the post
			'categories'          => false,
			'categ_text'          => __( 'Category:', 'posts-in-sidebar' ),
			'categ_sep'           => ',',

			// The tags of the post
			'tags'                => false,
			'tags_text'           => __( 'Tags:', 'posts-in-sidebar' ),
			'hashtag'             => '#',
			'tag_sep'             => '',

			// The custom taxonomies of the post
			'display_custom_tax'  => false,
			'term_hashtag'        => '',
			'term_sep'            => ',',

			// The custom field
			'custom_field'        => false,
			'custom_field_txt'    => '',
			'meta'                => '',
			'custom_field_count'  => '',  // In characters.
			'custom_field_hellip' => '&hellip;',
			'custom_field_key'    => false,
			'custom_field_sep'    => ':',

			// The link to the archive
			'archive_link'        => false,
			'link_to'             => 'category',
			'tax_name'            => '',
			'tax_term_name'       => '',
			'archive_text'        => __( 'Display all posts under %s', 'posts-in-sidebar' ),

			// Text when no posts found
			'nopost_text'         => __( 'No posts yet.', 'posts-in-sidebar' ),
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
			'container_class'     => '',
			'list_element'        => 'ul',
			'remove_bullets'      => false,

			// Cache
			'cached'              => false,
			'cache_time'          => '',

			// Debug
			'debug_query'         => false,
			'debug_params'        => false,
			'debug_query_number'  => false,
		);
		$instance             = wp_parse_args( (array) $instance, $defaults );
		$ignore_sticky        = (bool) $instance['ignore_sticky'];
		$get_from_same_cat    = (bool) $instance['get_from_same_cat'];
		$get_from_same_author = (bool) $instance['get_from_same_author'];
		$get_from_custom_fld  = (bool) $instance['get_from_custom_fld'];
		$date_inclusive       = (bool) $instance['date_inclusive'];
		$exclude_current_post = (bool) $instance['exclude_current_post'];
		$display_title        = (bool) $instance['display_title'];
		$link_on_title        = (bool) $instance['link_on_title'];
		$display_image        = (bool) $instance['display_image'];
		$image_before_title   = (bool) $instance['image_before_title'];
		$arrow                = (bool) $instance['arrow'];
		$custom_img_no_thumb  = (bool) $instance['custom_img_no_thumb'];
		$image_link_to_post   = (bool) $instance['image_link_to_post'];
		$exc_arrow            = (bool) $instance['exc_arrow'];
		$utility_after_title  = (bool) $instance['utility_after_title'];
		$display_author       = (bool) $instance['display_author'];
		$linkify_author       = (bool) $instance['linkify_author'];
		$gravatar_display     = (bool) $instance['gravatar_display'];
		$display_date         = (bool) $instance['display_date'];
		$linkify_date         = (bool) $instance['linkify_date'];
		$display_mod_date     = (bool) $instance['display_mod_date'];
		$linkify_mod_date     = (bool) $instance['linkify_mod_date'];
		$comments             = (bool) $instance['comments'];
		$linkify_comments     = (bool) $instance['linkify_comments'];
		$categories           = (bool) $instance['categories'];
		$tags                 = (bool) $instance['tags'];
		$display_custom_tax   = (bool) $instance['display_custom_tax'];
		$custom_field         = (bool) $instance['custom_field'];
		$custom_field_key     = (bool) $instance['custom_field_key'];
		$archive_link         = (bool) $instance['archive_link'];
		$hide_widget          = (bool) $instance['hide_widget'];
		$remove_bullets       = (bool) $instance['remove_bullets'];
		$cached               = (bool) $instance['cached'];
		$debug_query          = (bool) $instance['debug_query'];
		$debug_params         = (bool) $instance['debug_params'];
		$debug_query_number   = (bool) $instance['debug_query_number'];

		/*
		 * When upgrading from old version, $author, $cat, and $tag could be 'NULL' (as string).
		 * See above for more informations (the long note on function update).
		 *
		 * @since 2.0.3
		 */
		if ( 'NULL' == $instance['author'] ) $instance['author'] = '';
		if ( 'NULL' == $instance['cat'] )    $instance['cat']    = '';
		if ( 'NULL' == $instance['tag'] )    $instance['tag']    = '';
		?>

		<!-- Widget title -->
		<div class="pis-section">

			<h4 class="pis-widget-title"><?php _e( 'The title of the widget', 'posts-in-sidebar' ); ?></h4>

			<div class="pis-container pis-container-open">

				<?php pis_form_input_text(
					__( 'Title', 'posts-in-sidebar' ),
					$this->get_field_id('title'),
					$this->get_field_name('title'),
					esc_attr( $instance['title'] ),
					__( 'From the archive', 'posts-in-sidebar' )
				); ?>

				<?php pis_form_input_text(
					__( 'Link the title of the widget to this URL', 'posts-in-sidebar' ),
					$this->get_field_id('title_link'),
					$this->get_field_name('title_link'),
					esc_url( strip_tags( $instance['title_link'] ) ),
					'http://example.com/readings-series/'
				); ?>

				<?php pis_form_textarea(
					__( 'Place this text after the title', 'posts-in-sidebar' ),
					$this->get_field_id('intro'),
					$this->get_field_name('intro'),
					$instance['intro'],
					__( 'These posts are part of my Readings series.', 'posts-in-sidebar' ),
					$style = 'resize: vertical; width: 100%; height: 80px;',
					$comment = sprintf( __( 'Allowed HTML: %s. Other tags will be stripped.', 'posts-in-sidebar' ), '<code>a</code>, <code>strong</code>, <code>em</code>' )
				); ?>

			</div>

		</div>

		<!-- Getting posts -->
		<div class="pis-section">

			<h4 class="pis-widget-title"><?php _e( 'Getting posts', 'posts-in-sidebar' ); ?></h4>

			<div class="pis-container">

				<p><em><?php _e( 'In this section you can define which type of posts you want to retrieve and which taxonomy the plugin will use. Other parameters are available to better define the query.', 'posts-in-sidebar' ); ?></em></p>

				<p><em><?php printf( __( 'If a field requires one or more IDs, install %1$sthis plugin%2$s to easily find the IDs.', 'posts-in-sidebar' ), '<a href="http://wordpress.org/plugins/reveal-ids-for-wp-admin-25/" target="_blank">', '</a>' ); ?></em></p>

				<div class="pis-column-container">

					<div class="pis-column">

						<?php // ================= Post types

						$args = array(
							'public' => true,
						);
						$post_types = (array) get_post_types( $args, 'objects', 'and' );

						$options = array(
							array(
								'value' => 'any',
								'desc'  => __( 'Any', 'posts-in-sidebar' ),
							)
						);
						foreach ( $post_types as $post_type ) {
							$options[] = array(
								'value' => $post_type->name,
								'desc'  => $post_type->labels->singular_name,
							);
						}

						pis_form_select(
							__( 'Post type', 'posts-in-sidebar' ),
							$this->get_field_id('post_type'),
							$this->get_field_name('post_type'),
							$options,
							$instance['post_type']
						); ?>

						<?php // ================= Posts ID
						pis_form_input_text(
							__( 'Get these posts exactly', 'posts-in-sidebar' ),
							$this->get_field_id('posts_id'),
							$this->get_field_name('posts_id'),
							esc_attr( $instance['posts_id'] ),
							'5, 29, 523, 4519',
							__( 'Enter IDs separated by commas.', 'posts-in-sidebar' )
						); ?>

					</div>

					<div class="pis-column">

						<?php // ================= Category
						pis_form_input_text(
							__( 'Get posts with these categories', 'posts-in-sidebar' ),
							$this->get_field_id('cat'),
							$this->get_field_name('cat'),
							esc_attr( $instance['cat'] ),
							__( 'books, ebooks', 'posts-in-sidebar' ),
							sprintf( __( 'Enter slugs separated by commas. To display posts that have all of the categories, use %1$s (a plus) between terms, for example:%2$s.', 'posts-in-sidebar' ), '<code>+</code>', '<br /><code>staff+news+our-works</code>' )
						); ?>

					</div>

					<div class="pis-column">

						<?php // ================= Tag
						pis_form_input_text(
							__( 'Get posts with these tags', 'posts-in-sidebar' ),
							$this->get_field_id('tag'),
							$this->get_field_name('tag'),
							esc_attr( $instance['tag'] ),
							__( 'best-sellers', 'posts-in-sidebar' ),
							sprintf( __( 'Enter slugs separated by commas. To display posts that have all of the tags, use %1$s (a plus) between terms, for example:%2$s.', 'posts-in-sidebar' ), '<code>+</code>', '<br /><code>staff+news+our-works</code>' )
						); ?>

					</div>

				</div>

				<div class="pis-column-container">

					<div class="pis-column">

						<?php // ================= Author
						$options = array(
							array(
								'value' => '',
								'desc'  => __( 'Any', 'posts-in-sidebar' )
							)
						);
						$authors = (array) get_users( 'who=authors' ); // If set to 'authors', only authors (user level greater than 0) will be returned.
						foreach ( $authors as $author ) {
							$options[] = array(
								'value' => $author->user_nicename,
								'desc'  => $author->display_name,
							);
						}
						pis_form_select(
							__( 'Get posts by this author', 'posts-in-sidebar' ),
							$this->get_field_id('author'),
							$this->get_field_name('author'),
							$options,
							$instance['author']
						); ?>

						<?php // ================= Multiple authors
						pis_form_input_text(
							__( 'Get posts by these authors', 'posts-in-sidebar' ),
							$this->get_field_id('author_in'),
							$this->get_field_name('author_in'),
							esc_attr( $instance['author_in'] ),
							__( '1, 23, 45', 'posts-in-sidebar' ),
							__( 'Enter IDs separated by commas. Note that if you fill this field, the previous one will be ignored.', 'posts-in-sidebar' )
						); ?>

					</div>

					<div class="pis-column">

						<?php // ================= Post parent
						pis_form_input_text(
							__( 'Get posts whose parent is in these IDs', 'posts-in-sidebar' ),
							$this->get_field_id('post_parent_in'),
							$this->get_field_name('post_parent_in'),
							esc_attr( $instance['post_parent_in'] ),
							__( '2, 5, 12, 14, 20', 'posts-in-sidebar' ),
							__( 'Enter IDs separated by commas.', 'posts-in-sidebar' )
						); ?>

						<?php // ================= Post format
						$options = array(
							array(
								'value' => '',
								'desc'  => __( 'Any', 'posts-in-sidebar' )
							)
						);
						$post_formats = get_terms( 'post_format' );
						foreach ( $post_formats as $post_format ) {
							$options[] = array(
								'value' => $post_format->slug,
								'desc'  => $post_format->name,
							);
						}
						pis_form_select(
							__( 'Get posts with this post format', 'posts-in-sidebar' ),
							$this->get_field_id('post_format'),
							$this->get_field_name('post_format'),
							$options,
							$instance['post_format']
						); ?>

						<?php // ================= Post status
						$options = array( array(
							'value' => 'any',
							'desc'  => 'Any',
						) );
						$statuses = get_post_stati( array(), 'objects' );
						foreach( $statuses as $status ) {
							$options[] = array(
								'value' => $status->name,
								'desc'  => $status->label,
							);
						}
						pis_form_select(
							__( 'Get posts with this post status', 'posts-in-sidebar' ),
							$this->get_field_id('post_status'),
							$this->get_field_name('post_status'),
							$options,
							$instance['post_status']
						); ?>

					</div>

					<div class="pis-column">

						<?php // ================= Post meta key
						pis_form_input_text(
							__( 'Get posts with this meta key', 'posts-in-sidebar' ),
							$this->get_field_id('post_meta_key'),
							$this->get_field_name('post_meta_key'),
							esc_attr( $instance['post_meta_key'] ),
							__( 'meta-key', 'posts-in-sidebar' )
						); ?>

						<?php // ================= Post meta value
						pis_form_input_text(
							__( 'Get posts with this meta value', 'posts-in-sidebar' ),
							$this->get_field_id('post_meta_val'),
							$this->get_field_name('post_meta_val'),
							esc_attr( $instance['post_meta_val'] ),
							__( 'meta-value', 'posts-in-sidebar' )
						); ?>

						<?php // ================= Search
						pis_form_input_text(
							__( 'Get posts from this search', 'posts-in-sidebar' ),
							$this->get_field_id('search'),
							$this->get_field_name('search'),
							esc_attr( $instance['search'] ),
							__( 'words to search', 'posts-in-sidebar' )
						); ?>

					</div>

				</div>

				<div class="pis-column-container pis-2col">

					<div class="pis-column">

						<?php // ================= Posts quantity
						pis_form_input_text(
							__( 'Get this number of posts', 'posts-in-sidebar' ),
							$this->get_field_id('number'),
							$this->get_field_name('number'),
							esc_attr( $instance['number'] ),
							'3',
							sprintf( __( 'The value %s shows all the posts.', 'posts-in-sidebar' ), '<code>-1</code>' )
						); ?>

						<?php // ================= Ignore sticky post
						pis_form_checkbox( __( 'Do not display sticky posts on top of other posts', 'posts-in-sidebar' ), $this->get_field_id( 'ignore_sticky' ), $this->get_field_name( 'ignore_sticky' ), checked( $ignore_sticky, true, false ), __( 'If you activate this option, sticky posts will be managed as other posts. Sticky post status will be automatically ignored if you set up an author or a taxonomy in this widget.', 'posts-in-sidebar' ) ); ?>

					</div>

					<div class="pis-column">

						<?php // ================= Post order by
						$options = array(
							'none' => array(
								'value' => 'none',
								'desc'  => __( 'None', 'posts-in-sidebar' )
							),
							'id' => array(
								'value' => 'id',
								'desc'  => __( 'ID', 'posts-in-sidebar' )
							),
							'author' => array(
								'value' => 'author',
								'desc'  => __( 'Author', 'posts-in-sidebar' )
							),
							'title' => array(
								'value' => 'title',
								'desc'  => __( 'Title', 'posts-in-sidebar' )
							),
							'name' => array(
								'value' => 'name',
								'desc'  => __( 'Name (post slug)', 'posts-in-sidebar' )
							),
							'date' => array(
								'value' => 'date',
								'desc'  => __( 'Date', 'posts-in-sidebar' )
							),
							'modified' => array(
								'value' => 'modified',
								'desc'  => __( 'Modified', 'posts-in-sidebar' )
							),
							'parent' => array(
								'value' => 'parent',
								'desc'  => __( 'Parent', 'posts-in-sidebar' )
							),
							'rand' => array(
								'value' => 'rand',
								'desc'  => __( 'Random', 'posts-in-sidebar' )
							),
							'comment_count' => array(
								'value' => 'comment_count',
								'desc'  => __( 'Comment count', 'posts-in-sidebar' )
							),
							'menu_order' => array(
								'value' => 'menu_order',
								'desc'  => __( 'Menu order', 'posts-in-sidebar' )
							),
							'meta_value' => array(
								'value' => 'meta_value',
								'desc'  => __( 'Meta value', 'posts-in-sidebar' )
							),
							'meta_value_num' => array(
								'value' => 'meta_value_num',
								'desc'  => __( 'Meta value number', 'posts-in-sidebar' )
							),
							'post__in' => array(
								'value' => 'post__in',
								'desc'  => __( 'Preserve ID order', 'posts-in-sidebar' )
							),
						);
						pis_form_select(
							__( 'Order posts by', 'posts-in-sidebar' ),
							$this->get_field_id('orderby'),
							$this->get_field_name('orderby'),
							$options,
							$instance['orderby']
						); ?>

						<?php // ================= Post order
						$options = array(
							'asc' => array(
								'value' => 'ASC',
								'desc'  => __( 'Ascending', 'posts-in-sidebar' )
							),
							'desc' => array(
								'value' => 'DESC',
								'desc'  => __( 'Descending', 'posts-in-sidebar' )
							),
						);
						pis_form_select(
							__( 'The order will be', 'posts-in-sidebar' ),
							$this->get_field_id('order'),
							$this->get_field_name('order'),
							$options,
							$instance['order']
						); ?>

						<?php // ================= Number of posts to skip
						pis_form_input_text(
							__( 'Skip this number of posts', 'posts-in-sidebar' ),
							$this->get_field_id('offset_number'),
							$this->get_field_name('offset_number'),
							esc_attr( $instance['offset_number'] ),
							'5'
						); ?>

					</div>

				</div>

				<div class="pis-section pis-2col">

					<h4 class="pis-widget-title"><?php _e( 'Change the query when on single posts', 'posts-in-sidebar' ); ?></h4>

					<div class="pis-container">

						<p><em><?php _e( 'In this section you can change some parameters of the query when on single posts. Activate only one of these.', 'posts-in-sidebar' ); ?></em></p>

						<div class="pis-column-container">

							<h5><?php _e( 'Get posts from current category', 'posts-in-sidebar' ); ?></h5>

							<div class="pis-column">

								<?php // ================= Get posts from same category
								pis_form_checkbox( __( 'When on single posts, get posts from the current category', 'posts-in-sidebar' ),
									$this->get_field_id( 'get_from_same_cat' ),
									$this->get_field_name( 'get_from_same_cat' ),
									checked( $get_from_same_cat, true, false ),
									__( 'When activated, this function will get posts from the first category of the post, ignoring other parameters like tags, date, post formats, etc. If the post has multiple categories, the plugin will use the first category in the array of categories (the category with the lowest key in the array). Custom post types are excluded from this feature.', 'posts-in-sidebar' )
								);
								?>

							</div>

							<div class="pis-column">

								<?php // ================= Posts quantity
								pis_form_input_text(
									__( 'When on single posts, get this number of posts', 'posts-in-sidebar' ),
									$this->get_field_id('number_same_cat'),
									$this->get_field_name('number_same_cat'),
									esc_attr( $instance['number_same_cat'] ),
									'3',
									sprintf( __( 'The value %s shows all the posts.', 'posts-in-sidebar' ), '<code>-1</code>' )
								); ?>

								<?php // ================= The custom widget title when on single posts
								pis_form_input_text(
									__( 'When on single posts, use this widget title', 'posts-in-sidebar' ),
									$this->get_field_id('title_same_cat'),
									$this->get_field_name('title_same_cat'),
									esc_attr( $instance['title_same_cat'] ),
									__( 'Posts under %s', 'posts-in-sidebar' ),
									sprintf( __( 'Use %s to display the name of the category.', 'posts-in-sidebar' ), '<code>%s</code>' )
								); ?>

							</div>

						</div>

						<hr>

						<div class="pis-column-container">

							<h5><?php _e( 'Get posts from current author', 'posts-in-sidebar' ); ?></h5>

							<div class="pis-column">

								<?php // ================= Get posts from same author
								pis_form_checkbox( __( 'When on single posts, get posts from the current author', 'posts-in-sidebar' ),
									$this->get_field_id( 'get_from_same_author' ),
									$this->get_field_name( 'get_from_same_author' ),
									checked( $get_from_same_author, true, false ),
									__( 'When activated, this function will get posts by the author of the post, ignoring other parameters like tags, date, post formats, etc. Custom post types are excluded from this feature.', 'posts-in-sidebar' )
								);
								?>

							</div>

							<div class="pis-column">

								<?php // ================= Posts quantity
								pis_form_input_text(
									__( 'When on single posts, get this number of posts', 'posts-in-sidebar' ),
									$this->get_field_id('number_same_author'),
									$this->get_field_name('number_same_author'),
									esc_attr( $instance['number_same_author'] ),
									'3',
									sprintf( __( 'The value %s shows all the posts.', 'posts-in-sidebar' ), '<code>-1</code>' )
								); ?>

								<?php // ================= The custom widget title when on single posts
								pis_form_input_text(
									__( 'When on single posts, use this widget title', 'posts-in-sidebar' ),
									$this->get_field_id('title_same_author'),
									$this->get_field_name('title_same_author'),
									esc_attr( $instance['title_same_author'] ),
									__( 'Posts by %s', 'posts-in-sidebar' ),
									sprintf( __( 'Use %s to display the name of the author.', 'posts-in-sidebar' ), '<code>%s</code>' )
								); ?>

							</div>

						</div>

						<hr>

						<div class="pis-column-container">

							<h5><?php _e( 'Get posts from taxonomy using custom field', 'posts-in-sidebar' ); ?></h5>

							<div class="pis-column">

								<?php // ================= Get posts from category/tags using custom field when on single post
								pis_form_checkbox( __( 'When on single posts, get posts from this custom field', 'posts-in-sidebar' ),
									$this->get_field_id( 'get_from_custom_fld' ),
									$this->get_field_name( 'get_from_custom_fld' ),
									checked( $get_from_custom_fld, true, false ),
									sprintf( __( 'When activated, this function will get posts from the category defined by the user via custom field, ignoring other parameters like tags, date, post formats, etc. Custom post types are excluded from this feature. %1$sRead more on this%2$s.', 'posts-in-sidebar' ), '<a href="https://github.com/aldolat/posts-in-sidebar/wiki/Advanced-Use#the-get-posts-from-taxonomy-using-custom-field-option" target="_blank">', '</a>' )
								);
								?>

								<?php // ================= Define the custom field key
								pis_form_input_text(
									__( 'Get posts with this custom field key', 'posts-in-sidebar' ),
									$this->get_field_id( 's_custom_field_key' ),
									$this->get_field_name( 's_custom_field_key' ),
									esc_attr( $instance['s_custom_field_key'] ),
									'custom_field_key'
								); ?>

								<?php // ================= Type of the taxonomy
								$options = array();
								$args = array(
									'public' => true,
								);
								$registered_taxonomies = get_taxonomies( $args, 'object' );
								foreach ( $registered_taxonomies as $registered_taxonomy ) {
									$options[] = array(
										'value' => $registered_taxonomy->name,
										'desc'  => $registered_taxonomy->labels->singular_name
									);
								}
								pis_form_select(
									__( 'Type of the taxonomy', 'posts-in-sidebar' ),
									$this->get_field_id('s_custom_field_tax'),
									$this->get_field_name('s_custom_field_tax'),
									$options,
									$instance['s_custom_field_tax']
								); ?>

							</div>

							<div class="pis-column">

								<?php // ================= Posts quantity
								pis_form_input_text(
									__( 'When on single posts, get this number of posts', 'posts-in-sidebar' ),
									$this->get_field_id('number_custom_field'),
									$this->get_field_name('number_custom_field'),
									esc_attr( $instance['number_custom_field'] ),
									'3',
									sprintf( __( 'The value %s shows all the posts.', 'posts-in-sidebar' ), '<code>-1</code>' )
								); ?>

								<?php // ================= The custom widget title when on single posts
								pis_form_input_text(
									__( 'When on single posts, use this widget title', 'posts-in-sidebar' ),
									$this->get_field_id( 'title_custom_field' ),
									$this->get_field_name( 'title_custom_field' ),
									esc_attr( $instance['title_custom_field'] ),
									__( 'Posts', 'posts-in-sidebar' ),
									sprintf( __( 'Use %s to display the name of the taxonomy.', 'posts-in-sidebar' ), '<code>%s</code>' )
								); ?>

							</div>

						</div>

					</div>

				</div>

				<!-- Excluding posts -->
				<div class="pis-section">

					<h4 class="pis-widget-title"><?php _e( 'Excluding posts', 'posts-in-sidebar' ); ?></h4>

					<div class="pis-container">

						<p><em><?php _e( 'Define here which posts must be excluded from the query.', 'posts-in-sidebar' ); ?></em></p>

						<p><em><?php printf( __( 'If a field requires one or more IDs, install %1$sthis plugin%2$s to easily find the IDs.', 'posts-in-sidebar' ), '<a href="http://wordpress.org/plugins/reveal-ids-for-wp-admin-25/" target="_blank">', '</a>' ); ?></em></p>

						<div class="pis-column-container">

							<div class="pis-column">

								<?php // ================= Exclude posts by these authors
								if ( is_array( $instance['author_not_in'] ) )
									$var = implode( ',', $instance['author_not_in'] );
								else
									$var = $instance['author_not_in'];
								pis_form_input_text(
									__( 'Exclude posts by these authors', 'posts-in-sidebar' ),
									$this->get_field_id('author_not_in'),
									$this->get_field_name('author_not_in'),
									esc_attr( $var ),
									'1, 23, 45',
									__( 'Enter IDs separated by commas.', 'posts-in-sidebar' )
								); ?>

								<?php // ================= Exclude posts from categories
								if ( is_array( $instance['cat_not_in'] ) )
									$var = implode( ',', $instance['cat_not_in'] );
								else
									$var = $instance['cat_not_in'];
								pis_form_input_text(
									__( 'Exclude posts from these categories', 'posts-in-sidebar' ),
									$this->get_field_id('cat_not_in'),
									$this->get_field_name('cat_not_in'),
									esc_attr( $var ),
									'3, 31',
									__( 'Enter IDs separated by commas.', 'posts-in-sidebar' )
								); ?>

							</div>

							<div class="pis-column">

								<?php // ================= Exclude posts from tags
								if ( is_array( $instance['tag_not_in'] ) )
									$var = implode( ',', $instance['tag_not_in'] );
								else
									$var = $instance['tag_not_in'];
								pis_form_input_text(
									__( 'Exclude posts from these tags', 'posts-in-sidebar' ),
									$this->get_field_id('tag_not_in'),
									$this->get_field_name('tag_not_in'),
									esc_attr( $var ),
									'7, 11',
									__( 'Enter IDs separated by commas.', 'posts-in-sidebar' )
								); ?>

								<?php // ================= Exclude posts that have these ids.
								pis_form_input_text(
									__( 'Exclude posts with these IDs', 'posts-in-sidebar' ),
									$this->get_field_id('post_not_in'),
									$this->get_field_name('post_not_in'),
									esc_attr( $instance['post_not_in'] ),
									'5, 29, 523, 4519',
									__( 'Enter IDs separated by commas.', 'posts-in-sidebar' )
								); ?>

							</div>

							<div class="pis-column">

								<?php // ================= Exclude posts whose parent is in these IDs.
								pis_form_input_text(
									__( 'Exclude posts whose parent is in these IDs', 'posts-in-sidebar' ),
									$this->get_field_id('post_parent_not_in'),
									$this->get_field_name('post_parent_not_in'),
									esc_attr( $instance['post_parent_not_in'] ),
									'5, 29, 523, 4519',
									__( 'Enter IDs separated by commas.', 'posts-in-sidebar' )
								); ?>

								<?php // ================= Exclude current post
								pis_form_checkbox( __( 'Automatically exclude the current post in single post or the current page in single page', 'posts-in-sidebar' ), $this->get_field_id( 'exclude_current_post' ), $this->get_field_name( 'exclude_current_post' ), checked( $exclude_current_post, true, false ) ); ?>

							</div>

						</div>

					</div>

				</div>

				<!-- Custom taxonomy query -->
				<div class="pis-section pis-2col">

					<h4 class="pis-widget-title"><?php _e( 'Custom taxonomy query', 'posts-in-sidebar' ); ?></h4>

					<div class="pis-container">

						<p><em><?php _e( 'This section lets you retrieve posts from any taxonomy (category, tags, and custom taxonomies). If you want to use only one taxonomy, use the "Taxonomy A1" field. If you have to put in relation two taxonomies (e.g., display posts that are in the "quotes" category but not in the "wisdom" tag), then use also the "Taxonomy B1" field. If you have to put in relation more taxonomies, start using also the "A2" and "B2" fields (e.g., display posts that are in the "quotes" category [A1] OR both have the "Quote" post format [B1] AND are in the "wisdom" category [B2]).', 'posts-in-sidebar' ); ?></em></p>

						<p><em><?php printf( __( 'If a field requires one or more IDs, install %1$sthis plugin%2$s to easily find the IDs.', 'posts-in-sidebar' ), '<a href="http://wordpress.org/plugins/reveal-ids-for-wp-admin-25/" target="_blank">', '</a>' ); ?></em></p>

						<hr />

						<div class="pis-column-container">

							<div class="pis-column centered">
								<?php // ================= Taxonomy relation between aa and bb
								$options = array(
									'empty' => array(
										'value' => '',
										'desc'  => ''
									),
									'and' => array(
										'value' => 'AND',
										'desc'  => 'AND'
									),
									'or' => array(
										'value' => 'OR',
										'desc'  => 'OR'
									),
								);
								pis_form_select( __( 'Relation between Column A and Column B', 'posts-in-sidebar' ), $this->get_field_id('relation'), $this->get_field_name('relation'), $options, $instance['relation'], __( 'The logical relationship between each inner taxonomy array when there is more than one. Do not use with a single inner taxonomy array.', 'posts-in-sidebar' ) ); ?>

							</div>

						</div>

						<div class="pis-column-container">

							<div class="pis-column">

								<h4 class="pis-title-center"><?php _e( 'Column A', 'posts-in-sidebar' ); ?></h4>

								<?php // ================= Taxonomy aa
								pis_form_input_text( sprintf( __( '%1$sTaxonomy A1%2$s', 'posts-in-sidebar' ), '<strong>', '</strong>' ), $this->get_field_id('taxonomy_aa'), $this->get_field_name('taxonomy_aa'), esc_attr( $instance['taxonomy_aa'] ), __( 'category', 'posts-in-sidebar' ), __( 'Enter the slug of the taxonomy.', 'posts-in-sidebar' ) ); ?>

								<?php // ================= Field aa
								$options = array(
									'term_id' => array(
										'value' => 'term_id',
										'desc'  => __( 'Term ID', 'posts-in-sidebar' )
									),
									'slug' => array(
										'value' => 'slug',
										'desc'  => __( 'Slug', 'posts-in-sidebar' )
									),
									'name' => array(
										'value' => 'name',
										'desc'  => __( 'Name', 'posts-in-sidebar' )
									),
								);
								pis_form_select( __( 'Field', 'posts-in-sidebar' ), $this->get_field_id('field_aa'), $this->get_field_name('field_aa'), $options, $instance['field_aa'], __( 'Select taxonomy term by this field.', 'posts-in-sidebar' ) ); ?>

								<?php // ================= Terms aa
								pis_form_input_text( __( 'Terms', 'posts-in-sidebar' ), $this->get_field_id('terms_aa'), $this->get_field_name('terms_aa'), esc_attr( $instance['terms_aa'] ), __( 'gnu-linux,kde', 'posts-in-sidebar' ), __( 'Enter terms, separated by comma.', 'posts-in-sidebar' ) ); ?>

								<?php // ================= Operator aa
								$options = array(
									'in' => array(
										'value' => 'IN',
										'desc'  => 'IN'
									),
									'not_in' => array(
										'value' => 'NOT IN',
										'desc'  => 'NOT IN'
									),
									'and' => array(
										'value' => 'AND',
										'desc'  => 'AND'
									),
								);
								pis_form_select( __( 'Operator', 'posts-in-sidebar' ), $this->get_field_id('operator_aa'), $this->get_field_name('operator_aa'), $options, $instance['operator_aa'], __( 'Operator to test for terms.', 'posts-in-sidebar' ) ); ?>

								<hr />

								<?php // ================= Taxonomy relation between aa and ab
								$options = array(
									'empty' => array(
										'value' => '',
										'desc'  => ''
									),
									'and' => array(
										'value' => 'AND',
										'desc'  => 'AND'
									),
									'or' => array(
										'value' => 'OR',
										'desc'  => 'OR'
									),
								);
								pis_form_select( __( 'Relation between A1 and A2 taxonomies', 'posts-in-sidebar' ), $this->get_field_id('relation_a'), $this->get_field_name('relation_a'), $options, $instance['relation_a'] ); ?>

								<hr />

								<?php // ================= Taxonomy ab
								pis_form_input_text( sprintf( __( '%1$sTaxonomy A2%2$s', 'posts-in-sidebar' ), '<strong>', '</strong>' ), $this->get_field_id('taxonomy_ab'), $this->get_field_name('taxonomy_ab'), esc_attr( $instance['taxonomy_ab'] ), __( 'movie-genre', 'posts-in-sidebar' ), __( 'Enter the slug of the taxonomy.', 'posts-in-sidebar' ) ); ?>

								<?php // ================= Field ab
								$options = array(
									'term_id' => array(
										'value' => 'term_id',
										'desc'  => __( 'Term ID', 'posts-in-sidebar' )
									),
									'slug' => array(
										'value' => 'slug',
										'desc'  => __( 'Slug', 'posts-in-sidebar' )
									),
									'name' => array(
										'value' => 'name',
										'desc'  => __( 'Name', 'posts-in-sidebar' )
									),
								);
								pis_form_select( __( 'Field', 'posts-in-sidebar' ), $this->get_field_id('field_ab'), $this->get_field_name('field_ab'), $options, $instance['field_ab'], __( 'Select taxonomy term by this field.', 'posts-in-sidebar' ) ); ?>

								<?php // ================= Terms ab
								pis_form_input_text( __( 'Terms', 'posts-in-sidebar' ), $this->get_field_id('terms_ab'), $this->get_field_name('terms_ab'), esc_attr( $instance['terms_ab'] ), __( 'action,sci-fi', 'posts-in-sidebar' ), __( 'Enter terms, separated by comma.', 'posts-in-sidebar' ) ); ?>

								<?php // ================= Operator ab
								$options = array(
									'in' => array(
										'value' => 'IN',
										'desc'  => 'IN'
									),
									'not_in' => array(
										'value' => 'NOT IN',
										'desc'  => 'NOT IN'
									),
									'and' => array(
										'value' => 'AND',
										'desc'  => 'AND'
									),
								);
								pis_form_select( __( 'Operator', 'posts-in-sidebar' ), $this->get_field_id('operator_ab'), $this->get_field_name('operator_ab'), $options, $instance['operator_ab'], __( 'Operator to test for terms.', 'posts-in-sidebar' ) ); ?>

							</div>

							<div class="pis-column">

								<h4 class="pis-title-center"><?php _e( 'Column B', 'posts-in-sidebar' ); ?></h4>

								<?php // ================= Taxonomy ba
								pis_form_input_text( sprintf( __( '%1$sTaxonomy B1%2$s', 'posts-in-sidebar' ), '<strong>', '</strong>' ), $this->get_field_id('taxonomy_ba'), $this->get_field_name('taxonomy_ba'), esc_attr( $instance['taxonomy_ba'] ), __( 'post_tag', 'posts-in-sidebar' ), __( 'Enter the slug of the taxonomy.', 'posts-in-sidebar' ) ); ?>

								<?php // ================= Field ba
								$options = array(
									'term_id' => array(
										'value' => 'term_id',
										'desc'  => __( 'Term ID', 'posts-in-sidebar' )
									),
									'slug' => array(
										'value' => 'slug',
										'desc'  => __( 'Slug', 'posts-in-sidebar' )
									),
									'name' => array(
										'value' => 'name',
										'desc'  => __( 'Name', 'posts-in-sidebar' )
									),
								);
								pis_form_select( __( 'Field', 'posts-in-sidebar' ), $this->get_field_id('field_ba'), $this->get_field_name('field_ba'), $options, $instance['field_ba'], __( 'Select taxonomy term by this field.', 'posts-in-sidebar' ) ); ?>

								<?php // ================= Terms ba
								pis_form_input_text( __( 'Terms', 'posts-in-sidebar' ), $this->get_field_id('terms_ba'), $this->get_field_name('terms_ba'), esc_attr( $instance['terms_ba'] ), __( 'system,apache', 'posts-in-sidebar' ), __( 'Enter terms, separated by comma.', 'posts-in-sidebar' ) ); ?>

								<?php // ================= Operator ba
								$options = array(
									'in' => array(
										'value' => 'IN',
										'desc'  => 'IN'
									),
									'not_in' => array(
										'value' => 'NOT IN',
										'desc'  => 'NOT IN'
									),
									'and' => array(
										'value' => 'AND',
										'desc'  => 'AND'
									),
								);
								pis_form_select( __( 'Operator', 'posts-in-sidebar' ), $this->get_field_id('operator_ba'), $this->get_field_name('operator_ba'), $options, $instance['operator_ba'], __( 'Operator to test for terms.', 'posts-in-sidebar' ) ); ?>

								<hr />

								<?php // ================= Taxonomy relation between ba and bb
								$options = array(
									'empty' => array(
										'value' => '',
										'desc'  => ''
									),
									'and' => array(
										'value' => 'AND',
										'desc'  => 'AND'
									),
									'or' => array(
										'value' => 'OR',
										'desc'  => 'OR'
									),
								);
								pis_form_select( __( 'Relation between B1 and B2 taxonomies', 'posts-in-sidebar' ), $this->get_field_id('relation_b'), $this->get_field_name('relation_b'), $options, $instance['relation_b'] ); ?>

								<hr />

								<?php // ================= Taxonomy bb
								pis_form_input_text( sprintf( __( '%1$sTaxonomy B2%2$s', 'posts-in-sidebar' ), '<strong>', '</strong>' ), $this->get_field_id('taxonomy_bb'), $this->get_field_name('taxonomy_bb'), esc_attr( $instance['taxonomy_bb'] ), __( 'post_format', 'posts-in-sidebar' ), __( 'Enter the slug of the taxonomy.', 'posts-in-sidebar' ) ); ?>

								<?php // ================= Field bb
								$options = array(
									'term_id' => array(
										'value' => 'term_id',
										'desc'  => __( 'Term ID', 'posts-in-sidebar' )
									),
									'slug' => array(
										'value' => 'slug',
										'desc'  => __( 'Slug', 'posts-in-sidebar' )
									),
									'name' => array(
										'value' => 'name',
										'desc'  => __( 'Name', 'posts-in-sidebar' )
									),
								);
								pis_form_select( __( 'Field', 'posts-in-sidebar' ), $this->get_field_id('field_bb'), $this->get_field_name('field_bb'), $options, $instance['field_bb'], __( 'Select taxonomy term by this field.', 'posts-in-sidebar' ) ); ?>

								<?php // ================= Terms bb
								pis_form_input_text( __( 'Terms', 'posts-in-sidebar' ), $this->get_field_id('terms_bb'), $this->get_field_name('terms_bb'), esc_attr( $instance['terms_bb'] ), __( 'post-format-quote', 'posts-in-sidebar' ), __( 'Enter terms, separated by comma.', 'posts-in-sidebar' ) ); ?>

								<?php // ================= Operator bb
								$options = array(
									'in' => array(
										'value' => 'IN',
										'desc'  => 'IN'
									),
									'not_in' => array(
										'value' => 'NOT IN',
										'desc'  => 'NOT IN'
									),
									'and' => array(
										'value' => 'AND',
										'desc'  => 'AND'
									),
								);
								pis_form_select( __( 'Operator', 'posts-in-sidebar' ), $this->get_field_id('operator_bb'), $this->get_field_name('operator_bb'), $options, $instance['operator_bb'], __( 'Operator to test for terms.', 'posts-in-sidebar' ) ); ?>

							</div>

						</div>

					</div>

				</div>

				<!-- Date query -->
				<div class="pis-section pis-2col">

					<h4 class="pis-widget-title"><?php _e( 'Date query', 'posts-in-sidebar' ); ?></h4>

					<div class="pis-container">

						<p><em><?php _e( 'Define the date period within posts are published.', 'posts-in-sidebar' ); ?></em></p>

						<div class="pis-column-container">

							<div class="pis-column">

								<?php pis_form_input_text(
									__( 'Year', 'posts-in-sidebar' ),
									$this->get_field_id('date_year'),
									$this->get_field_name('date_year'),
									esc_attr( $instance['date_year'] ),
									'2015',
									__( '4 digits year (e.g. 2015).', 'posts-in-sidebar' )
								); ?>

								<?php pis_form_input_text(
									__( 'Month', 'posts-in-sidebar' ),
									$this->get_field_id('date_month'),
									$this->get_field_name('date_month'),
									esc_attr( $instance['date_month'] ),
									'06',
									__( 'Month number (from 1 to 12).', 'posts-in-sidebar' )
								); ?>

								<?php pis_form_input_text(
									__( 'Week', 'posts-in-sidebar' ),
									$this->get_field_id('date_week'),
									$this->get_field_name('date_week'),
									esc_attr( $instance['date_week'] ),
									'32',
									__( 'Week of the year (from 0 to 53).', 'posts-in-sidebar' )
								); ?>

								<?php pis_form_input_text(
									__( 'Day', 'posts-in-sidebar' ),
									$this->get_field_id('date_day'),
									$this->get_field_name('date_day'),
									esc_attr( $instance['date_day'] ),
									'12',
									__( 'Day of the month (from 1 to 31).', 'posts-in-sidebar' )
								); ?>

							</div>

							<div class="pis-column">

								<?php pis_form_input_text(
									__( 'Hour', 'posts-in-sidebar' ),
									$this->get_field_id('date_hour'),
									$this->get_field_name('date_hour'),
									esc_attr( $instance['date_hour'] ),
									'09',
									__( 'Hour (from 0 to 23).', 'posts-in-sidebar' )
								); ?>

								<?php pis_form_input_text(
									__( 'Minute', 'posts-in-sidebar' ),
									$this->get_field_id('date_minute'),
									$this->get_field_name('date_minute'),
									esc_attr( $instance['date_minute'] ),
									'24',
									__( 'Minute (from 0 to 59).', 'posts-in-sidebar' )
								); ?>

								<?php pis_form_input_text(
									__( 'Second', 'posts-in-sidebar' ),
									$this->get_field_id('date_second'),
									$this->get_field_name('date_second'),
									esc_attr( $instance['date_second'] ),
									'32',
									__( 'Second (from 0 to 59).', 'posts-in-sidebar' )
								); ?>

							</div>

						</div>

						<div class="pis-column-container">

							<div class="pis-column">

								<h5 class="pis-title-center"><?php _e( 'Get posts after this date', 'posts-in-sidebar' ); ?></h5>

								<?php pis_form_input_text(
									__( 'Year', 'posts-in-sidebar' ),
									$this->get_field_id('date_after_year'),
									$this->get_field_name('date_after_year'),
									esc_attr( $instance['date_after_year'] ),
									'2011',
									__( 'Accepts any four-digit year.', 'posts-in-sidebar' )
								); ?>

								<?php pis_form_input_text(
									__( 'Month', 'posts-in-sidebar' ),
									$this->get_field_id('date_after_month'),
									$this->get_field_name('date_after_month'),
									esc_attr( $instance['date_after_month'] ),
									'10',
									__( 'The month of the year. Accepts numbers 1-12.', 'posts-in-sidebar' )
								); ?>

								<?php pis_form_input_text(
									__( 'Day', 'posts-in-sidebar' ),
									$this->get_field_id('date_after_day'),
									$this->get_field_name('date_after_day'),
									esc_attr( $instance['date_after_day'] ),
									'10',
									__( 'The day of the month. Accepts numbers 1-31.', 'posts-in-sidebar' )
								); ?>

							</div>

							<div class="pis-column">

								<h5 class="pis-title-center"><?php _e( 'Get posts before this date', 'posts-in-sidebar' ); ?></h5>

								<?php pis_form_input_text(
									__( 'Year', 'posts-in-sidebar' ),
									$this->get_field_id('date_before_year'),
									$this->get_field_name('date_before_year'),
									esc_attr( $instance['date_before_year'] ),
									'2011',
									__( 'Accepts any four-digit year.', 'posts-in-sidebar' )
								); ?>

								<?php pis_form_input_text(
									__( 'Month', 'posts-in-sidebar' ),
									$this->get_field_id('date_before_month'),
									$this->get_field_name('date_before_month'),
									esc_attr( $instance['date_before_month'] ),
									'10',
									__( 'The month of the year. Accepts numbers 1-12.', 'posts-in-sidebar' )
								); ?>

								<?php pis_form_input_text(
									__( 'Day', 'posts-in-sidebar' ),
									$this->get_field_id('date_before_day'),
									$this->get_field_name('date_before_day'),
									esc_attr( $instance['date_before_day'] ),
									'10',
									__( 'The day of the month. Accepts numbers 1-31.', 'posts-in-sidebar' )
								); ?>

							</div>

						</div>

						<div class="pis-column-container">

							<h5 class="pis-title-center"><?php _e( 'Other options', 'posts-in-sidebar' ); ?></h5>

							<div class="pis-column">

								<?php
								pis_form_checkbox( __( 'Inclusive', 'posts-in-sidebar' ), $this->get_field_id( 'date_inclusive' ), $this->get_field_name( 'date_inclusive' ), checked( $date_inclusive, true, false ), __( 'For after/before, whether exact value should be matched or not', 'posts-in-sidebar' ) ); ?>

							</div>

							<div class="pis-column">

								<?php
								$options = array(
									'empty' => array(
										'value' => '',
										'desc'  => ''
									),
									'post_date' => array(
										'value' => 'post_date',
										'desc'  => __( 'Post date', 'posts-in-sidebar' )
									),
									'post_date_gmt' => array(
										'value' => 'post_date_gmt',
										'desc'  => __( 'Post date GMT', 'posts-in-sidebar' )
									),
									'post_modified' => array(
										'value' => 'post_modified',
										'desc'  => __( 'Post modified', 'posts-in-sidebar' )
									),
									'post_modified_gmt' => array(
										'value' => 'post_modified_gmt',
										'desc'  => __( 'Post modified GMT', 'posts-in-sidebar' )
									)
								);
								pis_form_select(
									__( 'Column', 'posts-in-sidebar' ),
									$this->get_field_id('date_column'),
									$this->get_field_name('date_column'),
									$options,
									$instance['date_column'],
									__( 'Column to query against.', 'posts-in-sidebar' )
								); ?>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

		<!-- Displaying posts -->
		<div class="pis-section">

			<h4 class="pis-widget-title"><?php _e( 'Displaying posts', 'posts-in-sidebar' ); ?></h4>

			<div class="pis-container">

				<p><em><?php _e( 'Define here which elements you want to display in the widget.', 'posts-in-sidebar' ); ?></em></p>

				<div class="pis-section pis-2col">

					<div class="pis-column-container">

						<div class="pis-column">

							<h4><?php _e( 'The title of the post', 'posts-in-sidebar' ); ?></h4>

							<?php // ================= Title of the post
							pis_form_checkbox( __( 'Display the title of the post', 'posts-in-sidebar' ), $this->get_field_id( 'display_title' ), $this->get_field_name( 'display_title' ), checked( $display_title, true, false ) ); ?>

							<?php // ================= Link to the title
							pis_form_checkbox( __( 'Link the title to the post', 'posts-in-sidebar' ), $this->get_field_id( 'link_on_title' ), $this->get_field_name( 'link_on_title' ), checked( $link_on_title, true, false ) ); ?>

							<?php // ================= Arrow after the title
							pis_form_checkbox( __( 'Show an arrow after the title', 'posts-in-sidebar' ), $this->get_field_id( 'arrow' ), $this->get_field_name( 'arrow' ), checked( $arrow, true, false ) ); ?>

						</div>

						<div class="pis-column">
							<h4><?php _e( 'The text of the post', 'posts-in-sidebar' ); ?></h4>

							<?php // ================= Type of text
							$options = array(
								'full_content' => array(
									'value' => 'full_content',
									'desc'  => __( 'The full content', 'posts-in-sidebar' )
								),
								'rich_content' => array(
									'value' => 'rich_content',
									'desc'  => __( 'The rich content', 'posts-in-sidebar' )
								),
								'content' => array(
									'value' => 'content',
									'desc'  => __( 'The simple text', 'posts-in-sidebar' )
								),
								'more_excerpt' => array(
									'value' => 'more_excerpt',
									'desc'  => __( 'The excerpt up to "more" tag', 'posts-in-sidebar' )
								),
								'excerpt' => array(
									'value' => 'excerpt',
									'desc'  => __( 'The excerpt', 'posts-in-sidebar' )
								),
								'only_read_more' => array(
									'value' => 'only_read_more',
									'desc'  => __( 'Display only the Read more link', 'posts-in-sidebar' )
								),
								'none' => array(
									'value' => 'none',
									'desc'  => __( 'Do not show any text', 'posts-in-sidebar' )
								),
							);
							pis_form_select(
								__( 'Display this type of text', 'posts-in-sidebar' ),
								$this->get_field_id('excerpt'),
								$this->get_field_name('excerpt'),
								$options,
								$instance['excerpt'],
								sprintf( __( 'For informations regarding these types of text, please see %1$shere%2$s.', 'posts-in-sidebar' ), '<a href="https://github.com/aldolat/posts-in-sidebar/wiki/Usage#types-of-text-to-display" target="_blank">', '</a>' )
							); ?>

							<?php // ================= Excerpt length
							pis_form_input_text( __( 'The WordPress generated excerpt length will be (in words)', 'posts-in-sidebar' ), $this->get_field_id( 'exc_length' ), $this->get_field_name( 'exc_length' ), esc_attr( $instance['exc_length'] ), '20' ); ?>

							<?php // ================= More link text
							pis_form_input_text( __( 'Use this text for More link', 'posts-in-sidebar' ), $this->get_field_id( 'the_more' ), $this->get_field_name( 'the_more' ), esc_attr( $instance['the_more'] ), __( 'Read more&hellip;', 'posts-in-sidebar' ), __( 'The "Read more" text will be automatically hidden if the length of the WordPress-generated excerpt is smaller than or equal to the user-defined length.', 'posts-in-sidebar' ) ); ?>

							<?php // ================= Arrow after the excerpt
							pis_form_checkbox( __( 'Display an arrow after the text of the post', 'posts-in-sidebar' ), $this->get_field_id( 'exc_arrow' ), $this->get_field_name( 'exc_arrow' ), checked( $exc_arrow, true, false ) ); ?>

						</div>

					</div>

				</div>

				<div class="pis-section pis-2col">
					<h4 class="pis-widget-title"><?php _e( 'The featured image of the post', 'posts-in-sidebar' ); ?></h4>

					<div class="pis-container">

						<div class="pis-column-container">

							<div class="pis-column">

								<?php if ( ! current_theme_supports( 'post-thumbnails' ) ) { ?>
									<p class="pis-alert"><strong><?php _e( 'Your theme does not support the Post Thumbnail feature. No image will be displayed.', 'posts-in-sidebar' ); ?></strong></p>
								<?php } ?>

								<?php // ================= Featured image
								pis_form_checkbox( __( 'Display the featured image of the post', 'posts-in-sidebar' ), $this->get_field_id( 'display_image' ), $this->get_field_name( 'display_image' ), checked( $display_image, true, false ) ); ?>

								<?php // ================= Image sizes
								$options = array();
								$sizes = (array) get_intermediate_image_sizes();
								$sizes[] = 'full';
								foreach ( $sizes as $size ) {
									$options[] = array(
										'value' => $size,
										'desc'  => $size,
									);
								}
								pis_form_select(
									__( 'The size of the thumbnail will be', 'posts-in-sidebar' ),
									$this->get_field_id('image_size'),
									$this->get_field_name('image_size'),
									$options,
									$instance['image_size']
								); ?>

								<?php // ================= Image align
								$options = array(
									'nochange' => array(
										'value' => 'nochange',
										'desc'  => __( 'Do not change', 'posts-in-sidebar' )
									),
									'left' => array(
										'value' => 'left',
										'desc'  => __( 'Left', 'posts-in-sidebar' )
									),
									'right' => array(
										'value' => 'right',
										'desc'  => __( 'Right', 'posts-in-sidebar' )
									),
									'center' => array(
										'value' => 'center',
										'desc'  => __( 'Center', 'posts-in-sidebar' )
									),

								);
								pis_form_select(
									__( 'Align the image to', 'posts-in-sidebar' ),
									$this->get_field_id('image_align'),
									$this->get_field_name('image_align'),
									$options,
									$instance['image_align']
								); ?>

								<p>
									<em>
										<?php printf(
											__( 'Note that in order to use image sizes different from the WordPress standards, add them to your theme\'s %3$sfunctions.php%4$s file. See the %1$sCodex%2$s for further information.', 'posts-in-sidebar' ),
											'<a href="http://codex.wordpress.org/Function_Reference/add_image_size" target="_blank">', '</a>', '<code>', '</code>'
										); ?>
										<?php printf(
											__( 'You can also use %1$sa plugin%2$s that could help you in doing it.', 'posts-in-sidebar' ),
											'<a href="http://wordpress.org/plugins/simple-image-sizes/" target="_blank">', '</a>'
										); ?>
									</em>
								</p>

								<?php // ================= Positioning image before title
								pis_form_checkbox( __( 'Display the image before the title of the post', 'posts-in-sidebar' ), $this->get_field_id( 'image_before_title' ), $this->get_field_name( 'image_before_title' ), checked( $image_before_title, true, false ) ); ?>

							</div>

							<div class="pis-column">

								<h4><?php _e( 'The link of the featured image', 'posts-in-sidebar' ); ?></h4>

								<?php // ================= The link of the image to post
								pis_form_checkbox(
									__( 'Link the image to the post', 'posts-in-sidebar' ),
									$this->get_field_id( 'image_link_to_post' ),
									$this->get_field_name( 'image_link_to_post' ),
									checked( $image_link_to_post, true, false ),
									__( 'If activated, the image will be linked to the post. If you want to change the link, enter another URL in the box below.', 'posts-in-sidebar' )
								); ?>

								<?php // ================= Custom link of the image
								pis_form_input_text(
									__( 'Link the image to this URL', 'posts-in-sidebar' ),
									$this->get_field_id( 'image_link' ),
									$this->get_field_name( 'image_link' ),
									esc_url( strip_tags( $instance['image_link'] ) ),
									'http://example.com/mypage',
									__( 'By default the featured image is linked to the post. Use this field to link the image to a URL of your choice. Please, note that every featured image of this widget will be linked to the same URL.', 'posts-in-sidebar' )
								); ?>

								<h4><?php _e( 'Customized featured image', 'posts-in-sidebar' ); ?></h4>

								<?php // ================= Custom image URL
								pis_form_input_text(
									__( 'Use this image instead of the standard featured image', 'posts-in-sidebar' ),
									$this->get_field_id( 'custom_image_url' ),
									$this->get_field_name( 'custom_image_url' ),
									esc_url( strip_tags( $instance['custom_image_url'] ) ),
									'http://example.com/image.jpg',
									__( 'Paste here the URL of the image. Note that the same image will be used for all the posts in the widget, unless you active the checkbox below.', 'posts-in-sidebar' )
								); ?>

								<?php // ================= Use custom image URL only if the post thumbnail is not defined.
								pis_form_checkbox( __( 'Use custom image URL only if the post has not a featured image.', 'posts-in-sidebar' ), $this->get_field_id( 'custom_img_no_thumb' ), $this->get_field_name( 'custom_img_no_thumb' ), checked( $custom_img_no_thumb, true, false ) ); ?>

							</div>

						</div>

					</div>

				</div>

				<div class="pis-section">

					<h4 class="pis-widget-title"><?php _e( 'Author, date and comments', 'posts-in-sidebar' ); ?></h4>

					<div class="pis-container">

						<div class="pis-column-container">

							<div class="pis-column">

								<?php // ================= Author
								pis_form_checkbox( __( 'Display the author of the post', 'posts-in-sidebar' ), $this->get_field_id( 'display_author' ), $this->get_field_name( 'display_author' ), checked( $display_author, true, false ) ); ?>

								<?php // ================= Author text
								pis_form_input_text( __( 'Use this text before author\'s name', 'posts-in-sidebar' ), $this->get_field_id( 'author_text' ), $this->get_field_name( 'author_text' ), esc_attr( $instance['author_text'] ), __( 'By', 'posts-in-sidebar' ) ); ?>

								<?php // ================= Author archive
								pis_form_checkbox( __( 'Link the author to his archive', 'posts-in-sidebar' ), $this->get_field_id( 'linkify_author' ), $this->get_field_name( 'linkify_author' ), checked( $linkify_author, true, false ) ); ?>

							</div>

							<div class="pis-column">

								<?php // ================= Date
								pis_form_checkbox( __( 'Display the date of the post', 'posts-in-sidebar' ), $this->get_field_id( 'display_date' ), $this->get_field_name( 'display_date' ), checked( $display_date, true, false ) ); ?>

								<?php // ================= Date text
								pis_form_input_text( __( 'Use this text before date', 'posts-in-sidebar' ), $this->get_field_id( 'date_text' ), $this->get_field_name( 'date_text' ), esc_attr( $instance['date_text'] ), __( 'Published on', 'posts-in-sidebar' ) ); ?>

								<?php // ================= Date link
								pis_form_checkbox( __( 'Link the date to the post', 'posts-in-sidebar' ), $this->get_field_id( 'linkify_date' ), $this->get_field_name( 'linkify_date' ), checked( $linkify_date, true, false ) ); ?>

							</div>

							<div class="pis-column">

								<?php // ================= Number of comments
								pis_form_checkbox( __( 'Display the number of comments', 'posts-in-sidebar' ), $this->get_field_id( 'comments' ), $this->get_field_name( 'comments' ), checked( $comments, true, false ) ); ?>

								<?php // ================= Comments text
								pis_form_input_text( __( 'Use this text before comments', 'posts-in-sidebar' ), $this->get_field_id( 'comments_text' ), $this->get_field_name( 'comments_text' ), esc_attr( $instance['comments_text'] ), __( 'Comments:', 'posts-in-sidebar' ) ); ?>

								<?php // ================= Comments link
								pis_form_checkbox( __( 'Link the comments to post\'s comments', 'posts-in-sidebar' ), $this->get_field_id( 'linkify_comments' ), $this->get_field_name( 'linkify_comments' ), checked( $linkify_comments, true, false ) ); ?>

							</div>

						</div>

						<div class="pis-column-container">

							<div class="pis-column">

								<?php // ================= Author gravatar
								pis_form_checkbox( __( 'Display author\'s Gravatar', 'posts-in-sidebar' ), $this->get_field_id( 'gravatar_display' ), $this->get_field_name( 'gravatar_display' ), checked( $gravatar_display, true, false ), '', 'pis-gravatar' ); ?>

								<?php // ================= Gravatar size
								pis_form_input_text( __( 'Gravatar size', 'posts-in-sidebar' ), $this->get_field_id( 'gravatar_size' ), $this->get_field_name( 'gravatar_size' ), esc_attr( $instance['gravatar_size'] ), '32' ); ?>

								<?php // ================= Gravatar default image
								pis_form_input_text( __( 'URL of the default Gravatar image', 'posts-in-sidebar' ), $this->get_field_id( 'gravatar_default' ), $this->get_field_name( 'gravatar_default' ), esc_attr( $instance['gravatar_default'] ), 'http://example.com/image.jpg' ); ?>

								<?php // ================= Gravatar position
								$options = array(
									'next_title' => array(
										'value' => 'next_title',
										'desc'  => __( 'Next to the post title', 'posts-in-sidebar' )
									),
									'next_post' => array(
										'value' => 'next_post',
										'desc'  => __( 'Next to the post content', 'posts-in-sidebar' )
									),
									'next_author' => array(
										'value' => 'next_author',
										'desc'  => __( 'Next to the author name', 'posts-in-sidebar' )
									),
								);
								pis_form_select( __( 'Gravatar position', 'posts-in-sidebar' ), $this->get_field_id('gravatar_position'), $this->get_field_name('gravatar_position'), $options, $instance['gravatar_position'] ); ?>

							</div>

							<div class="pis-column">

								<?php // ================= Modification Date
								pis_form_checkbox( __( 'Display the modification date of the post', 'posts-in-sidebar' ), $this->get_field_id( 'display_mod_date' ), $this->get_field_name( 'display_mod_date' ), checked( $display_mod_date, true, false ) ); ?>

								<?php // ================= Modification Date text
								pis_form_input_text( __( 'Use this text before modification date', 'posts-in-sidebar' ), $this->get_field_id( 'mod_date_text' ), $this->get_field_name( 'mod_date_text' ), esc_attr( $instance['mod_date_text'] ), __( 'Modified on', 'posts-in-sidebar' ) ); ?>

								<?php // ================= Modification Date link
								pis_form_checkbox( __( 'Link the modification date to the post', 'posts-in-sidebar' ), $this->get_field_id( 'linkify_mod_date' ), $this->get_field_name( 'linkify_mod_date' ), checked( $linkify_mod_date, true, false ) ); ?>

							</div>

							<div class="pis-column">

								<?php // ================= Utility separator
								pis_form_input_text( __( 'Use this separator between author, date and comments', 'posts-in-sidebar' ), $this->get_field_id( 'utility_sep' ), $this->get_field_name( 'utility_sep' ), esc_attr( $instance['utility_sep'] ), '|', __( 'A space will be added before and after the separator.', 'posts-in-sidebar' ) ); ?>

								<?php // ================= Section position
								pis_form_checkbox( __( 'Display this section after the title of the post', 'posts-in-sidebar' ), $this->get_field_id( 'utility_after_title' ), $this->get_field_name( 'utility_after_title' ), checked( $utility_after_title, true, false ) ); ?>

							</div>

						</div>

					</div>

				</div>

				<div class="pis-section">

					<h4 class="pis-widget-title"><?php _e( 'Taxonomies', 'posts-in-sidebar' ); ?></h4>

					<div class="pis-container">

						<div class="pis-column-container">

							<div class="pis-column">

								<h4><?php _e( 'Categories', 'posts-in-sidebar' ); ?></h4>

								<?php // ================= Post categories
								pis_form_checkbox( __( 'Show the categories', 'posts-in-sidebar' ), $this->get_field_id( 'categories' ), $this->get_field_name( 'categories' ), checked( $categories, true, false ) ); ?>

								<?php // ================= Categories text
								pis_form_input_text( __( 'Use this text before categories list', 'posts-in-sidebar' ), $this->get_field_id( 'categ_text' ), $this->get_field_name( 'categ_text' ), esc_attr( $instance['categ_text'] ), __( 'Category:', 'posts-in-sidebar' ) ); ?>

								<?php // ================= Categories separator
								pis_form_input_text(
									__( 'Use this separator between categories', 'posts-in-sidebar' ),
									$this->get_field_id( 'categ_sep' ),
									$this->get_field_name( 'categ_sep' ),
									esc_attr( $instance['categ_sep'] ),
									',',
									__( 'A space will be added after the separator.', 'posts-in-sidebar' )
								); ?>

							</div>

							<div class="pis-column">

								<h4><?php _e( 'Tags', 'posts-in-sidebar' ); ?></h4>

								<?php // ================= Post tags
								pis_form_checkbox( __( 'Show the tags', 'posts-in-sidebar' ), $this->get_field_id( 'tags' ), $this->get_field_name( 'tags' ), checked( $tags, true, false ) ); ?>

								<?php // ================= Tags text
								pis_form_input_text( __( 'Use this text before tags list', 'posts-in-sidebar' ), $this->get_field_id( 'tags_text' ), $this->get_field_name( 'tags_text' ), esc_attr( $instance['tags_text'] ), __( 'Tags:', 'posts-in-sidebar' ) ); ?>

								<?php // ================= Hashtag
								pis_form_input_text( __( 'Use this hashtag', 'posts-in-sidebar' ), $this->get_field_id( 'hashtag' ), $this->get_field_name( 'hashtag' ), esc_attr( $instance['hashtag'] ), '#' ); ?>

								<?php // ================= Tags separator
								pis_form_input_text(
									__( 'Use this separator between tags', 'posts-in-sidebar' ),
									$this->get_field_id( 'tag_sep' ),
									$this->get_field_name( 'tag_sep' ),
									esc_attr( $instance['tag_sep'] ),
									',',
									__( 'A space will be added after the separator.', 'posts-in-sidebar' )
								); ?>

							</div>

							<div class="pis-column">

								<h4><?php _e( 'Custom taxonomies', 'posts-in-sidebar' ); ?></h4>

								<?php // ================= Custom taxonomies
								pis_form_checkbox( __( 'Show the custom taxonomies', 'posts-in-sidebar' ),
								$this->get_field_id( 'display_custom_tax' ),
								$this->get_field_name( 'display_custom_tax' ),
								checked( $display_custom_tax, true, false ) ); ?>

								<?php // ================= Terms hashtag
								pis_form_input_text( __( 'Use this hashtag for terms', 'posts-in-sidebar' ), $this->get_field_id( 'term_hashtag' ), $this->get_field_name( 'term_hashtag' ), esc_attr( $instance['term_hashtag'] ), '#' ); ?>

								<?php // ================= Terms separator
								pis_form_input_text(
									__( 'Use this separator between terms', 'posts-in-sidebar' ),
									$this->get_field_id( 'term_sep' ),
									$this->get_field_name( 'term_sep' ),
									esc_attr( $instance['term_sep'] ),
									',',
									__( 'A space will be added after the separator.', 'posts-in-sidebar' )
								); ?>

							</div>

						</div>
					</div>

				</div>

				<div class="pis-section pis-2col">

					<h4 class="pis-widget-title"><?php _e( 'The custom field', 'posts-in-sidebar' ); ?></h4>

					<div class="pis-container">

						<div class="pis-column-container">

							<div class="pis-column">

								<?php // ================= Display custom field
								pis_form_checkbox( __( 'Display the custom field of the post', 'posts-in-sidebar' ), $this->get_field_id( 'custom_field' ), $this->get_field_name( 'custom_field' ), checked( $custom_field, true, false ) ); ?>

								<?php // ================= Custom fields text
								pis_form_input_text( __( 'Use this text before the custom field', 'posts-in-sidebar' ), $this->get_field_id( 'custom_field_txt' ), $this->get_field_name( 'custom_field_txt' ), esc_attr( $instance['custom_field_txt'] ), __( 'Custom field:', 'posts-in-sidebar' ) ); ?>

								<?php // ================= Which custom field
								$options = array();
								$metas = (array) pis_meta();
								foreach ( $metas as $meta ) {
									if ( ! is_protected_meta( $meta, 'post' ) ) {
										$options[] = array(
											'value' => $meta,
											'desc'  => $meta,
										);
									}
								}
								pis_form_select(
									__( 'Display this custom field', 'posts-in-sidebar' ),
									$this->get_field_id('meta'),
									$this->get_field_name('meta'),
									$options,
									$instance['meta']
								); ?>

								<?php // ================= Custom field count
								pis_form_input_text( __( 'The custom field content length will be (in characters)', 'posts-in-sidebar' ), $this->get_field_id( 'custom_field_count' ), $this->get_field_name( 'custom_field_count' ), esc_attr( $instance['custom_field_count'] ), '10' ); ?>

								<?php // ================= Custom field hellip
								pis_form_input_text( __( 'Use this text for horizontal ellipsis', 'posts-in-sidebar' ), $this->get_field_id( 'custom_field_hellip' ), $this->get_field_name( 'custom_field_hellip' ), esc_attr( $instance['custom_field_hellip'] ), '&hellip;' ); ?>

							</div>

							<div class="pis-column">

								<?php // ================= Custom field key
								pis_form_checkbox( __( 'Also display the key of the custom field', 'posts-in-sidebar' ), $this->get_field_id( 'custom_field_key' ), $this->get_field_name( 'custom_field_key' ), checked( $custom_field_key, true, false ) ); ?>

								<?php // ================= Custom field separator
								pis_form_input_text( __( 'Use this separator between meta key and value', 'posts-in-sidebar' ), $this->get_field_id( 'custom_field_sep' ), $this->get_field_name( 'custom_field_sep' ), esc_attr( $instance['custom_field_sep'] ), ':' ); ?>

							</div>

						</div>

					</div>

				</div>

				<div class="pis-section pis-2col">

					<h4 class="pis-widget-title"><?php _e( 'The link to the archive', 'posts-in-sidebar' ); ?></h4>

					<div class="pis-container">

						<div class="pis-column-container">

							<div class="pis-column">

								<?php // ================= Taxonomy archive link
								pis_form_checkbox( __( 'Display the link to the taxonomy archive', 'posts-in-sidebar' ), $this->get_field_id( 'archive_link' ), $this->get_field_name( 'archive_link' ), checked( $archive_link, true, false ) ); ?>

								<?php // ================= Which taxonomy
								$options = array(
									/* Author */
									'author' => array(
										'value' => 'author',
										'desc'  => __( 'Author', 'posts-in-sidebar' )
									),
									/* Category */
									'category' => array(
										'value' => 'category',
										'desc'  => __( 'Category', 'posts-in-sidebar' )
									),
									/* tag */
									'tag' => array(
										'value' => 'tag',
										'desc'  => __( 'Tag', 'posts-in-sidebar' )
									),
								);
								/* Custom post type */
								$custom_post_types = get_post_types( array(
									'_builtin' => false
								) );
								if ( $custom_post_types ) {
									$options[] = array(
										'value' => 'custom_post_type',
										'desc'  => __( 'Custom post type', 'posts-in-sidebar' ),
									);
								}
								/* Custom taxonomy */
								$custom_taxonomy = get_taxonomies( array(
									'public'   => true,
									'_builtin' => false
								) );
								if ( $custom_taxonomy ) {
									$options[] = array(
										'value' => 'custom_taxonomy',
										'desc'  => __( 'Custom taxonomy', 'posts-in-sidebar' ),
									);
								}
								/* Post format */
								if ( $post_formats ) { // $post_formats has been already declared (search above).
									foreach ( $post_formats as $post_format ) {
										$options[] = array(
											'value' => $post_format->slug,
											'desc'  => sprintf( __( 'Post format: %s', 'posts-in-sidebar' ), $post_format->name ),
										);
									}
								}
								pis_form_select(
									__( 'Link to the archive of', 'posts-in-sidebar' ),
									$this->get_field_id('link_to'),
									$this->get_field_name('link_to'),
									$options,
									$instance['link_to'],
									'',
									'pis-linkto-form'
								); ?>

							</div>

							<div class="pis-column">

								<div class="pis-linkto-tax-name pis-alert">
									<?php // ================= Taxonomy name for archive link
									pis_form_input_text(
										__( 'Taxonomy name', 'posts-in-sidebar' ),
										$this->get_field_id( 'tax_name' ),
										$this->get_field_name( 'tax_name' ),
										esc_attr( $instance['tax_name'] ),
										__( 'genre', 'posts-in-sidebar' ),
										sprintf( __( 'Enter the term name of the custom taxonomy (e.g., %1$sgenre%2$s).%3$sUse this field only if you selected "Custom taxonomy" in the "Link to the archive of" dropdown menu.', 'posts-in-sidebar' ), '<code>', '</code>', '<br />' ),
										'margin: 0; padding: 0.5em;'
									); ?>
								</div>

								<div class="pis-linkto-term-name">
									<?php // ================= Taxonomy term name for archive link
									pis_form_input_text(
										__( 'Taxonomy term name', 'posts-in-sidebar' ),
										$this->get_field_id( 'tax_term_name' ),
										$this->get_field_name( 'tax_term_name' ),
										esc_attr( $instance['tax_term_name'] ),
										__( 'science', 'posts-in-sidebar' ),
										sprintf( __( 'Enter the name of the taxonomy term (e.g., %1$sscience%2$s if the taxonomy is "genre").%3$sIf you selected "Author" in "Link to the archive of" field, enter the author slug; if you selected "Category", enter the category slug, and so on.', 'posts-in-sidebar' ), '<code>', '</code>', '<br />' ),
										'margin: 0; padding: 0.5em;'
									); ?>
								</div>

							</div>

						</div>

						<?php // ================= Archive link text
						pis_form_input_text(
							__( 'Use this text for archive link', 'posts-in-sidebar' ),
							$this->get_field_id( 'archive_text' ),
							$this->get_field_name( 'archive_text' ),
							esc_attr( $instance['archive_text'] ),
							__( 'Display all posts under %s', 'posts-in-sidebar' ),
							sprintf( __( 'Use %s to display the taxonomy term name.', 'posts-in-sidebar' ), '<code>%s</code>' )
						); ?>

					</div>

				</div>

				<div class="pis-section">

					<h4 class="pis-widget-title"><?php _e( 'When no posts are found', 'posts-in-sidebar' ); ?></h4>

					<div class="pis-container">

							<?php // ================= When no posts are found
							// Text when no posts found
							pis_form_input_text(
								__( 'Use this text when there are no posts', 'posts-in-sidebar' ),
								$this->get_field_id( 'nopost_text' ),
								$this->get_field_name( 'nopost_text' ),
								esc_attr( $instance['nopost_text'] ),
								__( 'No posts yet.', 'posts-in-sidebar' )
							); ?>

							<?php
							// Hide the widget if no posts found
							pis_form_checkbox(
								__( 'Completely hide the widget if no posts are found', 'posts-in-sidebar' ),
								$this->get_field_id( 'hide_widget' ),
								$this->get_field_name( 'hide_widget' ),
								checked( $hide_widget, true, false )
							); ?>

					</div>

				</div>

			</div>

		</div>

		<!-- Styles -->
		<div class="pis-section">

			<h4 class="pis-widget-title"><?php _e( 'Styles', 'posts-in-sidebar' ); ?></h4>

			<div class="pis-container">

				<p><em><?php _e( 'This section defines the margin for each line of the widget. Leave blank if you don\'t want to add any local style.', 'posts-in-sidebar' ); ?></em></p>

				<div class="pis-column-container">

					<div class="pis-column">

						<?php // ================= Margin unit
						$options = array(
							'px' => array(
								'value' => 'px',
								'desc'  => 'px'
							),
							'%' => array(
								'value' => '%',
								'desc'  => '%'
							),
							'em' => array(
								'value' => 'em',
								'desc'  => 'em'
							),
							'rem' => array(
								'value' => 'rem',
								'desc'  => 'rem'
							),
						);
						pis_form_select(
							__( 'Unit for margins', 'posts-in-sidebar' ),
							$this->get_field_id('margin_unit'),
							$this->get_field_name('margin_unit'),
							$options,
							$instance['margin_unit']
						); ?>

						<p><?php printf( __( 'Enter here only the value without any unit, e.g. enter %1$s if you want a space of 10px or enter %2$s if you don\'t want any space.', 'posts-in-sidebar' ), '<code>10</code>', '<code>0</code>' ); ?></p>

					</div>

				</div>

				<div class="pis-column-container">

					<?php // ================= Margins ?>

					<div class="pis-column">
						<?php pis_form_input_text( __( 'Introduction bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'intro_margin' ), $this->get_field_name( 'intro_margin' ), esc_attr( $instance['intro_margin'] ) ); ?>
						<?php pis_form_input_text( __( 'Title bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'title_margin' ), $this->get_field_name( 'title_margin' ), esc_attr( $instance['title_margin'] ) ); ?>
						<?php pis_form_input_text( __( 'Image left &amp; right margin', 'posts-in-sidebar' ), $this->get_field_id( 'side_image_margin' ), $this->get_field_name( 'side_image_margin' ), esc_attr( $instance['side_image_margin'] ) ); ?>
						<?php pis_form_input_text( __( 'Image bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'bottom_image_margin' ), $this->get_field_name( 'bottom_image_margin' ), esc_attr( $instance['bottom_image_margin'] ) ); ?>
					</div>

					<div class="pis-column">
						<?php pis_form_input_text( __( 'Excerpt bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'excerpt_margin' ), $this->get_field_name( 'excerpt_margin' ), esc_attr( $instance['excerpt_margin'] ) ); ?>
						<?php pis_form_input_text( __( 'Utility bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'utility_margin' ), $this->get_field_name( 'utility_margin' ), esc_attr( $instance['utility_margin'] ) ); ?>
						<?php pis_form_input_text( __( 'Categories bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'categories_margin' ), $this->get_field_name( 'categories_margin' ), esc_attr( $instance['categories_margin'] ) ); ?>
						<?php pis_form_input_text( __( 'Tags bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'tags_margin' ), $this->get_field_name( 'tags_margin' ), esc_attr( $instance['tags_margin'] ) ); ?>
					</div>

					<div class="pis-column">
						<?php pis_form_input_text( __( 'Terms bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'terms_margin' ), $this->get_field_name( 'terms_margin' ), esc_attr( $instance['terms_margin'] ) ); ?>
						<?php pis_form_input_text( __( 'Custom field bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'custom_field_margin' ), $this->get_field_name( 'custom_field_margin' ), esc_attr( $instance['custom_field_margin'] ) ); ?>
						<?php pis_form_input_text( __( 'Archive bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'archive_margin' ), $this->get_field_name( 'archive_margin' ), esc_attr( $instance['archive_margin'] ) ); ?>
						<?php pis_form_input_text( __( 'No-posts bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'noposts_margin' ), $this->get_field_name( 'noposts_margin' ), esc_attr( $instance['noposts_margin'] ) ); ?>
					</div>

				</div>

				<!-- Custom styles -->
				<div class="pis-section">

					<h4 class="pis-widget-title"><?php _e( 'Custom styles', 'posts-in-sidebar' ); ?></h4>

					<div class="pis-container">

						<p><em>
							<?php printf( __( 'In this field you can add your own styles, for example: %s', 'posts-in-sidebar' ), '<code>.pis-excerpt { color: green; }</code>' ); ?>
							<br>
							<?php printf( __( 'To apply a style only to elements of this widget, prefix every style with this ID selector: %s', 'posts-in-sidebar' ), '<code>#' . $this->id . '</code>' ); ?>
							<br>
							<?php printf( __( 'For example: %s', 'posts-in-sidebar' ), '<pre><code>#' . $this->id . ' .pis-title { font-size: 18px !important; }</code></pre>' ); ?>
						</em></p>

						<?php // ================= Custom styles
						pis_form_textarea(
							__( 'Custom styles', 'posts-in-sidebar' ),
							$this->get_field_id('custom_styles'),
							$this->get_field_name('custom_styles'),
							$instance['custom_styles'],
							__( 'Enter here your CSS styles', 'posts-in-sidebar' ),
							$style = 'resize: vertical; width: 100%; height: 80px;'
						); ?>

					</div>

				</div>

				<!-- Extras -->
				<div class="pis-section">

					<h4 class="pis-widget-title"><?php _e( 'Extras', 'posts-in-sidebar' ); ?></h4>

					<div class="pis-container">

						<?php // ================= Container Class
						pis_form_input_text(
							__( 'Add a global container with this CSS class', 'posts-in-sidebar' ),
							$this->get_field_id('container_class'),
							$this->get_field_name('container_class'),
							esc_attr( $instance['container_class'] ),
							'posts-container',
							sprintf(
								__( 'Enter the name of your container (for example, %1$s). The plugin will add a new %2$s container with this class. You can enter only one class and the name may contain only letters, hyphens and underscores. The new container will enclose all the widget, from the title to the last line.', 'posts-in-sidebar' ), '<code>my-container</code>', '<code>div</code>' )
						); ?>

						<?php // ================= Type of HTML for list of posts
						$options = array(
							'ul' => array(
								'value' => 'ul',
								'desc'  => __( 'Unordered list', 'posts-in-sidebar' )
							),
							'ol' => array(
								'value' => 'ol',
								'desc'  => __( 'Ordered list', 'posts-in-sidebar' )
							),
						);
						pis_form_select(
							__( 'Use this type of list for the posts', 'posts-in-sidebar' ),
							$this->get_field_id('list_element'),
							$this->get_field_name('list_element'),
							$options,
							$instance['list_element']
						); ?>

						<?php // ================= Remove bullets and left space
						pis_form_checkbox(
							__( 'Try to remove the bullets and the extra left space from the list elements', 'posts-in-sidebar' ),
							$this->get_field_id( 'remove_bullets' ),
							$this->get_field_name( 'remove_bullets' ),
							checked( $remove_bullets, true, false ),
							sprintf( __( 'If the plugin doesn\'t remove the bullets and/or the extra left space, you have to %1$sedit your CSS file%2$s manually.', 'posts-in-sidebar' ), '<a href="' . admin_url( 'theme-editor.php' ) . '" target="_blank">', '</a>' )
						); ?>

					</div>

				</div>

			</div>

		</div>

		<!-- Cache -->
		<div class="pis-section">

			<h4 class="pis-widget-title"><?php _e( 'Cache', 'posts-in-sidebar' ); ?></h4>

			<div class="pis-container pis-2col">

				<div class="pis-column-container">

					<div class="pis-column">

						<?php // ================= Cache for the query
						pis_form_checkbox( __( 'Use a cache to serve the output', 'posts-in-sidebar' ),
							$this->get_field_id( 'cached' ),
							$this->get_field_name( 'cached' ),
							checked( $cached, true, false ),
							__( 'This option, if activated, will increase the performance but will show the same output during the defined cache time.', 'posts-in-sidebar' )
						); ?>

					</div>

					<div class="pis-column">

						<?php // ================= Cache duration
						pis_form_input_text(
							__( 'The cache will be used for (in seconds)', 'posts-in-sidebar' ),
							$this->get_field_id('cache_time'),
							$this->get_field_name('cache_time'),
							esc_attr( $instance['cache_time'] ),
							'3600',
							sprintf( __( 'For example, %1$s for one hour of cache. To reset the cache, enter %2$s and save the widget.', 'posts-in-sidebar' ), '<code>3600</code>', '<code>0</code>' )
						); ?>
					</div>

				</div>

			</div>

		</div>

		<!-- Debugging -->
		<div class="pis-section">

			<h4 class="pis-widget-title"><?php _e( 'Debugging', 'posts-in-sidebar' ); ?></h4>

			<div class="pis-container">

				<p><?php printf( __( 'You are using Posts in Sidebar version %s.', 'posts-in-sidebar' ), '<strong>' . PIS_VERSION . '</strong>' ); ?></p>

				<p class="pis-alert"><strong><?php _e( 'Use this options for debugging purposes only. Please note that the informations will be displayed publicly on your site.', 'posts-in-sidebar' ); ?></strong></p>

				<?php // ================= Debug: display the query for the widget
				pis_form_checkbox(
					__( 'Display the query for the widget', 'posts-in-sidebar' ),
					$this->get_field_id( 'debug_query' ),
					$this->get_field_name( 'debug_query' ),
					checked( $debug_query, true, false )
				); ?>

				<?php // ================= Debug: display the complete set of parameters for the widget
				pis_form_checkbox(
					__( 'Display the complete set of parameters for the widget', 'posts-in-sidebar' ),
					$this->get_field_id( 'debug_params' ),
					$this->get_field_name( 'debug_params' ),
					checked( $debug_params, true, false )
				); ?>

				<?php // ================= Debug: display the total number of queries
				pis_form_checkbox(
					__( 'Display the total number of queries, including WordPress, current theme and all active plugins', 'posts-in-sidebar' ),
					$this->get_field_id( 'debug_query_number' ),
					$this->get_field_name( 'debug_query_number' ),
					checked( $debug_query_number, true, false )
				); ?>

			</div>

		</div>

		<?php
	}

}

/***********************************************************************
 *                            CODE IS POETRY
 **********************************************************************/
