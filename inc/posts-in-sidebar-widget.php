<?php
/**
 * Create the widget.
 *
 * @package PostsInSidebar
 * @since 1.0
 */

class PIS_Posts_In_Sidebar extends WP_Widget {

	/**
	 * Create the widget's base settings.
	 * Uses PHP4 constructor method.
	 * 
	 * @since 1.0
	 */
	function PIS_Posts_In_Sidebar() {

		/* Widget settings. */
		$widget_ops = array(
			'classname'   => 'posts-in-sidebar',
			'description' => __( 'Display a list of posts in a widget', 'pis' ),
		);

		/* Widget control settings. */
		$control_ops = array(
			'width'   => 800,
			'id_base' => 'pis_posts_in_sidebar',
		);

		/* Create the widget. */
		$this->WP_Widget( 'pis_posts_in_sidebar', __( 'Posts in Sidebar', 'pis' ), $widget_ops, $control_ops );
	}

	/**
	 * Display the content of the widget in the front-end.
	 *
	 * @param array $args
	 * @param array $instance
	 * @since 1.0
	 */
	function widget( $args, $instance ) {
		/**
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

		echo $before_widget;

		// Add a new container if the "Container Class" is not empty
		if ( $instance['container_class'] ) {
			echo '<div class="' . $instance['container_class'] . '">';
		}

		if ( $title && $instance['title_link'] ) {
			echo $before_title . '<a class="pis-title-link" href="' . esc_url( $instance['title_link'] ) . '">' . $title . '</a>' . $after_title;
		} else if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		pis_posts_in_sidebar( array(
			// The title of the widget
			'intro'               => $instance['intro'],

			// Posts retrieving
			'post_type'           => $instance['post_type'],
			'posts_id'            => $instance['posts_id'],
			'author'              => $instance['author'],
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
			'date_compare'        => $instance['date_compare'],
			'date_column'         => $instance['date_column'],
			'date_relation'       => $instance['date_relation'],

			// Posts exclusion
			'exclude_current_post'=> $instance['exclude_current_post'],
			'post_not_in'         => $instance['post_not_in'],
			'cat_not_in'          => $instance['cat_not_in'],
			'tag_not_in'          => $instance['tag_not_in'],

			// The title of the post
			'display_title'       => $instance['display_title'],
			'link_on_title'       => $instance['link_on_title'],
			'arrow'               => $instance['arrow'],

			// The featured image of the post
			'display_image'       => $instance['display_image'],
			'image_size'          => $instance['image_size'],
			'image_align'         => $instance['image_align'],
			'image_before_title'  => $instance['image_before_title'],
			'custom_image_url'    => $instance['custom_image_url'],
			'custom_img_no_thumb' => $instance['custom_img_no_thumb'],

			// The text of the post
			'excerpt'             => $instance['excerpt'],
			'exc_length'          => $instance['exc_length'],
			'the_more'            => $instance['the_more'],
			'exc_arrow'           => $instance['exc_arrow'],

			// Author, date and comments
			'display_author'      => $instance['display_author'],
			'author_text'         => $instance['author_text'],
			'linkify_author'      => $instance['linkify_author'],
			'display_date'        => $instance['display_date'],
			'date_text'           => $instance['date_text'],
			'linkify_date'        => $instance['linkify_date'],
			'comments'            => $instance['comments'],
			'comments_text'       => $instance['comments_text'],
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
			'custom_field_key'    => $instance['custom_field_key'],
			'custom_field_sep'    => $instance['custom_field_sep'],

			// The link to the archive
			'archive_link'        => $instance['archive_link'],
			'link_to'             => $instance['link_to'],
			'archive_text'        => $instance['archive_text'],

			// Text when no posts found
			'nopost_text'         => $instance['nopost_text'],
			'hide_widget'         => $instance['hide_widget'],

			// Extras
			'list_element'        => $instance['list_element'],
			'remove_bullets'      => $instance['remove_bullets'],

			// Cache
			'cached'              => $instance['cached'],
			'cache_time'          => $instance['cache_time'],
			/*
				The following 'widget_id' variable will be used in the main function
				to check if a cached version of the query already exists
				for every instance of the widget.
			*/
			'widget_id'           => $this->id, // $this->id is the id of the widget instance.

			// Elements margins
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

			// Debug
			'debug_query'         => $instance['debug_query'],
			'debug_params'        => $instance['debug_params'],
			'debug_query_number'  => $instance['debug_query_number'],
		));

		if ( $instance['container_class'] ) {
			echo '</div>';
		}

		echo $after_widget;
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
	function update( $new_instance, $old_instance ) {
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
			/**
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
		$instance['ignore_sticky']       = isset( $new_instance['ignore_sticky'] ) ? 1 : 0;

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
		$instance['date_compare']        = $new_instance['date_compare'];
		$instance['date_column']         = $new_instance['date_column'];
		$instance['date_relation']       = $new_instance['date_relation'];
		
		// Posts exclusion
		$instance['exclude_current_post']= isset( $new_instance['exclude_current_post'] ) ? 1 : 0 ;
		$instance['post_not_in']         = strip_tags( $new_instance['post_not_in'] );
		$instance['cat_not_in']          = strip_tags( $new_instance['cat_not_in'] );
		$instance['tag_not_in']          = strip_tags( $new_instance['tag_not_in'] );

		// The title of the post
		$instance['display_title']       = isset( $new_instance['display_title'] ) ? 1 : 0;
		$instance['link_on_title']       = isset( $new_instance['link_on_title'] ) ? 1 : 0;
		$instance['arrow']               = isset( $new_instance['arrow'] ) ? 1 : 0;

		// The featured image of the post
		$instance['display_image']       = isset( $new_instance['display_image'] ) ? 1 : 0;
		$instance['image_size']          = $new_instance['image_size'];
		$instance['image_align']         = $new_instance['image_align'];
		$instance['image_before_title']  = isset( $new_instance['image_before_title'] ) ? 1 : 0;
		$instance['custom_image_url']    = esc_url( strip_tags( $new_instance['custom_image_url'] ) );
		$instance['custom_img_no_thumb'] = isset( $new_instance['custom_img_no_thumb'] ) ? 1 : 0;

		// The text of the post
		$instance['excerpt']             = $new_instance['excerpt'];
		$instance['exc_length']          = absint( strip_tags( $new_instance['exc_length'] ) );
			if ( '' == $instance['exc_length'] || ! is_numeric( $instance['exc_length'] ) ) $instance['exc_length'] = 20;
		$instance['the_more']            = strip_tags( $new_instance['the_more'] );
		$instance['exc_arrow']           = isset( $new_instance['exc_arrow'] ) ? 1 : 0;

		// Author, date and comments
		$instance['display_author']      = isset( $new_instance['display_author'] ) ? 1 : 0;
		$instance['author_text']         = strip_tags( $new_instance['author_text'] );
		$instance['linkify_author']      = isset( $new_instance['linkify_author'] ) ? 1 : 0;
		$instance['display_date']        = isset( $new_instance['display_date'] ) ? 1 : 0;
		$instance['date_text']           = strip_tags( $new_instance['date_text'] );
		$instance['linkify_date']        = isset( $new_instance['linkify_date'] ) ? 1 : 0;
		$instance['comments']            = isset( $new_instance['comments'] ) ? 1 : 0;
		$instance['comments_text']       = strip_tags( $new_instance['comments_text'] );
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
		$instance['custom_field_key']    = isset( $new_instance['custom_field_key'] ) ? 1 : 0;
		$instance['custom_field_sep']    = strip_tags( $new_instance['custom_field_sep'] );

		// The link to the archive
		$instance['archive_link']        = isset( $new_instance['archive_link'] ) ? 1 : 0;
		$instance['link_to']             = $new_instance['link_to'];
		$instance['archive_text']        = strip_tags( $new_instance['archive_text'] );

		// Text when no posts found
		$instance['nopost_text']         = strip_tags( $new_instance['nopost_text'] );
		$instance['hide_widget']         = isset( $new_instance['hide_widget'] ) ? 1 : 0;

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

		// Elements margins
		$instance['margin_unit']         = $new_instance['margin_unit'];
		$instance['intro_margin']        = strip_tags( $new_instance['intro_margin'] );
			if ( ! is_numeric( $new_instance['intro_margin'] ) ) $instance['intro_margin'] = NULL;
		$instance['title_margin']        = strip_tags( $new_instance['title_margin'] );
			if ( ! is_numeric( $new_instance['title_margin'] ) ) $instance['title_margin'] = NULL;
		$instance['side_image_margin']   = $new_instance['side_image_margin'];
			if ( ! is_numeric( $new_instance['side_image_margin'] ) ) $instance['side_image_margin'] = NULL;
		$instance['bottom_image_margin'] = $new_instance['bottom_image_margin'];
			if ( ! is_numeric( $new_instance['bottom_image_margin'] ) ) $instance['bottom_image_margin'] = NULL;
		$instance['excerpt_margin']      = strip_tags( $new_instance['excerpt_margin'] );
			if ( ! is_numeric( $new_instance['excerpt_margin'] ) ) $instance['excerpt_margin'] = NULL;
		$instance['utility_margin']      = strip_tags( $new_instance['utility_margin'] );
			if ( ! is_numeric( $new_instance['utility_margin'] ) ) $instance['utility_margin'] = NULL;
		$instance['categories_margin']   = strip_tags( $new_instance['categories_margin'] );
			if ( ! is_numeric( $new_instance['categories_margin'] ) ) $instance['categories_margin'] = NULL;
		$instance['tags_margin']         = strip_tags( $new_instance['tags_margin'] );
			if ( ! is_numeric( $new_instance['tags_margin'] ) ) $instance['tags_margin'] = NULL;
		$instance['terms_margin']        = strip_tags( $new_instance['terms_margin'] );
			if ( ! is_numeric( $new_instance['terms_margin'] ) ) $instance['terms_margin'] = NULL;
		$instance['custom_field_margin'] = strip_tags( $new_instance['custom_field_margin'] );
			if ( ! is_numeric( $new_instance['custom_field_margin'] ) ) $instance['custom_field_margin'] = NULL;
		$instance['archive_margin']      = strip_tags( $new_instance['archive_margin'] );
			if ( ! is_numeric( $new_instance['archive_margin'] ) ) $instance['archive_margin'] = NULL;
		$instance['noposts_margin']      = strip_tags( $new_instance['noposts_margin'] );
			if ( ! is_numeric( $new_instance['noposts_margin'] ) ) $instance['noposts_margin'] = NULL;
		$instance['custom_styles']       = strip_tags( $new_instance['custom_styles'] );

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
	function form( $instance ) {
		$defaults = array(
			// The title of the widget
			'title'               => __( 'Posts', 'pis' ),
			'title_link'          => '',
			'intro'               => '',

			// Posts retrieving
			'post_type'           => 'post',
			'posts_id'            => '',
			'author'              => '',
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
			'search'              => '',
			'ignore_sticky'       => false,
			
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
			'date_inclusive'      => '',
			'date_compare'        => '',
			'date_column'         => '',
			'date_relation'       => '',

			// Posts exclusion
			'exclude_current_post'=> false,
			'post_not_in'         => '',
			'cat_not_in'          => '',
			'tag_not_in'          => '',

			// The title of the post
			'display_title'       => true,
			'link_on_title'       => true,
			'arrow'               => false,

			// The featured image of the post
			'display_image'       => false,
			'image_size'          => 'thumbnail',
			'image_align'         => 'no_change',
			'image_before_title'  => false,
			'custom_image_url'    => '',
			'custom_img_no_thumb' => true,

			// The text of the post
			'excerpt'             => 'excerpt',
			'exc_length'          => 20,
			'the_more'            => __( 'Read more&hellip;', 'pis' ),
			'exc_arrow'           => false,

			// Author, date and comments
			'display_author'      => false,
			'author_text'         => __( 'By', 'pis' ),
			'linkify_author'      => false,
			'display_date'        => false,
			'date_text'           => __( 'Published on', 'pis' ),
			'linkify_date'        => false,
			'comments'            => false,
			'comments_text'       => __( 'Comments:', 'pis' ),
			'utility_sep'         => '|',
			'utility_after_title' => false,

			// The categories of the post
			'categories'          => false,
			'categ_text'          => __( 'Category:', 'pis' ),
			'categ_sep'           => ',',

			// The tags of the post
			'tags'                => false,
			'tags_text'           => __( 'Tags:', 'pis' ),
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
			'custom_field_key'    => false,
			'custom_field_sep'    => ':',

			// The link to the archive
			'archive_link'        => false,
			'link_to'             => 'category',
			'archive_text'        => __( 'Display all posts', 'pis' ),

			// Text when no posts found
			'nopost_text'         => __( 'No posts yet.', 'pis' ),
			'hide_widget'         => false,

			// Extras
			'container_class'     => '',
			'list_element'        => 'ul',
			'remove_bullets'      => false,

			// Cache
			'cached'              => false,
			'cache_time'          => '',

			// Elements margins
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

			// Debug
			'debug_query'         => false,
			'debug_params'        => false,
			'debug_query_number'  => false,
		);
		$instance             = wp_parse_args( (array) $instance, $defaults );
		$ignore_sticky        = (bool) $instance['ignore_sticky'];
		$date_inclusive       = (bool) $instance['date_inclusive'];
		$exclude_current_post = (bool) $instance['exclude_current_post'];
		$display_title        = (bool) $instance['display_title'];
		$link_on_title        = (bool) $instance['link_on_title'];
		$display_image        = (bool) $instance['display_image'];
		$image_before_title   = (bool) $instance['image_before_title'];
		$arrow                = (bool) $instance['arrow'];
		$custom_img_no_thumb  = (bool) $instance['custom_img_no_thumb'];
		$exc_arrow            = (bool) $instance['exc_arrow'];
		$utility_after_title  = (bool) $instance['utility_after_title'];
		$display_author       = (bool) $instance['display_author'];
		$linkify_author       = (bool) $instance['linkify_author'];
		$display_date         = (bool) $instance['display_date'];
		$linkify_date         = (bool) $instance['linkify_date'];
		$comments             = (bool) $instance['comments'];
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
		?>

		<style>
			.pis-section, .clear {
				clear: both;
			}
			.pis-section:after, div.clear {
				content: "";
				display: table;
				clear: both;
			}
			.pis-section-gray {
				background-color: #E5EAED;
				margin-top: 20px;
			}
			.pis-column {
				float: left;
				width: 31%;
				margin-right: 2%;
			}
			.pis-column-last {
				margin-right: 0;
			}
			.pis-2col {
				width: 98%;
				padding: 1%;
			}
			.pis-2col .pis-column {
				width: 48%;
			}
			.pis-widget-title {
				background-color: #0073AA;
				color: #fff;
				padding: 3px 5px;
				clear: left;
				text-align: center;
			}
			.pis-2col .pis-widget-title {
				margin-top: 0;
			}
			.pis-alert {
				color: #777;
				font-weight: bold;
			}
			.pis-title-center {
				text-align: center;
			}
			.pis-clickable {
				cursor: pointer;
			}
			.pis-clickable:hover {
				background-color: #2B8AB8;
			}
			.pis-title-center:hover {
				background-color: transparent;
			}
			.pis-container {
				display: none;
			}
			.pis-container-open {
				display: block;
			}
		</style>

		<!-- Widget title -->
		<div class="pis-section">

			<h4 class="pis-widget-title pis-clickable"><?php _e( 'The title of the widget', 'pis' ); ?></h4>

			<div class="pis-container pis-container-open">

				<?php pis_form_input_text(
					__( 'Title', 'pis' ),
					$this->get_field_id('title'),
					$this->get_field_name('title'),
					esc_attr( $instance['title'] ),
					__( 'From the archive', 'pis' )
				); ?>

				<?php pis_form_input_text(
					__( 'Link the title of the widget to this URL', 'pis' ),
					$this->get_field_id('title_link'),
					$this->get_field_name('title_link'),
					esc_url( strip_tags( $instance['title_link'] ) ),
					'http://example.com/readings-series/'
				); ?>

				<?php pis_form_textarea(
					__( 'Place this text after the title', 'pis' ),
					$this->get_field_id('intro'),
					$this->get_field_name('intro'),
					$instance['intro'],
					__( 'These posts are part of my Readings series.', 'pis' ),
					$style = 'resize: vertical; width: 100%; height: 80px;',
					$comment = sprintf( __( 'Allowed HTML: %s. Other tags will be stripped.', 'pis' ), '<code>a</code>, <code>strong</code>, <code>em</code>' )
				); ?>

			</div>

		</div>

		<hr />

		<!-- Getting posts -->
		<div class="pis-section">

			<h4 class="pis-widget-title pis-clickable"><?php _e( 'Getting posts', 'pis' ); ?></h4>

			<div class="pis-container">

				<div class="pis-column">

					<?php // ================= Post types

					$args = array(
						'public' => true,
					);
					$post_types = (array) get_post_types( $args, 'objects', 'and' );

					$options = array(
						array(
							'value' => 'any',
							'desc'  => __( 'Any', 'pis' ),
						)
					);
					foreach ( $post_types as $post_type ) {
						$options[] = array(
							'value' => $post_type->name,
							'desc'  => $post_type->labels->singular_name,
						);
					}

					pis_form_select(
						__( 'Post type', 'pis' ),
						$this->get_field_id('post_type'),
						$this->get_field_name('post_type'),
						$options,
						$instance['post_type']
					); ?>

					<?php // ================= Posts ID
					pis_form_input_text(
						__( 'Get these posts exactly', 'pis' ),
						$this->get_field_id('posts_id'),
						$this->get_field_name('posts_id'),
						esc_attr( $instance['posts_id'] ),
						'5, 29, 523, 4519',
						sprintf( __( 'Insert IDs separated by commas. To easily find the IDs, install %1$sthis plugin%2$s.', 'pis' ), '<a href="http://wordpress.org/plugins/reveal-ids-for-wp-admin-25/" target="_blank">', '</a>' )
					); ?>

					<?php // ================= Author
					$options = array(
						array(
							'value' => '',
							'desc'  => __( 'Any', 'pis' )
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
						__( 'Get posts by this author', 'pis' ),
						$this->get_field_id('author'),
						$this->get_field_name('author'),
						$options,
						$instance['author']
					); ?>

					<?php // ================= Category
					pis_form_input_text(
						__( 'Get posts with these categories', 'pis' ),
						$this->get_field_id('cat'),
						$this->get_field_name('cat'),
						esc_attr( $instance['cat'] ),
						__( 'books, ebooks', 'pis' ),
						sprintf( __( 'Insert slugs separated by commas. To display posts that have all of the categories, use %1$s (a plus) between terms, for example:%2$s%3$s.', 'pis' ), '<code>+</code>', '<br />', '<code>staff+news+our-works</code>' )
					); ?>

					<?php // ================= Tag
					pis_form_input_text(
						__( 'Get posts with these tags', 'pis' ),
						$this->get_field_id('tag'),
						$this->get_field_name('tag'),
						esc_attr( $instance['tag'] ),
						__( 'best-sellers', 'pis' ),
						sprintf( __( 'Insert slugs separated by commas. To display posts that have all of the tags, use %1$s (a plus) between terms, for example:%2$s%3$s.', 'pis' ), '<code>+</code>', '<br />', '<code>staff+news+our-works</code>' )
					); ?>

				</div>

				<div class="pis-column">

					<?php // ================= Post parent
					pis_form_input_text(
						__( 'Get posts whose parent is in these IDs', 'pis' ),
						$this->get_field_id('post_parent_in'),
						$this->get_field_name('post_parent_in'),
						esc_attr( $instance['post_parent_in'] ),
						__( '2, 5, 12, 14, 20', 'pis' ),
						__( 'Insert IDs separated by commas.' )
					); ?>

					<?php // ================= Post format
					$options = array(
						array(
							'value' => '',
							'desc'  => __( 'Any', 'pis' )
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
						__( 'Get posts with this post format', 'pis' ),
						$this->get_field_id('post_format'),
						$this->get_field_name('post_format'),
						$options,
						$instance['post_format']
					); ?>

					<?php // ================= Post status
					$options = array();
					$statuses = get_post_stati( '', 'objects' );
					foreach( $statuses as $status ) {
						$options[] = array(
							'value' => $status->name,
							'desc'  => $status->label,
						);
					}
					pis_form_select(
						__( 'Get posts with this post status', 'pis' ),
						$this->get_field_id('post_status'),
						$this->get_field_name('post_status'),
						$options,
						$instance['post_status']
					); ?>

					<?php // ================= Post meta key
					pis_form_input_text(
						__( 'Get post with this meta key', 'pis' ),
						$this->get_field_id('post_meta_key'),
						$this->get_field_name('post_meta_key'),
						esc_attr( $instance['post_meta_key'] ),
						__( 'meta-key', 'pis' )
					); ?>

					<?php // ================= Post meta value
					pis_form_input_text(
						__( 'Get post with this meta value', 'pis' ),
						$this->get_field_id('post_meta_val'),
						$this->get_field_name('post_meta_val'),
						esc_attr( $instance['post_meta_val'] ),
						__( 'meta-value', 'pis' )
					); ?>

					<?php // ================= Search
					pis_form_input_text(
						__( 'Get post from this search', 'pis' ),
						$this->get_field_id('search'),
						$this->get_field_name('search'),
						esc_attr( $instance['search'] ),
						__( 'words to search', 'pis' )
					); ?>

				</div>

				<div class="pis-column pis-column-last">

					<?php // ================= Ignore sticky post
					pis_form_checkbox( __( 'Do not display sticky posts on top of other posts', 'pis' ), $this->get_field_id( 'ignore_sticky' ), $this->get_field_name( 'ignore_sticky' ), checked( $ignore_sticky, true, false ), __( 'If you activate this option, sticky posts will be managed as other posts. Sticky post status will be automatically ignored if you set up an author or a taxonomy in this widget.', 'pis' ) ); ?>

					<?php // ================= Posts quantity
					pis_form_input_text(
						__( 'Get this number of posts', 'pis' ),
						$this->get_field_id('number'),
						$this->get_field_name('number'),
						esc_attr( $instance['number'] ),
						'3',
						sprintf( __( 'The value %s shows all the posts.', 'pis' ), '<code>-1</code>' )
					); ?>

					<?php // ================= Post order by
					$options = array(
						'none' => array(
							'value' => 'none',
							'desc'  => __( 'None', 'pis' )
						),
						'id' => array(
							'value' => 'id',
							'desc'  => __( 'ID', 'pis' )
						),
						'author' => array(
							'value' => 'author',
							'desc'  => __( 'Author', 'pis' )
						),
						'title' => array(
							'value' => 'title',
							'desc'  => __( 'Title', 'pis' )
						),
						'name' => array(
							'value' => 'name',
							'desc'  => __( 'Name (post slug)', 'pis' )
						),
						'date' => array(
							'value' => 'date',
							'desc'  => __( 'Date', 'pis' )
						),
						'modified' => array(
							'value' => 'modified',
							'desc'  => __( 'Modified', 'pis' )
						),
						'parent' => array(
							'value' => 'parent',
							'desc'  => __( 'Parent', 'pis' )
						),
						'rand' => array(
							'value' => 'rand',
							'desc'  => __( 'Random', 'pis' )
						),
						'comment_count' => array(
							'value' => 'comment_count',
							'desc'  => __( 'Comment count', 'pis' )
						),
						'menu_order' => array(
							'value' => 'menu_order',
							'desc'  => __( 'Menu order', 'pis' )
						),
						'meta_value' => array(
							'value' => 'meta_value',
							'desc'  => __( 'Meta value', 'pis' )
						),
						'meta_value_num' => array(
							'value' => 'meta_value_num',
							'desc'  => __( 'Meta value number', 'pis' )
						),
						'post__in' => array(
							'value' => 'post__in',
							'desc'  => __( 'Preserve ID order', 'pis' )
						),
					);
					pis_form_select(
						__( 'Order posts by', 'pis' ),
						$this->get_field_id('orderby'),
						$this->get_field_name('orderby'),
						$options,
						$instance['orderby']
					); ?>

					<?php // ================= Post order
					$options = array(
						'asc' => array(
							'value' => 'ASC',
							'desc'  => __( 'Ascending', 'pis' )
						),
						'desc' => array(
							'value' => 'DESC',
							'desc'  => __( 'Descending', 'pis' )
						),
					);
					pis_form_select(
						__( 'The order will be', 'pis' ),
						$this->get_field_id('order'),
						$this->get_field_name('order'),
						$options,
						$instance['order']
					); ?>

					<?php // ================= Number of posts to skip
					pis_form_input_text(
						__( 'Skip this number of posts', 'pis' ),
						$this->get_field_id('offset_number'),
						$this->get_field_name('offset_number'),
						esc_attr( $instance['offset_number'] ),
						'5'
					); ?>

				</div>

				<hr class="clear" />

				<h4 class="pis-title-center pis-clickable"><?php _e( 'Excluding posts', 'pis' ); ?></h4>

				<div class="pis-container">

					<div class="pis-column">

						<?php // ================= Exclude posts from categories
						if ( is_array( $instance['cat_not_in'] ) )
							$var = implode( ',', $instance['cat_not_in'] );
						else
							$var = $instance['cat_not_in'];
						pis_form_input_text(
							__( 'Exclude posts from these categories', 'pis' ),
							$this->get_field_id('cat_not_in'),
							$this->get_field_name('cat_not_in'),
							esc_attr( $var ),
							'3, 31',
							__( 'Insert IDs separated by commas.', 'pis' )
						); ?>

						<?php // ================= Exclude posts from tags
						if ( is_array( $instance['tag_not_in'] ) )
							$var = implode( ',', $instance['tag_not_in'] );
						else
							$var = $instance['tag_not_in'];
						pis_form_input_text(
							__( 'Exclude posts from these tags', 'pis' ),
							$this->get_field_id('tag_not_in'),
							$this->get_field_name('tag_not_in'),
							esc_attr( $var ),
							'7, 11',
							__( 'Insert IDs separated by commas.', 'pis' )
						); ?>

					</div>

					<div class="pis-column">

						<?php // ================= Exclude posts that have these ids.
						pis_form_input_text(
							__( 'Exclude posts with these IDs', 'pis' ),
							$this->get_field_id('post_not_in'),
							$this->get_field_name('post_not_in'),
							esc_attr( $instance['post_not_in'] ),
							'5, 29, 523, 4519',
							sprintf( __( 'Insert IDs separated by commas. To easily find the IDs, install %1$sthis plugin%2$s.', 'pis' ), '<a href="http://wordpress.org/plugins/reveal-ids-for-wp-admin-25/" target="_blank">', '</a>' )
						); ?>

					</div>

					<div class="pis-column pis-column-last">

						<?php // ================= Exclude current post
						pis_form_checkbox( __( 'Automatically exclude the current post in single post or the current page in single page', 'pis' ), $this->get_field_id( 'exclude_current_post' ), $this->get_field_name( 'exclude_current_post' ), checked( $exclude_current_post, true, false ) ); ?>

					</div>

				</div>

				<hr class="clear" />

				<!-- Taxonomy complex query -->
				<div class="pis-section pis-section-gray pis-2col">

					<h4 class="pis-title-center pis-clickable"><?php _e( 'Taxonomy complex query', 'pis' ); ?></h4>

					<div class="pis-container">

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
						pis_form_select( __( 'Relation between Column A and Column B', 'pis' ), $this->get_field_id('relation'), $this->get_field_name('relation'), $options, $instance['relation'], __( 'The logical relationship between each inner taxonomy array when there is more than one. Do not use with a single inner taxonomy array.', 'pis' ) ); ?>

						<hr />

						<div class="pis-column">

							<h4 class="pis-title-center"><?php _e( 'Column A', 'pis' ); ?></h4>

							<?php // ================= Taxonomy aa
							pis_form_input_text( sprintf( __( '%1$sTaxonomy A1%2$s', 'pis' ), '<strong>', '</strong>' ), $this->get_field_id('taxonomy_aa'), $this->get_field_name('taxonomy_aa'), esc_attr( $instance['taxonomy_aa'] ), __( 'category', 'pis' ), __( 'Insert the slug of the taxonomy.', 'pis' ) ); ?>

							<?php // ================= Field aa
							$options = array(
								'term_id' => array(
									'value' => 'term_id',
									'desc'  => __( 'Term ID', 'pis' )
								),
								'slug' => array(
									'value' => 'slug',
									'desc'  => __( 'Slug', 'pis' )
								),
								'name' => array(
									'value' => 'name',
									'desc'  => __( 'Name', 'pis' )
								),
							);
							pis_form_select( __( 'Field', 'pis' ), $this->get_field_id('field_aa'), $this->get_field_name('field_aa'), $options, $instance['field_aa'], __( 'Select taxonomy term by this field.', 'pis' ) ); ?>

							<?php // ================= Terms aa
							pis_form_input_text( __( 'Terms', 'pis' ), $this->get_field_id('terms_aa'), $this->get_field_name('terms_aa'), esc_attr( $instance['terms_aa'] ), __( 'gnu-linux,kde', 'pis' ), __( 'Insert terms, separated by comma.', 'pis' ) ); ?>

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
							pis_form_select( __( 'Operator', 'pis' ), $this->get_field_id('operator_aa'), $this->get_field_name('operator_aa'), $options, $instance['operator_aa'], __( 'Operator to test for terms.', 'pis' ) ); ?>

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
							pis_form_select( __( 'Relation between A1 and A2 taxonomies', 'pis' ), $this->get_field_id('relation_a'), $this->get_field_name('relation_a'), $options, $instance['relation_a'] ); ?>

							<hr />

							<?php // ================= Taxonomy ab
							pis_form_input_text( sprintf( __( '%1$sTaxonomy A2%2$s', 'pis' ), '<strong>', '</strong>' ), $this->get_field_id('taxonomy_ab'), $this->get_field_name('taxonomy_ab'), esc_attr( $instance['taxonomy_ab'] ), __( 'movie-genre', 'pis' ), __( 'Insert the slug of the taxonomy.', 'pis' ) ); ?>

							<?php // ================= Field ab
							$options = array(
								'term_id' => array(
									'value' => 'term_id',
									'desc'  => __( 'Term ID', 'pis' )
								),
								'slug' => array(
									'value' => 'slug',
									'desc'  => __( 'Slug', 'pis' )
								),
								'name' => array(
									'value' => 'name',
									'desc'  => __( 'Name', 'pis' )
								),
							);
							pis_form_select( __( 'Field', 'pis' ), $this->get_field_id('field_ab'), $this->get_field_name('field_ab'), $options, $instance['field_ab'], __( 'Select taxonomy term by this field.', 'pis' ) ); ?>

							<?php // ================= Terms ab
							pis_form_input_text( __( 'Terms', 'pis' ), $this->get_field_id('terms_ab'), $this->get_field_name('terms_ab'), esc_attr( $instance['terms_ab'] ), __( 'action,sci-fi', 'pis' ), __( 'Insert terms, separated by comma.', 'pis' ) ); ?>

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
							pis_form_select( __( 'Operator', 'pis' ), $this->get_field_id('operator_ab'), $this->get_field_name('operator_ab'), $options, $instance['operator_ab'], __( 'Operator to test for terms.', 'pis' ) ); ?>

						</div>

						<div class="pis-column pis-column-last">

							<h4 class="pis-title-center"><?php _e( 'Column B', 'pis' ); ?></h4>

							<?php // ================= Taxonomy ba
							pis_form_input_text( sprintf( __( '%1$sTaxonomy B1%2$s', 'pis' ), '<strong>', '</strong>' ), $this->get_field_id('taxonomy_ba'), $this->get_field_name('taxonomy_ba'), esc_attr( $instance['taxonomy_ba'] ), __( 'post_tag', 'pis' ), __( 'Insert the slug of the taxonomy.', 'pis' ) ); ?>

							<?php // ================= Field ba
							$options = array(
								'term_id' => array(
									'value' => 'term_id',
									'desc'  => __( 'Term ID', 'pis' )
								),
								'slug' => array(
									'value' => 'slug',
									'desc'  => __( 'Slug', 'pis' )
								),
								'name' => array(
									'value' => 'name',
									'desc'  => __( 'Name', 'pis' )
								),
							);
							pis_form_select( __( 'Field', 'pis' ), $this->get_field_id('field_ba'), $this->get_field_name('field_ba'), $options, $instance['field_ba'], __( 'Select taxonomy term by this field.', 'pis' ) ); ?>

							<?php // ================= Terms ba
							pis_form_input_text( __( 'Terms', 'pis' ), $this->get_field_id('terms_ba'), $this->get_field_name('terms_ba'), esc_attr( $instance['terms_ba'] ), __( 'system,apache', 'pis' ), __( 'Insert terms, separated by comma.', 'pis' ) ); ?>

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
							pis_form_select( __( 'Operator', 'pis' ), $this->get_field_id('operator_ba'), $this->get_field_name('operator_ba'), $options, $instance['operator_ba'], __( 'Operator to test for terms.', 'pis' ) ); ?>

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
							pis_form_select( __( 'Relation between B1 and B2 taxonomies', 'pis' ), $this->get_field_id('relation_b'), $this->get_field_name('relation_b'), $options, $instance['relation_b'] ); ?>

							<hr />

							<?php // ================= Taxonomy bb
							pis_form_input_text( sprintf( __( '%1$sTaxonomy B2%2$s', 'pis' ), '<strong>', '</strong>' ), $this->get_field_id('taxonomy_bb'), $this->get_field_name('taxonomy_bb'), esc_attr( $instance['taxonomy_bb'] ), __( 'recipe', 'pis' ), __( 'Insert the slug of the taxonomy.', 'pis' ) ); ?>

							<?php // ================= Field bb
							$options = array(
								'term_id' => array(
									'value' => 'term_id',
									'desc'  => __( 'Term ID', 'pis' )
								),
								'slug' => array(
									'value' => 'slug',
									'desc'  => __( 'Slug', 'pis' )
								),
								'name' => array(
									'value' => 'name',
									'desc'  => __( 'Name', 'pis' )
								),
							);
							pis_form_select( __( 'Field', 'pis' ), $this->get_field_id('field_bb'), $this->get_field_name('field_bb'), $options, $instance['field_bb'], __( 'Select taxonomy term by this field.', 'pis' ) ); ?>

							<?php // ================= Terms bb
							pis_form_input_text( __( 'Terms', 'pis' ), $this->get_field_id('terms_bb'), $this->get_field_name('terms_bb'), esc_attr( $instance['terms_bb'] ), __( 'pomodoro,onion', 'pis' ), __( 'Insert terms, separated by comma.', 'pis' ) ); ?>

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
							pis_form_select( __( 'Operator', 'pis' ), $this->get_field_id('operator_bb'), $this->get_field_name('operator_bb'), $options, $instance['operator_bb'], __( 'Operator to test for terms.', 'pis' ) ); ?>

						</div>

					</div>

				</div>

				<div class="clear"></div>

				<hr />

				<!-- Date query -->
				<div class="pis-section pis-section-gray pis-2col">

					<h4 class="pis-title-center pis-clickable"><?php _e( 'Date query', 'pis' ); ?></h4>

					<div class="pis-container">

						<div class="pis-column">

							<?php pis_form_input_text(
								__( 'Year', 'pis' ),
								$this->get_field_id('date_year'),
								$this->get_field_name('date_year'),
								esc_attr( $instance['date_year'] ),
								'2015',
								__( '4 digits year (e.g. 2015).', 'pis' )
							); ?>

							<?php pis_form_input_text(
								__( 'Month', 'pis' ),
								$this->get_field_id('date_month'),
								$this->get_field_name('date_month'),
								esc_attr( $instance['date_month'] ),
								'06',
								__( 'Month number (from 1 to 12).', 'pis' )
							); ?>

							<?php pis_form_input_text(
								__( 'Week', 'pis' ),
								$this->get_field_id('date_week'),
								$this->get_field_name('date_week'),
								esc_attr( $instance['date_week'] ),
								'32',
								__( 'Week of the year (from 0 to 53).', 'pis' )
							); ?>

							<?php pis_form_input_text(
								__( 'Day', 'pis' ),
								$this->get_field_id('date_day'),
								$this->get_field_name('date_day'),
								esc_attr( $instance['date_day'] ),
								'12',
								__( 'Day of the month (from 1 to 31).', 'pis' )
							); ?>

						</div>

						<div class="pis-column pis-column-last">

							<?php pis_form_input_text(
								__( 'Hour', 'pis' ),
								$this->get_field_id('date_hour'),
								$this->get_field_name('date_hour'),
								esc_attr( $instance['date_hour'] ),
								'09',
								__( 'Hour (from 0 to 23).', 'pis' )
							); ?>

							<?php pis_form_input_text(
								__( 'Minute', 'pis' ),
								$this->get_field_id('date_minute'),
								$this->get_field_name('date_minute'),
								esc_attr( $instance['date_minute'] ),
								'24',
								__( 'Minute (from 0 to 59).', 'pis' )
							); ?>

							<?php pis_form_input_text(
								__( 'Second', 'pis' ),
								$this->get_field_id('date_second'),
								$this->get_field_name('date_second'),
								esc_attr( $instance['date_second'] ),
								'32',
								__( 'Second (from 0 to 59).', 'pis' )
							); ?>

						</div>

						<hr class="clear" />

						<div class="pis-column">

							<h5 class="pis-title-center"><?php _e( 'After section', 'pis' ); ?></h5>

							<?php pis_form_input_text(
								__( 'Year', 'pis' ),
								$this->get_field_id('date_after_year'),
								$this->get_field_name('date_after_year'),
								esc_attr( $instance['date_after_year'] ),
								'2011',
								__( 'Accepts any four-digit year.', 'pis' )
							); ?>

							<?php pis_form_input_text(
								__( 'Month', 'pis' ),
								$this->get_field_id('date_after_month'),
								$this->get_field_name('date_after_month'),
								esc_attr( $instance['date_after_month'] ),
								'10',
								__( 'The month of the year. Accepts numbers 1-12.', 'pis' )
							); ?>

							<?php pis_form_input_text(
								__( 'Day', 'pis' ),
								$this->get_field_id('date_after_day'),
								$this->get_field_name('date_after_day'),
								esc_attr( $instance['date_after_day'] ),
								'10',
								__( 'The day of the month. Accepts numbers 1-31.', 'pis' )
							); ?>

						</div>

						<div class="pis-column">

							<h5 class="pis-title-center"><?php _e( 'Before section', 'pis' ); ?></h5>

							<?php pis_form_input_text(
								__( 'Year', 'pis' ),
								$this->get_field_id('date_before_year'),
								$this->get_field_name('date_before_year'),
								esc_attr( $instance['date_before_year'] ),
								'2011',
								__( 'Accepts any four-digit year.', 'pis' )
							); ?>

							<?php pis_form_input_text(
								__( 'Month', 'pis' ),
								$this->get_field_id('date_before_month'),
								$this->get_field_name('date_before_month'),
								esc_attr( $instance['date_before_month'] ),
								'10',
								__( 'The month of the year. Accepts numbers 1-12.', 'pis' )
							); ?>

							<?php pis_form_input_text(
								__( 'Day', 'pis' ),
								$this->get_field_id('date_before_day'),
								$this->get_field_name('date_before_day'),
								esc_attr( $instance['date_before_day'] ),
								'10',
								__( 'The day of the month. Accepts numbers 1-31.', 'pis' )
							); ?>

						</div>

						<hr class="clear" />

						<div class="pis-column">

							<?php
							pis_form_checkbox( __( 'Inclusive', 'pis' ), $this->get_field_id( 'date_inclusive' ), $this->get_field_name( 'date_inclusive' ), checked( $date_inclusive, true, false ) ); ?>

							<?php
							$options = array(
								'empty' => array(
									'value' => '',
									'desc'  => ''
								),
								'equal' => array(
									'value' => '=',
									'desc'  => __( 'Equal to (=)', 'pis' )
								),
								'different' => array(
									'value' => '!=',
									'desc'  => __( 'Different (!=)', 'pis' )
								),
								'greater' => array(
									'value' => '>',
									'desc'  => __( 'Greater (>)', 'pis' )
								),
								'greater_equal' => array(
									'value' => '>=',
									'desc'  => __( 'Greater/Equal (>=)', 'pis' )
								),
								'minor' => array(
									'value' => '<',
									'desc'  => __( 'Minor (<)', 'pis' )
								),
								'minor_equal' => array(
									'value' => '<=',
									'desc'  => __( 'Minor/Equal (<=)', 'pis' )
								),
								'in' => array(
									'value' => 'IN',
									'desc'  => __( 'IN', 'pis' )
								),
								'not_in' => array(
									'value' => 'NOT IN',
									'desc'  => __( 'NOT IN', 'pis' )
								),
								'between' => array(
									'value' => 'BETWEEN',
									'desc'  => __( 'BETWEEN', 'pis' )
								),
								'not_between' => array(
									'value' => 'NOT BETWEEN',
									'desc'  => __( 'NOT BETWEEN', 'pis' )
								)
							);
							pis_form_select(
								__( 'Compare', 'pis' ),
								$this->get_field_id('date_compare'),
								$this->get_field_name('date_compare'),
								$options,
								$instance['date_compare'],
								__( 'Determines what comparison operator to use.', 'pis' )
							); ?>

						</div>

						<div class="pis-column pis-column-last">

							<?php
							$options = array(
								'empty' => array(
									'value' => '',
									'desc'  => ''
								),
								'post_date' => array(
									'value' => 'post_date',
									'desc'  => __( 'Post date', 'pis' )
								),
								'post_date_gmt' => array(
									'value' => 'post_date_gmt',
									'desc'  => __( 'Post date GMT', 'pis' )
								),
								'post_modified' => array(
									'value' => 'post_modified',
									'desc'  => __( 'Post modified', 'pis' )
								),
								'post_modified_gmt' => array(
									'value' => 'post_modified_gmt',
									'desc'  => __( 'Post modified GMT', 'pis' )
								)
							);
							pis_form_select(
								__( 'Column', 'pis' ),
								$this->get_field_id('date_column'),
								$this->get_field_name('date_column'),
								$options,
								$instance['date_column'],
								__( 'Column to query against.', 'pis' )
							); ?>

							<?php
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
								)
							);
							pis_form_select(
								__( 'Relation', 'pis' ),
								$this->get_field_id('date_relation'),
								$this->get_field_name('date_relation'),
								$options,
								$instance['date_relation'],
								__( 'How the sub-arrays should be compared.', 'pis' )
							); ?>

						</div>

					</div>

				</div>

			</div>

		</div>

		<hr />

		<!-- Displaying posts -->
		<div class="pis-section">

			<h4 class="pis-widget-title pis-clickable"><?php _e( 'Displaying posts', 'pis' ); ?></h4>

			<div class="pis-container">

				<div class="pis-column">

					<h4><?php _e( 'The title of the post', 'pis' ); ?></h4>

					<?php // ================= Title of the post
					pis_form_checkbox( __( 'Display the title of the post', 'pis' ), $this->get_field_id( 'display_title' ), $this->get_field_name( 'display_title' ), checked( $display_title, true, false ) ); ?>

					<?php // ================= Link to the title
					pis_form_checkbox( __( 'Link the title to the post', 'pis' ), $this->get_field_id( 'link_on_title' ), $this->get_field_name( 'link_on_title' ), checked( $link_on_title, true, false ) ); ?>

					<?php // ================= Arrow after the title
					pis_form_checkbox( __( 'Show an arrow after the title', 'pis' ), $this->get_field_id( 'arrow' ), $this->get_field_name( 'arrow' ), checked( $arrow, true, false ) ); ?>

					<hr />

					<h4><?php _e( 'The featured image of the post', 'pis' ); ?></h4>

					<?php if ( ! current_theme_supports( 'post-thumbnails' ) ) { ?>
						<p class="pis-alert"><?php _e( 'Your theme does not support the Post Thumbnail feature. No image will be displayed.', 'pis' ); ?></p>
					<?php } ?>

					<?php // ================= Featured image
					pis_form_checkbox( __( 'Display the featured image of the post', 'pis' ), $this->get_field_id( 'display_image' ), $this->get_field_name( 'display_image' ), checked( $display_image, true, false ) ); ?>

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
						__( 'The size of the thumbnail will be', 'pis' ),
						$this->get_field_id('image_size'),
						$this->get_field_name('image_size'),
						$options,
						$instance['image_size']
					); ?>

					<?php // ================= Image align
					$options = array(
						'nochange' => array(
							'value' => 'nochange',
							'desc'  => __( 'Do not change', 'pis' )
						),
						'left' => array(
							'value' => 'left',
							'desc'  => __( 'Left', 'pis' )
						),
						'right' => array(
							'value' => 'right',
							'desc'  => __( 'Right', 'pis' )
						),
						'center' => array(
							'value' => 'center',
							'desc'  => __( 'Center', 'pis' )
						),

					);
					pis_form_select(
						__( 'Align the image to', 'pis' ),
						$this->get_field_id('image_align'),
						$this->get_field_name('image_align'),
						$options,
						$instance['image_align']
					); ?>

					<p>
						<em>
							<?php printf(
								__( 'Note that in order to use image sizes different from the WordPress standards, add them to your %3$sfunctions.php%4$s file. See the %1$sCodex%2$s for further information.', 'pis' ),
								'<a href="http://codex.wordpress.org/Function_Reference/add_image_size" target="_blank">', '</a>', '<code>', '</code>'
							); ?>
							<?php printf(
								__( 'You can also use %1$sa plugin%2$s that could help you in doing it.', 'pis' ),
								'<a href="http://wordpress.org/plugins/simple-image-sizes/" target="_blank">', '</a>'
							); ?>
						</em>
					</p>

					<?php // ================= Positioning image before title
					pis_form_checkbox( __( 'Display the image before the title of the post', 'pis' ), $this->get_field_id( 'image_before_title' ), $this->get_field_name( 'image_before_title' ), checked( $image_before_title, true, false ) ); ?>

					<hr />

					<h4><?php _e( 'Customized featured image', 'pis' ); ?></h4>

					<?php // ================= Custom image URL
					pis_form_input_text(
						__( 'Use this image instead of the standard featured image', 'pis' ),
						$this->get_field_id( 'custom_image_url' ),
						$this->get_field_name( 'custom_image_url' ),
						esc_url( strip_tags( $instance['custom_image_url'] ) ),
						'http://example.com/image.jpg',
						__( 'Paste here the URL of the image. Note that the same image will be used for all the posts in the widget, unless you active the checkbox below.', 'pis' )
					); ?>

					<?php // ================= Use custom image URL only if the post thumbnail is not defined.
					pis_form_checkbox( __( 'Use custom image URL only if the post has not a featured image.', 'pis' ), $this->get_field_id( 'custom_img_no_thumb' ), $this->get_field_name( 'custom_img_no_thumb' ), checked( $custom_img_no_thumb, true, false ) ); ?>


					<hr />

					<h4><?php _e( 'The text of the post', 'pis' ); ?></h4>

					<?php // ================= Type of text
					$options = array(
						'full_content' => array(
							'value' => 'full_content',
							'desc'  => __( 'The full content', 'pis' )
						),
						'rich_content' => array(
							'value' => 'rich_content',
							'desc'  => __( 'The rich content', 'pis' )
						),
						'content' => array(
							'value' => 'content',
							'desc'  => __( 'The simple text', 'pis' )
						),
						'more_excerpt' => array(
							'value' => 'more_excerpt',
							'desc'  => __( 'The excerpt up to "more" tag', 'pis' )
						),
						'excerpt' => array(
							'value' => 'excerpt',
							'desc'  => __( 'The excerpt', 'pis' )
						),
						'only_read_more' => array(
							'value' => 'only_read_more',
							'desc'  => __( 'Display only the Read more link', 'pis' )
						),
						'none' => array(
							'value' => 'none',
							'desc'  => __( 'Do not show any text', 'pis' )
						),
					);
					pis_form_select(
						__( 'Display this type of text', 'pis' ),
						$this->get_field_id('excerpt'),
						$this->get_field_name('excerpt'),
						$options,
						$instance['excerpt']
					); ?>

					<?php // ================= Excerpt length
					pis_form_input_text( __( 'The WordPress generated excerpt length will be (in words)', 'pis' ), $this->get_field_id( 'exc_length' ), $this->get_field_name( 'exc_length' ), esc_attr( $instance['exc_length'] ), '20' ); ?>

					<?php // ================= More link text
					pis_form_input_text( __( 'Use this text for More link', 'pis' ), $this->get_field_id( 'the_more' ), $this->get_field_name( 'the_more' ), esc_attr( $instance['the_more'] ), __( 'Read more&hellip;', 'pis' ) ); ?>

					<?php // ================= Arrow after the excerpt
					pis_form_checkbox( __( 'Display an arrow after the "Read more" link', 'pis' ), $this->get_field_id( 'exc_arrow' ), $this->get_field_name( 'exc_arrow' ), checked( $exc_arrow, true, false ) ); ?>

				</div>

				<div class="pis-column">

					<h4><?php _e( 'Author, date and comments', 'pis' ); ?></h4>

					<?php // ================= Author
					pis_form_checkbox( __( 'Display the author of the post', 'pis' ), $this->get_field_id( 'display_author' ), $this->get_field_name( 'display_author' ), checked( $display_author, true, false ) ); ?>

					<?php // ================= Author text
					pis_form_input_text( __( 'Use this text before author\'s name', 'pis' ), $this->get_field_id( 'author_text' ), $this->get_field_name( 'author_text' ), esc_attr( $instance['author_text'] ), __( 'By', 'pis' ) ); ?>

					<?php // ================= Author archive
					pis_form_checkbox( __( 'Link the author to his archive', 'pis' ), $this->get_field_id( 'linkify_author' ), $this->get_field_name( 'linkify_author' ), checked( $linkify_author, true, false ) ); ?>

					<?php // ================= Date
					pis_form_checkbox( __( 'Display the date of the post', 'pis' ), $this->get_field_id( 'display_date' ), $this->get_field_name( 'display_date' ), checked( $display_date, true, false ) ); ?>

					<?php // ================= Date text
					pis_form_input_text( __( 'Use this text before date', 'pis' ), $this->get_field_id( 'date_text' ), $this->get_field_name( 'date_text' ), esc_attr( $instance['date_text'] ), __( 'Published on', 'pis' ) ); ?>

					<?php // ================= Date link
					pis_form_checkbox( __( 'Link the date to the post', 'pis' ), $this->get_field_id( 'linkify_date' ), $this->get_field_name( 'linkify_date' ), checked( $linkify_date, true, false ) ); ?>

					<?php // ================= Number of comments
					pis_form_checkbox( __( 'Display the number of comments', 'pis' ), $this->get_field_id( 'comments' ), $this->get_field_name( 'comments' ), checked( $comments, true, false ) ); ?>

					<?php // ================= Comments text
					pis_form_input_text( __( 'Use this text before comments number', 'pis' ), $this->get_field_id( 'comments_text' ), $this->get_field_name( 'comments_text' ), esc_attr( $instance['comments_text'] ), __( 'Comments:', 'pis' ) ); ?>

					<?php // ================= Utility separator
					pis_form_input_text( __( 'Use this separator between author, date and comments', 'pis' ), $this->get_field_id( 'utility_sep' ), $this->get_field_name( 'utility_sep' ), esc_attr( $instance['utility_sep'] ), '|', __( 'A space will be added before and after the separator.', 'pis' ) ); ?>

					<?php // ================= Author
					pis_form_checkbox( __( 'Display this section after the title of the post', 'pis' ), $this->get_field_id( 'utility_after_title' ), $this->get_field_name( 'utility_after_title' ), checked( $utility_after_title, true, false ) ); ?>

					<hr />

					<h4><?php _e( 'The categories of the post', 'pis' ); ?></h4>

					<?php // ================= Post categories
					pis_form_checkbox( __( 'Display the categories of the post', 'pis' ), $this->get_field_id( 'categories' ), $this->get_field_name( 'categories' ), checked( $categories, true, false ) ); ?>

					<?php // ================= Categories text
					pis_form_input_text( __( 'Use this text before categories list', 'pis' ), $this->get_field_id( 'categ_text' ), $this->get_field_name( 'categ_text' ), esc_attr( $instance['categ_text'] ), __( 'Category:', 'pis' ) ); ?>

					<?php // ================= Categories separator
					pis_form_input_text(
						__( 'Use this separator between categories', 'pis' ),
						$this->get_field_id( 'categ_sep' ),
						$this->get_field_name( 'categ_sep' ),
						esc_attr( $instance['categ_sep'] ),
						',',
						__( 'A space will be added after the separator.', 'pis' )
					); ?>

					<hr />

					<h4><?php _e( 'The tags of the post', 'pis' ); ?></h4>

					<?php // ================= Post tags
					pis_form_checkbox( __( 'Show the tags of the post', 'pis' ), $this->get_field_id( 'tags' ), $this->get_field_name( 'tags' ), checked( $tags, true, false ) ); ?>

					<?php // ================= Tags text
					pis_form_input_text( __( 'Use this text before tags list', 'pis' ), $this->get_field_id( 'tags_text' ), $this->get_field_name( 'tags_text' ), esc_attr( $instance['tags_text'] ), __( 'Tags:', 'pis' ) ); ?>

					<?php // ================= Hashtag
					pis_form_input_text( __( 'Use this hashtag', 'pis' ), $this->get_field_id( 'hashtag' ), $this->get_field_name( 'hashtag' ), esc_attr( $instance['hashtag'] ), '#' ); ?>

					<?php // ================= Tags separator
					pis_form_input_text(
						__( 'Use this separator between tags', 'pis' ),
						$this->get_field_id( 'tag_sep' ),
						$this->get_field_name( 'tag_sep' ),
						esc_attr( $instance['tag_sep'] ),
						',',
						__( 'A space will be added after the separator.', 'pis' )
					); ?>

				</div>

				<div class="pis-column pis-column-last">

					<h4><?php _e( 'The custom taxonomies of the post', 'pis' ); ?></h4>

					<?php // ================= Custom taxonomies
					pis_form_checkbox( __( 'Show the custom taxonomies of the post', 'pis' ),
					$this->get_field_id( 'display_custom_tax' ),
					$this->get_field_name( 'display_custom_tax' ),
					checked( $display_custom_tax, true, false ) ); ?>

					<?php // ================= Terms hashtag
					pis_form_input_text( __( 'Use this hashtag for terms', 'pis' ), $this->get_field_id( 'term_hashtag' ), $this->get_field_name( 'term_hashtag' ), esc_attr( $instance['term_hashtag'] ), '#' ); ?>

					<?php // ================= Terms separator
					pis_form_input_text(
						__( 'Use this separator between terms', 'pis' ),
						$this->get_field_id( 'term_sep' ),
						$this->get_field_name( 'term_sep' ),
						esc_attr( $instance['term_sep'] ),
						',',
						__( 'A space will be added after the separator.', 'pis' )
					); ?>

					<hr />

					<h4><?php _e( 'The custom field', 'pis' ); ?></h4>

					<?php // ================= Display custom field
					pis_form_checkbox( __( 'Display the custom field of the post', 'pis' ), $this->get_field_id( 'custom_field' ), $this->get_field_name( 'custom_field' ), checked( $custom_field, true, false ) ); ?>

					<?php // ================= Custom fields text
					pis_form_input_text( __( 'Use this text before the custom field', 'pis' ), $this->get_field_id( 'custom_field_txt' ), $this->get_field_name( 'custom_field_txt' ), esc_attr( $instance['custom_field_txt'] ), __( 'Custom field:', 'pis' ) ); ?>

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
						__( 'Display this custom field', 'pis' ),
						$this->get_field_id('meta'),
						$this->get_field_name('meta'),
						$options,
						$instance['meta']
					); ?>

					<?php // ================= Custom field key
					pis_form_checkbox( __( 'Also display the key of the custom field', 'pis' ), $this->get_field_id( 'custom_field_key' ), $this->get_field_name( 'custom_field_key' ), checked( $custom_field_key, true, false ) ); ?>

					<?php // ================= Custom field separator
					pis_form_input_text( __( 'Use this separator between meta key and value', 'pis' ), $this->get_field_id( 'custom_field_sep' ), $this->get_field_name( 'custom_field_sep' ), esc_attr( $instance['custom_field_sep'] ), ':' ); ?>

					<hr />

					<h4><?php _e( 'The link to the archive', 'pis' ); ?></h4>

					<?php // ================= Taxonomy archive link
					pis_form_checkbox( __( 'Display the link to the taxonomy archive', 'pis' ), $this->get_field_id( 'archive_link' ), $this->get_field_name( 'archive_link' ), checked( $archive_link, true, false ) ); ?>

					<?php // ================= Which taxonomy
					$options = array(
						'author' => array(
							'value' => 'author',
							'desc'  => __( 'Author', 'pis' )
						),
						'category' => array(
							'value' => 'category',
							'desc'  => __( 'Category', 'pis' )
						),
						'tag' => array(
							'value' => 'tag',
							'desc'  => __( 'Tag', 'pis' )
						),
					);
					$custom_post_types = (array) get_post_types( array(
						'_builtin'            => false,
						'exclude_from_search' => false,
					), 'objects' );
					foreach ( $custom_post_types as $custom_post_type ) {
						$options[] = array(
							'value' => $custom_post_type->name,
							'desc'  => sprintf( __( 'Post type: %s', 'pis' ), $custom_post_type->labels->singular_name ),
						);
					}
					if ( $post_formats ) {
						foreach ( $post_formats as $post_format ) {
							$options[] = array(
								'value' => $post_format->slug,
								'desc'  => sprintf( __( 'Post format: %s', 'pis' ), $post_format->name ),
							);
						}
					}
					pis_form_select(
						__( 'Link to the archive of', 'pis' ),
						$this->get_field_id('link_to'),
						$this->get_field_name('link_to'),
						$options,
						$instance['link_to']
					); ?>


					<?php // ================= Archive link text
					pis_form_input_text(
						__( 'Use this text for archive link', 'pis' ),
						$this->get_field_id( 'archive_text' ),
						$this->get_field_name( 'archive_text' ),
						esc_attr( $instance['archive_text'] ),
						__( 'Display all posts by %s', 'pis' ),
						sprintf( __( 'Use %s to display the name of the taxonomy. Also, note that if you haven\'t selected any taxonomy, the link won\'t appear.', 'pis' ), '<code>%s</code>' )
					); ?>

					<hr />

					<h4><?php _e( 'When no posts are found', 'pis' ); ?></h4>

					<?php // ================= When no posts are found
					// Text when no posts found
					pis_form_input_text(
						__( 'Use this text when there are no posts', 'pis' ),
						$this->get_field_id( 'nopost_text' ),
						$this->get_field_name( 'nopost_text' ),
						esc_attr( $instance['nopost_text'] ),
						__( 'No posts yet.', 'pis' )
					); ?>

					<?php
					// Hide the widget if no posts found
					pis_form_checkbox(
						__( 'Completely hide the widget if no posts are found', 'pis' ),
						$this->get_field_id( 'hide_widget' ),
						$this->get_field_name( 'hide_widget' ),
						checked( $hide_widget, true, false )
					); ?>

				</div>

			</div>

		</div>

		<hr />

		<!-- Extras -->
		<div class="pis-section">

			<h4 class="pis-widget-title pis-clickable"><?php _e( 'Extras', 'pis' ); ?></h4>

			<div class="pis-container">

				<?php // ================= Container Class
				pis_form_input_text(
					__( 'Add a global container with this CSS class', 'pis' ),
					$this->get_field_id('container_class'),
					$this->get_field_name('container_class'),
					esc_attr( $instance['container_class'] ),
					'posts-container',
					sprintf(
						__( 'Enter the name of your container (for example, %1$s). The plugin will add a new %2$s container with this class. You can enter only one class and the name may contain only letters, hyphens and underscores. The new container will enclose all the widget, from the title to the last line.', 'pis' ), '<code>my-container</code>', '<code>div</code>' )
				); ?>

				<?php // ================= Type of HTML for list of posts
				$options = array(
					'ul' => array(
						'value' => 'ul',
						'desc'  => __( 'Unordered list', 'pis' )
					),
					'ol' => array(
						'value' => 'ol',
						'desc'  => __( 'Ordered list', 'pis' )
					),
				);
				pis_form_select(
					__( 'Use this type of list for the posts', 'pis' ),
					$this->get_field_id('list_element'),
					$this->get_field_name('list_element'),
					$options,
					$instance['list_element']
				); ?>

				<?php // ================= Remove bullets and left space
				pis_form_checkbox(
					__( 'Try to remove the bullets and the extra left space from the list elements', 'pis' ),
					$this->get_field_id( 'remove_bullets' ),
					$this->get_field_name( 'remove_bullets' ),
					checked( $remove_bullets, true, false ),
					sprintf( __( 'If the plugin doesn\'t remove the bullets and/or the extra left space, you have to %1$sedit your CSS file%2$s manually.', 'pis' ), '<a href="' . admin_url( 'theme-editor.php' ) . '" target="_blank">', '</a>' )
				); ?>

			</div>

		</div>

		<hr />

		<!-- Cache -->
		<div class="pis-section">

			<h4 class="pis-widget-title pis-clickable"><?php _e( 'Cache', 'pis' ); ?></h4>

			<div class="pis-container">

				<?php // ================= Cache for the query
				pis_form_checkbox( __( 'Use a cache to serve the output', 'pis' ),
					$this->get_field_id( 'cached' ),
					$this->get_field_name( 'cached' ),
					checked( $cached, true, false ),
					__( 'This option, if activated, will increase the performance.', 'pis' )
				); ?>

				<?php // ================= Cache duration
				pis_form_input_text(
					__( 'The cache will be used for (in seconds)', 'pis' ),
					$this->get_field_id('cache_time'),
					$this->get_field_name('cache_time'),
					esc_attr( $instance['cache_time'] ),
					'3600',
					sprintf( __( 'For example, %1$s for one hour of cache. To reset the cache, enter %2$s and save the widget.', 'pis' ), '<code>3600</code>', '<code>0</code>' )
				); ?>

			</div>

		</div>

		<hr />

		<!-- Elements margins -->
		<div class="pis-section">

			<h4 class="pis-widget-title pis-clickable"><?php _e( 'Elements margins', 'pis' ); ?></h4>

			<div class="pis-container">

				<p><em><?php _e( 'This section defines the margin for each line of the widget. Leave blank if you don\'t want to add any local style.', 'pis' ); ?></em></p>

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
					__( 'Unit for margins', 'pis' ),
					$this->get_field_id('margin_unit'),
					$this->get_field_name('margin_unit'),
					$options,
					$instance['margin_unit']
				); ?>

				<p><strong><?php printf( __( 'Enter here only the value without any unit, e.g. enter %1$s if you want a space of 10px or enter %2$s if you don\'t want any space.', 'pis' ), '<code>10</code>', '<code>0</code>' ); ?></strong></p>

				<?php // ================= Margins ?>

				<div class="pis-column">
					<?php pis_form_input_text( __( 'Introduction bottom margin', 'pis' ), $this->get_field_id( 'intro_margin' ), $this->get_field_name( 'intro_margin' ), esc_attr( $instance['intro_margin'] ) ); ?>
					<?php pis_form_input_text( __( 'Title bottom margin', 'pis' ), $this->get_field_id( 'title_margin' ), $this->get_field_name( 'title_margin' ), esc_attr( $instance['title_margin'] ) ); ?>
					<?php pis_form_input_text( __( 'Image left &amp; right margin', 'pis' ), $this->get_field_id( 'side_image_margin' ), $this->get_field_name( 'side_image_margin' ), esc_attr( $instance['side_image_margin'] ) ); ?>
					<?php pis_form_input_text( __( 'Image bottom margin', 'pis' ), $this->get_field_id( 'bottom_image_margin' ), $this->get_field_name( 'bottom_image_margin' ), esc_attr( $instance['bottom_image_margin'] ) ); ?>
				</div>

				<div class="pis-column">
					<?php pis_form_input_text( __( 'Excerpt bottom margin', 'pis' ), $this->get_field_id( 'excerpt_margin' ), $this->get_field_name( 'excerpt_margin' ), esc_attr( $instance['excerpt_margin'] ) ); ?>
					<?php pis_form_input_text( __( 'Utility bottom margin', 'pis' ), $this->get_field_id( 'utility_margin' ), $this->get_field_name( 'utility_margin' ), esc_attr( $instance['utility_margin'] ) ); ?>
					<?php pis_form_input_text( __( 'Categories bottom margin', 'pis' ), $this->get_field_id( 'categories_margin' ), $this->get_field_name( 'categories_margin' ), esc_attr( $instance['categories_margin'] ) ); ?>
					<?php pis_form_input_text( __( 'Tags bottom margin', 'pis' ), $this->get_field_id( 'tags_margin' ), $this->get_field_name( 'tags_margin' ), esc_attr( $instance['tags_margin'] ) ); ?>
				</div>

				<div class="pis-column pis-column-last">
					<?php pis_form_input_text( __( 'Terms bottom margin', 'pis' ), $this->get_field_id( 'terms_margin' ), $this->get_field_name( 'terms_margin' ), esc_attr( $instance['terms_margin'] ) ); ?>
					<?php pis_form_input_text( __( 'Custom field bottom margin', 'pis' ), $this->get_field_id( 'custom_field_margin' ), $this->get_field_name( 'custom_field_margin' ), esc_attr( $instance['custom_field_margin'] ) ); ?>
					<?php pis_form_input_text( __( 'Archive bottom margin', 'pis' ), $this->get_field_id( 'archive_margin' ), $this->get_field_name( 'archive_margin' ), esc_attr( $instance['archive_margin'] ) ); ?>
					<?php pis_form_input_text( __( 'No-posts bottom margin', 'pis' ), $this->get_field_id( 'noposts_margin' ), $this->get_field_name( 'noposts_margin' ), esc_attr( $instance['noposts_margin'] ) ); ?>
				</div>

			</div>

		</div>

		<hr />

		<!-- Custom styles -->
		<div class="pis-section">

			<h4 class="pis-widget-title pis-clickable"><?php _e( 'Custom styles', 'pis' ); ?></h4>

			<div class="pis-container">

				<p><em><?php printf( __( 'In this field you can add your own styles, for example: %s', 'pis' ), '<code>.pis-excerpt { color: green; }</code>' ); ?></em></p>

				<?php // ================= Custom styles
				pis_form_textarea(
					__( 'Custom styles', 'pis' ),
					$this->get_field_id('custom_styles'),
					$this->get_field_name('custom_styles'),
					$instance['custom_styles'],
					'#' . $this->id . ' p.pis-title { text-align: center; }',
					$style = 'resize: vertical; width: 100%; height: 80px;'
				); ?>

			</div>

		</div>

		<hr />

		<!-- Debugging -->
		<div class="pis-section">

			<h4 class="pis-widget-title pis-clickable"><?php _e( 'Debugging', 'pis' ); ?></h4>

			<div class="pis-container">

				<p><strong><?php _e( 'Use this options for debugging purposes only. Please note that the informations will be displayed publicly on your site.', 'pis' ); ?></strong></p>

				<?php // ================= Debug: display the query for the widget
				pis_form_checkbox(
					__( 'Display the query for the widget', 'pis' ),
					$this->get_field_id( 'debug_query' ),
					$this->get_field_name( 'debug_query' ),
					checked( $debug_query, true, false )
				); ?>

				<?php // ================= Debug: display the complete set of parameters for the widget
				pis_form_checkbox(
					__( 'Display the complete set of parameters for the widget', 'pis' ),
					$this->get_field_id( 'debug_params' ),
					$this->get_field_name( 'debug_params' ),
					checked( $debug_params, true, false )
				); ?>

				<?php // ================= Debug: display the total number of queries
				pis_form_checkbox(
					__( 'Display the total number of queries, including WordPress, current theme and all active plugins', 'pis' ),
					$this->get_field_id( 'debug_query_number' ),
					$this->get_field_name( 'debug_query_number' ),
					checked( $debug_query_number, true, false )
				); ?>

			</div>

		</div>

		<hr />

		<?php
	}

}

/***********************************************************************
 *                            CODE IS POETRY
 **********************************************************************/
