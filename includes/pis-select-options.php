<?php
/**
 * This file contains the functions to get the options for select in the widget
 *
 * @package PostsInSidebar
 * @since 4.8.0
 */

/**
 * Get options for post types.
 *
 * @since 4.8.0
 * @return array $options The array with options to get post types.
 */
function pis_select_post_types() {
	$options = array(
		array(
			'value' => 'any',
			'desc'  => esc_html__( 'Any', 'posts-in-sidebar' ),
		),
	);

	$post_types = (array) get_post_types( '', 'objects' );
	foreach ( $post_types as $post_type ) {
		$options[] = array(
			'value' => $post_type->name,
			'desc'  => $post_type->labels->singular_name,
		);
	}

	return $options;
}

/**
 * Get options for authors.
 *
 * @since 4.8.0
 * @return array $options The array with options to get authors.
 */
function pis_select_authors() {
	$options = array(
		array(
			'value' => '',
			'desc'  => esc_html__( 'Any', 'posts-in-sidebar' ),
		),
	);
	$authors = (array) get_users( 'who=authors' ); // If set to 'authors', only authors (user level greater than 0) will be returned.
	foreach ( $authors as $author ) {
		$options[] = array(
			'value' => $author->user_nicename,
			'desc'  => $author->display_name,
		);
	}

	return $options;
}

/**
 * Get options for post formats.
 *
 * @since 4.8.0
 * @return array $options The array with options to get post formats.
 */
function pis_select_post_formats() {
	$options = array(
		array(
			'value' => '',
			'desc'  => esc_html__( 'Any', 'posts-in-sidebar' ),
		),
	);

	$post_formats = get_terms( 'post_format' );

	if ( ! $post_formats ) {
		return $options;
	}

	foreach ( $post_formats as $post_format ) {
		$options[] = array(
			'value' => $post_format->slug,
			'desc'  => $post_format->name,
		);
	}

	return $options;
}

/**
 * Get options for post status.
 *
 * @since 4.8.0
 * @return array $options The array with options to get post status.
 */
function pis_select_post_status() {
	$options  = array(
		array(
			'value' => 'any',
			'desc'  => esc_html__( 'Any', 'posts-in-sidebar' ),
		),
	);
	$statuses = get_post_stati( array(), 'objects' );
	foreach ( $statuses as $single_status ) {
		$options[] = array(
			'value' => $single_status->name,
			'desc'  => $single_status->label,
		);
	}

	return $options;
}

/**
 * Get options for post with or without password.
 *
 * @since 4.8.0
 * @return array $options The array with options to get post with/without password.
 */
function pis_select_post_password() {
	$options = array(
		array(
			'value' => 'null',
			'desc'  => esc_html__( 'With and without password', 'posts-in-sidebar' ),
		),
		array(
			'value' => 'true',
			'desc'  => esc_html__( 'With password', 'posts-in-sidebar' ),
		),
		array(
			'value' => 'false',
			'desc'  => esc_html__( 'Without password', 'posts-in-sidebar' ),
		),
	);

	return $options;
}

/**
 * Get options for order by.
 *
 * @since 4.8.0
 * @return array $options The array with options for order by.
 */
function pis_select_order_by() {
	$options = array(
		'none'            => array(
			'value' => 'none',
			'desc'  => esc_html__( 'None', 'posts-in-sidebar' ),
		),
		'id'              => array(
			'value' => 'id',
			'desc'  => esc_html__( 'ID', 'posts-in-sidebar' ),
		),
		'author'          => array(
			'value' => 'author',
			'desc'  => esc_html__( 'Author', 'posts-in-sidebar' ),
		),
		'title'           => array(
			'value' => 'title',
			'desc'  => esc_html__( 'Title', 'posts-in-sidebar' ),
		),
		'name'            => array(
			'value' => 'name',
			'desc'  => esc_html__( 'Name (post slug)', 'posts-in-sidebar' ),
		),
		'type'            => array(
			'value' => 'type',
			'desc'  => esc_html__( 'Post type', 'posts-in-sidebar' ),
		),
		'date'            => array(
			'value' => 'date',
			'desc'  => esc_html__( 'Date', 'posts-in-sidebar' ),
		),
		'modified'        => array(
			'value' => 'modified',
			'desc'  => esc_html__( 'Modified', 'posts-in-sidebar' ),
		),
		'parent'          => array(
			'value' => 'parent',
			'desc'  => esc_html__( 'Parent', 'posts-in-sidebar' ),
		),
		'rand'            => array(
			'value' => 'rand',
			'desc'  => esc_html__( 'Random', 'posts-in-sidebar' ),
		),
		'comment_count'   => array(
			'value' => 'comment_count',
			'desc'  => esc_html__( 'Comment count', 'posts-in-sidebar' ),
		),
		'menu_order'      => array(
			'value' => 'menu_order',
			'desc'  => esc_html__( 'Menu order', 'posts-in-sidebar' ),
		),
		'meta_value'      => array(
			'value' => 'meta_value',
			'desc'  => esc_html__( 'Meta value', 'posts-in-sidebar' ),
		),
		'meta_value_num'  => array(
			'value' => 'meta_value_num',
			'desc'  => esc_html__( 'Meta value number', 'posts-in-sidebar' ),
		),
		'post__in'        => array(
			'value' => 'post__in',
			'desc'  => esc_html__( 'Preserve ID order', 'posts-in-sidebar' ),
		),
		'post_parent__in' => array(
			'value' => 'post_parent__in',
			'desc'  => esc_html__( 'Preserve post parent order', 'posts-in-sidebar' ),
		),
	);

	return $options;
}

/**
 * Get options for order by (with relevance option).
 *
 * @since 4.8.0
 * @return array $options The array with options for order by (with relevance option).
 */
function pis_select_order_by_relevance() {
	$options = array(
		'none'            => array(
			'value' => 'none',
			'desc'  => esc_html__( 'None', 'posts-in-sidebar' ),
		),
		'id'              => array(
			'value' => 'id',
			'desc'  => esc_html__( 'ID', 'posts-in-sidebar' ),
		),
		'author'          => array(
			'value' => 'author',
			'desc'  => esc_html__( 'Author', 'posts-in-sidebar' ),
		),
		'title'           => array(
			'value' => 'title',
			'desc'  => esc_html__( 'Title', 'posts-in-sidebar' ),
		),
		'name'            => array(
			'value' => 'name',
			'desc'  => esc_html__( 'Name (post slug)', 'posts-in-sidebar' ),
		),
		'type'            => array(
			'value' => 'type',
			'desc'  => esc_html__( 'Post type', 'posts-in-sidebar' ),
		),
		'date'            => array(
			'value' => 'date',
			'desc'  => esc_html__( 'Date', 'posts-in-sidebar' ),
		),
		'modified'        => array(
			'value' => 'modified',
			'desc'  => esc_html__( 'Modified', 'posts-in-sidebar' ),
		),
		'parent'          => array(
			'value' => 'parent',
			'desc'  => esc_html__( 'Parent', 'posts-in-sidebar' ),
		),
		'rand'            => array(
			'value' => 'rand',
			'desc'  => esc_html__( 'Random', 'posts-in-sidebar' ),
		),
		'comment_count'   => array(
			'value' => 'comment_count',
			'desc'  => esc_html__( 'Comment count', 'posts-in-sidebar' ),
		),
		'relevance'       => array(
			'value' => 'relevance',
			'desc'  => esc_html__( 'Relevance (when searching)', 'posts-in-sidebar' ),
		),
		'menu_order'      => array(
			'value' => 'menu_order',
			'desc'  => esc_html__( 'Menu order', 'posts-in-sidebar' ),
		),
		'meta_value'      => array(
			'value' => 'meta_value',
			'desc'  => esc_html__( 'Meta value', 'posts-in-sidebar' ),
		),
		'meta_value_num'  => array(
			'value' => 'meta_value_num',
			'desc'  => esc_html__( 'Meta value number', 'posts-in-sidebar' ),
		),
		'post__in'        => array(
			'value' => 'post__in',
			'desc'  => esc_html__( 'Preserve ID order', 'posts-in-sidebar' ),
		),
		'post_parent__in' => array(
			'value' => 'post_parent__in',
			'desc'  => esc_html__( 'Preserve post parent order', 'posts-in-sidebar' ),
		),
	);

	return $options;
}

/**
 * Get options for order.
 *
 * @since 4.8.0
 * @return array $options The array with options for order.
 */
function pis_select_order() {
	$options = array(
		'asc'  => array(
			'value' => 'ASC',
			'desc'  => esc_html__( 'Ascending', 'posts-in-sidebar' ),
		),
		'desc' => array(
			'value' => 'DESC',
			'desc'  => esc_html__( 'Descending', 'posts-in-sidebar' ),
		),
	);

	return $options;
}

/**
 * Get options for taxonomies.
 *
 * @since 4.8.0
 * @return array $options The array with options for taxonomies.
 */
function pis_select_taxonomies() {
	$options = array(
		'empty' => array(
			'value' => '',
			'desc'  => '',
		),
	);

	$args = array(
		'public' => true,
	);

	$registered_taxonomies = get_taxonomies( $args, 'object' );

	foreach ( $registered_taxonomies as $registered_taxonomy ) {
		$options[] = array(
			'value' => $registered_taxonomy->name,
			'desc'  => $registered_taxonomy->labels->singular_name,
		);
	}

	return $options;
}

/**
 * Get options for column relation.
 *
 * @since 4.8.0
 * @return array $options The array with options for columns.
 */
function pis_select_relation() {
	$options = array(
		'empty' => array(
			'value' => '',
			'desc'  => '',
		),
		'and'   => array(
			'value' => 'AND',
			'desc'  => 'AND',
		),
		'or'    => array(
			'value' => 'OR',
			'desc'  => 'OR',
		),
	);

	return $options;
}

/**
 * Get options for field.
 *
 * @since 4.8.0
 * @return array $options The array with options for field.
 */
function pis_select_field() {
	$options = array(
		'empty'   => array(
			'value' => '',
			'desc'  => '',
		),
		'term_id' => array(
			'value' => 'term_id',
			'desc'  => esc_html__( 'Term ID', 'posts-in-sidebar' ),
		),
		'slug'    => array(
			'value' => 'slug',
			'desc'  => esc_html__( 'Slug', 'posts-in-sidebar' ),
		),
		'name'    => array(
			'value' => 'name',
			'desc'  => esc_html__( 'Name', 'posts-in-sidebar' ),
		),
	);

	return $options;
}

/**
 * Get options for operator.
 *
 * @since 4.8.0
 * @return array $options The array with options for operator.
 */
function pis_select_operator() {
	$options = array(
		'empty'  => array(
			'value' => '',
			'desc'  => '',
		),
		'in'     => array(
			'value' => 'IN',
			'desc'  => 'IN',
		),
		'not_in' => array(
			'value' => 'NOT IN',
			'desc'  => 'NOT IN',
		),
		'and'    => array(
			'value' => 'AND',
			'desc'  => 'AND',
		),
	);

	return $options;
}

/**
 * Get options for date column.
 *
 * @since 4.8.0
 * @return array $options The array with options for date column.
 */
function pis_select_date_column() {
	$options = array(
		'empty'             => array(
			'value' => '',
			'desc'  => '',
		),
		'post_date'         => array(
			'value' => 'post_date',
			'desc'  => esc_html__( 'Post date', 'posts-in-sidebar' ),
		),
		'post_date_gmt'     => array(
			'value' => 'post_date_gmt',
			'desc'  => esc_html__( 'Post date GMT', 'posts-in-sidebar' ),
		),
		'post_modified'     => array(
			'value' => 'post_modified',
			'desc'  => esc_html__( 'Post modified', 'posts-in-sidebar' ),
		),
		'post_modified_gmt' => array(
			'value' => 'post_modified_gmt',
			'desc'  => esc_html__( 'Post modified GMT', 'posts-in-sidebar' ),
		),
	);

	return $options;
}

/**
 * Get options for type of date.
 *
 * @since 4.8.0
 * @return array $options The array with options for type of date.
 */
function pis_select_date_type() {
	$options = array(
		'empty'  => array(
			'value' => '',
			'desc'  => '',
		),
		'year'   => array(
			'value' => 'year',
			'desc'  => esc_html__( 'Years', 'posts-in-sidebar' ),
		),
		'month'  => array(
			'value' => 'month',
			'desc'  => esc_html__( 'Months', 'posts-in-sidebar' ),
		),
		'week'   => array(
			'value' => 'week',
			'desc'  => esc_html__( 'Weeks', 'posts-in-sidebar' ),
		),
		'day'    => array(
			'value' => 'day',
			'desc'  => esc_html__( 'Days', 'posts-in-sidebar' ),
		),
		'hour'   => array(
			'value' => 'hour',
			'desc'  => esc_html__( 'Hours', 'posts-in-sidebar' ),
		),
		'minute' => array(
			'value' => 'minute',
			'desc'  => esc_html__( 'Minutes', 'posts-in-sidebar' ),
		),
		'second' => array(
			'value' => 'second',
			'desc'  => esc_html__( 'seconds', 'posts-in-sidebar' ),
		),
	);

	return $options;
}

/**
 * Get options for compare.
 *
 * @since 4.8.0
 * @return array $options The array with options for compare.
 */
function pis_select_compare() {
	$options = array(
		'empty'         => array(
			'value' => '',
			'desc'  => '',
		),
		'equal'         => array(
			'value' => '=',
			'desc'  => '=',
		),
		'not_equal'     => array(
			'value' => '!=',
			'desc'  => '!=',
		),
		'greater'       => array(
			'value' => '>',
			'desc'  => '>',
		),
		'greater_equal' => array(
			'value' => '>=',
			'desc'  => '>=',
		),
		'lower'         => array(
			'value' => '<',
			'desc'  => '<',
		),
		'lower_equal'   => array(
			'value' => '<=',
			'desc'  => '<=',
		),
		'like'          => array(
			'value' => 'LIKE',
			'desc'  => 'LIKE',
		),
		'not_like'      => array(
			'value' => 'NOT LIKE',
			'desc'  => 'NOT LIKE',
		),
		'in'            => array(
			'value' => 'IN',
			'desc'  => 'IN',
		),
		'not_in'        => array(
			'value' => 'NOT IN',
			'desc'  => 'NOT IN',
		),
		'between'       => array(
			'value' => 'BETWEEN',
			'desc'  => 'BETWEEN',
		),
		'not_between'   => array(
			'value' => 'NOT BETWEEN',
			'desc'  => 'NOT BETWEEN',
		),
		'exists'        => array(
			'value' => 'EXISTS',
			'desc'  => 'EXISTS',
		),
		'not_exists'    => array(
			'value' => 'NOT EXISTS',
			'desc'  => 'NOT EXISTS',
		),
	);

	return $options;
}

/**
 * Get options for custom field type.
 *
 * @since 4.8.0
 * @return array $options The array with options for custom field type.
 */
function pis_select_cf_type() {
	$options = array(
		'empty'    => array(
			'value' => '',
			'desc'  => '',
		),
		'numeric'  => array(
			'value' => 'NUMERIC',
			'desc'  => 'NUMERIC',
		),
		'binary'   => array(
			'value' => 'BINARY',
			'desc'  => 'BINARY',
		),
		'char'     => array(
			'value' => 'CHAR',
			'desc'  => 'CHAR',
		),
		'date'     => array(
			'value' => 'DATE',
			'desc'  => 'DATE',
		),
		'datetime' => array(
			'value' => 'DATETIME',
			'desc'  => 'DATETIME',
		),
		'decimal'  => array(
			'value' => 'DECIMAL',
			'desc'  => 'DECIMAL',
		),
		'signed'   => array(
			'value' => 'SIGNED',
			'desc'  => 'SIGNED',
		),
		'time'     => array(
			'value' => 'TIME',
			'desc'  => 'TIME',
		),
		'unsigned' => array(
			'value' => 'UNSIGNED',
			'desc'  => 'UNSIGNED',
		),
	);

	return $options;
}

/**
 * Get options for length unit.
 *
 * @since 4.8.0
 * @return array $options The array with options for length unit.
 */
function pis_select_length_unit() {
	$options = array(
		'words' => array(
			'value' => 'words',
			'desc'  => esc_html__( 'Words', 'posts-in-sidebar' ),
		),
		'chars' => array(
			'value' => 'chars',
			'desc'  => esc_html__( 'Characters', 'posts-in-sidebar' ),
		),
	);

	return $options;
}

/**
 * Get options for type of HTML tag for post titles.
 *
 * @since 4.8.2
 * @return array $options The array with the options for the HTML tag for post title.
 */
function pis_select_html_title_type_of() {
	$options = array(
		'p'  => array(
			'value' => 'p',
			'desc'  => 'P',
		),
		'h1' => array(
			'value' => 'h1',
			'desc'  => 'H1',
		),
		'h2' => array(
			'value' => 'h2',
			'desc'  => 'H2',
		),
		'h3' => array(
			'value' => 'h3',
			'desc'  => 'H3',
		),
		'h4' => array(
			'value' => 'h4',
			'desc'  => 'H4',
		),
		'h5' => array(
			'value' => 'h5',
			'desc'  => 'H5',
		),
		'h6' => array(
			'value' => 'h6',
			'desc'  => 'H6',
		),
		'h7' => array(
			'value' => 'h7',
			'desc'  => 'H7',
		),
	);

	return $options;
}

/**
 * Get options for type of text.
 *
 * @since 4.8.0
 * @return array $options The array with options for type of text.
 */
function pis_select_text_type() {
	$options = array(
		'full_content'   => array(
			'value' => 'full_content',
			'desc'  => esc_html__( 'The full content', 'posts-in-sidebar' ),
		),
		'rich_content'   => array(
			'value' => 'rich_content',
			'desc'  => esc_html__( 'The rich content', 'posts-in-sidebar' ),
		),
		'content'        => array(
			'value' => 'content',
			'desc'  => esc_html__( 'The simple text', 'posts-in-sidebar' ),
		),
		'more_excerpt'   => array(
			'value' => 'more_excerpt',
			'desc'  => esc_html__( 'The excerpt up to "more" tag', 'posts-in-sidebar' ),
		),
		'excerpt'        => array(
			'value' => 'excerpt',
			'desc'  => esc_html__( 'The excerpt', 'posts-in-sidebar' ),
		),
		'only_read_more' => array(
			'value' => 'only_read_more',
			'desc'  => esc_html__( 'Display only the Read more link', 'posts-in-sidebar' ),
		),
		'none'           => array(
			'value' => 'none',
			'desc'  => esc_html__( 'Do not show any text', 'posts-in-sidebar' ),
		),
	);

	return $options;
}

/**
 * Get options for image size.
 *
 * @since 4.8.0
 * @return array $options The array with options for image size.
 */
function pis_select_image_size() {
	$options = array();
	$sizes   = (array) get_intermediate_image_sizes();
	$sizes[] = 'full';
	foreach ( $sizes as $size ) {
		$options[] = array(
			'value' => $size,
			'desc'  => $size,
		);
	}

	return $options;
}

/**
 * Get options for image align.
 *
 * @since 4.8.0
 * @return array $options The array with options for image align.
 */
function pis_select_image_align() {
	$options = array(
		'nochange' => array(
			'value' => 'nochange',
			'desc'  => esc_html__( 'Do not change', 'posts-in-sidebar' ),
		),
		'left'     => array(
			'value' => 'left',
			'desc'  => esc_html__( 'Left', 'posts-in-sidebar' ),
		),
		'right'    => array(
			'value' => 'right',
			'desc'  => esc_html__( 'Right', 'posts-in-sidebar' ),
		),
		'center'   => array(
			'value' => 'center',
			'desc'  => esc_html__( 'Center', 'posts-in-sidebar' ),
		),

	);

	return $options;
}

/**
 * Get options for Gravatar position.
 *
 * @since 4.8.0
 * @return array $options The array with options for Gravatar position.
 */
function pis_select_gravatar_position() {
	$options = array(
		'next_title'  => array(
			'value' => 'next_title',
			'desc'  => esc_html__( 'Next to the post title', 'posts-in-sidebar' ),
		),
		'next_post'   => array(
			'value' => 'next_post',
			'desc'  => esc_html__( 'Next to the post content', 'posts-in-sidebar' ),
		),
		'next_author' => array(
			'value' => 'next_author',
			'desc'  => esc_html__( 'Next to the author name', 'posts-in-sidebar' ),
		),
	);

	return $options;
}

/**
 * Get options for meta.
 *
 * @since 4.8.0
 * @return array $options The array with options for meta.
 */
function pis_select_meta() {
	$options = array(
		'empty' => array(
			'value' => '',
			'desc'  => '',
		),
	);

	$metas = (array) pis_meta();
	foreach ( $metas as $meta ) {
		if ( is_protected_meta( $meta, 'post' ) ) {
			continue;
		}
		$options[] = array(
			'value' => $meta,
			'desc'  => $meta,
		);
	}

	/**
	 * Filters the list of custom fields.
	 *
	 * Using this filter, the user can add hidden (protected) custom fields
	 * to the list in the display section of custom fields in the widget admin.
	 *
	 * @since 4.9.0
	 */
	return apply_filters( 'pis_selected_metas', $options );
}

/**
 * Get options for archive link.
 *
 * @since 4.8.0
 * @return array $options The array with options for archive link.
 */
function pis_select_archive_link() {
	$options = array(
		/* Author */
		'author'   => array(
			'value' => 'author',
			'desc'  => esc_html__( 'Author', 'posts-in-sidebar' ),
		),
		/* Category */
		'category' => array(
			'value' => 'category',
			'desc'  => esc_html__( 'Category', 'posts-in-sidebar' ),
		),
		/* Tag */
		'tag'      => array(
			'value' => 'tag',
			'desc'  => esc_html__( 'Tag', 'posts-in-sidebar' ),
		),
	);

	/* Custom post type */
	$custom_post_types = get_post_types(
		array(
			'_builtin' => false,
		)
	);
	if ( $custom_post_types ) {
		$options[] = array(
			'value' => 'custom_post_type',
			'desc'  => esc_html__( 'Custom post type', 'posts-in-sidebar' ),
		);
	}

	/* Custom taxonomy */
	$custom_taxonomy = get_taxonomies(
		array(
			'public'   => true,
			'_builtin' => false,
		)
	);
	if ( $custom_taxonomy ) {
		$options[] = array(
			'value' => 'custom_taxonomy',
			'desc'  => esc_html__( 'Custom taxonomy', 'posts-in-sidebar' ),
		);
	}

	/* Post format */
	$post_formats = get_terms( 'post_format' );
	if ( $post_formats ) {
		foreach ( $post_formats as $post_format ) {
			$options[] = array(
				'value' => $post_format->slug,
				'desc'  => sprintf(
					// translators: This is the name of the post format.
					esc_html__( 'Post format: %s', 'posts-in-sidebar' ),
					$post_format->name
				),
			);
		}
	}

	return $options;
}

/**
 * Get options for margin unit.
 *
 * @since 4.8.0
 * @return array $options The array with options for margin unit.
 */
function pis_select_margin_unit() {
	$options = array(
		'px'  => array(
			'value' => 'px',
			'desc'  => 'px',
		),
		'%'   => array(
			'value' => '%',
			'desc'  => '%',
		),
		'em'  => array(
			'value' => 'em',
			'desc'  => 'em',
		),
		'rem' => array(
			'value' => 'rem',
			'desc'  => 'rem',
		),
	);

	return $options;
}

/**
 * Get options for list type.
 *
 * @since 4.8.0
 * @return array $options The array with options for list type.
 */
function pis_select_list_type() {
	$options = array(
		'ul' => array(
			'value' => 'ul',
			'desc'  => esc_html__( 'Unordered list', 'posts-in-sidebar' ),
		),
		'ol' => array(
			'value' => 'ol',
			'desc'  => esc_html__( 'Ordered list', 'posts-in-sidebar' ),
		),
	);

	return $options;
}
