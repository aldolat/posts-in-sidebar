<?php
/**
 * This file contains the functions for the widget
 *
 * @package PostsInSidebar
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
			'description' => esc_html__( 'Display a list of posts in a widget', 'posts-in-sidebar' ),
		);

		/* Widget control settings. */
		$control_ops = array(
			'width'   => 600,
			'id_base' => 'pis_posts_in_sidebar',
		);

		/* The PHP5 widget contructor */
		parent::__construct(
			'pis_posts_in_sidebar',
			esc_html__( 'Posts in Sidebar', 'posts-in-sidebar' ),
			$widget_ops,
			$control_ops
		);
	}

	/**
	 * Display the content of the widget in the front-end.
	 *
	 * @param array $args Widget arguments.
	 *                    $args contains (for example):
	 *                        $args['name'] = Sidebar
	 *                        $args['id'] = sidebar-1
	 *                        $args['description'] = Add widgets here to appear in your sidebar.
	 *                        $args['class'] = ''
	 *                        $args['before_widget'] = <section id="pis_posts_in_sidebar-40" class="widget posts-in-sidebar">
	 *                        $args['after_widget'] = </section>
	 *                        $args['before_title'] = <h2 class="widget-title">
	 *                        $args['after_title'] = </h2>
	 *                        $args['widget_id'] = pis_posts_in_sidebar-40
	 *                        $args['widget_name'] = Posts in Sidebar.
	 * @param array $instance Saved values from database.
	 * @since 1.0
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, pis_get_defaults() );

		/*
		 * Store the complete ID of the widget among the widget options.
		 *
		 * The ID will be used in the main function to check if a cached version
		 * of the query already exists for every instance of the widget.
		 * It is also useful to filter the widget title.
		 *
		 * What do $this->id, $this->id_base and $args['widget_id'] contain?
		 * $this->id          = 'pis_posts_in_sidebar-40' (the complete ID of the widget)
		 * $this->id_base     = 'pis_posts_in_sidebar'    (the base of the ID of the widget)
		 * $args['widget_id'] = 'pis_posts_in_sidebar-40' (the complete ID of the widget)
		 */
		$instance['widget_id'] = $this->id; // $this->id is the id of the widget instance.

		/*
		 * Change the widget title if requested by the user
		 * in the widget options.
		 *
		 * @since 4.7.7
		 */
		$instance['title'] = pis_change_widget_title( $instance );

		/*
		 * Filters the widget title.
		 */
		$instance['title'] = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		/*
		 * Start echoing the widget.
		 */
		echo "\n" . '<!-- Start Posts in Sidebar - ' . esc_html( $args['widget_id'] ) . ' -->' . "\n";

		echo $args['before_widget'] . "\n";

		// Add a new container if the "Container Class" is not empty.
		if ( ! empty( $instance['container_class'] ) ) {
			echo '<div class="' . sanitize_html_class( $instance['container_class'] ) . '">' . "\n";
		}

		/*
		 * Echo the title, along with link, if present.
		 */
		if ( $instance['title'] && ! empty( $instance['title_link'] ) ) {
			echo $args['before_title'] . '<a class="pis-title-link" href="' . esc_url( $instance['title_link'] ) . '">' . $instance['title'] . '</a>' . $args['after_title'];
		} elseif ( $instance['title'] ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		}

		/*
		 * Execute the main function in the front-end.
		 * Some parameters are passed only for the debugging list.
		 */
		pis_posts_in_sidebar( $instance );

		if ( ! empty( $instance['container_class'] ) ) {
			echo '</div>' . "\n";
		}

		echo $args['after_widget'] . "\n";

		echo '<!-- End Posts in Sidebar - ' . esc_html( $args['widget_id'] ) . ' -->' . "\n\n";
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

		// The title of the widget.
		$instance['title']      = sanitize_text_field( $new_instance['title'] );
		$instance['title_link'] = esc_url( wp_strip_all_tags( $new_instance['title_link'] ) );

		// The introduction for the widget.
		$instance['intro'] = trim( wp_kses_post( $new_instance['intro'] ) );

		// Posts retrieving.
		$instance['post_type'] = wp_strip_all_tags( $new_instance['post_type'] );

		$instance['post_type_multiple'] = pis_normalize_values( wp_strip_all_tags( $new_instance['post_type_multiple'] ) );

		/*
		 * Check post types entered.
		 * The function removes any post type that has not been defined.
		 * @since 3.8.8
		 */
		if ( ! empty( $instance['post_type_multiple'] ) ) {
			$instance['post_type_multiple'] = pis_check_post_types( $instance['post_type_multiple'] );
		}

		$instance['posts_id'] = pis_normalize_values( wp_strip_all_tags( $new_instance['posts_id'] ), true );
		if ( 0 == $instance['posts_id'] ) {
			$instance['posts_id'] = '';
		}

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
		$instance['author'] = wp_strip_all_tags( $new_instance['author'] );
		if ( 'NULL' === $instance['author'] ) {
			$instance['author'] = '';
		}
		$instance['author_in'] = pis_normalize_values( wp_strip_all_tags( $new_instance['author_in'] ), true );
		if ( 0 == $instance['author_in'] ) {
			$instance['author_in'] = '';
		}
		// Make $author empty if $author_in is not empty.
		if ( ! empty( $instance['author_in'] ) ) {
			$instance['author'] = '';
		}
		$instance['posts_by_comments'] = isset( $new_instance['posts_by_comments'] ) ? 1 : 0;
		$instance['cat']               = pis_normalize_values( wp_strip_all_tags( $new_instance['cat'] ) );
		if ( 'NULL' === $instance['cat'] ) {
			$instance['cat'] = '';
		}
		$instance['tag'] = pis_normalize_values( wp_strip_all_tags( $new_instance['tag'] ) );
		if ( 'NULL' === $instance['tag'] ) {
			$instance['tag'] = '';
		}
		$instance['post_parent_in'] = pis_normalize_values( wp_strip_all_tags( $new_instance['post_parent_in'] ), true );
		if ( 0 == $instance['post_parent_in'] ) {
			$instance['post_parent_in'] = '';
		}
		$instance['post_format'] = wp_strip_all_tags( $new_instance['post_format'] );
		$instance['number']      = intval( wp_strip_all_tags( $new_instance['number'] ) );
		if ( 0 === $instance['number'] || ! is_numeric( $instance['number'] ) ) {
			$instance['number'] = get_option( 'posts_per_page' );
		}
		$instance['orderby']       = wp_strip_all_tags( $new_instance['orderby'] );
		$instance['order']         = wp_strip_all_tags( $new_instance['order'] );
		$instance['offset_number'] = absint( wp_strip_all_tags( $new_instance['offset_number'] ) );
		if ( 0 === $instance['offset_number'] || ! is_numeric( $instance['offset_number'] ) ) {
			$instance['offset_number'] = '';
		}
		$instance['post_status']   = wp_strip_all_tags( $new_instance['post_status'] );
		$instance['post_meta_key'] = wp_strip_all_tags( $new_instance['post_meta_key'] );
		$instance['post_meta_val'] = wp_strip_all_tags( $new_instance['post_meta_val'] );
		$instance['search']        = wp_strip_all_tags( $new_instance['search'] );
		if ( '' === $instance['search'] ) {
			$instance['search'] = null;
		}
		$instance['has_password']  = wp_strip_all_tags( $new_instance['has_password'] );
		$instance['post_password'] = wp_strip_all_tags( $new_instance['post_password'] );
		$instance['ignore_sticky'] = isset( $new_instance['ignore_sticky'] ) ? 1 : 0;

		$instance['get_from_same_cat'] = isset( $new_instance['get_from_same_cat'] ) ? 1 : 0;
		$instance['number_same_cat']   = intval( wp_strip_all_tags( $new_instance['number_same_cat'] ) );
		if ( 0 === $instance['number_same_cat'] || ! is_numeric( $instance['number_same_cat'] ) ) {
			$instance['number_same_cat'] = '';
		}
		$instance['title_same_cat']   = wp_strip_all_tags( $new_instance['title_same_cat'] );
		$instance['sort_categories']  = isset( $new_instance['sort_categories'] ) ? 1 : 0;
		$instance['yoast_main_cat']   = isset( $new_instance['yoast_main_cat'] ) ? 1 : 0;
		$instance['orderby_same_cat'] = $new_instance['orderby_same_cat'];
		$instance['order_same_cat']   = $new_instance['order_same_cat'];
		$instance['offset_same_cat']  = absint( wp_strip_all_tags( $new_instance['offset_same_cat'] ) );
		if ( 0 === $instance['offset_same_cat'] || ! is_numeric( $instance['offset_same_cat'] ) ) {
			$instance['offset_same_cat'] = '';
		}
		$instance['search_same_cat']    = isset( $new_instance['search_same_cat'] ) ? 1 : 0;
		$instance['post_type_same_cat'] = $new_instance['post_type_same_cat'];
		$instance['ptm_sc']             = $new_instance['ptm_sc'];
		if ( ! empty( $instance['ptm_sc'] ) ) {
			$instance['ptm_sc'] = pis_check_post_types( $instance['ptm_sc'] );
		}

		$instance['get_from_same_tag'] = isset( $new_instance['get_from_same_tag'] ) ? 1 : 0;
		$instance['number_same_tag']   = intval( wp_strip_all_tags( $new_instance['number_same_tag'] ) );
		if ( 0 === $instance['number_same_tag'] || ! is_numeric( $instance['number_same_tag'] ) ) {
			$instance['number_same_tag'] = '';
		}
		$instance['title_same_tag']   = wp_strip_all_tags( $new_instance['title_same_tag'] );
		$instance['sort_tags']        = isset( $new_instance['sort_tags'] ) ? 1 : 0;
		$instance['orderby_same_tag'] = $new_instance['orderby_same_tag'];
		$instance['order_same_tag']   = $new_instance['order_same_tag'];
		$instance['offset_same_tag']  = absint( wp_strip_all_tags( $new_instance['offset_same_tag'] ) );
		if ( 0 === $instance['offset_same_tag'] || ! is_numeric( $instance['offset_same_tag'] ) ) {
			$instance['offset_same_tag'] = '';
		}
		$instance['search_same_tag']    = isset( $new_instance['search_same_tag'] ) ? 1 : 0;
		$instance['post_type_same_tag'] = $new_instance['post_type_same_tag'];
		$instance['ptm_st']             = $new_instance['ptm_st'];
		if ( ! empty( $instance['ptm_st'] ) ) {
			$instance['ptm_st'] = pis_check_post_types( $instance['ptm_st'] );
		}

		$instance['get_from_same_author'] = isset( $new_instance['get_from_same_author'] ) ? 1 : 0;
		$instance['number_same_author']   = intval( wp_strip_all_tags( $new_instance['number_same_author'] ) );
		if ( 0 === $instance['number_same_author'] || ! is_numeric( $instance['number_same_author'] ) ) {
			$instance['number_same_author'] = '';
		}
		$instance['title_same_author']   = wp_strip_all_tags( $new_instance['title_same_author'] );
		$instance['orderby_same_author'] = $new_instance['orderby_same_author'];
		$instance['order_same_author']   = $new_instance['order_same_author'];
		$instance['offset_same_author']  = absint( wp_strip_all_tags( $new_instance['offset_same_author'] ) );
		if ( 0 === $instance['offset_same_author'] || ! is_numeric( $instance['offset_same_author'] ) ) {
			$instance['offset_same_author'] = '';
		}
		$instance['search_same_author']    = isset( $new_instance['search_same_author'] ) ? 1 : 0;
		$instance['post_type_same_author'] = $new_instance['post_type_same_author'];
		$instance['ptm_sa']                = $new_instance['ptm_sa'];
		if ( ! empty( $instance['ptm_sa'] ) ) {
			$instance['ptm_sa'] = pis_check_post_types( $instance['ptm_sa'] );
		}

		$instance['get_from_custom_fld'] = isset( $new_instance['get_from_custom_fld'] ) ? 1 : 0;
		$instance['s_custom_field_key']  = wp_strip_all_tags( $new_instance['s_custom_field_key'] );
		$instance['s_custom_field_tax']  = $new_instance['s_custom_field_tax'];
		$instance['number_custom_field'] = intval( wp_strip_all_tags( $new_instance['number_custom_field'] ) );
		if ( 0 === $instance['number_custom_field'] || ! is_numeric( $instance['number_custom_field'] ) ) {
			$instance['number_custom_field'] = '';
		}
		$instance['title_custom_field'] = wp_strip_all_tags( $new_instance['title_custom_field'] );
		$instance['orderby_custom_fld'] = $new_instance['orderby_custom_fld'];
		$instance['order_custom_fld']   = $new_instance['order_custom_fld'];
		$instance['offset_custom_fld']  = absint( wp_strip_all_tags( $new_instance['offset_custom_fld'] ) );
		if ( 0 === $instance['offset_custom_fld'] || ! is_numeric( $instance['offset_custom_fld'] ) ) {
			$instance['offset_custom_fld'] = '';
		}
		$instance['search_same_cf']    = isset( $new_instance['search_same_cf'] ) ? 1 : 0;
		$instance['post_type_same_cf'] = $new_instance['post_type_same_cf'];
		$instance['ptm_scf']           = $new_instance['ptm_scf'];
		if ( ! empty( $instance['ptm_scf'] ) ) {
			$instance['ptm_scf'] = pis_check_post_types( $instance['ptm_scf'] );
		}

		$instance['get_from_same_post_format'] = isset( $new_instance['get_from_same_post_format'] ) ? 1 : 0;
		$instance['number_same_post_format']   = intval( wp_strip_all_tags( $new_instance['number_same_post_format'] ) );
		$instance['title_same_post_format']    = wp_strip_all_tags( $new_instance['title_same_post_format'] );
		$instance['orderby_same_post_format']  = $new_instance['orderby_same_post_format'];
		$instance['order_same_post_format']    = $new_instance['order_same_post_format'];
		$instance['offset_same_post_format']   = absint( wp_strip_all_tags( $new_instance['offset_same_post_format'] ) );
		if ( 0 === $instance['offset_same_post_format'] || ! is_numeric( $instance['offset_same_post_format'] ) ) {
			$instance['offset_same_post_format'] = '';
		}
		$instance['search_same_post_format']    = isset( $new_instance['search_same_post_format'] ) ? 1 : 0;
		$instance['post_type_same_post_format'] = $new_instance['post_type_same_post_format'];
		$instance['ptm_spf']                    = $new_instance['ptm_spf'];
		if ( ! empty( $instance['ptm_spf'] ) ) {
			$instance['ptm_spf'] = pis_check_post_types( $instance['ptm_spf'] );
		}

		$instance['dont_ignore_params'] = isset( $new_instance['dont_ignore_params'] ) ? 1 : 0;

		$instance['get_from_cat_page'] = isset( $new_instance['get_from_cat_page'] ) ? 1 : 0;
		$instance['number_cat_page']   = intval( wp_strip_all_tags( $new_instance['number_cat_page'] ) );
		if ( 0 === $instance['number_cat_page'] || ! is_numeric( $instance['number_cat_page'] ) ) {
			$instance['number_cat_page'] = '';
		}
		$instance['offset_cat_page'] = absint( wp_strip_all_tags( $new_instance['offset_cat_page'] ) );
		if ( 0 === $instance['offset_cat_page'] || ! is_numeric( $instance['offset_cat_page'] ) ) {
			$instance['offset_cat_page'] = '';
		}
		$instance['title_cat_page']     = wp_strip_all_tags( $new_instance['title_cat_page'] );
		$instance['orderby_cat_page']   = $new_instance['orderby_cat_page'];
		$instance['order_cat_page']     = $new_instance['order_cat_page'];
		$instance['post_type_cat_page'] = $new_instance['post_type_cat_page'];
		$instance['ptm_scp']            = $new_instance['ptm_scp'];
		if ( ! empty( $instance['ptm_scp'] ) ) {
			$instance['ptm_scp'] = pis_check_post_types( $instance['ptm_scp'] );
		}

		$instance['get_from_tag_page'] = isset( $new_instance['get_from_tag_page'] ) ? 1 : 0;
		$instance['number_tag_page']   = intval( wp_strip_all_tags( $new_instance['number_tag_page'] ) );
		if ( 0 === $instance['number_tag_page'] || ! is_numeric( $instance['number_tag_page'] ) ) {
			$instance['number_tag_page'] = '';
		}
		$instance['offset_tag_page'] = absint( wp_strip_all_tags( $new_instance['offset_tag_page'] ) );
		if ( 0 === $instance['offset_tag_page'] || ! is_numeric( $instance['offset_tag_page'] ) ) {
			$instance['offset_tag_page'] = '';
		}
		$instance['title_tag_page']     = wp_strip_all_tags( $new_instance['title_tag_page'] );
		$instance['orderby_tag_page']   = $new_instance['orderby_tag_page'];
		$instance['order_tag_page']     = $new_instance['order_tag_page'];
		$instance['post_type_tag_page'] = $new_instance['post_type_tag_page'];
		$instance['ptm_stp']            = $new_instance['ptm_stp'];
		if ( ! empty( $instance['ptm_stp'] ) ) {
			$instance['ptm_stp'] = pis_check_post_types( $instance['ptm_stp'] );
		}

		$instance['get_from_author_page'] = isset( $new_instance['get_from_author_page'] ) ? 1 : 0;
		$instance['number_author_page']   = intval( wp_strip_all_tags( $new_instance['number_author_page'] ) );
		if ( 0 === $instance['number_author_page'] || ! is_numeric( $instance['number_author_page'] ) ) {
			$instance['number_author_page'] = '';
		}
		$instance['offset_author_page'] = absint( wp_strip_all_tags( $new_instance['offset_author_page'] ) );
		if ( 0 === $instance['offset_author_page'] || ! is_numeric( $instance['offset_author_page'] ) ) {
			$instance['offset_author_page'] = '';
		}
		$instance['title_author_page']     = wp_strip_all_tags( $new_instance['title_author_page'] );
		$instance['orderby_author_page']   = $new_instance['orderby_author_page'];
		$instance['order_author_page']     = $new_instance['order_author_page'];
		$instance['post_type_author_page'] = $new_instance['post_type_author_page'];
		$instance['ptm_sap']               = $new_instance['ptm_sap'];
		if ( ! empty( $instance['ptm_sap'] ) ) {
			$instance['ptm_sap'] = pis_check_post_types( $instance['ptm_sap'] );
		}

		$instance['get_from_post_format_page'] = isset( $new_instance['get_from_post_format_page'] ) ? 1 : 0;
		$instance['number_post_format_page']   = intval( wp_strip_all_tags( $new_instance['number_post_format_page'] ) );
		if ( 0 === $instance['number_post_format_page'] || ! is_numeric( $instance['number_post_format_page'] ) ) {
			$instance['number_post_format_page'] = '';
		}
		$instance['offset_post_format_page'] = absint( wp_strip_all_tags( $new_instance['offset_post_format_page'] ) );
		if ( 0 === $instance['offset_post_format_page'] || ! is_numeric( $instance['offset_post_format_page'] ) ) {
			$instance['offset_post_format_page'] = '';
		}
		$instance['title_post_format_page']     = wp_strip_all_tags( $new_instance['title_post_format_page'] );
		$instance['orderby_post_format_page']   = $new_instance['orderby_post_format_page'];
		$instance['order_post_format_page']     = $new_instance['order_post_format_page'];
		$instance['post_type_post_format_page'] = $new_instance['post_type_post_format_page'];
		$instance['ptm_spfp']                   = $new_instance['ptm_spfp'];
		if ( ! empty( $instance['ptm_spfp'] ) ) {
			$instance['ptm_spfp'] = pis_check_post_types( $instance['ptm_spfp'] );
		}

		$instance['dont_ignore_params_page'] = isset( $new_instance['dont_ignore_params_page'] ) ? 1 : 0;

		// Taxonomies.
		$instance['relation'] = wp_strip_all_tags( $new_instance['relation'] );

		$instance['taxonomy_aa'] = wp_strip_all_tags( $new_instance['taxonomy_aa'] );
		$instance['field_aa']    = wp_strip_all_tags( $new_instance['field_aa'] );
		$instance['terms_aa']    = pis_normalize_values( wp_strip_all_tags( $new_instance['terms_aa'] ) );
		$instance['operator_aa'] = wp_strip_all_tags( $new_instance['operator_aa'] );

		$instance['relation_a'] = wp_strip_all_tags( $new_instance['relation_a'] );

		$instance['taxonomy_ab'] = wp_strip_all_tags( $new_instance['taxonomy_ab'] );
		$instance['field_ab']    = wp_strip_all_tags( $new_instance['field_ab'] );
		$instance['terms_ab']    = pis_normalize_values( wp_strip_all_tags( $new_instance['terms_ab'] ) );
		$instance['operator_ab'] = wp_strip_all_tags( $new_instance['operator_ab'] );

		$instance['taxonomy_ba'] = wp_strip_all_tags( $new_instance['taxonomy_ba'] );
		$instance['field_ba']    = wp_strip_all_tags( $new_instance['field_ba'] );
		$instance['terms_ba']    = pis_normalize_values( wp_strip_all_tags( $new_instance['terms_ba'] ) );
		$instance['operator_ba'] = wp_strip_all_tags( $new_instance['operator_ba'] );

		$instance['relation_b'] = wp_strip_all_tags( $new_instance['relation_b'] );

		$instance['taxonomy_bb'] = pis_normalize_values( wp_strip_all_tags( $new_instance['taxonomy_bb'] ) );
		$instance['field_bb']    = wp_strip_all_tags( $new_instance['field_bb'] );
		$instance['terms_bb']    = wp_strip_all_tags( $new_instance['terms_bb'] );
		$instance['operator_bb'] = wp_strip_all_tags( $new_instance['operator_bb'] );

		// Date query.
		$instance['date_year'] = wp_strip_all_tags( $new_instance['date_year'] );
		if ( ! is_numeric( $instance['date_year'] ) ) {
			$instance['date_year'] = '';
		}
		$instance['date_month'] = wp_strip_all_tags( $new_instance['date_month'] );
		if ( 1 > $instance['date_month'] || 12 < $instance['date_month'] || ! is_numeric( $instance['date_month'] ) ) {
			$instance['date_month'] = '';
		}
		$instance['date_week'] = wp_strip_all_tags( $new_instance['date_week'] );
		if ( 0 > $instance['date_week'] || 53 < $instance['date_week'] || ! is_numeric( $instance['date_week'] ) ) {
			$instance['date_week'] = '';
		}
		$instance['date_day'] = wp_strip_all_tags( $new_instance['date_day'] );
		if ( 1 > $instance['date_day'] || 31 < $instance['date_day'] || ! is_numeric( $instance['date_day'] ) ) {
			$instance['date_day'] = '';
		}
		$instance['date_hour'] = wp_strip_all_tags( $new_instance['date_hour'] );
		if ( 0 > $instance['date_hour'] || 23 < $instance['date_hour'] || ! is_numeric( $instance['date_hour'] ) ) {
			$instance['date_hour'] = '';
		}
		$instance['date_minute'] = wp_strip_all_tags( $new_instance['date_minute'] );
		if ( 0 > $instance['date_minute'] || 59 < $instance['date_minute'] || ! is_numeric( $instance['date_minute'] ) ) {
			$instance['date_minute'] = '';
		}
		$instance['date_second'] = wp_strip_all_tags( $new_instance['date_second'] );
		if ( 0 > $instance['date_second'] || 59 < $instance['date_second'] || ! is_numeric( $instance['date_second'] ) ) {
			$instance['date_second'] = '';
		}
		$instance['date_after_year'] = wp_strip_all_tags( $new_instance['date_after_year'] );
		if ( ! is_numeric( $instance['date_after_year'] ) ) {
			$instance['date_after_year'] = '';
		}
		$instance['date_after_month'] = wp_strip_all_tags( $new_instance['date_after_month'] );
		if ( 1 > $instance['date_after_month'] || 12 < $instance['date_after_month'] || ! is_numeric( $instance['date_after_month'] ) ) {
			$instance['date_after_month'] = '';
		}
		$instance['date_after_day'] = wp_strip_all_tags( $new_instance['date_after_day'] );
		if ( 1 > $instance['date_after_day'] || 31 < $instance['date_after_day'] || ! is_numeric( $instance['date_after_day'] ) ) {
			$instance['date_after_day'] = '';
		}
		$instance['date_before_year'] = wp_strip_all_tags( $new_instance['date_before_year'] );
		if ( ! is_numeric( $instance['date_before_year'] ) ) {
			$instance['date_before_year'] = '';
		}
		$instance['date_before_month'] = wp_strip_all_tags( $new_instance['date_before_month'] );
		if ( 1 > $instance['date_before_month'] || 12 < $instance['date_before_month'] || ! is_numeric( $instance['date_before_month'] ) ) {
			$instance['date_before_month'] = '';
		}
		$instance['date_before_day'] = wp_strip_all_tags( $new_instance['date_before_day'] );
		if ( 1 > $instance['date_before_day'] || 31 < $instance['date_before_day'] || ! is_numeric( $instance['date_before_day'] ) ) {
			$instance['date_before_day'] = '';
		}
		$instance['date_inclusive']     = isset( $new_instance['date_inclusive'] ) ? 1 : 0;
		$instance['date_column']        = wp_strip_all_tags( $new_instance['date_column'] );
		$instance['date_after_dyn_num'] = absint( wp_strip_all_tags( $new_instance['date_after_dyn_num'] ) );
		if ( ! is_numeric( $instance['date_after_dyn_num'] ) || 0 === $instance['date_after_dyn_num'] ) {
			$instance['date_after_dyn_num'] = '';
		}
		$instance['date_after_dyn_date'] = wp_strip_all_tags( $new_instance['date_after_dyn_date'] );
		if ( '' === $instance['date_after_dyn_num'] ) {
			$instance['date_after_dyn_date'] = '';
		}
		$instance['date_before_dyn_num'] = absint( wp_strip_all_tags( $new_instance['date_before_dyn_num'] ) );
		if ( ! is_numeric( $instance['date_before_dyn_num'] ) || 0 === $instance['date_before_dyn_num'] ) {
			$instance['date_before_dyn_num'] = '';
		}
		$instance['date_before_dyn_date'] = wp_strip_all_tags( $new_instance['date_before_dyn_date'] );
		if ( '' === $instance['date_before_dyn_num'] ) {
			$instance['date_before_dyn_date'] = '';
		}

		// Meta query.
		$instance['mq_relation'] = wp_strip_all_tags( $new_instance['mq_relation'] );

		// Notice that `$instance['mq_compare_xx']` cannot be passed through a stripping function
		// otherwise we cannot use operators like `<`.
		$instance['mq_key_aa']     = wp_strip_all_tags( $new_instance['mq_key_aa'] );
		$instance['mq_value_aa']   = pis_normalize_values( wp_strip_all_tags( $new_instance['mq_value_aa'] ) );
		$instance['mq_compare_aa'] = $new_instance['mq_compare_aa'];
		$instance['mq_type_aa']    = wp_strip_all_tags( $new_instance['mq_type_aa'] );

		$instance['mq_relation_a'] = wp_strip_all_tags( $new_instance['mq_relation_a'] );

		$instance['mq_key_ab']     = wp_strip_all_tags( $new_instance['mq_key_ab'] );
		$instance['mq_value_ab']   = pis_normalize_values( wp_strip_all_tags( $new_instance['mq_value_ab'] ) );
		$instance['mq_compare_ab'] = $new_instance['mq_compare_ab'];
		$instance['mq_type_ab']    = wp_strip_all_tags( $new_instance['mq_type_ab'] );

		$instance['mq_key_ba']     = wp_strip_all_tags( $new_instance['mq_key_ba'] );
		$instance['mq_value_ba']   = pis_normalize_values( wp_strip_all_tags( $new_instance['mq_value_ba'] ) );
		$instance['mq_compare_ba'] = $new_instance['mq_compare_ba'];
		$instance['mq_type_ba']    = wp_strip_all_tags( $new_instance['mq_type_ba'] );

		$instance['mq_relation_b'] = wp_strip_all_tags( $new_instance['mq_relation_b'] );

		$instance['mq_key_bb']     = wp_strip_all_tags( $new_instance['mq_key_bb'] );
		$instance['mq_value_bb']   = pis_normalize_values( wp_strip_all_tags( $new_instance['mq_value_bb'] ) );
		$instance['mq_compare_bb'] = $new_instance['mq_compare_bb'];
		$instance['mq_type_bb']    = wp_strip_all_tags( $new_instance['mq_type_bb'] );

		// Posts exclusion.
		$instance['author_not_in'] = pis_normalize_values( wp_strip_all_tags( $new_instance['author_not_in'] ), true );
		if ( 0 == $instance['author_not_in'] ) {
			$instance['author_not_in'] = '';
		}
		$instance['cat_not_in'] = pis_normalize_values( wp_strip_all_tags( $new_instance['cat_not_in'] ), true );
		if ( 0 == $instance['cat_not_in'] ) {
			$instance['cat_not_in'] = '';
		}
		$instance['tag_not_in'] = pis_normalize_values( wp_strip_all_tags( $new_instance['tag_not_in'] ), true );
		if ( 0 == $instance['tag_not_in'] ) {
			$instance['tag_not_in'] = '';
		}
		$instance['post_not_in'] = pis_normalize_values( wp_strip_all_tags( $new_instance['post_not_in'] ), true );
		if ( 0 == $instance['post_not_in'] ) {
			$instance['post_not_in'] = '';
		}
		$instance['post_parent_not_in'] = pis_normalize_values( wp_strip_all_tags( $new_instance['post_parent_not_in'] ), true );
		if ( 0 == $instance['post_parent_not_in'] ) {
			$instance['post_parent_not_in'] = '';
		}
		$instance['exclude_current_post'] = isset( $new_instance['exclude_current_post'] ) ? 1 : 0;

		// Get posts from current user.
		$instance['get_from_username'] = isset( $new_instance['get_from_username'] ) ? 1 : 0;
		$instance['use_categories']    = isset( $new_instance['use_categories'] ) ? 1 : 0;

		$instance['dont_ignore_params_username'] = isset( $new_instance['dont_ignore_params_username'] ) ? 1 : 0;

		// The title of the post.
		$instance['display_title'] = isset( $new_instance['display_title'] ) ? 1 : 0;
		$instance['link_on_title'] = isset( $new_instance['link_on_title'] ) ? 1 : 0;
		$instance['arrow']         = isset( $new_instance['arrow'] ) ? 1 : 0;
		$instance['title_length']  = absint( wp_strip_all_tags( $new_instance['title_length'] ) );
		if ( '' === $instance['title_length'] || ! is_numeric( $instance['title_length'] ) ) {
			$instance['title_length'] = 0;
		}
		$instance['title_length_unit']  = wp_strip_all_tags( $new_instance['title_length_unit'] );
		$instance['title_hellipsis']    = isset( $new_instance['title_hellipsis'] ) ? 1 : 0;
		$instance['html_title_type_of'] = wp_strip_all_tags( $new_instance['html_title_type_of'] );

		// The featured image of the post.
		$instance['display_image']       = isset( $new_instance['display_image'] ) ? 1 : 0;
		$instance['image_size']          = wp_strip_all_tags( $new_instance['image_size'] );
		$instance['image_align']         = wp_strip_all_tags( $new_instance['image_align'] );
		$instance['image_before_title']  = isset( $new_instance['image_before_title'] ) ? 1 : 0;
		$instance['image_link']          = esc_url( wp_strip_all_tags( $new_instance['image_link'] ) );
		$instance['custom_image_url']    = esc_url( wp_strip_all_tags( $new_instance['custom_image_url'] ) );
		$instance['custom_img_no_thumb'] = isset( $new_instance['custom_img_no_thumb'] ) ? 1 : 0;
		$instance['image_link_to_post']  = isset( $new_instance['image_link_to_post'] ) ? 1 : 0;

		// The text of the post.
		$instance['excerpt']    = wp_strip_all_tags( $new_instance['excerpt'] );
		$instance['exc_length'] = absint( wp_strip_all_tags( $new_instance['exc_length'] ) );
		if ( '' === $instance['exc_length'] || ! is_numeric( $instance['exc_length'] ) ) {
			$instance['exc_length'] = 20;
		}
		$instance['exc_length_unit'] = wp_strip_all_tags( $new_instance['exc_length_unit'] );
		$instance['the_more']        = wp_strip_all_tags( $new_instance['the_more'] );
		$instance['the_more']        = str_replace( '...', '&hellip;', $instance['the_more'] );
		$instance['exc_arrow']       = isset( $new_instance['exc_arrow'] ) ? 1 : 0;

		// Author, date/time and comments.
		$instance['display_author']        = isset( $new_instance['display_author'] ) ? 1 : 0;
		$instance['author_text']           = wp_strip_all_tags( $new_instance['author_text'] );
		$instance['linkify_author']        = isset( $new_instance['linkify_author'] ) ? 1 : 0;
		$instance['gravatar_display']      = isset( $new_instance['gravatar_display'] ) ? 1 : 0;
		$instance['gravatar_size']         = wp_strip_all_tags( $new_instance['gravatar_size'] );
		$instance['gravatar_default']      = esc_url( $new_instance['gravatar_default'] );
		$instance['gravatar_position']     = wp_strip_all_tags( $new_instance['gravatar_position'] );
		$instance['display_date']          = isset( $new_instance['display_date'] ) ? 1 : 0;
		$instance['date_text']             = wp_strip_all_tags( $new_instance['date_text'] );
		$instance['linkify_date']          = isset( $new_instance['linkify_date'] ) ? 1 : 0;
		$instance['display_date']          = isset( $new_instance['display_date'] ) ? 1 : 0;
		$instance['display_time']          = isset( $new_instance['display_time'] ) ? 1 : 0;
		$instance['date_format']           = wp_strip_all_tags( $new_instance['date_format'] );
		$instance['time_format']           = wp_strip_all_tags( $new_instance['time_format'] );
		$instance['display_mod_date']      = isset( $new_instance['display_mod_date'] ) ? 1 : 0;
		$instance['mod_date_text']         = wp_strip_all_tags( $new_instance['mod_date_text'] );
		$instance['linkify_mod_date']      = isset( $new_instance['linkify_mod_date'] ) ? 1 : 0;
		$instance['display_mod_time']      = isset( $new_instance['display_mod_time'] ) ? 1 : 0;
		$instance['date_mod_format']       = wp_strip_all_tags( $new_instance['date_mod_format'] );
		$instance['time_mod_format']       = wp_strip_all_tags( $new_instance['time_mod_format'] );
		$instance['comments']              = isset( $new_instance['comments'] ) ? 1 : 0;
		$instance['comments_text']         = wp_strip_all_tags( $new_instance['comments_text'] );
		$instance['linkify_comments']      = isset( $new_instance['linkify_comments'] ) ? 1 : 0;
		$instance['display_comm_num_only'] = isset( $new_instance['display_comm_num_only'] ) ? 1 : 0;
		$instance['hide_zero_comments']    = isset( $new_instance['hide_zero_comments'] ) ? 1 : 0;
		$instance['utility_sep']           = wp_strip_all_tags( $new_instance['utility_sep'] );
		$instance['utility_after_title']   = isset( $new_instance['utility_after_title'] ) ? 1 : 0;
		$instance['utility_before_title']  = isset( $new_instance['utility_before_title'] ) ? 1 : 0;

		// The categories of the post.
		$instance['categories']         = isset( $new_instance['categories'] ) ? 1 : 0;
		$instance['categ_text']         = wp_strip_all_tags( $new_instance['categ_text'] );
		$instance['categ_sep']          = wp_strip_all_tags( $new_instance['categ_sep'] );
		$instance['categ_before_title'] = isset( $new_instance['categ_before_title'] ) ? 1 : 0;
		$instance['categ_after_title']  = isset( $new_instance['categ_after_title'] ) ? 1 : 0;

		// The tags of the post.
		$instance['tags']              = isset( $new_instance['tags'] ) ? 1 : 0;
		$instance['tags_text']         = wp_strip_all_tags( $new_instance['tags_text'] );
		$instance['hashtag']           = wp_strip_all_tags( $new_instance['hashtag'] );
		$instance['tag_sep']           = wp_strip_all_tags( $new_instance['tag_sep'] );
		$instance['tags_before_title'] = isset( $new_instance['tags_before_title'] ) ? 1 : 0;
		$instance['tags_after_title']  = isset( $new_instance['tags_after_title'] ) ? 1 : 0;

		// The custom taxonomies of the post.
		$instance['display_custom_tax'] = isset( $new_instance['display_custom_tax'] ) ? 1 : 0;
		$instance['term_hashtag']       = wp_strip_all_tags( $new_instance['term_hashtag'] );
		$instance['term_sep']           = wp_strip_all_tags( $new_instance['term_sep'] );
		$instance['ctaxs_before_title'] = isset( $new_instance['ctaxs_before_title'] ) ? 1 : 0;
		$instance['ctaxs_after_title']  = isset( $new_instance['ctaxs_after_title'] ) ? 1 : 0;

		// The custom field.
		$instance['custom_field_all']   = isset( $new_instance['custom_field_all'] ) ? 1 : 0;
		$instance['custom_field']       = isset( $new_instance['custom_field'] ) ? 1 : 0;
		$instance['custom_field_txt']   = wp_strip_all_tags( $new_instance['custom_field_txt'] );
		$instance['meta']               = wp_strip_all_tags( $new_instance['meta'] );
		$instance['custom_field_count'] = wp_strip_all_tags( $new_instance['custom_field_count'] );
		if ( 0 >= $instance['custom_field_count'] || ! is_numeric( $instance['custom_field_count'] ) ) {
			$instance['custom_field_count'] = '';
		}
		$instance['custom_field_hellip'] = wp_strip_all_tags( $new_instance['custom_field_hellip'] );
		$instance['custom_field_hellip'] = str_replace( '...', '&hellip;', $instance['custom_field_hellip'] );
		$instance['custom_field_key']    = isset( $new_instance['custom_field_key'] ) ? 1 : 0;
		// In the following line do not use `wp_strip_all_tags()` because it removes a possible trailing space.
		$instance['custom_field_sep'] = strip_tags( $new_instance['custom_field_sep'] );
		$instance['cf_before_title']  = isset( $new_instance['cf_before_title'] ) ? 1 : 0;
		$instance['cf_after_title']   = isset( $new_instance['cf_after_title'] ) ? 1 : 0;

		// The link to the archive.
		$instance['archive_link']   = isset( $new_instance['archive_link'] ) ? 1 : 0;
		$instance['link_to']        = wp_strip_all_tags( $new_instance['link_to'] );
		$instance['tax_name']       = wp_strip_all_tags( $new_instance['tax_name'] );
		$instance['tax_term_name']  = wp_strip_all_tags( $new_instance['tax_term_name'] );
		$instance['auto_term_name'] = isset( $new_instance['auto_term_name'] ) ? 1 : 0;
		$instance['archive_text']   = wp_strip_all_tags( $new_instance['archive_text'] );

		// Text when no posts found.
		$instance['nopost_text'] = wp_strip_all_tags( $new_instance['nopost_text'] );
		$instance['hide_widget'] = isset( $new_instance['hide_widget'] ) ? 1 : 0;

		// Styles.
		$instance['margin_unit']  = wp_strip_all_tags( $new_instance['margin_unit'] );
		$instance['intro_margin'] = wp_strip_all_tags( $new_instance['intro_margin'] );
		if ( ! is_numeric( $instance['intro_margin'] ) ) {
			$instance['intro_margin'] = null;
		}
		$instance['title_margin'] = wp_strip_all_tags( $new_instance['title_margin'] );
		if ( ! is_numeric( $instance['title_margin'] ) ) {
			$instance['title_margin'] = null;
		}
		$instance['side_image_margin'] = wp_strip_all_tags( $new_instance['side_image_margin'] );
		if ( ! is_numeric( $instance['side_image_margin'] ) ) {
			$instance['side_image_margin'] = null;
		}
		$instance['bottom_image_margin'] = wp_strip_all_tags( $new_instance['bottom_image_margin'] );
		if ( ! is_numeric( $instance['bottom_image_margin'] ) ) {
			$instance['bottom_image_margin'] = null;
		}
		$instance['excerpt_margin'] = wp_strip_all_tags( $new_instance['excerpt_margin'] );
		if ( ! is_numeric( $instance['excerpt_margin'] ) ) {
			$instance['excerpt_margin'] = null;
		}
		$instance['utility_margin'] = wp_strip_all_tags( $new_instance['utility_margin'] );
		if ( ! is_numeric( $instance['utility_margin'] ) ) {
			$instance['utility_margin'] = null;
		}
		$instance['categories_margin'] = wp_strip_all_tags( $new_instance['categories_margin'] );
		if ( ! is_numeric( $instance['categories_margin'] ) ) {
			$instance['categories_margin'] = null;
		}
		$instance['tags_margin'] = wp_strip_all_tags( $new_instance['tags_margin'] );
		if ( ! is_numeric( $instance['tags_margin'] ) ) {
			$instance['tags_margin'] = null;
		}
		$instance['terms_margin'] = wp_strip_all_tags( $new_instance['terms_margin'] );
		if ( ! is_numeric( $instance['terms_margin'] ) ) {
			$instance['terms_margin'] = null;
		}
		$instance['custom_field_margin'] = wp_strip_all_tags( $new_instance['custom_field_margin'] );
		if ( ! is_numeric( $instance['custom_field_margin'] ) ) {
			$instance['custom_field_margin'] = null;
		}
		$instance['archive_margin'] = wp_strip_all_tags( $new_instance['archive_margin'] );
		if ( ! is_numeric( $instance['archive_margin'] ) ) {
			$instance['archive_margin'] = null;
		}
		$instance['noposts_margin'] = wp_strip_all_tags( $new_instance['noposts_margin'] );
		if ( ! is_numeric( $instance['noposts_margin'] ) ) {
			$instance['noposts_margin'] = null;
		}
		$instance['custom_styles'] = wp_strip_all_tags( $new_instance['custom_styles'] );

		// Extras.
		$instance['container_class']     = sanitize_html_class( $new_instance['container_class'] );
		$instance['list_element']        = wp_strip_all_tags( $new_instance['list_element'] );
		$instance['remove_bullets']      = isset( $new_instance['remove_bullets'] ) ? 1 : 0;
		$instance['add_wp_post_classes'] = isset( $new_instance['add_wp_post_classes'] ) ? 1 : 0;

		// Cache.
		$instance['cached']     = isset( $new_instance['cached'] ) ? 1 : 0;
		$instance['cache_time'] = absint( wp_strip_all_tags( $new_instance['cache_time'] ) );
		// If user entered a cache time different from the stored cache time, reset the cache.
		if ( isset( $old_instance['cache_time'] ) ) {
			if ( $instance['cache_time'] !== $old_instance['cache_time'] ) {
				delete_transient( $this->id . '_query_cache' );
			}
		}

		// If `0` is entered as cache time, set cache time to 3600.
		// Do not use strict comparison (`===`) because the value is stored as string in the database!
		if ( 0 == $instance['cache_time'] ) {
			$instance['cached'] ? $instance['cache_time'] = 3600 : $instance['cache_time'] = '';
		}

		// In the past, this option was stored only for uninstall purposes.
		$instance['widget_id'] = $this->id;

		// Debug.
		$instance['admin_only']    = isset( $new_instance['admin_only'] ) ? 1 : 0;
		$instance['debug_query']   = isset( $new_instance['debug_query'] ) ? 1 : 0;
		$instance['debug_params']  = isset( $new_instance['debug_params'] ) ? 1 : 0;
		$instance['debug_post_id'] = isset( $new_instance['debug_post_id'] ) ? 1 : 0;

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
		$instance = wp_parse_args( (array) $instance, pis_get_defaults() );

		/*
		 * When upgrading from old version, $author, $cat, and $tag could be 'NULL' (as string).
		 * See above for some information (the long note on function update).
		 *
		 * @since 2.0.3
		 */
		if ( 'NULL' === $instance['author'] ) {
			$instance['author'] = '';
		}
		if ( 'NULL' === $instance['cat'] ) {
			$instance['cat'] = '';
		}
		if ( 'NULL' === $instance['tag'] ) {
			$instance['tag'] = '';
		}
		?>

		<!-- Widget title -->
		<div class="pis-section">

			<h4><?php esc_html_e( 'The title of the widget', 'posts-in-sidebar' ); ?></h4>

			<?php
			pis_form_input_text(
				esc_html__( 'Title', 'posts-in-sidebar' ),
				$this->get_field_id( 'title' ),
				$this->get_field_name( 'title' ),
				esc_attr( $instance['title'] ),
				esc_html__( 'From the archive', 'posts-in-sidebar' )
			);
			?>

			<?php
			pis_form_input_text(
				esc_html__( 'Link the title of the widget to this URL', 'posts-in-sidebar' ),
				$this->get_field_id( 'title_link' ),
				$this->get_field_name( 'title_link' ),
				esc_url( wp_strip_all_tags( $instance['title_link'] ) ),
				'http://example.com/readings-series/'
			);
			?>

			<?php
			pis_form_textarea(
				esc_html__( 'Place this text after the title', 'posts-in-sidebar' ),
				$this->get_field_id( 'intro' ),
				$this->get_field_name( 'intro' ),
				$instance['intro'],
				esc_html__( 'These posts are part of my Readings series.', 'posts-in-sidebar' ),
				'resize: vertical; width: 100%; height: 80px; min-height: 80px;'
			);
			?>

		</div>

		<!-- Getting posts -->
		<div class="pis-section">

			<h4 data-panel="getting-posts" class="pis-widget-title"><?php esc_html_e( 'Getting posts', 'posts-in-sidebar' ); ?></h4>

			<div class="pis-container">

				<p><em><?php esc_html_e( 'In this section you can define which type of posts you want to retrieve and which taxonomy the plugin will use. Other parameters are available to better define the query.', 'posts-in-sidebar' ); ?></em></p>

				<p><em>
					<?php
					// translators: there is some code in placeholders.
					printf( esc_html__( 'If a field requires one or more IDs, install %1$sthis plugin%2$s to easily find the IDs.', 'posts-in-sidebar' ), '<a href="https://wordpress.org/plugins/reveal-ids-for-wp-admin-25/" target="_blank">', '</a>' );
					?>
				</em></p>

				<!-- Basic setup -->
				<div class="pis-section">

					<h5 class="pis-simple-title"><?php esc_html_e( 'Post type', 'posts-in-sidebar' ); ?></h5>

					<div class="pis-column-container pis-2col">

						<div class="pis-column">
							<?php
							// ================= Post types.
							pis_form_select(
								esc_html__( 'Post type', 'posts-in-sidebar' ),
								$this->get_field_id( 'post_type' ),
								$this->get_field_name( 'post_type' ),
								pis_select_post_types(),
								$instance['post_type'],
								esc_html__( 'Select a single post type.', 'posts-in-sidebar' )
							);
							?>
						</div>

						<div class="pis-column">
							<?php
							// ================= Multiple post types
							pis_form_input_text(
								esc_html__( 'Multiple post types', 'posts-in-sidebar' ),
								$this->get_field_id( 'post_type_multiple' ),
								$this->get_field_name( 'post_type_multiple' ),
								esc_attr( $instance['post_type_multiple'] ),
								esc_html__( 'post, page, book, recipe', 'posts-in-sidebar' ),
								esc_html__( 'Enter post types slugs, comma separated. This option, if filled, overrides the option above.', 'posts-in-sidebar' )
							);
							?>
						</div>

					</div>

					<div class="pis-column-container pis-2col">

						<h5 class="pis-simple-title"><?php esc_html_e( 'Get posts by IDs', 'posts-in-sidebar' ); ?></h5>

						<div class="pis-column">
							<?php
							// ================= Posts ID
							pis_form_input_text(
								esc_html__( 'Get posts with these IDs', 'posts-in-sidebar' ),
								$this->get_field_id( 'posts_id' ),
								$this->get_field_name( 'posts_id' ),
								esc_attr( $instance['posts_id'] ),
								'5, 29, 523, 4519',
								esc_html__( 'Enter IDs, comma separated.', 'posts-in-sidebar' )
							);
							?>
						</div>

						<div class="pis-column">
							<?php
							// ================= Post parent
							pis_form_input_text(
								esc_html__( 'Get posts whose parent is in these IDs', 'posts-in-sidebar' ),
								$this->get_field_id( 'post_parent_in' ),
								$this->get_field_name( 'post_parent_in' ),
								esc_attr( $instance['post_parent_in'] ),
								esc_html__( '2, 5, 12, 14, 20', 'posts-in-sidebar' ),
								esc_html__( 'Enter IDs, comma separated.', 'posts-in-sidebar' )
							);
							?>
						</div>

					</div>

					<div class="pis-column-container pis-2col">

						<h5 class="pis-simple-title"><?php esc_html_e( 'Get posts by taxonomy', 'posts-in-sidebar' ); ?></h5>

						<div class="pis-column">
							<?php
							// ================= Category
							pis_form_input_text(
								esc_html__( 'Get posts with these categories', 'posts-in-sidebar' ),
								$this->get_field_id( 'cat' ),
								$this->get_field_name( 'cat' ),
								esc_attr( $instance['cat'] ),
								esc_html__( 'books, ebooks', 'posts-in-sidebar' ),
								// translators: there is some code in placeholders.
								sprintf( esc_html__( 'Enter slugs, comma separated. To display posts that have all of the categories, use %1$s (a plus) between terms, for example:%2$s.', 'posts-in-sidebar' ), '<code>+</code>', '<br /><code>staff+news+our-works</code>' )
							);
							?>
						</div>

						<div class="pis-column">
							<?php
							// ================= Tag
							pis_form_input_text(
								esc_html__( 'Get posts with these tags', 'posts-in-sidebar' ),
								$this->get_field_id( 'tag' ),
								$this->get_field_name( 'tag' ),
								esc_attr( $instance['tag'] ),
								esc_html__( 'best-sellers', 'posts-in-sidebar' ),
								// translators: there is some code in placeholders.
								sprintf( esc_html__( 'Enter slugs, comma separated. To display posts that have all of the tags, use %1$s (a plus) between terms, for example:%2$s.', 'posts-in-sidebar' ), '<code>+</code>', '<br /><code>staff+news+our-works</code>' )
							);
							?>
						</div>

					</div>

					<div class="pis-column-container pis-2col">

						<h5 class="pis-simple-title"><?php esc_html_e( 'Get posts by author', 'posts-in-sidebar' ); ?></h5>

						<div class="pis-column">
							<?php
							// ================= Author
							pis_form_select(
								esc_html__( 'Get posts by this author', 'posts-in-sidebar' ),
								$this->get_field_id( 'author' ),
								$this->get_field_name( 'author' ),
								pis_select_authors(),
								$instance['author']
							);
							?>
						</div>

						<div class="pis-column">
							<?php
							// ================= Multiple authors
							pis_form_input_text(
								esc_html__( 'Get posts by these authors', 'posts-in-sidebar' ),
								$this->get_field_id( 'author_in' ),
								$this->get_field_name( 'author_in' ),
								esc_attr( $instance['author_in'] ),
								esc_html__( '1, 23, 45', 'posts-in-sidebar' ),
								esc_html__( 'Enter IDs, comma separated. Note that if you fill this field, the previous one will be ignored.', 'posts-in-sidebar' )
							);
							?>
						</div>

					</div>

					<div class="pis-column-container pis-2col">

						<h5 class="pis-simple-title"><?php esc_html_e( 'Get posts by post format and status', 'posts-in-sidebar' ); ?></h5>

						<div class="pis-column">
							<?php
							// ================= Post format
							pis_form_select(
								esc_html__( 'Get posts with this post format', 'posts-in-sidebar' ),
								$this->get_field_id( 'post_format' ),
								$this->get_field_name( 'post_format' ),
								pis_select_post_formats(),
								$instance['post_format']
							);
							?>
						</div>
						<div class="pis-column">
							<?php
							// ================= Post status
							pis_form_select(
								esc_html__( 'Get posts with this post status', 'posts-in-sidebar' ),
								$this->get_field_id( 'post_status' ),
								$this->get_field_name( 'post_status' ),
								pis_select_post_status(),
								$instance['post_status']
							);
							?>
						</div>

					</div>

					<div class="pis-column-container pis-2col">

						<h5 class="pis-simple-title"><?php esc_html_e( 'Get posts by custom field', 'posts-in-sidebar' ); ?></h5>

						<div class="pis-column">
							<?php
							// ================= Post meta key
							pis_form_input_text(
								esc_html__( 'Get posts with this meta key', 'posts-in-sidebar' ),
								$this->get_field_id( 'post_meta_key' ),
								$this->get_field_name( 'post_meta_key' ),
								esc_attr( $instance['post_meta_key'] ),
								esc_html__( 'meta-key', 'posts-in-sidebar' )
							);
							?>
						</div>

						<div class="pis-column">
							<?php
							// ================= Post meta value
							pis_form_input_text(
								esc_html__( 'Get posts with this meta value', 'posts-in-sidebar' ),
								$this->get_field_id( 'post_meta_val' ),
								$this->get_field_name( 'post_meta_val' ),
								esc_attr( $instance['post_meta_val'] ),
								esc_html__( 'meta-value', 'posts-in-sidebar' )
							);
							?>
						</div>

					</div>

					<div class="pis-column-container pis-2col">

						<h5 class="pis-simple-title"><?php esc_html_e( 'Get posts by recent comments and search', 'posts-in-sidebar' ); ?></h5>

						<div class="pis-column">
							<?php
							// ================= Get posts by recent comments
							pis_form_checkbox(
								esc_html__( 'Get posts by recent comments', 'posts-in-sidebar' ),
								$this->get_field_id( 'posts_by_comments' ),
								$this->get_field_name( 'posts_by_comments' ),
								$instance['posts_by_comments'],
								esc_html__( 'Only published posts, in descending order, will be retrieved.', 'posts-in-sidebar' )
							);
							?>
						</div>

						<div class="pis-column">
							<?php
							// ================= Search
							pis_form_input_text(
								esc_html__( 'Get posts from this search', 'posts-in-sidebar' ),
								$this->get_field_id( 'search' ),
								$this->get_field_name( 'search' ),
								esc_attr( $instance['search'] ),
								esc_html__( 'words to search', 'posts-in-sidebar' )
							);
							?>
						</div>

					</div>

					<div class="pis-column-container pis-2col">

						<h5 class="pis-simple-title"><?php esc_html_e( 'Get posts by password', 'posts-in-sidebar' ); ?></h5>

						<div class="pis-column">
							<?php
							// ================= Post with/without password
							pis_form_select(
								esc_html__( 'Get posts with/without password', 'posts-in-sidebar' ),
								$this->get_field_id( 'has_password' ),
								$this->get_field_name( 'has_password' ),
								pis_select_post_password(),
								$instance['has_password']
							);
							?>
						</div>

						<div class="pis-column">
							<?php
							// ================= Post password
							pis_form_input_text(
								esc_html__( 'Get posts with this password', 'posts-in-sidebar' ),
								$this->get_field_id( 'post_password' ),
								$this->get_field_name( 'post_password' ),
								esc_attr( $instance['post_password'] ),
								// XKCD, Password Strength, https://xkcd.com/936/.
								esc_html__( 'correct horse battery staple', 'posts-in-sidebar' )
							);
							?>
						</div>

					</div>

					<div class="pis-column-container pis-2col">

						<h5 class="pis-simple-title"><?php esc_html_e( 'Number of posts, order, sticky posts and skipping', 'posts-in-sidebar' ); ?></h5>

						<div class="pis-column">

							<?php
							// ================= Posts quantity
							pis_form_input_text(
								esc_html__( 'Get this number of posts', 'posts-in-sidebar' ),
								$this->get_field_id( 'number' ),
								$this->get_field_name( 'number' ),
								esc_attr( $instance['number'] ),
								'3',
								// translators: %s is -1.
								sprintf( esc_html__( 'The value %s shows all the posts.', 'posts-in-sidebar' ), '<code>-1</code>' )
							);
							?>

							<?php
							// ================= Ignore sticky post
							pis_form_checkbox(
								esc_html__( 'Do not display sticky posts on top of other posts', 'posts-in-sidebar' ),
								$this->get_field_id( 'ignore_sticky' ),
								$this->get_field_name( 'ignore_sticky' ),
								$instance['ignore_sticky'],
								esc_html__( 'If you activate this option, sticky posts will be managed as other posts. Sticky post status will be automatically ignored if you set up an author or a taxonomy in this widget.', 'posts-in-sidebar' )
							);
							?>

						</div>

						<div class="pis-column">

							<?php
							// ================= Post order by
							pis_form_select(
								esc_html__( 'Order posts by', 'posts-in-sidebar' ),
								$this->get_field_id( 'orderby' ),
								$this->get_field_name( 'orderby' ),
								pis_select_order_by_relevance(),
								$instance['orderby']
							);
							?>

							<?php
							// ================= Post order
							pis_form_select(
								esc_html__( 'The order will be', 'posts-in-sidebar' ),
								$this->get_field_id( 'order' ),
								$this->get_field_name( 'order' ),
								pis_select_order(),
								$instance['order']
							);
							?>

							<?php
							// ================= Number of posts to skip
							pis_form_input_text(
								esc_html__( 'Skip this number of posts', 'posts-in-sidebar' ),
								$this->get_field_id( 'offset_number' ),
								$this->get_field_name( 'offset_number' ),
								esc_attr( $instance['offset_number'] ),
								'5',
								sprintf(
									// translators: %s is -1.
									esc_html__( 'This option will be ignored if you set %s in "Get this number of posts".', 'posts-in-sidebar' ),
									'<code>-1</code>'
								)
							);
							?>

						</div>

					</div>

				</div>

				<!-- Changing query on single posts -->
				<div class="pis-section pis-2col">

					<h5 data-panel="change-query" class="pis-widget-title"><?php esc_html_e( 'Change the query when on single posts', 'posts-in-sidebar' ); ?></h5>

					<div class="pis-container">

						<p><em>
							<?php
							// translators: there is some code in placeholders.
							printf( esc_html__( 'In this section you can change some parameters of the query when on single posts. %1$sActivate only one of these.%2$s', 'posts-in-sidebar' ), '<strong>', '</strong>' );
							?>
						</em></p>

						<h6 data-panel="get-posts-current-category" class="pis-widget-title"><?php esc_html_e( 'Get posts from current category', 'posts-in-sidebar' ); ?></h6>

						<div class="pis-container">

							<p class="pis-boxed pis-boxed-light-blue">
								<em>
									<strong>
										<?php
										esc_html_e( 'How this plugin chooses the main category of the post.', 'posts-in-sidebar' );
										?>
									</strong>
									<br />
									<br />
									<?php
									esc_html_e(
										'When the option "When on single posts, get posts from the current category" is activated and the post has multiple categories, Posts in Sidebar will choose the category with the lowest initial letter, just as WordPress does when we get the list of the categories of a post.',
										'posts-in-sidebar'
									);
									?>
									<br />
									<br />
									<?php
									printf(
										// translators: Opening and closing "strong" HTML tag.
										esc_html__(
											'If you activate the option %1$s"Use the main category as WordPress does for permalinks"%2$s (formerly known here as "Sort categories"), Posts in Sidebar will get the category with the lowest category ID. This is the WordPress behaviour when it determines the permalink structure if the category is used in the permalink.',
											'posts-in-sidebar'
										),
										'<strong>',
										'</strong>'
									);
									?>
									<br />
									<br />
									<?php
									printf(
										// translators: Opening and closing "strong" HTML tag.
										esc_html__(
											'If you use the Yoast SEO plugin and want to use the main category as defined with that plugin, activate the option %1$s"Use the main category as defined in the Yoast SEO plugin"%2$s.',
											'posts-in-sidebar'
										),
										'<strong>',
										'</strong>'
									);
									?>
								</em>
							</p>

							<div class="pis-column-container">

								<div class="pis-column">

									<?php
									// ================= Get posts from same category
									pis_form_checkbox(
										esc_html__( 'When on single posts, get posts from the current category', 'posts-in-sidebar' ),
										$this->get_field_id( 'get_from_same_cat' ),
										$this->get_field_name( 'get_from_same_cat' ),
										$instance['get_from_same_cat'],
										esc_html__( 'When activated, this option will ignore other parameters like tags, date, post formats, etc. If you don\'t want to ignore other parameters, activate the checkbox below, at the end of this panel.', 'posts-in-sidebar' )
									);
									?>

									<?php
									// ================= Sort categories
									pis_form_checkbox(
										esc_html__( 'Use the main category as WordPress does for permalinks', 'posts-in-sidebar' ),
										$this->get_field_id( 'sort_categories' ),
										$this->get_field_name( 'sort_categories' ),
										$instance['sort_categories']
									);
									?>

									<?php
									// ================= Yoast main category
									pis_form_checkbox(
										esc_html__( 'Use the main category as defined in the Yoast SEO plugin', 'posts-in-sidebar' ),
										$this->get_field_id( 'yoast_main_cat' ),
										$this->get_field_name( 'yoast_main_cat' ),
										$instance['yoast_main_cat']
									);
									?>

									<?php
									// ================= Post types from same category
									pis_form_select(
										esc_html__( 'Post type', 'posts-in-sidebar' ),
										$this->get_field_id( 'post_type_same_cat' ),
										$this->get_field_name( 'post_type_same_cat' ),
										pis_select_post_types(),
										$instance['post_type_same_cat'],
										esc_html__( 'Select a single post type.', 'posts-in-sidebar' )
									);
									?>

									<?php
									// ================= Multiple post types in same category
									pis_form_input_text(
										esc_html__( 'Multiple post types', 'posts-in-sidebar' ),
										$this->get_field_id( 'ptm_sc' ),
										$this->get_field_name( 'ptm_sc' ),
										esc_attr( $instance['ptm_sc'] ),
										esc_html__( 'post, page, book, recipe', 'posts-in-sidebar' ),
										esc_html__( 'Enter post types slugs, comma separated. This option, if filled, overrides the option above.', 'posts-in-sidebar' )
									);
									?>

								</div>

								<div class="pis-column">

									<?php
									// ================= Posts quantity
									pis_form_input_text(
										esc_html__( 'When on single posts, get this number of posts', 'posts-in-sidebar' ),
										$this->get_field_id( 'number_same_cat' ),
										$this->get_field_name( 'number_same_cat' ),
										esc_attr( $instance['number_same_cat'] ),
										'3',
										// translators: %s is -1.
										sprintf( esc_html__( 'The value %s shows all the posts.', 'posts-in-sidebar' ), '<code>-1</code>' )
									);
									?>

									<?php
									// ================= The custom widget title when on single posts
									pis_form_input_text(
										esc_html__( 'When on single posts, use this widget title', 'posts-in-sidebar' ),
										$this->get_field_id( 'title_same_cat' ),
										$this->get_field_name( 'title_same_cat' ),
										esc_attr( $instance['title_same_cat'] ),
										// translators: %s is a placeholder for a taxonomy.
										esc_html__( 'Posts under %s', 'posts-in-sidebar' ),
										// translators: there is some code in placeholders.
										sprintf( esc_html__( 'Use %s to display the name of the category.', 'posts-in-sidebar' ), '<code>%s</code>' )
									);
									?>

									<?php
									// ================= Post order by
									pis_form_select(
										esc_html__( 'Order posts by', 'posts-in-sidebar' ),
										$this->get_field_id( 'orderby_same_cat' ),
										$this->get_field_name( 'orderby_same_cat' ),
										pis_select_order_by_relevance(),
										$instance['orderby_same_cat']
									);
									?>

									<?php
									// ================= Post order.
									pis_form_select(
										esc_html__( 'The order will be', 'posts-in-sidebar' ),
										$this->get_field_id( 'order_same_cat' ),
										$this->get_field_name( 'order_same_cat' ),
										pis_select_order(),
										$instance['order_same_cat']
									);
									?>

									<?php
									// ================= Number of posts to skip
									pis_form_input_text(
										esc_html__( 'Skip this number of posts', 'posts-in-sidebar' ),
										$this->get_field_id( 'offset_same_cat' ),
										$this->get_field_name( 'offset_same_cat' ),
										esc_attr( $instance['offset_same_cat'] ),
										'5'
									);
									?>

									<?php
									// ================= Search post title
									pis_form_checkbox(
										esc_html__( 'Post title matching', 'posts-in-sidebar' ),
										$this->get_field_id( 'search_same_cat' ),
										$this->get_field_name( 'search_same_cat' ),
										$instance['search_same_cat'],
										esc_html__( 'Show posts that match the main post title. WordPress will show posts under the same category of the main post and matching in a search for the title of the main post.', 'posts-in-sidebar' )
									);
									?>

								</div>

							</div>

						</div>

						<h6 data-panel="get-posts-current-tag" class="pis-widget-title"><?php esc_html_e( 'Get posts from current tag', 'posts-in-sidebar' ); ?></h6>

						<div class="pis-container">

							<div class="pis-column-container">

								<div class="pis-column">

									<?php
									// ================= Get posts from same tag
									pis_form_checkbox(
										esc_html__( 'When on single posts, get posts from the current tag', 'posts-in-sidebar' ),
										$this->get_field_id( 'get_from_same_tag' ),
										$this->get_field_name( 'get_from_same_tag' ),
										$instance['get_from_same_tag'],
										esc_html__( 'When activated, this function will get posts from the tag of the post, ignoring other parameters like categories, date, post formats, etc. If the post has multiple tags, the plugin will use the first tag in the array of tags (the tag with the lowest initial letter). If you don\'t want to ignore other parameters, activate the checkbox below, at the end of this panel.', 'posts-in-sidebar' )
									);
									?>

									<?php
									// ================= Post types from same tag
									pis_form_select(
										esc_html__( 'Post type', 'posts-in-sidebar' ),
										$this->get_field_id( 'post_type_same_tag' ),
										$this->get_field_name( 'post_type_same_tag' ),
										pis_select_post_types(),
										$instance['post_type_same_tag'],
										esc_html__( 'Select a single post type.', 'posts-in-sidebar' )
									);
									?>

									<?php
									// ================= Multiple post types in same tag
									pis_form_input_text(
										esc_html__( 'Multiple post types', 'posts-in-sidebar' ),
										$this->get_field_id( 'ptm_st' ),
										$this->get_field_name( 'ptm_st' ),
										esc_attr( $instance['ptm_st'] ),
										esc_html__( 'post, page, book, recipe', 'posts-in-sidebar' ),
										esc_html__( 'Enter post types slugs, comma separated. This option, if filled, overrides the option above.', 'posts-in-sidebar' )
									);
									?>

									<?php
									// ================= Sort tags
									pis_form_checkbox(
										esc_html__( 'Sort tags', 'posts-in-sidebar' ),
										$this->get_field_id( 'sort_tags' ),
										$this->get_field_name( 'sort_tags' ),
										$instance['sort_tags'],
										esc_html__( 'When activated, this function will sort the tags of the main post by tag ID. In this way the plugin will get posts from the tag with the lowest ID.', 'posts-in-sidebar' )
									);
									?>

								</div>

								<div class="pis-column">

									<?php
									// ================= Posts quantity
									pis_form_input_text(
										esc_html__( 'When on single posts, get this number of posts', 'posts-in-sidebar' ),
										$this->get_field_id( 'number_same_tag' ),
										$this->get_field_name( 'number_same_tag' ),
										esc_attr( $instance['number_same_tag'] ),
										'3',
										// translators: %s is -1.
										sprintf( esc_html__( 'The value %s shows all the posts.', 'posts-in-sidebar' ), '<code>-1</code>' )
									);
									?>

									<?php
									// ================= The custom widget title when on single posts
									pis_form_input_text(
										esc_html__( 'When on single posts, use this widget title', 'posts-in-sidebar' ),
										$this->get_field_id( 'title_same_tag' ),
										$this->get_field_name( 'title_same_tag' ),
										esc_attr( $instance['title_same_tag'] ),
										// translators: %s is the name of the tag.
										esc_html__( 'Posts tagged with %s', 'posts-in-sidebar' ),
										// translators: there is some code in placeholders.
										sprintf( esc_html__( 'Use %s to display the name of the tag.', 'posts-in-sidebar' ), '<code>%s</code>' )
									);
									?>

									<?php
									// ================= Post order by
									pis_form_select(
										esc_html__( 'Order posts by', 'posts-in-sidebar' ),
										$this->get_field_id( 'orderby_same_tag' ),
										$this->get_field_name( 'orderby_same_tag' ),
										pis_select_order_by_relevance(),
										$instance['orderby_same_tag']
									);
									?>

									<?php
									// ================= Post order
									pis_form_select(
										esc_html__( 'The order will be', 'posts-in-sidebar' ),
										$this->get_field_id( 'order_same_tag' ),
										$this->get_field_name( 'order_same_tag' ),
										pis_select_order(),
										$instance['order_same_tag']
									);
									?>

									<?php
									// ================= Number of posts to skip
									pis_form_input_text(
										esc_html__( 'Skip this number of posts', 'posts-in-sidebar' ),
										$this->get_field_id( 'offset_same_tag' ),
										$this->get_field_name( 'offset_same_tag' ),
										esc_attr( $instance['offset_same_tag'] ),
										'5'
									);
									?>

									<?php
									// ================= Search post title
									pis_form_checkbox(
										esc_html__( 'Post title matching', 'posts-in-sidebar' ),
										$this->get_field_id( 'search_same_tag' ),
										$this->get_field_name( 'search_same_tag' ),
										$instance['search_same_tag'],
										esc_html__( 'Show posts that match the main post title. WordPress will show posts with the same tag of the main post and matching in a search for the title of the main post.', 'posts-in-sidebar' )
									);
									?>

								</div>

							</div>

						</div>

						<h6 data-panel="get-posts-current-author" class="pis-widget-title"><?php esc_html_e( 'Get posts from current author', 'posts-in-sidebar' ); ?></h6>

						<div class="pis-container">

							<div class="pis-column-container">

								<div class="pis-column">

									<?php
									// ================= Get posts from same author
									pis_form_checkbox(
										esc_html__( 'When on single posts, get posts from the current author', 'posts-in-sidebar' ),
										$this->get_field_id( 'get_from_same_author' ),
										$this->get_field_name( 'get_from_same_author' ),
										$instance['get_from_same_author'],
										esc_html__( 'When activated, this function will get posts by the author of the post, ignoring other parameters like categories, tags, date, post formats, etc. If you don\'t want to ignore other parameters, activate the checkbox below, at the end of this panel.', 'posts-in-sidebar' )
									);
									?>

									<?php
									// ================= Post types from same author
									pis_form_select(
										esc_html__( 'Post type', 'posts-in-sidebar' ),
										$this->get_field_id( 'post_type_same_author' ),
										$this->get_field_name( 'post_type_same_author' ),
										pis_select_post_types(),
										$instance['post_type_same_author'],
										esc_html__( 'Select a single post type.', 'posts-in-sidebar' )
									);
									?>

									<?php
									// ================= Multiple post types in same author
									pis_form_input_text(
										esc_html__( 'Multiple post types', 'posts-in-sidebar' ),
										$this->get_field_id( 'ptm_sa' ),
										$this->get_field_name( 'ptm_sa' ),
										esc_attr( $instance['ptm_sa'] ),
										esc_html__( 'post, page, book, recipe', 'posts-in-sidebar' ),
										esc_html__( 'Enter post types slugs, comma separated. This option, if filled, overrides the option above.', 'posts-in-sidebar' )
									);
									?>

								</div>

								<div class="pis-column">

									<?php
									// ================= Posts quantity
									pis_form_input_text(
										esc_html__( 'When on single posts, get this number of posts', 'posts-in-sidebar' ),
										$this->get_field_id( 'number_same_author' ),
										$this->get_field_name( 'number_same_author' ),
										esc_attr( $instance['number_same_author'] ),
										'3',
										// translators: %s is -1.
										sprintf( esc_html__( 'The value %s shows all the posts.', 'posts-in-sidebar' ), '<code>-1</code>' )
									);
									?>

									<?php
									// ================= The custom widget title when on single posts
									pis_form_input_text(
										esc_html__( 'When on single posts, use this widget title', 'posts-in-sidebar' ),
										$this->get_field_id( 'title_same_author' ),
										$this->get_field_name( 'title_same_author' ),
										esc_attr( $instance['title_same_author'] ),
										// translators: %s is the name of the author.
										esc_html__( 'Posts by %s', 'posts-in-sidebar' ),
										// translators: there is some code in placeholders.
										sprintf( esc_html__( 'Use %s to display the name of the author.', 'posts-in-sidebar' ), '<code>%s</code>' )
									);
									?>

									<?php
									// ================= Post order by
									pis_form_select(
										esc_html__( 'Order posts by', 'posts-in-sidebar' ),
										$this->get_field_id( 'orderby_same_author' ),
										$this->get_field_name( 'orderby_same_author' ),
										pis_select_order_by_relevance(),
										$instance['orderby_same_author']
									);
									?>

									<?php
									// ================= Post order
									pis_form_select(
										esc_html__( 'The order will be', 'posts-in-sidebar' ),
										$this->get_field_id( 'order_same_author' ),
										$this->get_field_name( 'order_same_author' ),
										pis_select_order(),
										$instance['order_same_author']
									);
									?>

									<?php
									// ================= Number of posts to skip
									pis_form_input_text(
										esc_html__( 'Skip this number of posts', 'posts-in-sidebar' ),
										$this->get_field_id( 'offset_same_author' ),
										$this->get_field_name( 'offset_same_author' ),
										esc_attr( $instance['offset_same_author'] ),
										'5'
									);
									?>

									<?php
									// ================= Search post title
									pis_form_checkbox(
										esc_html__( 'Post title matching', 'posts-in-sidebar' ),
										$this->get_field_id( 'search_same_author' ),
										$this->get_field_name( 'search_same_author' ),
										$instance['search_same_author'],
										esc_html__( 'Show posts that match the main post title. WordPress will show posts by the same author of the main post and matching in a search for the title of the main post.', 'posts-in-sidebar' )
									);
									?>

								</div>

							</div>

						</div>

						<h6 data-panel="get-posts-current-cf" class="pis-widget-title"><?php esc_html_e( 'Get posts from taxonomy using custom field', 'posts-in-sidebar' ); ?></h6>

						<div class="pis-container">

							<div class="pis-column-container">

								<div class="pis-column">

									<?php
									// ================= Get posts from category/tags using custom field when on single post
									pis_form_checkbox(
										esc_html__( 'When on single posts, get posts from this custom field', 'posts-in-sidebar' ),
										$this->get_field_id( 'get_from_custom_fld' ),
										$this->get_field_name( 'get_from_custom_fld' ),
										$instance['get_from_custom_fld'],
										// translators: there is some code in placeholders.
										sprintf( esc_html__( 'When activated, this function will get posts from the category defined by the user via custom field, ignoring other parameters like categories, tags, date, post formats, etc. %1$sRead more on this%2$s. If you don\'t want to ignore other parameters, activate the checkbox below, at the end of this panel.', 'posts-in-sidebar' ), '<a href="https://github.com/aldolat/posts-in-sidebar/wiki/Advanced-Usage#the-get-posts-from-taxonomy-using-custom-field-option" target="_blank">', '</a>' )
									);
									?>

									<?php
									// ================= Post types from same custom field
									pis_form_select(
										esc_html__( 'Post type', 'posts-in-sidebar' ),
										$this->get_field_id( 'post_type_same_cf' ),
										$this->get_field_name( 'post_type_same_cf' ),
										pis_select_post_types(),
										$instance['post_type_same_cf'],
										esc_html__( 'Select a single post type.', 'posts-in-sidebar' )
									);
									?>

									<?php
									// ================= Multiple post types in same custom field
									pis_form_input_text(
										esc_html__( 'Multiple post types', 'posts-in-sidebar' ),
										$this->get_field_id( 'ptm_scf' ),
										$this->get_field_name( 'ptm_scf' ),
										esc_attr( $instance['ptm_scf'] ),
										esc_html__( 'post, page, book, recipe', 'posts-in-sidebar' ),
										esc_html__( 'Enter post types slugs, comma separated. This option, if filled, overrides the option above.', 'posts-in-sidebar' )
									);
									?>

									<?php
									// ================= Define the custom field key
									pis_form_input_text(
										esc_html__( 'Get posts with this custom field key', 'posts-in-sidebar' ),
										$this->get_field_id( 's_custom_field_key' ),
										$this->get_field_name( 's_custom_field_key' ),
										esc_attr( $instance['s_custom_field_key'] ),
										'custom_field_key'
									);
									?>

									<?php
									// ================= Type of the taxonomy
									pis_form_select(
										esc_html__( 'Type of the taxonomy', 'posts-in-sidebar' ),
										$this->get_field_id( 's_custom_field_tax' ),
										$this->get_field_name( 's_custom_field_tax' ),
										pis_select_taxonomies(),
										$instance['s_custom_field_tax']
									);
									?>

								</div>

								<div class="pis-column">

									<?php
									// ================= Posts quantity
									pis_form_input_text(
										esc_html__( 'When on single posts, get this number of posts', 'posts-in-sidebar' ),
										$this->get_field_id( 'number_custom_field' ),
										$this->get_field_name( 'number_custom_field' ),
										esc_attr( $instance['number_custom_field'] ),
										'3',
										// translators: %s is -1.
										sprintf( esc_html__( 'The value %s shows all the posts.', 'posts-in-sidebar' ), '<code>-1</code>' )
									);
									?>

									<?php
									// ================= The custom widget title when on single posts
									pis_form_input_text(
										esc_html__( 'When on single posts, use this widget title', 'posts-in-sidebar' ),
										$this->get_field_id( 'title_custom_field' ),
										$this->get_field_name( 'title_custom_field' ),
										esc_attr( $instance['title_custom_field'] ),
										esc_html__( 'Posts', 'posts-in-sidebar' ),
										// translators: %s is a `%s`.
										sprintf( esc_html__( 'Use %s to display the name of the taxonomy.', 'posts-in-sidebar' ), '<code>%s</code>' )
									);
									?>

									<?php
									// ================= Post order by
									pis_form_select(
										esc_html__( 'Order posts by', 'posts-in-sidebar' ),
										$this->get_field_id( 'orderby_custom_fld' ),
										$this->get_field_name( 'orderby_custom_fld' ),
										pis_select_order_by_relevance(),
										$instance['orderby_custom_fld']
									);
									?>

									<?php
									// ================= Post order
									pis_form_select(
										esc_html__( 'The order will be', 'posts-in-sidebar' ),
										$this->get_field_id( 'order_custom_fld' ),
										$this->get_field_name( 'order_custom_fld' ),
										pis_select_order(),
										$instance['order_custom_fld']
									);
									?>

									<?php
									// ================= Number of posts to skip
									pis_form_input_text(
										esc_html__( 'Skip this number of posts', 'posts-in-sidebar' ),
										$this->get_field_id( 'offset_custom_fld' ),
										$this->get_field_name( 'offset_custom_fld' ),
										esc_attr( $instance['offset_custom_fld'] ),
										'5'
									);
									?>

									<?php
									// ================= Search post title
									pis_form_checkbox(
										esc_html__( 'Post title matching', 'posts-in-sidebar' ),
										$this->get_field_id( 'search_same_cf' ),
										$this->get_field_name( 'search_same_cf' ),
										$instance['search_same_cf'],
										esc_html__( 'Show posts that match the main post title. WordPress will show posts with the same custom field of the main post and matching in a search for the title of the main post.', 'posts-in-sidebar' )
									);
									?>

								</div>

							</div>

						</div>

						<h6 data-panel="get-posts-current-pf" class="pis-widget-title"><?php esc_html_e( 'Get posts from current post format', 'posts-in-sidebar' ); ?></h6>

						<div class="pis-container">

							<div class="pis-column-container">

								<div class="pis-column">

									<?php
									// ================= Get posts from same post format when on single post
									pis_form_checkbox(
										esc_html__( 'When on single posts, get posts with the current post format', 'posts-in-sidebar' ),
										$this->get_field_id( 'get_from_same_post_format' ),
										$this->get_field_name( 'get_from_same_post_format' ),
										$instance['get_from_same_post_format'],
										esc_html__( 'When activated, this function will get posts with the same post format of the post, ignoring other parameters like categories, tags, date, author, etc. If you don\'t want to ignore other parameters, activate the checkbox below, at the end of this panel.', 'posts-in-sidebar' )
									);
									?>

									<?php
									// ================= Post types from same post format
									pis_form_select(
										esc_html__( 'Post type', 'posts-in-sidebar' ),
										$this->get_field_id( 'post_type_same_post_format' ),
										$this->get_field_name( 'post_type_same_post_format' ),
										pis_select_post_types(),
										$instance['post_type_same_post_format'],
										esc_html__( 'Select a single post type.', 'posts-in-sidebar' )
									);
									?>

									<?php
									// ================= Multiple post types in same post format
									pis_form_input_text(
										esc_html__( 'Multiple post types', 'posts-in-sidebar' ),
										$this->get_field_id( 'ptm_spf' ),
										$this->get_field_name( 'ptm_spf' ),
										esc_attr( $instance['ptm_spf'] ),
										esc_html__( 'post, page, book, recipe', 'posts-in-sidebar' ),
										esc_html__( 'Enter post types slugs, comma separated. This option, if filled, overrides the option above.', 'posts-in-sidebar' )
									);
									?>

								</div>

								<div class="pis-column">

									<?php
									// ================= Posts quantity
									pis_form_input_text(
										esc_html__( 'When on single posts, get this number of posts', 'posts-in-sidebar' ),
										$this->get_field_id( 'number_same_post_format' ),
										$this->get_field_name( 'number_same_post_format' ),
										esc_attr( $instance['number_same_post_format'] ),
										'3',
										// translators: %s is -1.
										sprintf( esc_html__( 'The value %s shows all the posts.', 'posts-in-sidebar' ), '<code>-1</code>' )
									);
									?>

									<?php
									// ================= The custom widget title when on single posts
									pis_form_input_text(
										esc_html__( 'When on single posts, use this widget title', 'posts-in-sidebar' ),
										$this->get_field_id( 'title_same_post_format' ),
										$this->get_field_name( 'title_same_post_format' ),
										esc_attr( $instance['title_same_post_format'] ),
										// translators: %s is the name of a post format.
										esc_html__( 'Posts with %s post format', 'posts-in-sidebar' ),
										// translators: %s is a literal `%s`.
										sprintf( esc_html__( 'Use %s to display the name of the post format.', 'posts-in-sidebar' ), '<code>%s</code>' )
									);
									?>

									<?php
									// ================= Post order by
									pis_form_select(
										esc_html__( 'Order posts by', 'posts-in-sidebar' ),
										$this->get_field_id( 'orderby_same_post_format' ),
										$this->get_field_name( 'orderby_same_post_format' ),
										pis_select_order_by_relevance(),
										$instance['orderby_same_post_format']
									);
									?>

									<?php
									// ================= Post order
									pis_form_select(
										esc_html__( 'The order will be', 'posts-in-sidebar' ),
										$this->get_field_id( 'order_same_post_format' ),
										$this->get_field_name( 'order_same_post_format' ),
										pis_select_order(),
										$instance['order_same_post_format']
									);
									?>

									<?php
									// ================= Number of posts to skip
									pis_form_input_text(
										esc_html__( 'Skip this number of posts', 'posts-in-sidebar' ),
										$this->get_field_id( 'offset_same_post_format' ),
										$this->get_field_name( 'offset_same_post_format' ),
										esc_attr( $instance['offset_same_post_format'] ),
										'5'
									);
									?>

									<?php
									// ================= Search post title
									pis_form_checkbox(
										esc_html__( 'Post title matching', 'posts-in-sidebar' ),
										$this->get_field_id( 'search_same_post_format' ),
										$this->get_field_name( 'search_same_post_format' ),
										$instance['search_same_post_format'],
										esc_html__( 'Show posts that match the main post title. WordPress will show posts with the same post format of the main post and matching in a search for the title of the main post.', 'posts-in-sidebar' )
									);
									?>

								</div>

							</div>

						</div>

						<div class="pis-column-container">

							<?php
							// ================= Don't ignore other parameters
							pis_form_checkbox(
								esc_html__( 'Do not ignore other parameters', 'posts-in-sidebar' ),
								$this->get_field_id( 'dont_ignore_params' ),
								$this->get_field_name( 'dont_ignore_params' ),
								$instance['dont_ignore_params'],
								esc_html__( 'By default, when you activate one of the options above to change the query on single posts, the plugin will deactivate other parameters like categories, tags, date, author, and so on. To leave in action these parameters, activate this option.', 'posts-in-sidebar' ),
								'pis-boxed pis-boxed-light-blue'
							);
							?>

						</div>

					</div>

				</div>

				<!-- Changing query on archive pages -->
				<div class="pis-section pis-2col">

					<h5 data-panel="change-query-archive" class="pis-widget-title"><?php esc_html_e( 'Change the query when on archive page', 'posts-in-sidebar' ); ?></h5>

					<div class="pis-container">

						<p><em>
							<?php
							// translators: there is some code in placeholders.
							printf( esc_html__( 'In this section you can change some parameters of the query when on archive pages. %1$sYou can activate them all together.%2$s', 'posts-in-sidebar' ), '<strong>', '</strong>' );
							?>
						</em></p>

						<h6 data-panel="get-posts-category-archive-page" class="pis-widget-title"><?php esc_html_e( 'Get posts from current category archive page', 'posts-in-sidebar' ); ?></h6>

						<div class="pis-container">

							<div class="pis-column-container">

								<div class="pis-column">

									<?php
									// ================= Get posts from same category
									pis_form_checkbox(
										esc_html__( 'When on archive pages, get posts from the current category archive page', 'posts-in-sidebar' ),
										$this->get_field_id( 'get_from_cat_page' ),
										$this->get_field_name( 'get_from_cat_page' ),
										$instance['get_from_cat_page'],
										esc_html__( 'When activated, this function will get posts from the archive page of the current category, ignoring other parameters like tags, date, post formats, etc. If you don\'t want to ignore other parameters, activate the checkbox below, at the end of this panel.', 'posts-in-sidebar' )
									);
									?>

									<?php
									// ================= Post types from same category archive page
									pis_form_select(
										esc_html__( 'Post type', 'posts-in-sidebar' ),
										$this->get_field_id( 'post_type_cat_page' ),
										$this->get_field_name( 'post_type_cat_page' ),
										pis_select_post_types(),
										$instance['post_type_cat_page'],
										esc_html__( 'Select a single post type.', 'posts-in-sidebar' )
									);
									?>

									<?php
									// ================= Multiple post types in same category archive page
									pis_form_input_text(
										esc_html__( 'Multiple post types', 'posts-in-sidebar' ),
										$this->get_field_id( 'ptm_scp' ),
										$this->get_field_name( 'ptm_scp' ),
										esc_attr( $instance['ptm_scp'] ),
										esc_html__( 'post, page, book, recipe', 'posts-in-sidebar' ),
										esc_html__( 'Enter post types slugs, comma separated. This option, if filled, overrides the option above.', 'posts-in-sidebar' )
									);
									?>

								</div>

								<div class="pis-column">

									<?php
									// ================= Posts quantity
									pis_form_input_text(
										esc_html__( 'When on archive pages, get this number of posts', 'posts-in-sidebar' ),
										$this->get_field_id( 'number_cat_page' ),
										$this->get_field_name( 'number_cat_page' ),
										esc_attr( $instance['number_cat_page'] ),
										'3',
										// translators: %s is -1.
										sprintf( esc_html__( 'The value %s shows all the posts.', 'posts-in-sidebar' ), '<code>-1</code>' )
									);
									?>

									<?php
									// ================= Offset
									pis_form_input_text(
										esc_html__( 'Skip this number of posts', 'posts-in-sidebar' ),
										$this->get_field_id( 'offset_cat_page' ),
										$this->get_field_name( 'offset_cat_page' ),
										esc_attr( $instance['offset_cat_page'] ),
										'10',
										// translators: %s is -1.
										sprintf( esc_html__( 'If you entered %s in the previous field, this option will be ignored.', 'posts-in-sidebar' ), '<code>-1</code>' )
									);
									?>

									<?php
									// ================= The custom widget title when on single posts
									pis_form_input_text(
										esc_html__( 'When on archive pages, use this widget title', 'posts-in-sidebar' ),
										$this->get_field_id( 'title_cat_page' ),
										$this->get_field_name( 'title_cat_page' ),
										esc_attr( $instance['title_cat_page'] ),
										// translators: %s is a placeholder for a taxonomy.
										esc_html__( 'Posts under %s', 'posts-in-sidebar' ),
										// translators: there is some code in placeholders.
										sprintf( esc_html__( 'Use %s to display the name of the category.', 'posts-in-sidebar' ), '<code>%s</code>' )
									);
									?>

									<?php
									// ================= Post order by
									pis_form_select(
										esc_html__( 'Order posts by', 'posts-in-sidebar' ),
										$this->get_field_id( 'orderby_cat_page' ),
										$this->get_field_name( 'orderby_cat_page' ),
										pis_select_order_by(),
										$instance['orderby_cat_page']
									);
									?>

									<?php
									// ================= Post order
									pis_form_select(
										esc_html__( 'The order will be', 'posts-in-sidebar' ),
										$this->get_field_id( 'order_cat_page' ),
										$this->get_field_name( 'order_cat_page' ),
										pis_select_order(),
										$instance['order_cat_page']
									);
									?>

								</div>

							</div>

						</div>

						<h6 data-panel="get-posts-tag-archive-page" class="pis-widget-title"><?php esc_html_e( 'Get posts from current tag archive page', 'posts-in-sidebar' ); ?></h6>

						<div class="pis-container">

							<div class="pis-column-container">

								<div class="pis-column">

									<?php
									// ================= Get posts from same tag
									pis_form_checkbox(
										esc_html__( 'When on archive pages, get posts from the current tag archive page', 'posts-in-sidebar' ),
										$this->get_field_id( 'get_from_tag_page' ),
										$this->get_field_name( 'get_from_tag_page' ),
										$instance['get_from_tag_page'],
										esc_html__( 'When activated, this function will get posts from the archive page of the current tag, ignoring other parameters like categories, date, post formats, etc. If you don\'t want to ignore other parameters, activate the checkbox below, at the end of this panel.', 'posts-in-sidebar' )
									);
									?>

									<?php
									// ================= Post types from same tag archive page
									pis_form_select(
										esc_html__( 'Post type', 'posts-in-sidebar' ),
										$this->get_field_id( 'post_type_tag_page' ),
										$this->get_field_name( 'post_type_tag_page' ),
										pis_select_post_types(),
										$instance['post_type_tag_page'],
										esc_html__( 'Select a single post type.', 'posts-in-sidebar' )
									);
									?>

									<?php
									// ================= Multiple post types in same tag archive page
									pis_form_input_text(
										esc_html__( 'Multiple post types', 'posts-in-sidebar' ),
										$this->get_field_id( 'ptm_stp' ),
										$this->get_field_name( 'ptm_stp' ),
										esc_attr( $instance['ptm_stp'] ),
										esc_html__( 'post, page, book, recipe', 'posts-in-sidebar' ),
										esc_html__( 'Enter post types slugs, comma separated. This option, if filled, overrides the option above.', 'posts-in-sidebar' )
									);
									?>

								</div>

								<div class="pis-column">

									<?php
									// ================= Posts quantity
									pis_form_input_text(
										esc_html__( 'When on archive pages, get this number of posts', 'posts-in-sidebar' ),
										$this->get_field_id( 'number_tag_page' ),
										$this->get_field_name( 'number_tag_page' ),
										esc_attr( $instance['number_tag_page'] ),
										'3',
										// translators: %s is -1.
										sprintf( esc_html__( 'The value %s shows all the posts.', 'posts-in-sidebar' ), '<code>-1</code>' )
									);
									?>

									<?php
									// ================= Offset
									pis_form_input_text(
										esc_html__( 'Skip this number of posts', 'posts-in-sidebar' ),
										$this->get_field_id( 'offset_tag_page' ),
										$this->get_field_name( 'offset_tag_page' ),
										esc_attr( $instance['offset_tag_page'] ),
										'10',
										// translators: %s is -1.
										sprintf( esc_html__( 'If you entered %s in the previous field, this option will be ignored.', 'posts-in-sidebar' ), '<code>-1</code>' )
									);
									?>

									<?php
									// ================= The custom widget title when on single posts
									pis_form_input_text(
										esc_html__( 'When on archive pages, use this widget title', 'posts-in-sidebar' ),
										$this->get_field_id( 'title_tag_page' ),
										$this->get_field_name( 'title_tag_page' ),
										esc_attr( $instance['title_tag_page'] ),
										// translators: %s is the name of the tag.
										esc_html__( 'Posts tagged with %s', 'posts-in-sidebar' ),
										// translators: there is some code in placeholders.
										sprintf( esc_html__( 'Use %s to display the name of the tag.', 'posts-in-sidebar' ), '<code>%s</code>' )
									);
									?>

									<?php
									// ================= Post order by
									pis_form_select(
										esc_html__( 'Order posts by', 'posts-in-sidebar' ),
										$this->get_field_id( 'orderby_tag_page' ),
										$this->get_field_name( 'orderby_tag_page' ),
										pis_select_order_by(),
										$instance['orderby_tag_page']
									);
									?>

									<?php
									// ================= Post order
									pis_form_select(
										esc_html__( 'The order will be', 'posts-in-sidebar' ),
										$this->get_field_id( 'order_tag_page' ),
										$this->get_field_name( 'order_tag_page' ),
										pis_select_order(),
										$instance['order_tag_page']
									);
									?>

								</div>

							</div>

						</div>

						<h6 data-panel="get-posts-author-archive-page" class="pis-widget-title"><?php esc_html_e( 'Get posts from current author archive page', 'posts-in-sidebar' ); ?></h6>

						<div class="pis-container">

							<div class="pis-column-container">

								<div class="pis-column">

									<?php
									// ================= Get posts from same author
									pis_form_checkbox(
										esc_html__( 'When on archive pages, get posts from the current author archive page', 'posts-in-sidebar' ),
										$this->get_field_id( 'get_from_author_page' ),
										$this->get_field_name( 'get_from_author_page' ),
										$instance['get_from_author_page'],
										esc_html__( 'When activated, this function will get posts from the archive page of the current author, ignoring other parameters like categories, tags, date, post formats, etc. If you don\'t want to ignore other parameters, activate the checkbox below, at the end of this panel.', 'posts-in-sidebar' )
									);
									?>

									<?php
									// ================= Post types from same author archive page
									pis_form_select(
										esc_html__( 'Post type', 'posts-in-sidebar' ),
										$this->get_field_id( 'post_type_author_page' ),
										$this->get_field_name( 'post_type_author_page' ),
										pis_select_post_types(),
										$instance['post_type_author_page'],
										esc_html__( 'Select a single post type.', 'posts-in-sidebar' )
									);
									?>

									<?php
									// ================= Multiple post types in same author archive page
									pis_form_input_text(
										esc_html__( 'Multiple post types', 'posts-in-sidebar' ),
										$this->get_field_id( 'ptm_sap' ),
										$this->get_field_name( 'ptm_sap' ),
										esc_attr( $instance['ptm_sap'] ),
										esc_html__( 'post, page, book, recipe', 'posts-in-sidebar' ),
										esc_html__( 'Enter post types slugs, comma separated. This option, if filled, overrides the option above.', 'posts-in-sidebar' )
									);
									?>

								</div>

								<div class="pis-column">

									<?php
									// ================= Posts quantity
									pis_form_input_text(
										esc_html__( 'When on archive pages, get this number of posts', 'posts-in-sidebar' ),
										$this->get_field_id( 'number_author_page' ),
										$this->get_field_name( 'number_author_page' ),
										esc_attr( $instance['number_author_page'] ),
										'3',
										// translators: %s is -1.
										sprintf( esc_html__( 'The value %s shows all the posts.', 'posts-in-sidebar' ), '<code>-1</code>' )
									);
									?>

									<?php
									// ================= Offset
									pis_form_input_text(
										esc_html__( 'Skip this number of posts', 'posts-in-sidebar' ),
										$this->get_field_id( 'offset_author_page' ),
										$this->get_field_name( 'offset_author_page' ),
										esc_attr( $instance['offset_author_page'] ),
										'10',
										// translators: %s is -1.
										sprintf( esc_html__( 'If you entered %s in the previous field, this option will be ignored.', 'posts-in-sidebar' ), '<code>-1</code>' )
									);
									?>

									<?php
									// ================= The custom widget title when on single posts
									pis_form_input_text(
										esc_html__( 'When on archive pages, use this widget title', 'posts-in-sidebar' ),
										$this->get_field_id( 'title_author_page' ),
										$this->get_field_name( 'title_author_page' ),
										esc_attr( $instance['title_author_page'] ),
										// translators: %s is the name of the author.
										esc_html__( 'Posts by %s', 'posts-in-sidebar' ),
										// translators: there is some code in placeholders.
										sprintf( esc_html__( 'Use %s to display the name of the author.', 'posts-in-sidebar' ), '<code>%s</code>' )
									);
									?>

									<?php
									// ================= Post order by
									pis_form_select(
										esc_html__( 'Order posts by', 'posts-in-sidebar' ),
										$this->get_field_id( 'orderby_author_page' ),
										$this->get_field_name( 'orderby_author_page' ),
										pis_select_order_by(),
										$instance['orderby_author_page']
									);
									?>

									<?php
									// ================= Post order
									pis_form_select(
										esc_html__( 'The order will be', 'posts-in-sidebar' ),
										$this->get_field_id( 'order_author_page' ),
										$this->get_field_name( 'order_author_page' ),
										pis_select_order(),
										$instance['order_author_page']
									);
									?>

								</div>

							</div>

						</div>

						<h6 data-panel="get-posts-post-format-archive-page" class="pis-widget-title"><?php esc_html_e( 'Get posts from current post format archive page', 'posts-in-sidebar' ); ?></h6>

						<div class="pis-container">

							<div class="pis-column-container">

								<div class="pis-column">

									<?php
									// ================= Get posts from same post format
									pis_form_checkbox(
										esc_html__( 'When on archive pages, get posts from the current post format archive page', 'posts-in-sidebar' ),
										$this->get_field_id( 'get_from_post_format_page' ),
										$this->get_field_name( 'get_from_post_format_page' ),
										$instance['get_from_post_format_page'],
										esc_html__( 'When activated, this function will get posts from the archive page of the current post format, ignoring other parameters like categories, tags, date, author, etc. If you don\'t want to ignore other parameters, activate the checkbox below, at the end of this panel.', 'posts-in-sidebar' )
									);
									?>

									<?php
									// ================= Post types from same post format archive page
									pis_form_select(
										esc_html__( 'Post type', 'posts-in-sidebar' ),
										$this->get_field_id( 'post_type_post_format_page' ),
										$this->get_field_name( 'post_type_post_format_page' ),
										pis_select_post_types(),
										$instance['post_type_post_format_page'],
										esc_html__( 'Select a single post type.', 'posts-in-sidebar' )
									);
									?>

									<?php
									// ================= Multiple post types in same post format archive page
									pis_form_input_text(
										esc_html__( 'Multiple post types', 'posts-in-sidebar' ),
										$this->get_field_id( 'ptm_spfp' ),
										$this->get_field_name( 'ptm_spfp' ),
										esc_attr( $instance['ptm_spfp'] ),
										esc_html__( 'post, page, book, recipe', 'posts-in-sidebar' ),
										esc_html__( 'Enter post types slugs, comma separated. This option, if filled, overrides the option above.', 'posts-in-sidebar' )
									);
									?>

								</div>

								<div class="pis-column">

									<?php
									// ================= Posts quantity
									pis_form_input_text(
										esc_html__( 'When on archive pages, get this number of posts', 'posts-in-sidebar' ),
										$this->get_field_id( 'number_post_format_page' ),
										$this->get_field_name( 'number_post_format_page' ),
										esc_attr( $instance['number_post_format_page'] ),
										'3',
										// translators: %s is -1.
										sprintf( esc_html__( 'The value %s shows all the posts.', 'posts-in-sidebar' ), '<code>-1</code>' )
									);
									?>

									<?php
									// ================= Offset
									pis_form_input_text(
										esc_html__( 'Skip this number of posts', 'posts-in-sidebar' ),
										$this->get_field_id( 'offset_post_format_page' ),
										$this->get_field_name( 'offset_post_format_page' ),
										esc_attr( $instance['offset_post_format_page'] ),
										'10',
										// translators: %s is -1.
										sprintf( esc_html__( 'If you entered %s in the previous field, this option will be ignored.', 'posts-in-sidebar' ), '<code>-1</code>' )
									);
									?>

									<?php
									// ================= The custom widget title when on archive page
									pis_form_input_text(
										esc_html__( 'When on archive pages, use this widget title', 'posts-in-sidebar' ),
										$this->get_field_id( 'title_post_format_page' ),
										$this->get_field_name( 'title_post_format_page' ),
										esc_attr( $instance['title_post_format_page'] ),
										// translators: %s is the name of a post format.
										esc_html__( 'Posts with %s post format', 'posts-in-sidebar' ),
										// translators: %s is a literal `%s`.
										sprintf( esc_html__( 'Use %s to display the name of the post format.', 'posts-in-sidebar' ), '<code>%s</code>' )
									);
									?>

									<?php
									// ================= Post order by
									pis_form_select(
										esc_html__( 'Order posts by', 'posts-in-sidebar' ),
										$this->get_field_id( 'orderby_post_format_page' ),
										$this->get_field_name( 'orderby_post_format_page' ),
										pis_select_order_by(),
										$instance['orderby_post_format_page']
									);
									?>

									<?php
									// ================= Post order
									pis_form_select(
										esc_html__( 'The order will be', 'posts-in-sidebar' ),
										$this->get_field_id( 'order_post_format_page' ),
										$this->get_field_name( 'order_post_format_page' ),
										pis_select_order(),
										$instance['order_post_format_page']
									);
									?>

								</div>

							</div>

						</div>

						<div class="pis-column-container">

							<?php
							// ================= Don't ignore other parameters
							pis_form_checkbox(
								esc_html__( 'Do not ignore other parameters', 'posts-in-sidebar' ),
								$this->get_field_id( 'dont_ignore_params_page' ),
								$this->get_field_name( 'dont_ignore_params_page' ),
								$instance['dont_ignore_params_page'],
								esc_html__( 'By default, when you activate one of the options above to change the query on single posts, the plugin will deactivate other parameters like categories, tags, date, author, and so on. To leave in action these parameters, activate this option.', 'posts-in-sidebar' ),
								'pis-boxed pis-boxed-light-blue'
							);
							?>

						</div>

					</div>

				</div>

				<!-- Custom taxonomy query -->
				<div class="pis-section pis-2col">

					<h5 data-panel="custom-taxonomy-query" class="pis-widget-title"><?php esc_html_e( 'Custom taxonomy query', 'posts-in-sidebar' ); ?></h5>

					<div class="pis-container">

						<p><em><?php esc_html_e( 'This section lets you retrieve posts from any taxonomy (category, tags, and custom taxonomies). If you want to use only one taxonomy, use the "Taxonomy A1" field. If you have to put in relation two taxonomies (e.g., display posts that are in the "quotes" category but not in the "wisdom" tag), then use also the "Taxonomy B1" field. If you have to put in relation more taxonomies, start using also the "A2" and "B2" fields (e.g., display posts that are in the "quotes" category [A1] OR both have the "Quote" post format [B1] AND are in the "wisdom" category [B2]).', 'posts-in-sidebar' ); ?></em></p>

						<p><em>
							<?php
							// translators: there is some code in placeholders.
							printf( esc_html__( 'If a field requires one or more IDs, install %1$sthis plugin%2$s to easily find the IDs.', 'posts-in-sidebar' ), '<a href="https://wordpress.org/plugins/reveal-ids-for-wp-admin-25/" target="_blank">', '</a>' );
							?>
						</em></p>

						<hr />

						<div class="pis-column-container">

							<div class="pis-column centered">
								<?php
								// ================= Taxonomy relation between aa and bb
								pis_form_select(
									esc_html__( 'Relation between Column A and Column B', 'posts-in-sidebar' ),
									$this->get_field_id( 'relation' ),
									$this->get_field_name( 'relation' ),
									pis_select_relation(),
									$instance['relation'],
									esc_html__( 'The logical relationship between each inner taxonomy array when there is more than one. Do not use with a single inner taxonomy array.', 'posts-in-sidebar' )
								);
								?>

							</div>

						</div>

						<div class="pis-column-container">

							<div class="pis-column">

								<h6 class="pis-title-center"><?php esc_html_e( 'Column A', 'posts-in-sidebar' ); ?></h6>

								<?php
								// ================= Taxonomy aa
								pis_form_input_text(
									sprintf( '%1$s' . esc_html__( 'Taxonomy A1', 'posts-in-sidebar' ) . '%2$s', '<strong>', '</strong>' ),
									$this->get_field_id( 'taxonomy_aa' ),
									$this->get_field_name( 'taxonomy_aa' ),
									esc_attr( $instance['taxonomy_aa'] ),
									esc_html__( 'category', 'posts-in-sidebar' ),
									esc_html__( 'Enter the slug of the taxonomy.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Field aa
								pis_form_select(
									esc_html__( 'Field', 'posts-in-sidebar' ),
									$this->get_field_id( 'field_aa' ),
									$this->get_field_name( 'field_aa' ),
									pis_select_field(),
									$instance['field_aa'],
									esc_html__( 'Select taxonomy term by this field.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Terms aa
								pis_form_input_text(
									esc_html__( 'Terms', 'posts-in-sidebar' ),
									$this->get_field_id( 'terms_aa' ),
									$this->get_field_name( 'terms_aa' ),
									esc_attr( $instance['terms_aa'] ),
									esc_html__( 'gnu-linux,kde', 'posts-in-sidebar' ),
									esc_html__( 'Enter terms, comma separated.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Operator aa
								pis_form_select(
									esc_html__( 'Operator', 'posts-in-sidebar' ),
									$this->get_field_id( 'operator_aa' ),
									$this->get_field_name( 'operator_aa' ),
									pis_select_operator(),
									$instance['operator_aa'],
									esc_html__( 'Operator to test for terms.', 'posts-in-sidebar' )
								);
								?>

								<hr />

								<?php
								// ================= Taxonomy relation between aa and ab
								pis_form_select(
									esc_html__( 'Relation between A1 and A2 taxonomies', 'posts-in-sidebar' ),
									$this->get_field_id( 'relation_a' ),
									$this->get_field_name( 'relation_a' ),
									pis_select_relation(),
									$instance['relation_a']
								);
								?>

								<hr />

								<?php
								// ================= Taxonomy ab
								pis_form_input_text(
									sprintf( '%1$s' . esc_html__( 'Taxonomy A2', 'posts-in-sidebar' ) . '%2$s', '<strong>', '</strong>' ),
									$this->get_field_id( 'taxonomy_ab' ),
									$this->get_field_name( 'taxonomy_ab' ),
									esc_attr( $instance['taxonomy_ab'] ),
									esc_html__( 'movie-genre', 'posts-in-sidebar' ),
									esc_html__( 'Enter the slug of the taxonomy.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Field ab
								pis_form_select(
									esc_html__( 'Field', 'posts-in-sidebar' ),
									$this->get_field_id( 'field_ab' ),
									$this->get_field_name( 'field_ab' ),
									pis_select_field(),
									$instance['field_ab'],
									esc_html__( 'Select taxonomy term by this field.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Terms ab
								pis_form_input_text(
									esc_html__( 'Terms', 'posts-in-sidebar' ),
									$this->get_field_id( 'terms_ab' ),
									$this->get_field_name( 'terms_ab' ),
									esc_attr( $instance['terms_ab'] ),
									esc_html__( 'action,sci-fi', 'posts-in-sidebar' ),
									esc_html__( 'Enter terms, comma separated.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Operator ab
								pis_form_select(
									esc_html__( 'Operator', 'posts-in-sidebar' ),
									$this->get_field_id( 'operator_ab' ),
									$this->get_field_name( 'operator_ab' ),
									pis_select_operator(),
									$instance['operator_ab'],
									esc_html__( 'Operator to test for terms.', 'posts-in-sidebar' )
								);
								?>

							</div>

							<div class="pis-column">

								<h6 class="pis-title-center"><?php esc_html_e( 'Column B', 'posts-in-sidebar' ); ?></h6>

								<?php
								// ================= Taxonomy ba
								pis_form_input_text(
									sprintf( '%1$s' . esc_html__( 'Taxonomy B1', 'posts-in-sidebar' ) . '%2$s', '<strong>', '</strong>' ),
									$this->get_field_id( 'taxonomy_ba' ),
									$this->get_field_name( 'taxonomy_ba' ),
									esc_attr( $instance['taxonomy_ba'] ),
									esc_html__( 'post_tag', 'posts-in-sidebar' ),
									esc_html__( 'Enter the slug of the taxonomy.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Field ba
								pis_form_select(
									esc_html__( 'Field', 'posts-in-sidebar' ),
									$this->get_field_id( 'field_ba' ),
									$this->get_field_name( 'field_ba' ),
									pis_select_field(),
									$instance['field_ba'],
									esc_html__( 'Select taxonomy term by this field.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Terms ba
								pis_form_input_text(
									esc_html__( 'Terms', 'posts-in-sidebar' ),
									$this->get_field_id( 'terms_ba' ),
									$this->get_field_name( 'terms_ba' ),
									esc_attr( $instance['terms_ba'] ),
									esc_html__( 'system,apache', 'posts-in-sidebar' ),
									esc_html__( 'Enter terms, comma separated.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Operator ba
								pis_form_select(
									esc_html__( 'Operator', 'posts-in-sidebar' ),
									$this->get_field_id( 'operator_ba' ),
									$this->get_field_name( 'operator_ba' ),
									pis_select_operator(),
									$instance['operator_ba'],
									esc_html__( 'Operator to test for terms.', 'posts-in-sidebar' )
								);
								?>

								<hr />

								<?php
								// ================= Taxonomy relation between ba and bb
								pis_form_select(
									esc_html__( 'Relation between B1 and B2 taxonomies', 'posts-in-sidebar' ),
									$this->get_field_id( 'relation_b' ),
									$this->get_field_name( 'relation_b' ),
									pis_select_relation(),
									$instance['relation_b']
								);
								?>

								<hr />

								<?php
								// ================= Taxonomy bb
								pis_form_input_text(
									sprintf( '%1$s' . esc_html__( 'Taxonomy B2', 'posts-in-sidebar' ) . '%2$s', '<strong>', '</strong>' ),
									$this->get_field_id( 'taxonomy_bb' ),
									$this->get_field_name( 'taxonomy_bb' ),
									esc_attr( $instance['taxonomy_bb'] ),
									esc_html__( 'post_format', 'posts-in-sidebar' ),
									esc_html__( 'Enter the slug of the taxonomy.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Field bb
								pis_form_select(
									esc_html__( 'Field', 'posts-in-sidebar' ),
									$this->get_field_id( 'field_bb' ),
									$this->get_field_name( 'field_bb' ),
									pis_select_field(),
									$instance['field_bb'],
									esc_html__( 'Select taxonomy term by this field.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Terms bb
								pis_form_input_text(
									esc_html__( 'Terms', 'posts-in-sidebar' ),
									$this->get_field_id( 'terms_bb' ),
									$this->get_field_name( 'terms_bb' ),
									esc_attr( $instance['terms_bb'] ),
									esc_html__( 'post-format-quote', 'posts-in-sidebar' ),
									esc_html__( 'Enter terms, comma separated.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Operator bb
								pis_form_select(
									esc_html__( 'Operator', 'posts-in-sidebar' ),
									$this->get_field_id( 'operator_bb' ),
									$this->get_field_name( 'operator_bb' ),
									pis_select_operator(),
									$instance['operator_bb'],
									esc_html__( 'Operator to test for terms.', 'posts-in-sidebar' )
								);
								?>

							</div>

						</div>

					</div>

				</div>

				<!-- Date query -->
				<div class="pis-section pis-2col">

					<h5 data-panel="date-query" class="pis-widget-title"><?php esc_html_e( 'Date query', 'posts-in-sidebar' ); ?></h5>

					<div class="pis-container">

						<p><em><?php esc_html_e( 'In this section you can define the period within posts have been published. You can get posts published in a certain period or after/before a date or in a dynamic period (based on current date).', 'posts-in-sidebar' ); ?></em></p>

						<div class="pis-column-container">

							<h5 class="pis-title-center"><?php esc_html_e( 'Get posts published in a certain period', 'posts-in-sidebar' ); ?></h5>

							<p><em><?php esc_html_e( 'Define the period within posts are published. For example, you can pick up the posts published on 15 January, 2017 at 10.', 'posts-in-sidebar' ); ?></em></p>

							<div class="pis-column">

								<?php
								pis_form_input_text(
									esc_html__( 'Year', 'posts-in-sidebar' ),
									$this->get_field_id( 'date_year' ),
									$this->get_field_name( 'date_year' ),
									esc_attr( $instance['date_year'] ),
									'2015',
									esc_html__( '4 digits year (e.g. 2015).', 'posts-in-sidebar' )
								);
								?>

								<?php
								pis_form_input_text(
									esc_html__( 'Month', 'posts-in-sidebar' ),
									$this->get_field_id( 'date_month' ),
									$this->get_field_name( 'date_month' ),
									esc_attr( $instance['date_month'] ),
									'06',
									esc_html__( 'Month number (from 1 to 12).', 'posts-in-sidebar' )
								);
								?>

								<?php
								pis_form_input_text(
									esc_html__( 'Week', 'posts-in-sidebar' ),
									$this->get_field_id( 'date_week' ),
									$this->get_field_name( 'date_week' ),
									esc_attr( $instance['date_week'] ),
									'32',
									esc_html__( 'Week of the year (from 0 to 53).', 'posts-in-sidebar' )
								);
								?>

								<?php
								pis_form_input_text(
									esc_html__( 'Day', 'posts-in-sidebar' ),
									$this->get_field_id( 'date_day' ),
									$this->get_field_name( 'date_day' ),
									esc_attr( $instance['date_day'] ),
									'12',
									esc_html__( 'Day of the month (from 1 to 31).', 'posts-in-sidebar' )
								);
								?>

							</div>

							<div class="pis-column">

								<?php
								pis_form_input_text(
									esc_html__( 'Hour', 'posts-in-sidebar' ),
									$this->get_field_id( 'date_hour' ),
									$this->get_field_name( 'date_hour' ),
									esc_attr( $instance['date_hour'] ),
									'09',
									esc_html__( 'Hour (from 0 to 23).', 'posts-in-sidebar' )
								);
								?>

								<?php
								pis_form_input_text(
									esc_html__( 'Minute', 'posts-in-sidebar' ),
									$this->get_field_id( 'date_minute' ),
									$this->get_field_name( 'date_minute' ),
									esc_attr( $instance['date_minute'] ),
									'24',
									esc_html__( 'Minute (from 0 to 59).', 'posts-in-sidebar' )
								);
								?>

								<?php
								pis_form_input_text(
									esc_html__( 'Second', 'posts-in-sidebar' ),
									$this->get_field_id( 'date_second' ),
									$this->get_field_name( 'date_second' ),
									esc_attr( $instance['date_second'] ),
									'32',
									esc_html__( 'Second (from 0 to 59).', 'posts-in-sidebar' )
								);
								?>

							</div>

						</div>

						<hr>

						<div class="pis-column-container">

							<h5 class="pis-title-center"><?php esc_html_e( 'Get posts published after/before a date', 'posts-in-sidebar' ); ?></h5>

							<p><em><?php esc_html_e( 'Here you can get posts published after/before a certain date. You can also use the two options together, for example, to get posts published between 2017-01-15 and 2017-01-31.', 'posts-in-sidebar' ); ?></em></p>

							<div class="pis-column">

								<h6><?php esc_html_e( 'Get posts published after this date', 'posts-in-sidebar' ); ?></h6>

								<p><em><?php esc_html_e( 'Get posts published after a certain date.', 'posts-in-sidebar' ); ?></em></p>

								<?php
								pis_form_input_text(
									esc_html__( 'Year', 'posts-in-sidebar' ),
									$this->get_field_id( 'date_after_year' ),
									$this->get_field_name( 'date_after_year' ),
									esc_attr( $instance['date_after_year'] ),
									'2011',
									esc_html__( 'Accepts any four-digit year.', 'posts-in-sidebar' )
								);
								?>

								<?php
								pis_form_input_text(
									esc_html__( 'Month', 'posts-in-sidebar' ),
									$this->get_field_id( 'date_after_month' ),
									$this->get_field_name( 'date_after_month' ),
									esc_attr( $instance['date_after_month'] ),
									'10',
									esc_html__( 'The month of the year. Accepts numbers 1-12.', 'posts-in-sidebar' )
								);
								?>

								<?php
								pis_form_input_text(
									esc_html__( 'Day', 'posts-in-sidebar' ),
									$this->get_field_id( 'date_after_day' ),
									$this->get_field_name( 'date_after_day' ),
									esc_attr( $instance['date_after_day'] ),
									'10',
									esc_html__( 'The day of the month. Accepts numbers 1-31.', 'posts-in-sidebar' )
								);
								?>

							</div>

							<div class="pis-column">

								<h6><?php esc_html_e( 'Get posts published before this date', 'posts-in-sidebar' ); ?></h6>

								<p><em><?php esc_html_e( 'Get posts published before a certain date.', 'posts-in-sidebar' ); ?></em></p>

								<?php
								pis_form_input_text(
									esc_html__( 'Year', 'posts-in-sidebar' ),
									$this->get_field_id( 'date_before_year' ),
									$this->get_field_name( 'date_before_year' ),
									esc_attr( $instance['date_before_year'] ),
									'2011',
									esc_html__( 'Accepts any four-digit year.', 'posts-in-sidebar' )
								);
								?>

								<?php
								pis_form_input_text(
									esc_html__( 'Month', 'posts-in-sidebar' ),
									$this->get_field_id( 'date_before_month' ),
									$this->get_field_name( 'date_before_month' ),
									esc_attr( $instance['date_before_month'] ),
									'10',
									esc_html__( 'The month of the year. Accepts numbers 1-12.', 'posts-in-sidebar' )
								);
								?>

								<?php
								pis_form_input_text(
									esc_html__( 'Day', 'posts-in-sidebar' ),
									$this->get_field_id( 'date_before_day' ),
									$this->get_field_name( 'date_before_day' ),
									esc_attr( $instance['date_before_day'] ),
									'10',
									esc_html__( 'The day of the month. Accepts numbers 1-31.', 'posts-in-sidebar' )
								);
								?>

							</div>

						</div>

						<div class="pis-column-container">

							<h5><?php esc_html_e( 'Other options for after/before', 'posts-in-sidebar' ); ?></h5>

							<div class="pis-column">

								<?php
								pis_form_checkbox(
									esc_html__( 'Inclusive', 'posts-in-sidebar' ),
									$this->get_field_id( 'date_inclusive' ),
									$this->get_field_name( 'date_inclusive' ),
									$instance['date_inclusive'],
									esc_html__( 'For after/before, whether exact value should be matched or not', 'posts-in-sidebar' )
								);
								?>

							</div>

							<div class="pis-column">

								<?php
								pis_form_select(
									esc_html__( 'Column', 'posts-in-sidebar' ),
									$this->get_field_id( 'date_column' ),
									$this->get_field_name( 'date_column' ),
									pis_select_date_column(),
									$instance['date_column'],
									esc_html__( 'Column to query against.', 'posts-in-sidebar' )
								);
								?>

							</div>

						</div>

						<hr>

						<div class="pis-column-container">

							<h5 class="pis-title-center"><?php esc_html_e( 'Dynamic date query', 'posts-in-sidebar' ); ?></h5>

							<p><em><?php esc_html_e( 'Define the amount of time from now within the posts have been published. For example, you can get posts published 1 month ago or 2 years ago, and so on. An expression like "1 month ago" means: get posts that have been published in the last month, and not only published exactly one month ago.', 'posts-in-sidebar' ); ?></em></p>

							<p><em><?php esc_html_e( 'Also, please note that these options will override the corresponding options under "Get posts published after/before a date".', 'posts-in-sidebar' ); ?></em></p>

							<div class="pis-column">

								<h6><?php esc_html_e( 'Get posts published after this amount of time', 'posts-in-sidebar' ); ?></h6>

								<?php
								// ================= The amount of time for the dynamic date
								pis_form_input_text(
									esc_html__( 'Amount of time', 'posts-in-sidebar' ),
									$this->get_field_id( 'date_after_dyn_num' ),
									$this->get_field_name( 'date_after_dyn_num' ),
									esc_attr( $instance['date_after_dyn_num'] ),
									'1'
								);
								?>

								<?php
								// ================= Type of date
								pis_form_select(
									esc_html__( 'Type of date', 'posts-in-sidebar' ),
									$this->get_field_id( 'date_after_dyn_date' ),
									$this->get_field_name( 'date_after_dyn_date' ),
									pis_select_date_type(),
									$instance['date_after_dyn_date']
								);
								?>

							</div>

							<div class="pis-column">

								<h6><?php esc_html_e( 'Get posts published before this amount of time', 'posts-in-sidebar' ); ?></h6>

								<?php
								// ================= The amount of time for the dynamic date
								pis_form_input_text(
									esc_html__( 'Amount of time', 'posts-in-sidebar' ),
									$this->get_field_id( 'date_before_dyn_num' ),
									$this->get_field_name( 'date_before_dyn_num' ),
									esc_attr( $instance['date_before_dyn_num'] ),
									'1'
								);
								?>

								<?php
								// ================= Type of date
								pis_form_select(
									esc_html__( 'Type of date', 'posts-in-sidebar' ),
									$this->get_field_id( 'date_before_dyn_date' ),
									$this->get_field_name( 'date_before_dyn_date' ),
									pis_select_date_type(),
									$instance['date_before_dyn_date']
								);
								?>

							</div>

						</div>

					</div>

				</div>

				<!-- Meta query -->
				<div class="pis-section pis-2col">

					<h5 data-panel="meta-query" class="pis-widget-title"><?php esc_html_e( 'Custom fields query', 'posts-in-sidebar' ); ?></h5>

					<div class="pis-container">

						<p><em><?php esc_html_e( 'This section lets you retrieve posts from any custom field. If you want to use only one custom field, use the "Custom field key A1" field. If you have to put in relation two custom fields (e.g., display posts that have the meta key "color" and value "red" but meta key "price" with values between 10 and 20), then use also the "Custom field key B1" field. If you have to put in relation more custom fields, start using also the "A2" and "B2" fields.', 'posts-in-sidebar' ); ?></em></p>

						<div class="pis-column-container">

							<div class="pis-column centered">

								<?php
								// ================= Meta relation between aa and bb
								pis_form_select(
									esc_html__( 'Relation between Column A and Column B', 'posts-in-sidebar' ),
									$this->get_field_id( 'mq_relation' ),
									$this->get_field_name( 'mq_relation' ),
									pis_select_relation(),
									$instance['mq_relation'],
									esc_html__( 'The logical relationship between each inner meta_query array when there is more than one.', 'posts-in-sidebar' )
								);
								?>

							</div>

						</div>

						<div class="pis-column-container">

							<div class="pis-column">

								<h6 class="pis-title-center"><?php esc_html_e( 'Column A', 'posts-in-sidebar' ); ?></h6>

								<?php
								// ================= Custom field key aa
								pis_form_input_text(
									sprintf( '%1$s' . esc_html__( 'Custom field key A1', 'posts-in-sidebar' ) . '%2$s', '<strong>', '</strong>' ),
									$this->get_field_id( 'mq_key_aa' ),
									$this->get_field_name( 'mq_key_aa' ),
									esc_attr( $instance['mq_key_aa'] ),
									esc_html__( 'color', 'posts-in-sidebar' ),
									esc_html__( 'Enter the custom field key.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Custom field value aa
								pis_form_input_text(
									esc_html__( 'Custom field value', 'posts-in-sidebar' ),
									$this->get_field_id( 'mq_value_aa' ),
									$this->get_field_name( 'mq_value_aa' ),
									esc_attr( $instance['mq_value_aa'] ),
									esc_html__( 'blue, orange, red', 'posts-in-sidebar' ),
									// translators: the placeholder contains some code.
									sprintf( esc_html__( 'Enter one or more values of the custom field, comma separated. If you enter %s, this will be replaced with the current date and time.', 'posts-in-sidebar' ), '<code>now</code>' )
								);
								?>

								<?php
								// ================= Custom field compare aa
								pis_form_select(
									esc_html__( 'Operator', 'posts-in-sidebar' ),
									$this->get_field_id( 'mq_compare_aa' ),
									$this->get_field_name( 'mq_compare_aa' ),
									pis_select_compare(),
									$instance['mq_compare_aa'],
									esc_html__( 'Operator to test for values.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Custom field type aa
								pis_form_select(
									esc_html__( 'Type', 'posts-in-sidebar' ),
									$this->get_field_id( 'mq_type_aa' ),
									$this->get_field_name( 'mq_type_aa' ),
									pis_select_cf_type(),
									$instance['mq_type_aa'],
									// For decimal values see https://stackoverflow.com/questions/30018711/wordpress-meta-query-not-working-with-decimal-type
									esc_html__( 'Custom field type. Leave this field blank when using decimal values.', 'posts-in-sidebar' )
								);
								?>

								<hr />

								<?php
								// ================= Relation between aa and ab
								pis_form_select(
									esc_html__( 'Relation between A1 and A2 custom fields', 'posts-in-sidebar' ),
									$this->get_field_id( 'mq_relation_a' ),
									$this->get_field_name( 'mq_relation_a' ),
									pis_select_relation(),
									$instance['mq_relation_a']
								);
								?>

								<hr />

								<?php
								// ================= Custom field key ab
								pis_form_input_text(
									sprintf( '%1$s' . esc_html__( 'Custom field key A2', 'posts-in-sidebar' ) . '%2$s', '<strong>', '</strong>' ),
									$this->get_field_id( 'mq_key_ab' ),
									$this->get_field_name( 'mq_key_ab' ),
									esc_attr( $instance['mq_key_ab'] ),
									esc_html__( 'color', 'posts-in-sidebar' ),
									esc_html__( 'Enter the custom field key.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Custom field value ab
								pis_form_input_text(
									esc_html__( 'Custom field value', 'posts-in-sidebar' ),
									$this->get_field_id( 'mq_value_ab' ),
									$this->get_field_name( 'mq_value_ab' ),
									esc_attr( $instance['mq_value_ab'] ),
									esc_html__( 'blue, orange, red', 'posts-in-sidebar' ),
									// translators: the placeholder contains some code.
									sprintf( esc_html__( 'Enter one or more values of the custom field, comma separated. If you enter %s, this will be replaced with the current date and time.', 'posts-in-sidebar' ), '<code>now</code>' )
								);
								?>

								<?php
								// ================= Custom field compare ab
								pis_form_select(
									esc_html__( 'Operator', 'posts-in-sidebar' ),
									$this->get_field_id( 'mq_compare_ab' ),
									$this->get_field_name( 'mq_compare_ab' ),
									pis_select_compare(),
									$instance['mq_compare_ab'],
									esc_html__( 'Operator to test for values.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Custom field type ab
								pis_form_select(
									esc_html__( 'Type', 'posts-in-sidebar' ),
									$this->get_field_id( 'mq_type_ab' ),
									$this->get_field_name( 'mq_type_ab' ),
									pis_select_cf_type(),
									$instance['mq_type_ab'],
									esc_html__( 'Custom field type. Leave this field blank when using decimal values.', 'posts-in-sidebar' )
								);
								?>

							</div>

							<div class="pis-column">

								<h6 class="pis-title-center"><?php esc_html_e( 'Column B', 'posts-in-sidebar' ); ?></h6>

								<?php
								// ================= Custom field key ba
								pis_form_input_text(
									sprintf( '%1$s' . esc_html__( 'Custom field key B1', 'posts-in-sidebar' ) . '%2$s', '<strong>', '</strong>' ),
									$this->get_field_id( 'mq_key_ba' ),
									$this->get_field_name( 'mq_key_ba' ),
									esc_attr( $instance['mq_key_ba'] ),
									esc_html__( 'color', 'posts-in-sidebar' ),
									esc_html__( 'Enter the custom field key.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Custom field value ba
								pis_form_input_text(
									esc_html__( 'Custom field value', 'posts-in-sidebar' ),
									$this->get_field_id( 'mq_value_ba' ),
									$this->get_field_name( 'mq_value_ba' ),
									esc_attr( $instance['mq_value_ba'] ),
									esc_html__( 'blue, orange, red', 'posts-in-sidebar' ),
									// translators: the placeholder contains some code.
									sprintf( esc_html__( 'Enter one or more values of the custom field, comma separated. If you enter %s, this will be replaced with the current date and time.', 'posts-in-sidebar' ), '<code>now</code>' )
								);
								?>

								<?php
								// ================= Custom field compare ba
								pis_form_select(
									esc_html__( 'Operator', 'posts-in-sidebar' ),
									$this->get_field_id( 'mq_compare_ba' ),
									$this->get_field_name( 'mq_compare_ba' ),
									pis_select_compare(),
									$instance['mq_compare_ba'],
									esc_html__( 'Operator to test for values.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Custom field type ba
								pis_form_select(
									esc_html__( 'Type', 'posts-in-sidebar' ),
									$this->get_field_id( 'mq_type_ba' ),
									$this->get_field_name( 'mq_type_ba' ),
									pis_select_cf_type(),
									$instance['mq_type_ba'],
									esc_html__( 'Custom field type. Leave this field blank when using decimal values.', 'posts-in-sidebar' )
								);
								?>

								<hr />

								<?php
								// ================= Relation between ba and bb
								pis_form_select(
									esc_html__( 'Relation between B1 and B2 custom fields', 'posts-in-sidebar' ),
									$this->get_field_id( 'mq_relation_b' ),
									$this->get_field_name( 'mq_relation_b' ),
									pis_select_relation(),
									$instance['mq_relation_b']
								);
								?>

								<hr />

								<?php
								// ================= Custom field key bb
								pis_form_input_text(
									sprintf( '%1$s' . esc_html__( 'Custom field key B2', 'posts-in-sidebar' ) . '%2$s', '<strong>', '</strong>' ),
									$this->get_field_id( 'mq_key_bb' ),
									$this->get_field_name( 'mq_key_bb' ),
									esc_attr( $instance['mq_key_bb'] ),
									esc_html__( 'color', 'posts-in-sidebar' ),
									esc_html__( 'Enter the custom field key.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Custom field value bb
								pis_form_input_text(
									esc_html__( 'Custom field value', 'posts-in-sidebar' ),
									$this->get_field_id( 'mq_value_bb' ),
									$this->get_field_name( 'mq_value_bb' ),
									esc_attr( $instance['mq_value_bb'] ),
									esc_html__( 'blue, orange, red', 'posts-in-sidebar' ),
									// translators: the placeholder contains some code.
									sprintf( esc_html__( 'Enter one or more values of the custom field, comma separated. If you enter %s, this will be replaced with the current date and time.', 'posts-in-sidebar' ), '<code>now</code>' )
								);
								?>

								<?php
								// ================= Custom field compare bb
								pis_form_select(
									esc_html__( 'Operator', 'posts-in-sidebar' ),
									$this->get_field_id( 'mq_compare_bb' ),
									$this->get_field_name( 'mq_compare_bb' ),
									pis_select_compare(),
									$instance['mq_compare_bb'],
									esc_html__( 'Operator to test for values.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Custom field type bb
								pis_form_select(
									esc_html__( 'Type', 'posts-in-sidebar' ),
									$this->get_field_id( 'mq_type_bb' ),
									$this->get_field_name( 'mq_type_bb' ),
									pis_select_cf_type(),
									$instance['mq_type_bb'],
									esc_html__( 'Custom field type. Leave this field blank when using decimal values.', 'posts-in-sidebar' )
								);
								?>

							</div>

						</div>

					</div>

				</div>

				<!-- Get posts from user login name -->
				<div class="pis-section pis-2col">
					<h5 data-panel="loginname-query" class="pis-widget-title"><?php esc_html_e( 'Get posts from username', 'posts-in-sidebar' ); ?></h5>

					<div class="pis-container">

						<p>
							<em>
								<?php esc_html_e( 'This section lets you retrieve posts that have a custom field key or a category equal to the username of the currently logged-in user. This means that you can show a custom list of posts for each user.', 'posts-in-sidebar' ); ?>
							</em>
						</p>

						<p>
							<em>
								<?php esc_html_e( 'If you want to use custom field keys, create &mdash; for each post or for the posts you want &mdash; a custom field where the meta key is equal to the user login name. The meta value can be empty, since it is ignored here. A single post can have multiple custom fields, one for each user.', 'posts-in-sidebar' ); ?>
							</em>
						</p>

						<p>
							<em>
								<?php esc_html_e( 'If you want to use categories, assign the posts you want to a category that has the same name of the user.', 'posts-in-sidebar' ); ?>
							</em>
						</p>

						<p>
							<em>
								<?php esc_html_e( 'When logged in, the user will see the posts you have marked with his username.', 'posts-in-sidebar' ); ?>
							</em>
						</p>

						<p>
							<em>
								<?php esc_html_e( 'If the user is not logged-in or the widget does not find any post having a meta key (or a category) with the username of the logged-in user, the widget will simply ignore this option.', 'posts-in-sidebar' ); ?>
							</em>
						</p>

						<div class="pis-column-container">

							<?php
							// ================= Get posts from current username (will use custom field key).
							pis_form_checkbox(
								esc_html__( 'Get posts from current username', 'posts-in-sidebar' ),
								$this->get_field_id( 'get_from_username' ),
								$this->get_field_name( 'get_from_username' ),
								$instance['get_from_username'],
								esc_html__( 'This option will use custom field keys.', 'posts-in-sidebar' )
							);
							?>

							<?php
							// ================= Use category for "Get posts from current username".
							pis_form_checkbox(
								esc_html__( 'Use category instead of meta key', 'posts-in-sidebar' ),
								$this->get_field_id( 'use_categories' ),
								$this->get_field_name( 'use_categories' ),
								$instance['use_categories'],
								esc_html__( 'When you activate this option, make sure to activate also the option "Get posts from current username"', 'posts-in-sidebar' )
							);
							?>

						</div>

						<div class="pis-column-container">

							<?php
							// ================= Don't ignore other parameters
							pis_form_checkbox(
								esc_html__( 'Do not ignore other parameters', 'posts-in-sidebar' ),
								$this->get_field_id( 'dont_ignore_params_username' ),
								$this->get_field_name( 'dont_ignore_params_username' ),
								$instance['dont_ignore_params_username'],
								esc_html__( 'By default, when you activate the option above to change the query, the plugin will deactivate other parameters like categories, tags, date, author, and so on. To leave in action these parameters, activate this option.', 'posts-in-sidebar' ),
								'pis-boxed pis-boxed-light-blue'
							);
							?>

						</div>

					</div>

				</div>

				<!-- Excluding posts -->
				<div class="pis-section">

					<h5 data-panel="excluding-posts" class="pis-widget-title"><?php esc_html_e( 'Excluding posts', 'posts-in-sidebar' ); ?></h5>

					<div class="pis-container">

						<p><em>
							<?php
							esc_html_e( 'Define here which posts must be excluded from the query.', 'posts-in-sidebar' );
							?>
						</em></p>

						<p><em>
							<?php
							// translators: there is some code in placeholders.
							printf( esc_html__( 'If a field requires one or more IDs, install %1$sthis plugin%2$s to easily find the IDs.', 'posts-in-sidebar' ), '<a href="https://wordpress.org/plugins/reveal-ids-for-wp-admin-25/" target="_blank">', '</a>' );
							?>
						</em></p>

						<div class="pis-column-container">

							<div class="pis-column">

								<?php
								// ================= Exclude posts by these authors
								if ( is_array( $instance['author_not_in'] ) ) {
									$var = implode( ',', $instance['author_not_in'] );
								} else {
									$var = $instance['author_not_in'];
								}
								pis_form_input_text(
									esc_html__( 'Exclude posts by these authors', 'posts-in-sidebar' ),
									$this->get_field_id( 'author_not_in' ),
									$this->get_field_name( 'author_not_in' ),
									esc_attr( $var ),
									'1, 23, 45',
									esc_html__( 'Enter IDs, comma separated.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Exclude posts from categories
								if ( is_array( $instance['cat_not_in'] ) ) {
									$var = implode( ',', $instance['cat_not_in'] );
								} else {
									$var = $instance['cat_not_in'];
								}
								pis_form_input_text(
									esc_html__( 'Exclude posts from these categories', 'posts-in-sidebar' ),
									$this->get_field_id( 'cat_not_in' ),
									$this->get_field_name( 'cat_not_in' ),
									esc_attr( $var ),
									'3, 31',
									esc_html__( 'Enter IDs, comma separated.', 'posts-in-sidebar' )
								);
								?>

							</div>

							<div class="pis-column">

								<?php
								// ================= Exclude posts from tags
								if ( is_array( $instance['tag_not_in'] ) ) {
									$var = implode( ',', $instance['tag_not_in'] );
								} else {
									$var = $instance['tag_not_in'];
								}
								pis_form_input_text(
									esc_html__( 'Exclude posts from these tags', 'posts-in-sidebar' ),
									$this->get_field_id( 'tag_not_in' ),
									$this->get_field_name( 'tag_not_in' ),
									esc_attr( $var ),
									'7, 11',
									esc_html__( 'Enter IDs, comma separated.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Exclude posts that have these ids.
								pis_form_input_text(
									esc_html__( 'Exclude posts with these IDs', 'posts-in-sidebar' ),
									$this->get_field_id( 'post_not_in' ),
									$this->get_field_name( 'post_not_in' ),
									esc_attr( $instance['post_not_in'] ),
									'5, 29, 523, 4519',
									esc_html__( 'Enter IDs, comma separated.', 'posts-in-sidebar' )
								);
								?>

							</div>

							<div class="pis-column">

								<?php
								// ================= Exclude posts whose parent is in these IDs.
								pis_form_input_text(
									esc_html__( 'Exclude posts whose parent is in these IDs', 'posts-in-sidebar' ),
									$this->get_field_id( 'post_parent_not_in' ),
									$this->get_field_name( 'post_parent_not_in' ),
									esc_attr( $instance['post_parent_not_in'] ),
									'5, 29, 523, 4519',
									esc_html__( 'Enter IDs, comma separated.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Exclude current post
								pis_form_checkbox(
									esc_html__( 'Automatically exclude the current post in single post or the current page in single page', 'posts-in-sidebar' ),
									$this->get_field_id( 'exclude_current_post' ),
									$this->get_field_name( 'exclude_current_post' ),
									$instance['exclude_current_post']
								);
								?>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

		<!-- Displaying posts -->
		<div class="pis-section">

			<h4 data-panel="displaying-posts" class="pis-widget-title"><?php esc_html_e( 'Displaying posts', 'posts-in-sidebar' ); ?></h4>

			<div class="pis-container">

				<p><em><?php esc_html_e( 'Define here which elements you want to display in the widget.', 'posts-in-sidebar' ); ?></em></p>

				<div class="pis-section pis-2col">

					<div class="pis-column-container">

						<div class="pis-column">

							<h5><?php esc_html_e( 'The title of the post', 'posts-in-sidebar' ); ?></h5>

							<?php
							// ================= Title of the post
							pis_form_checkbox(
								esc_html__( 'Display the title of the post', 'posts-in-sidebar' ),
								$this->get_field_id( 'display_title' ),
								$this->get_field_name( 'display_title' ),
								$instance['display_title']
							);
							?>

							<?php
							// ================= Link to the title
							pis_form_checkbox(
								esc_html__( 'Link the title to the post', 'posts-in-sidebar' ),
								$this->get_field_id( 'link_on_title' ),
								$this->get_field_name( 'link_on_title' ),
								$instance['link_on_title']
							);
							?>

							<?php
							// ================= Arrow after the title
							pis_form_checkbox(
								esc_html__( 'Show an arrow after the title', 'posts-in-sidebar' ),
								$this->get_field_id( 'arrow' ),
								$this->get_field_name( 'arrow' ),
								$instance['arrow']
							);
							?>

							<?php
							// ================= Title length
							pis_form_input_text(
								esc_html__( 'The length of the title', 'posts-in-sidebar' ),
								$this->get_field_id( 'title_length' ),
								$this->get_field_name( 'title_length' ),
								esc_attr( $instance['title_length'] ),
								'10',
								// translators: there is some code.
								sprintf( esc_html__( 'Use %s to leave the length unchanged.', 'posts-in-sidebar' ), '<code>0</code>' )
							);
							?>

							<?php
							// ================= Title length Unit
							pis_form_select(
								esc_html__( 'Title length unit', 'posts-in-sidebar' ),
								$this->get_field_id( 'title_length_unit' ),
								$this->get_field_name( 'title_length_unit' ),
								pis_select_length_unit(),
								$instance['title_length_unit']
							);
							?>

							<?php
							// ================= Title ellipsis
							pis_form_checkbox(
								esc_html__( 'Add an ellipsis after the shortened title', 'posts-in-sidebar' ),
								$this->get_field_id( 'title_hellipsis' ),
								$this->get_field_name( 'title_hellipsis' ),
								$instance['title_hellipsis']
							);
							?>

							<?php
							// ================= Type of HTML tag for post title
							pis_form_select(
								esc_html__( 'Type of HTML tag for post title', 'posts-in-sidebar' ),
								$this->get_field_id( 'html_title_type_of' ),
								$this->get_field_name( 'html_title_type_of' ),
								pis_select_html_title_type_of(),
								$instance['html_title_type_of']
							);
							?>

						</div>

						<div class="pis-column">
							<h5><?php esc_html_e( 'The text of the post', 'posts-in-sidebar' ); ?></h5>

							<?php
							// ================= Type of text
							pis_form_select(
								esc_html__( 'Display this type of text', 'posts-in-sidebar' ),
								$this->get_field_id( 'excerpt' ),
								$this->get_field_name( 'excerpt' ),
								pis_select_text_type(),
								$instance['excerpt'],
								// translators: there is some code.
								sprintf( esc_html__( 'For more information regarding these types of text, please see %1$shere%2$s.', 'posts-in-sidebar' ), '<a href="https://github.com/aldolat/posts-in-sidebar/wiki/Usage#types-of-text-to-display" target="_blank">', '</a>' )
							);
							?>

							<?php
							// ================= Excerpt length
							pis_form_input_text(
								esc_html__( 'The WordPress generated excerpt length will be', 'posts-in-sidebar' ),
								$this->get_field_id( 'exc_length' ),
								$this->get_field_name( 'exc_length' ),
								esc_attr( $instance['exc_length'] ),
								'20'
							);
							?>

							<?php
							// ================= Excerpt length Unit
							pis_form_select(
								esc_html__( 'Excerpt length unit', 'posts-in-sidebar' ),
								$this->get_field_id( 'exc_length_unit' ),
								$this->get_field_name( 'exc_length_unit' ),
								pis_select_length_unit(),
								$instance['exc_length_unit']
							);
							?>

							<?php
							// ================= More link text
							pis_form_input_text(
								esc_html__( 'Use this text for More link', 'posts-in-sidebar' ),
								$this->get_field_id( 'the_more' ),
								$this->get_field_name( 'the_more' ),
								esc_attr( $instance['the_more'] ),
								esc_html__( 'Read more&hellip;', 'posts-in-sidebar' ),
								esc_html__( 'The "Read more" text will be automatically hidden if the length of the WordPress-generated excerpt is smaller than or equal to the user-defined length.', 'posts-in-sidebar' )
							);
							?>

							<?php
							// ================= Arrow after the excerpt
							pis_form_checkbox(
								esc_html__( 'Display an arrow after the text of the post', 'posts-in-sidebar' ),
								$this->get_field_id( 'exc_arrow' ),
								$this->get_field_name( 'exc_arrow' ),
								$instance['exc_arrow']
							);
							?>

						</div>

					</div>

				</div>

				<div class="pis-section pis-2col">

					<h5 data-panel="featured-image" class="pis-widget-title"><?php esc_html_e( 'The featured image of the post', 'posts-in-sidebar' ); ?></h5>

					<div class="pis-container">

						<div class="pis-column-container">

							<div class="pis-column">

								<?php if ( ! current_theme_supports( 'post-thumbnails' ) ) { ?>
									<p class="pis-boxed pis-boxed-red"><strong><?php esc_html_e( 'Your theme does not support the Post Thumbnail feature. No image will be displayed.', 'posts-in-sidebar' ); ?></strong></p>
								<?php } ?>

								<?php
								// ================= Featured image
								pis_form_checkbox(
									esc_html__( 'Display the featured image of the post', 'posts-in-sidebar' ),
									$this->get_field_id( 'display_image' ),
									$this->get_field_name( 'display_image' ),
									$instance['display_image']
								);
								?>

								<?php
								// ================= Image sizes
								pis_form_select(
									esc_html__( 'The size of the thumbnail will be', 'posts-in-sidebar' ),
									$this->get_field_id( 'image_size' ),
									$this->get_field_name( 'image_size' ),
									pis_select_image_size(),
									$instance['image_size']
								);
								?>

								<?php
								// ================= Image align
								pis_form_select(
									esc_html__( 'Align the image to', 'posts-in-sidebar' ),
									$this->get_field_id( 'image_align' ),
									$this->get_field_name( 'image_align' ),
									pis_select_image_align(),
									$instance['image_align']
								);
								?>

								<p><em>
									<?php
									printf(
										// translators: there is some code.
										esc_html__( 'Note that in order to use image sizes different from the WordPress standards, add them to your theme\'s %3$sfunctions.php%4$s file. See the %1$sCodex%2$s for further information.', 'posts-in-sidebar' ),
										'<a href="https://developer.wordpress.org/reference/functions/add_image_size/" target="_blank">',
										'</a>',
										'<code>',
										'</code>'
									);
									?>
									<?php
									printf(
										// translators: there is some code.
										esc_html__( 'You can also use %1$sa plugin%2$s that could help you in doing it.', 'posts-in-sidebar' ),
										'<a href="https://wordpress.org/plugins/simple-image-sizes/" target="_blank">',
										'</a>'
									);
									?>
								</em></p>

							</div>

							<div class="pis-column">

								<h6><?php esc_html_e( 'The link of the featured image', 'posts-in-sidebar' ); ?></h6>

								<?php
								// ================= The link of the image to post
								pis_form_checkbox(
									esc_html__( 'Link the image to the post', 'posts-in-sidebar' ),
									$this->get_field_id( 'image_link_to_post' ),
									$this->get_field_name( 'image_link_to_post' ),
									$instance['image_link_to_post'],
									esc_html__( 'If activated, the image will be linked to the post. If you want to change the link, enter another URL in the box below.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Custom link of the image
								pis_form_input_text(
									esc_html__( 'Link the image to this URL', 'posts-in-sidebar' ),
									$this->get_field_id( 'image_link' ),
									$this->get_field_name( 'image_link' ),
									esc_url( wp_strip_all_tags( $instance['image_link'] ) ),
									'http://example.com/mypage',
									esc_html__( 'By default the featured image is linked to the post. Use this field to link the image to a URL of your choice. Please, note that every featured image of this widget will be linked to the same URL.', 'posts-in-sidebar' )
								);
								?>

								<h6><?php esc_html_e( 'Custom featured image', 'posts-in-sidebar' ); ?></h6>

								<?php
								// ================= Custom image URL
								pis_form_input_text(
									esc_html__( 'Use this image instead of the standard featured image', 'posts-in-sidebar' ),
									$this->get_field_id( 'custom_image_url' ),
									$this->get_field_name( 'custom_image_url' ),
									esc_url( wp_strip_all_tags( $instance['custom_image_url'] ) ),
									'http://example.com/image.jpg',
									esc_html__( 'Paste here the URL of the image. Note that the same image will be used for all the posts in the widget, unless you activate the checkbox below.', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Use custom image URL only if the post thumbnail is not defined.
								pis_form_checkbox(
									esc_html__( 'Use custom image URL only if the post has not a featured image.', 'posts-in-sidebar' ),
									$this->get_field_id( 'custom_img_no_thumb' ),
									$this->get_field_name( 'custom_img_no_thumb' ),
									$instance['custom_img_no_thumb']
								);
								?>

							</div>

						</div>

						<div class="pis-boxed pis-boxed-light-blue">

							<h6><?php esc_html_e( 'Move this section', 'posts-in-sidebar' ); ?></h6>

							<?php
							// ================= Positioning image before title
							pis_form_checkbox(
								esc_html__( 'Display this section before the title of the post', 'posts-in-sidebar' ),
								$this->get_field_id( 'image_before_title' ),
								$this->get_field_name( 'image_before_title' ),
								$instance['image_before_title']
							);
							?>

						</div>

					</div>

				</div>

				<div class="pis-section">

					<h5 data-panel="author-date-comments" class="pis-widget-title"><?php esc_html_e( 'Author, date/time and comments', 'posts-in-sidebar' ); ?></h5>

					<div class="pis-container">

						<div class="pis-column-container">

							<div class="pis-column">

							<h6><?php esc_html_e( 'Author', 'posts-in-sidebar' ); ?></h6>

								<?php
								// ================= Author
								pis_form_checkbox(
									esc_html__( 'Display the author of the post', 'posts-in-sidebar' ),
									$this->get_field_id( 'display_author' ),
									$this->get_field_name( 'display_author' ),
									$instance['display_author']
								);
								?>

								<?php
								// ================= Author text
								pis_form_input_text(
									esc_html__( 'Use this text before author\'s name', 'posts-in-sidebar' ),
									$this->get_field_id( 'author_text' ),
									$this->get_field_name( 'author_text' ),
									esc_attr( $instance['author_text'] ),
									esc_html__( 'By', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Author archive
								pis_form_checkbox(
									esc_html__( 'Link the author to his archive', 'posts-in-sidebar' ),
									$this->get_field_id( 'linkify_author' ),
									$this->get_field_name( 'linkify_author' ),
									$instance['linkify_author']
								);
								?>

							</div>

							<div class="pis-column">

								<h6><?php esc_html_e( 'Date', 'posts-in-sidebar' ); ?></h6>

								<?php
								// ================= Date
								pis_form_checkbox(
									esc_html__( 'Display the date of the post', 'posts-in-sidebar' ),
									$this->get_field_id( 'display_date' ),
									$this->get_field_name( 'display_date' ),
									$instance['display_date']
								);
								?>

								<?php
								// ================= Date text
								pis_form_input_text(
									esc_html__( 'Use this text before date', 'posts-in-sidebar' ),
									$this->get_field_id( 'date_text' ),
									$this->get_field_name( 'date_text' ),
									esc_attr( $instance['date_text'] ),
									esc_html__( 'Published on', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Date link
								pis_form_checkbox(
									esc_html__( 'Link the date to the post', 'posts-in-sidebar' ),
									$this->get_field_id( 'linkify_date' ),
									$this->get_field_name( 'linkify_date' ),
									$instance['linkify_date']
								);
								?>

								<?php
								// ================= Time
								pis_form_checkbox(
									esc_html__( 'Display the time of the post', 'posts-in-sidebar' ),
									$this->get_field_id( 'display_time' ),
									$this->get_field_name( 'display_time' ),
									$instance['display_time']
								);
								?>

							</div>

							<div class="pis-column">

								<h6><?php esc_html_e( 'Comments', 'posts-in-sidebar' ); ?></h6>

								<?php
								// ================= Number of comments
								pis_form_checkbox(
									esc_html__( 'Display the number of comments', 'posts-in-sidebar' ),
									$this->get_field_id( 'comments' ),
									$this->get_field_name( 'comments' ),
									$instance['comments']
								);
								?>

								<?php
								// ================= Comments text
								pis_form_input_text(
									esc_html__( 'Use this text before comments', 'posts-in-sidebar' ),
									$this->get_field_id( 'comments_text' ),
									$this->get_field_name( 'comments_text' ),
									esc_attr( $instance['comments_text'] ),
									esc_html__( 'Comments:', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Comments link
								pis_form_checkbox(
									esc_html__( 'Link the comments to post\'s comments', 'posts-in-sidebar' ),
									$this->get_field_id( 'linkify_comments' ),
									$this->get_field_name( 'linkify_comments' ),
									$instance['linkify_comments']
								);
								?>

								<?php
								// ================= Display comments number only
								pis_form_checkbox(
									esc_html__( 'Display the number of comments only', 'posts-in-sidebar' ),
									$this->get_field_id( 'display_comm_num_only' ),
									$this->get_field_name( 'display_comm_num_only' ),
									$instance['display_comm_num_only']
								);
								?>

							</div>

						</div>

						<div class="pis-column-container">

							<div class="pis-column">

								<?php
								// ================= Author gravatar
								pis_form_checkbox(
									esc_html__( 'Display author\'s Gravatar', 'posts-in-sidebar' ),
									$this->get_field_id( 'gravatar_display' ),
									$this->get_field_name( 'gravatar_display' ),
									$instance['gravatar_display'],
									'',
									'pis-gravatar'
								);
								?>

								<?php
								// ================= Gravatar size
								pis_form_input_text(
									esc_html__( 'Gravatar size', 'posts-in-sidebar' ),
									$this->get_field_id( 'gravatar_size' ),
									$this->get_field_name( 'gravatar_size' ),
									esc_attr( $instance['gravatar_size'] ),
									'32'
								);
								?>

								<?php
								// ================= Gravatar default image
								pis_form_input_text(
									esc_html__( 'URL of the default Gravatar image', 'posts-in-sidebar' ),
									$this->get_field_id( 'gravatar_default' ),
									$this->get_field_name( 'gravatar_default' ),
									esc_attr( $instance['gravatar_default'] ),
									'http://example.com/image.jpg'
								);
								?>

								<?php
								// ================= Gravatar position
								pis_form_select(
									esc_html__( 'Gravatar position', 'posts-in-sidebar' ),
									$this->get_field_id( 'gravatar_position' ),
									$this->get_field_name( 'gravatar_position' ),
									pis_select_gravatar_position(),
									$instance['gravatar_position']
								);
								?>

							</div>

							<div class="pis-column">

								<?php
								// ================= Modification Date
								pis_form_checkbox(
									esc_html__( 'Display the modification date of the post', 'posts-in-sidebar' ),
									$this->get_field_id( 'display_mod_date' ),
									$this->get_field_name( 'display_mod_date' ),
									$instance['display_mod_date']
								);
								?>

								<?php
								// ================= Modification Date text
								pis_form_input_text(
									esc_html__( 'Use this text before modification date', 'posts-in-sidebar' ),
									$this->get_field_id( 'mod_date_text' ),
									$this->get_field_name( 'mod_date_text' ),
									esc_attr( $instance['mod_date_text'] ),
									esc_html__( 'Modified on', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Modification Date link
								pis_form_checkbox(
									esc_html__( 'Link the modification date to the post', 'posts-in-sidebar' ),
									$this->get_field_id( 'linkify_mod_date' ),
									$this->get_field_name( 'linkify_mod_date' ),
									$instance['linkify_mod_date']
								);
								?>

								<?php
								// ================= Modification time
								pis_form_checkbox(
									esc_html__( 'Display the modification time of the post', 'posts-in-sidebar' ),
									$this->get_field_id( 'display_mod_time' ),
									$this->get_field_name( 'display_mod_time' ),
									$instance['display_mod_time']
								);
								?>

							</div>

							<div class="pis-column">

								<?php
								// ================= Hide comments section if there is no comment
								pis_form_checkbox(
									esc_html__( 'Hide the comments section if there is no comment', 'posts-in-sidebar' ),
									$this->get_field_id( 'hide_zero_comments' ),
									$this->get_field_name( 'hide_zero_comments' ),
									$instance['hide_zero_comments']
								);
								?>

								<?php
								// ================= Utility separator
								pis_form_input_text(
									esc_html__( 'Use this separator between author, date/time and comments', 'posts-in-sidebar' ),
									$this->get_field_id( 'utility_sep' ),
									$this->get_field_name( 'utility_sep' ),
									esc_attr( $instance['utility_sep'] ),
									'|',
									esc_html__( 'A space will be added before and after the separator.', 'posts-in-sidebar' )
								);
								?>

							</div>

						</div>

						<div class="pis-column-container">

						<h6><?php esc_html_e( 'Date and time formatting', 'posts-in-sidebar' ); ?></h6>

						<p>
							<?php
							esc_html_e( 'In these fields you can define the format of date and time for this widget.', 'posts-in-sidebar' );
							echo ' ';
							esc_html_e( 'If you leave these fields blank, the widget will use the format as defined in the Settings page of WordPress.', 'posts-in-sidebar' );
							echo ' ';
							esc_html_e( 'It\'s not necessary to fill all the fields, but only those you want to change.', 'posts-in-sidebar' );
							?>
						</p>

						<p>
							<?php
							printf(
								// translators: The link to date and time formatting in WordPress documentation.
								esc_html__( 'For more information about date and time formatting see the %1$sWordPress documentation%2$s.', 'posts-in-sidebar' ),
								'<a href="https://wordpress.org/support/article/formatting-date-and-time/" target="_blank" rel="noopener noreferrer">',
								'</a>'
							);
							?>
						</p>

						<div class="pis-2col">

								<div class="pis-column">

									<?php
									// ================= Date format
									pis_form_input_text(
										esc_html__( 'Date format', 'posts-in-sidebar' ),
										$this->get_field_id( 'date_format' ),
										$this->get_field_name( 'date_format' ),
										esc_attr( $instance['date_format'] ),
										'F j, Y'
									);
									?>

									<?php
									// ================= Time format
									pis_form_input_text(
										esc_html__( 'Time format', 'posts-in-sidebar' ),
										$this->get_field_id( 'time_format' ),
										$this->get_field_name( 'time_format' ),
										esc_attr( $instance['time_format'] ),
										'g:i a'
									);
									?>

								</div>

								<div class="pis-column">

									<?php
									// ================= Date modified format
									pis_form_input_text(
										esc_html__( 'Modification date format', 'posts-in-sidebar' ),
										$this->get_field_id( 'date_mod_format' ),
										$this->get_field_name( 'date_mod_format' ),
										esc_attr( $instance['date_mod_format'] ),
										'F j, Y'
									);
									?>

									<?php
									// ================= Time modified format
									pis_form_input_text(
										esc_html__( 'Modification time format', 'posts-in-sidebar' ),
										$this->get_field_id( 'time_mod_format' ),
										$this->get_field_name( 'time_mod_format' ),
										esc_attr( $instance['time_mod_format'] ),
										'g:i a'
									);
									?>

								</div>

							</div>

						</div>

						<div class="pis-boxed pis-boxed-light-blue">

							<h6><?php esc_html_e( 'Move this section', 'posts-in-sidebar' ); ?></h6>

							<?php
							// ================= Section position
							pis_form_checkbox(
								esc_html__( 'Display this section before the title of the post', 'posts-in-sidebar' ),
								$this->get_field_id( 'utility_before_title' ),
								$this->get_field_name( 'utility_before_title' ),
								$instance['utility_before_title']
							);
							?>

							<?php
							// ================= Section position
							pis_form_checkbox(
								esc_html__( 'Display this section after the title of the post', 'posts-in-sidebar' ),
								$this->get_field_id( 'utility_after_title' ),
								$this->get_field_name( 'utility_after_title' ),
								$instance['utility_after_title']
							);
							?>

						</div>

					</div>

				</div>

				<div class="pis-section">

					<h5 data-panel="taxonomies" class="pis-widget-title"><?php esc_html_e( 'Taxonomies', 'posts-in-sidebar' ); ?></h5>

					<div class="pis-container">

						<div class="pis-column-container">

							<div class="pis-column">

								<h6><?php esc_html_e( 'Categories', 'posts-in-sidebar' ); ?></h6>

								<?php
								// ================= Post categories
								pis_form_checkbox(
									esc_html__( 'Show the categories', 'posts-in-sidebar' ),
									$this->get_field_id( 'categories' ),
									$this->get_field_name( 'categories' ),
									$instance['categories']
								);
								?>

								<?php
								// ================= Categories text
								pis_form_input_text(
									esc_html__( 'Use this text before categories list', 'posts-in-sidebar' ),
									$this->get_field_id( 'categ_text' ),
									$this->get_field_name( 'categ_text' ),
									esc_attr( $instance['categ_text'] ),
									esc_html__( 'Category:', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Categories separator
								pis_form_input_text(
									esc_html__( 'Use this separator between categories', 'posts-in-sidebar' ),
									$this->get_field_id( 'categ_sep' ),
									$this->get_field_name( 'categ_sep' ),
									esc_attr( $instance['categ_sep'] ),
									',',
									esc_html__( 'A space will be added after the separator.', 'posts-in-sidebar' )
								);
								?>

							</div>

							<div class="pis-column">

								<h6><?php esc_html_e( 'Tags', 'posts-in-sidebar' ); ?></h6>

								<?php
								// ================= Post tags
								pis_form_checkbox(
									esc_html__( 'Show the tags', 'posts-in-sidebar' ),
									$this->get_field_id( 'tags' ),
									$this->get_field_name( 'tags' ),
									$instance['tags']
								);
								?>

								<?php
								// ================= Tags text
								pis_form_input_text(
									esc_html__( 'Use this text before tags list', 'posts-in-sidebar' ),
									$this->get_field_id( 'tags_text' ),
									$this->get_field_name( 'tags_text' ),
									esc_attr( $instance['tags_text'] ),
									esc_html__( 'Tags:', 'posts-in-sidebar' )
								);
								?>

								<?php
								// ================= Hashtag
								pis_form_input_text(
									esc_html__( 'Use this hashtag', 'posts-in-sidebar' ),
									$this->get_field_id( 'hashtag' ),
									$this->get_field_name( 'hashtag' ),
									esc_attr( $instance['hashtag'] ),
									'#'
								);
								?>

								<?php
								// ================= Tags separator
								pis_form_input_text(
									esc_html__( 'Use this separator between tags', 'posts-in-sidebar' ),
									$this->get_field_id( 'tag_sep' ),
									$this->get_field_name( 'tag_sep' ),
									esc_attr( $instance['tag_sep'] ),
									',',
									esc_html__( 'A space will be added after the separator.', 'posts-in-sidebar' )
								);
								?>

							</div>

							<div class="pis-column">

								<h6><?php esc_html_e( 'Custom taxonomies', 'posts-in-sidebar' ); ?></h6>

								<?php
								// ================= Custom taxonomies
								pis_form_checkbox(
									esc_html__( 'Show the custom taxonomies', 'posts-in-sidebar' ),
									$this->get_field_id( 'display_custom_tax' ),
									$this->get_field_name( 'display_custom_tax' ),
									$instance['display_custom_tax']
								);
								?>

								<?php
								// ================= Terms hashtag
								pis_form_input_text(
									esc_html__( 'Use this hashtag for terms', 'posts-in-sidebar' ),
									$this->get_field_id( 'term_hashtag' ),
									$this->get_field_name( 'term_hashtag' ),
									esc_attr( $instance['term_hashtag'] ),
									'#'
								);
								?>

								<?php
								// ================= Terms separator
								pis_form_input_text(
									esc_html__( 'Use this separator between terms', 'posts-in-sidebar' ),
									$this->get_field_id( 'term_sep' ),
									$this->get_field_name( 'term_sep' ),
									esc_attr( $instance['term_sep'] ),
									',',
									esc_html__( 'A space will be added after the separator.', 'posts-in-sidebar' )
								);
								?>

							</div>

						</div>

						<div class="pis-column-container">

							<div class="pis-column">

								<div class="pis-boxed pis-boxed-light-blue">

									<h6><?php esc_html_e( 'Move this section', 'posts-in-sidebar' ); ?></h6>

									<?php
									// ================= Section position
									pis_form_checkbox(
										esc_html__( 'Display the categories before the title of the post', 'posts-in-sidebar' ),
										$this->get_field_id( 'categ_before_title' ),
										$this->get_field_name( 'categ_before_title' ),
										$instance['categ_before_title']
									);
									?>

									<?php
									// ================= Section position
									pis_form_checkbox(
										esc_html__( 'Display the categories after the title of the post', 'posts-in-sidebar' ),
										$this->get_field_id( 'categ_after_title' ),
										$this->get_field_name( 'categ_after_title' ),
										$instance['categ_after_title']
									);
									?>

								</div>

							</div>

							<div class="pis-column">

								<div class="pis-boxed pis-boxed-light-blue">

									<h6><?php esc_html_e( 'Move this section', 'posts-in-sidebar' ); ?></h6>

									<?php
									// ================= Section position
									pis_form_checkbox(
										esc_html__( 'Display the tags before the title of the post', 'posts-in-sidebar' ),
										$this->get_field_id( 'tags_before_title' ),
										$this->get_field_name( 'tags_before_title' ),
										$instance['tags_before_title']
									);
									?>

									<?php
									// ================= Section position
									pis_form_checkbox(
										esc_html__( 'Display the tags after the title of the post', 'posts-in-sidebar' ),
										$this->get_field_id( 'tags_after_title' ),
										$this->get_field_name( 'tags_after_title' ),
										$instance['tags_after_title']
									);
									?>

								</div>

							</div>

							<div class="pis-column">

								<div class="pis-boxed pis-boxed-light-blue">

									<h6><?php esc_html_e( 'Move this section', 'posts-in-sidebar' ); ?></h6>

									<?php
									// ================= Section position
									pis_form_checkbox(
										esc_html__( 'Display the custom taxonomies before the title of the post', 'posts-in-sidebar' ),
										$this->get_field_id( 'ctaxs_before_title' ),
										$this->get_field_name( 'ctaxs_before_title' ),
										$instance['ctaxs_before_title']
									);
									?>

									<?php
									// ================= Section position
									pis_form_checkbox(
										esc_html__( 'Display the custom taxonomies after the title of the post', 'posts-in-sidebar' ),
										$this->get_field_id( 'ctaxs_after_title' ),
										$this->get_field_name( 'ctaxs_after_title' ),
										$instance['ctaxs_after_title']
									);
									?>

								</div>

							</div>

						</div>

					</div>

				</div>

				<div class="pis-section">

					<h5 data-panel="custom-field" class="pis-widget-title"><?php esc_html_e( 'The custom fields', 'posts-in-sidebar' ); ?></h5>

					<div class="pis-container">

						<div class="pis-column-container pis-2col">

							<div class="pis-column">

								<h6><?php esc_html_e( 'Display all the custom fields', 'posts-in-sidebar' ); ?></h6>

								<?php
								// ================= Display all the custom fields
								pis_form_checkbox(
									esc_html__( 'Display all the custom fields of the post', 'posts-in-sidebar' ),
									$this->get_field_id( 'custom_field_all' ),
									$this->get_field_name( 'custom_field_all' ),
									$instance['custom_field_all']
								);
								?>

							</div>

							<div class="pis-column">

								<h6><?php esc_html_e( 'Display a single custom field', 'posts-in-sidebar' ); ?></h6>

								<?php
								// ================= Display a single custom field
								pis_form_checkbox(
									esc_html__( 'Display the custom field of the post', 'posts-in-sidebar' ),
									$this->get_field_id( 'custom_field' ),
									$this->get_field_name( 'custom_field' ),
									$instance['custom_field']
								);
								?>

								<?php
								// ================= Which custom field
								pis_form_select(
									esc_html__( 'Display this custom field', 'posts-in-sidebar' ),
									$this->get_field_id( 'meta' ),
									$this->get_field_name( 'meta' ),
									pis_select_meta(),
									$instance['meta']
								);
								?>

							</div>

						</div>

						<?php
						// ================= Custom fields text
						pis_form_input_text(
							esc_html__( 'Use this text before the custom field', 'posts-in-sidebar' ),
							$this->get_field_id( 'custom_field_txt' ),
							$this->get_field_name( 'custom_field_txt' ),
							esc_attr( $instance['custom_field_txt'] ),
							esc_html__( 'Custom field:', 'posts-in-sidebar' )
						);
						?>

						<?php
						// ================= Custom field count
						pis_form_input_text(
							esc_html__( 'The custom field content length will be (in characters)', 'posts-in-sidebar' ),
							$this->get_field_id( 'custom_field_count' ),
							$this->get_field_name( 'custom_field_count' ),
							esc_attr( $instance['custom_field_count'] ),
							'10'
						);
						?>

						<?php
						// ================= Custom field hellip
						pis_form_input_text(
							esc_html__( 'Use this text for horizontal ellipsis', 'posts-in-sidebar' ),
							$this->get_field_id( 'custom_field_hellip' ),
							$this->get_field_name( 'custom_field_hellip' ),
							esc_attr( $instance['custom_field_hellip'] ),
							'&hellip;'
						);
						?>

						<?php
						// ================= Custom field key
						pis_form_checkbox(
							esc_html__( 'Also display the key of the custom field', 'posts-in-sidebar' ),
							$this->get_field_id( 'custom_field_key' ),
							$this->get_field_name( 'custom_field_key' ),
							$instance['custom_field_key']
						);
						?>

						<?php
						// ================= Custom field separator
						pis_form_input_text(
							esc_html__( 'Use this separator between meta key and value', 'posts-in-sidebar' ),
							$this->get_field_id( 'custom_field_sep' ),
							$this->get_field_name( 'custom_field_sep' ),
							esc_attr( $instance['custom_field_sep'] ),
							':'
						);
						?>

						<div class="pis-boxed pis-boxed-light-blue">

							<h6><?php esc_html_e( 'Move this section', 'posts-in-sidebar' ); ?></h6>

							<?php
							// ================= Section position
							pis_form_checkbox(
								esc_html__( 'Display this section before the title of the post', 'posts-in-sidebar' ),
								$this->get_field_id( 'cf_before_title' ),
								$this->get_field_name( 'cf_before_title' ),
								$instance['cf_before_title']
							);
							?>

							<?php
							// ================= Section position
							pis_form_checkbox(
								esc_html__( 'Display this section after the title of the post', 'posts-in-sidebar' ),
								$this->get_field_id( 'cf_after_title' ),
								$this->get_field_name( 'cf_after_title' ),
								$instance['cf_after_title']
							);
							?>

						</div>

					</div>

				</div>

				<div class="pis-section pis-2col">

					<h5 data-panel="archive-link" class="pis-widget-title"><?php esc_html_e( 'The link to the archive', 'posts-in-sidebar' ); ?></h5>

					<div class="pis-container">

						<div class="pis-column-container">

							<div class="pis-column">

								<?php
								// ================= Taxonomy archive link
								pis_form_checkbox(
									esc_html__( 'Display the link to the taxonomy archive', 'posts-in-sidebar' ),
									$this->get_field_id( 'archive_link' ),
									$this->get_field_name( 'archive_link' ),
									$instance['archive_link']
								);
								?>

								<?php
								// ================= Which taxonomy
								pis_form_select(
									esc_html__( 'Link to the archive of', 'posts-in-sidebar' ),
									$this->get_field_id( 'link_to' ),
									$this->get_field_name( 'link_to' ),
									pis_select_archive_link(),
									$instance['link_to'],
									'',
									'pis-linkto-form'
								);
								?>

								<div class="pis-linkto-tax-name pis-boxed pis-boxed-light-blue">
									<?php
									// ================= Taxonomy name for archive link
									pis_form_input_text(
										esc_html__( 'Taxonomy name', 'posts-in-sidebar' ),
										$this->get_field_id( 'tax_name' ),
										$this->get_field_name( 'tax_name' ),
										esc_attr( $instance['tax_name'] ),
										esc_html__( 'genre', 'posts-in-sidebar' ),
										sprintf(
											// translators: %s contains some code.
											esc_html__(
												'Enter the term name of the custom taxonomy (e.g., %1$sgenre%2$s). %3$sUse this field only if you selected "Custom taxonomy" in the "Link to the archive of" dropdown menu.%4$s',
												'posts-in-sidebar'
											),
											'<code>',
											'</code>',
											'<br /><strong>',
											'</strong>'
										),
										'margin: 0; padding: 0.5em;'
									);
									?>
								</div>

							</div>

							<div class="pis-column">

								<div class="pis-linkto-term-name">
									<?php
									// ================= Taxonomy term name for archive link
									pis_form_input_text(
										esc_html__( 'Taxonomy term name', 'posts-in-sidebar' ),
										$this->get_field_id( 'tax_term_name' ),
										$this->get_field_name( 'tax_term_name' ),
										esc_attr( $instance['tax_term_name'] ),
										esc_html__( 'science', 'posts-in-sidebar' ),
										sprintf(
											// translators: %s contains some code.
											esc_html__(
												'Enter the name of the taxonomy term (e.g., %1$sscience%2$s if the taxonomy is "genre").%3$sIf you selected "Author" in "Link to the archive of" field, enter the author slug; if you selected "Category", enter the category slug, and so on.',
												'posts-in-sidebar'
											),
											'<code>',
											'</code>',
											'<br />'
										),
										'margin: 0; padding: 0.5em;'
									);
									?>

									<?php
									// ================= Automatic taxonomy term name
									pis_form_checkbox(
										esc_html__( 'Enable dynamic link in single posts and archive pages', 'posts-in-sidebar' ),
										$this->get_field_id( 'auto_term_name' ),
										$this->get_field_name( 'auto_term_name' ),
										$instance['auto_term_name'],
										esc_html__( 'When activated, this function will get the term name automatically in single posts and archive pages. The only required option is the field "Link to the archive of". If activated, the content of the field "Taxonomy term name" will be ignored in single posts and archive pages. This option will not work on post formats archives.', 'posts-in-sidebar' )
									);
									?>
								</div>

							</div>

						</div>

						<?php
						// ================= Archive link text
						pis_form_input_text(
							esc_html__( 'Use this text for archive link', 'posts-in-sidebar' ),
							$this->get_field_id( 'archive_text' ),
							$this->get_field_name( 'archive_text' ),
							esc_attr( $instance['archive_text'] ),
							// translators: %s is the name of the taxonomy for the archive page link.
							esc_html__( 'Display all posts under %s', 'posts-in-sidebar' ),
							sprintf(
								// translators: %s contains some code.
								esc_html__( 'Use %s to display the taxonomy term name.', 'posts-in-sidebar' ),
								'<code>%s</code>'
							)
						);
						?>

					</div>

				</div>

				<div class="pis-section">

					<h5 data-panel="no-posts" class="pis-widget-title"><?php esc_html_e( 'When no posts are found', 'posts-in-sidebar' ); ?></h5>

					<div class="pis-container">

							<?php
							// ================= When no posts are found
							// Text when no posts found.
							pis_form_input_text(
								esc_html__( 'Use this text when there are no posts', 'posts-in-sidebar' ),
								$this->get_field_id( 'nopost_text' ),
								$this->get_field_name( 'nopost_text' ),
								esc_attr( $instance['nopost_text'] ),
								esc_html__( 'No posts yet.', 'posts-in-sidebar' )
							);
							?>

							<?php
							// Hide the widget if no posts found.
							pis_form_checkbox(
								esc_html__( 'Completely hide the widget if no posts are found', 'posts-in-sidebar' ),
								$this->get_field_id( 'hide_widget' ),
								$this->get_field_name( 'hide_widget' ),
								$instance['hide_widget']
							);
							?>

					</div>

				</div>

			</div>

		</div>

		<!-- Styles -->
		<div class="pis-section">

			<h4 data-panel="styles" class="pis-widget-title"><?php esc_html_e( 'Styles', 'posts-in-sidebar' ); ?></h4>

			<div class="pis-container">

				<p><em><?php esc_html_e( 'This section defines the margin for each line of the widget. Leave blank if you don\'t want to add any local style.', 'posts-in-sidebar' ); ?></em></p>

				<div class="pis-column-container">

					<div class="pis-column">

						<?php
						// ================= Margin unit
						pis_form_select(
							esc_html__( 'Unit for margins', 'posts-in-sidebar' ),
							$this->get_field_id( 'margin_unit' ),
							$this->get_field_name( 'margin_unit' ),
							pis_select_margin_unit(),
							$instance['margin_unit']
						);
						?>

					</div>

					<p class="clear">
						<?php
						printf(
							// translators: %s contains some code.
							esc_html__( 'Enter here only the value without any unit, e.g. enter %1$s if you want a space of 10px or enter %2$s if you don\'t want any space.', 'posts-in-sidebar' ),
							'<code>10</code>',
							'<code>0</code>'
						);
						?>
					</p>

				</div>

				<div class="pis-column-container">

					<?php // ================= Margins ?>

					<div class="pis-column">
						<?php pis_form_input_text( esc_html__( 'Introduction bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'intro_margin' ), $this->get_field_name( 'intro_margin' ), esc_attr( $instance['intro_margin'] ) ); ?>
						<?php pis_form_input_text( esc_html__( 'Title bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'title_margin' ), $this->get_field_name( 'title_margin' ), esc_attr( $instance['title_margin'] ) ); ?>
						<?php pis_form_input_text( esc_html__( 'Image left &amp; right margin', 'posts-in-sidebar' ), $this->get_field_id( 'side_image_margin' ), $this->get_field_name( 'side_image_margin' ), esc_attr( $instance['side_image_margin'] ) ); ?>
						<?php pis_form_input_text( esc_html__( 'Image bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'bottom_image_margin' ), $this->get_field_name( 'bottom_image_margin' ), esc_attr( $instance['bottom_image_margin'] ) ); ?>
					</div>

					<div class="pis-column">
						<?php pis_form_input_text( esc_html__( 'Excerpt bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'excerpt_margin' ), $this->get_field_name( 'excerpt_margin' ), esc_attr( $instance['excerpt_margin'] ) ); ?>
						<?php pis_form_input_text( esc_html__( 'Utility bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'utility_margin' ), $this->get_field_name( 'utility_margin' ), esc_attr( $instance['utility_margin'] ) ); ?>
						<?php pis_form_input_text( esc_html__( 'Categories bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'categories_margin' ), $this->get_field_name( 'categories_margin' ), esc_attr( $instance['categories_margin'] ) ); ?>
						<?php pis_form_input_text( esc_html__( 'Tags bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'tags_margin' ), $this->get_field_name( 'tags_margin' ), esc_attr( $instance['tags_margin'] ) ); ?>
					</div>

					<div class="pis-column">
						<?php pis_form_input_text( esc_html__( 'Terms bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'terms_margin' ), $this->get_field_name( 'terms_margin' ), esc_attr( $instance['terms_margin'] ) ); ?>
						<?php pis_form_input_text( esc_html__( 'Custom field bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'custom_field_margin' ), $this->get_field_name( 'custom_field_margin' ), esc_attr( $instance['custom_field_margin'] ) ); ?>
						<?php pis_form_input_text( esc_html__( 'Archive bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'archive_margin' ), $this->get_field_name( 'archive_margin' ), esc_attr( $instance['archive_margin'] ) ); ?>
						<?php pis_form_input_text( esc_html__( 'No-posts bottom margin', 'posts-in-sidebar' ), $this->get_field_id( 'noposts_margin' ), $this->get_field_name( 'noposts_margin' ), esc_attr( $instance['noposts_margin'] ) ); ?>
					</div>

				</div>

				<!-- Custom styles -->
				<div class="pis-section">

					<h5 data-panel="custom-styles" class="pis-widget-title"><?php esc_html_e( 'Custom styles', 'posts-in-sidebar' ); ?></h5>

					<div class="pis-container">

						<p><em>
							<?php
							printf(
								// translators: %s contains some code.
								esc_html__( 'In this field you can add your own styles, for example: %s', 'posts-in-sidebar' ),
								'<code>.pis-excerpt { color: green; }</code>'
							);
							?>
							<br>
							<?php
							printf(
								// translators: %s contains some code.
								esc_html__( 'To apply a style only to elements of this widget, prefix every style with this ID selector: %s', 'posts-in-sidebar' ),
								'<code>#' . esc_attr( $this->id ) . '</code>'
							);
							?>
							<br>
							<?php
							printf(
								// translators: %s contains some code.
								esc_html__( 'For example: %s', 'posts-in-sidebar' ),
								'<pre><code>#' . esc_attr( $this->id ) . ' .pis-title { font-size: 18px !important; }</code></pre>'
							);
							?>
						</em></p>

						<?php
						// ================= Custom styles
						pis_form_textarea(
							esc_html__( 'Custom styles', 'posts-in-sidebar' ),
							$this->get_field_id( 'custom_styles' ),
							$this->get_field_name( 'custom_styles' ),
							$instance['custom_styles'],
							esc_html__( 'Enter here your CSS styles', 'posts-in-sidebar' ),
							'resize: vertical; width: 100%; height: 80px; min-height: 80px;'
						);
						?>

					</div>

				</div>

				<!-- Extras -->
				<div class="pis-section">

					<h5 data-panel="extras" class="pis-widget-title"><?php esc_html_e( 'Extras', 'posts-in-sidebar' ); ?></h5>

					<div class="pis-container">

						<?php
						// ================= Container Class
						pis_form_input_text(
							esc_html__( 'Add a global container with this CSS class', 'posts-in-sidebar' ),
							$this->get_field_id( 'container_class' ),
							$this->get_field_name( 'container_class' ),
							esc_attr( $instance['container_class'] ),
							'posts-container',
							sprintf(
								// translators: %s contains some code.
								esc_html__(
									'Enter the name of your container (for example, %1$s). The plugin will add a new %2$s container with this class. You can enter only one class and the name may contain only letters, hyphens and underscores. The new container will enclose all the widget, from the widget title to the last line.',
									'posts-in-sidebar'
								),
								'<code>my-container</code>',
								'<code>div</code>'
							)
						);
						?>

						<?php
						// ================= Type of HTML for list of posts
						pis_form_select(
							esc_html__( 'Use this type of list for the posts', 'posts-in-sidebar' ),
							$this->get_field_id( 'list_element' ),
							$this->get_field_name( 'list_element' ),
							pis_select_list_type(),
							$instance['list_element']
						);
						?>

						<?php
						// ================= Remove bullets and left space
						pis_form_checkbox(
							esc_html__( 'Try to remove the bullets and the extra left space from the list elements', 'posts-in-sidebar' ),
							$this->get_field_id( 'remove_bullets' ),
							$this->get_field_name( 'remove_bullets' ),
							$instance['remove_bullets'],
							sprintf(
								// translators: %s contains some code.
								esc_html__( 'If the plugin doesn\'t remove the bullets and/or the extra left space, you have to %1$sedit your CSS file%2$s manually.', 'posts-in-sidebar' ),
								'<a href="' . admin_url( 'theme-editor.php' ) . '" target="_blank">',
								'</a>'
							)
						);
						?>

						<?php
						// ================= Get WordPress post classes for the post.
						pis_form_checkbox(
							esc_html__( 'Add the WordPress standard classes to the post in the widget', 'posts-in-sidebar' ),
							$this->get_field_id( 'add_wp_post_classes' ),
							$this->get_field_name( 'add_wp_post_classes' ),
							$instance['add_wp_post_classes'],
							sprintf(
								// translators: %s contains some code.
								esc_html__( 'Every standard WordPress posts has a series of HTML classes, such as %s, and so on. Activating this option you can add these classes to the usual plugin\'s classes.', 'posts-in-sidebar' ),
								'<code>post</code>, <code>type-post</code>, <code>format-standard</code>, <code>category-categoryname</code>, <code>tag-tagname</code>'
							)
						);
						?>

					</div>

				</div>

			</div>

		</div>

		<!-- Cache -->
		<div class="pis-section">

			<h4 data-panel="cache" class="pis-widget-title"><?php esc_html_e( 'Cache', 'posts-in-sidebar' ); ?></h4>

			<div class="pis-container pis-2col">

				<div class="pis-column-container">

					<div class="pis-column">

						<?php
						// ================= Cache for the query
						pis_form_checkbox(
							esc_html__( 'Use a cache to serve the output', 'posts-in-sidebar' ),
							$this->get_field_id( 'cached' ),
							$this->get_field_name( 'cached' ),
							$instance['cached'],
							esc_html__(
								'This option, if activated, will increase the performance but will show the same output during the defined cache time.',
								'posts-in-sidebar'
							)
						);
						?>

					</div>

					<div class="pis-column">

						<?php
						// ================= Cache duration
						pis_form_input_text(
							esc_html__( 'The cache will be used for (in seconds)', 'posts-in-sidebar' ),
							$this->get_field_id( 'cache_time' ),
							$this->get_field_name( 'cache_time' ),
							esc_attr( $instance['cache_time'] ),
							'3600',
							sprintf(
								// translators: %s contains some code.
								esc_html__( 'For example, %s for one hour of cache. To reset the cache, enter a value different from the previously saved.', 'posts-in-sidebar' ),
								'<code>3600</code>'
							)
						);
						?>
					</div>

				</div>

			</div>

		</div>

		<!-- Debugging -->
		<div class="pis-section">

			<h4 data-panel="debugging" class="pis-widget-title"><?php esc_html_e( 'Debugging', 'posts-in-sidebar' ); ?></h4>

			<div class="pis-container">

				<p>
					<?php
					printf(
						// translators: %s contains the plugin version.
						esc_html__( 'You are using Posts in Sidebar version %s', 'posts-in-sidebar' ),
						'<strong>' . esc_attr( PIS_VERSION ) . '</strong>'
					);
					?>
				</p>

				<p>
					<?php
					printf(
						// translators: %s contains the widget id.
						esc_html__( 'The ID of this widget is: %s', 'posts-in-sidebar' ),
						'<strong>' . esc_attr( $this->id ) . '</strong>'
					);
					?>
				</p>

				<p class="pis-boxed pis-boxed-orange"><strong><?php esc_html_e( 'Use this options for debugging purposes only.', 'posts-in-sidebar' ); ?></strong> </p>

				<div class="pis-boxed pis-boxed-red"><strong><?php esc_html_e( 'Deactivate the following option only if you want to display debugging information publicly on your site.', 'posts-in-sidebar' ); ?></strong>
					<?php
					// ================= Debug: display debugging information to admins only
					pis_form_checkbox(
						esc_html__( 'Display debugging information to admins only', 'posts-in-sidebar' ),
						$this->get_field_id( 'admin_only' ),
						$this->get_field_name( 'admin_only' ),
						$instance['admin_only']
					);
					?>
				</div>

				<?php
				// ================= Debug: display the query for the widget
				pis_form_checkbox(
					esc_html__( 'Display the query for the widget', 'posts-in-sidebar' ),
					$this->get_field_id( 'debug_query' ),
					$this->get_field_name( 'debug_query' ),
					$instance['debug_query']
				);
				?>

				<?php
				// ================= Debug: display the complete set of parameters for the widget
				pis_form_checkbox(
					esc_html__( 'Display the complete set of parameters for the widget', 'posts-in-sidebar' ),
					$this->get_field_id( 'debug_params' ),
					$this->get_field_name( 'debug_params' ),
					$instance['debug_params']
				);
				?>

				<?php
				// ================= Debug: display the ID of the post just before the post title
				pis_form_checkbox(
					esc_html__( 'Display the ID of the post just before the post title', 'posts-in-sidebar' ),
					$this->get_field_id( 'debug_post_id' ),
					$this->get_field_name( 'debug_post_id' ),
					$instance['debug_post_id'],
					sprintf(
						// translators: %s contains the post ID as string.
						esc_html__( 'The ID will be displayed as %s.', 'posts-in-sidebar' ),
						'[123]'
					)
				);
				?>

			</div>

		</div>

		<div class="pis-section pis-boxed pis-boxed-light-blue">

			<h5><?php esc_html_e( 'Note for widget duplication', 'posts-in-sidebar' ); ?></h5>

			<p>
				<?php
				esc_html_e(
					'When you duplicate the widget, please reload the page, so that you can open the panels in the duplicated widget.',
					'posts-in-sidebar'
				);
				?>
			</p>
		</div>

		<?php
	}

}

/*
 * CODE IS POETRY
 */
