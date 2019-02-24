<?php
/**
 * This file contains the functions of the plugin
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
 * Queries functions.
 *******************************************************************************
 */

/**
 * Build the query based on taxonomies.
 *
 * @param  array $args      The array containing the custom parameters.
 * @return array $tax_query An array of array of parameters.
 * @since 1.29
 */
function pis_tax_query( $args ) {
	$defaults = array(
		'relation'    => '',
		'taxonomy_aa' => '',
		'field_aa'    => 'slug',
		'terms_aa'    => '',
		'operator_aa' => 'IN',
		'relation_a'  => '',
		'taxonomy_ab' => '',
		'field_ab'    => 'slug',
		'terms_ab'    => '',
		'operator_ab' => 'IN',
		'taxonomy_ba' => '',
		'field_ba'    => 'slug',
		'terms_ba'    => '',
		'operator_ba' => 'IN',
		'relation_b'  => '',
		'taxonomy_bb' => '',
		'field_bb'    => 'slug',
		'terms_bb'    => '',
		'operator_bb' => 'IN',
	);

	$args = wp_parse_args( $args, $defaults );

	if ( '' === $args['taxonomy_aa'] || '' === $args['field_aa'] || '' === $args['terms_aa'] ) {
		$tax_query = '';
	} else {
		// Convert terms into arrays.
		$args['terms_aa'] = explode( ',', preg_replace( '/\s+/', '', $args['terms_aa'] ) );

		if ( $args['terms_ab'] ) {
			$args['terms_ab'] = explode( ',', preg_replace( '/\s+/', '', $args['terms_ab'] ) );
		}
		if ( $args['terms_ba'] ) {
			$args['terms_ba'] = explode( ',', preg_replace( '/\s+/', '', $args['terms_ba'] ) );
		}
		if ( $args['terms_bb'] ) {
			$args['terms_bb'] = explode( ',', preg_replace( '/\s+/', '', $args['terms_bb'] ) );
		}

		// Let's figure out the tax_query to build.
		if ( $args['taxonomy_aa'] && ! $args['taxonomy_ab'] && ! $args['taxonomy_ba'] && ! $args['taxonomy_bb'] ) {
			$tax_query = array(
				array(
					'taxonomy' => $args['taxonomy_aa'],
					'field'    => $args['field_aa'],
					'terms'    => $args['terms_aa'], // This must be an array.
					'operator' => $args['operator_aa'],
				),
			);
		} elseif ( $args['taxonomy_aa'] && ! $args['taxonomy_ab'] && $args['taxonomy_ba'] && ! $args['taxonomy_bb'] && ! empty( $args['relation'] ) ) {
			$tax_query = array(
				'relation' => $args['relation'],
				array(
					'taxonomy' => $args['taxonomy_aa'],
					'field'    => $args['field_aa'],
					'terms'    => $args['terms_aa'], // This must be an array.
					'operator' => $args['operator_aa'],
				),
				array(
					'taxonomy' => $args['taxonomy_ba'],
					'field'    => $args['field_ba'],
					'terms'    => $args['terms_ba'], // This must be an array.
					'operator' => $args['operator_ba'],
				),
			);
		} elseif ( $args['taxonomy_aa'] && $args['taxonomy_ab'] && $args['taxonomy_ba'] && ! $args['taxonomy_bb'] && ! empty( $args['relation'] ) ) {
			$tax_query = array(
				'relation' => $args['relation'],
				array(
					'relation_a' => $args['relation_a'],
					array(
						'taxonomy' => $args['taxonomy_aa'],
						'field'    => $args['field_aa'],
						'terms'    => $args['terms_aa'], // This must be an array.
						'operator' => $args['operator_aa'],
					),
					array(
						'taxonomy' => $args['taxonomy_ab'],
						'field'    => $args['field_ab'],
						'terms'    => $args['terms_ab'], // This must be an array.
						'operator' => $args['operator_ab'],
					),
				),
				array(
					'taxonomy' => $args['taxonomy_ba'],
					'field'    => $args['field_ba'],
					'terms'    => $args['terms_ba'], // This must be an array.
					'operator' => $args['operator_ba'],
				),
			);
		} elseif ( $args['taxonomy_aa'] && ! $args['taxonomy_ab'] && $args['taxonomy_ba'] && $args['taxonomy_bb'] && ! empty( $args['relation'] ) ) {
			$tax_query = array(
				'relation' => $args['relation'],
				array(
					'taxonomy' => $args['taxonomy_aa'],
					'field'    => $args['field_aa'],
					'terms'    => $args['terms_aa'], // This must be an array.
					'operator' => $args['operator_aa'],
				),
				array(
					'relation_b' => $args['relation_b'],
					array(
						'taxonomy' => $args['taxonomy_ba'],
						'field'    => $args['field_ba'],
						'terms'    => $args['terms_ba'], // This must be an array.
						'operator' => $args['operator_ba'],
					),
					array(
						'taxonomy' => $args['taxonomy_bb'],
						'field'    => $args['field_bb'],
						'terms'    => $args['terms_bb'], // This must be an array.
						'operator' => $args['operator_bb'],
					),
				),
			);
		} elseif ( $args['taxonomy_aa'] && $args['taxonomy_ab'] && $args['taxonomy_ba'] && $args['taxonomy_bb'] && ! empty( $args['relation'] ) ) {
			$tax_query = array(
				'relation' => $args['relation'],
				array(
					'relation_a' => $args['relation_a'],
					array(
						'taxonomy' => $args['taxonomy_aa'],
						'field'    => $args['field_aa'],
						'terms'    => $args['terms_aa'], // This must be an array.
						'operator' => $args['operator_aa'],
					),
					array(
						'taxonomy' => $args['taxonomy_ab'],
						'field'    => $args['field_ab'],
						'terms'    => $args['terms_ab'], // This must be an array.
						'operator' => $args['operator_ab'],
					),
				),
				array(
					'relation_b' => $args['relation_b'],
					array(
						'taxonomy' => $args['taxonomy_ba'],
						'field'    => $args['field_ba'],
						'terms'    => $args['terms_ba'], // This must be an array.
						'operator' => $args['operator_ba'],
					),
					array(
						'taxonomy' => $args['taxonomy_bb'],
						'field'    => $args['field_bb'],
						'terms'    => $args['terms_bb'], // This must be an array.
						'operator' => $args['operator_bb'],
					),
				),
			);
		}
	}

	if ( isset( $tax_query ) ) {
		return $tax_query;
	} else {
		return '';
	}
}

/**
 * Build the query based on custom fields.
 *
 * @param  array $args The array containing the custom parameters.
 * @return array An array of array of parameters.
 * @since 4.0
 */
function pis_meta_query( $args ) {
	$defaults = array(
		'mq_relation'   => '',
		'mq_key_aa'     => '',
		'mq_value_aa'   => '',
		'mq_compare_aa' => '',
		'mq_type_aa'    => '',
		'mq_relation_a' => '',
		'mq_key_ab'     => '',
		'mq_value_ab'   => '',
		'mq_compare_ab' => '',
		'mq_type_ab'    => '',
		'mq_key_ba'     => '',
		'mq_value_ba'   => '',
		'mq_compare_ba' => '',
		'mq_type_ba'    => '',
		'mq_relation_b' => '',
		'mq_key_bb'     => '',
		'mq_value_bb'   => '',
		'mq_compare_bb' => '',
		'mq_type_bb'    => '',
	);

	$args = wp_parse_args( $args, $defaults );

	// If the first meta key (i.e. "Custom field key A1") is empty, return an empty string and stop the function.
	if ( '' === $args['mq_key_aa'] ) {
		return '';
	}

	// If `mq_compare_xx` is one of `IN`, `NOT IN`, `BETWEEN`, `NOT BETWEEN`, then make `mq_value_xx` an array.
	// See https://codex.wordpress.org/Class_Reference/WP_Query#Custom_Field_Parameters.
	$compare_array = array( 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN' );
	if ( strpos( $args['mq_value_aa'], ',' ) && in_array( $args['mq_compare_aa'], $compare_array, true ) ) {
		$args['mq_value_aa'] = explode( ',', preg_replace( '/\s+/', '', $args['mq_value_aa'] ) );
	}
	if ( $args['mq_value_ab'] && strpos( $args['mq_value_ab'], ',' ) && in_array( $args['mq_compare_ab'], $compare_array, true ) ) {
		$args['mq_value_ab'] = explode( ',', preg_replace( '/\s+/', '', $args['mq_value_ab'] ) );
	}
	if ( $args['mq_value_ba'] && strpos( $args['mq_value_ba'], ',' ) && in_array( $args['mq_compare_ba'], $compare_array, true ) ) {
		$args['mq_value_ba'] = explode( ',', preg_replace( '/\s+/', '', $args['mq_value_ba'] ) );
	}
	if ( $args['mq_value_bb'] && strpos( $args['mq_value_bb'], ',' ) && in_array( $args['mq_compare_bb'], $compare_array, true ) ) {
		$args['mq_value_bb'] = explode( ',', preg_replace( '/\s+/', '', $args['mq_value_bb'] ) );
	}

	// We have "Custom field key A1".
	if ( $args['mq_key_aa'] && ! $args['mq_key_ab'] && ! $args['mq_key_ba'] && ! $args['mq_key_bb'] ) {
		$meta_query = array(
			array(
				'key'     => $args['mq_key_aa'],
				'value'   => $args['mq_value_aa'], // This could be an array.
				'compare' => $args['mq_compare_aa'],
				'type'    => $args['mq_type_aa'],
			),
		);
	}
	// We have "Custom field key A1" + "Custom field key B1".
	elseif ( $args['mq_key_aa'] && ! $args['mq_key_ab'] && $args['mq_key_ba'] && ! $args['mq_key_bb'] && ! empty( $args['mq_relation'] ) ) {
		$meta_query = array(
			'relation' => $args['mq_relation'],
			array(
				'key'     => $args['mq_key_aa'],
				'value'   => $args['mq_value_aa'], // This could be an array.
				'compare' => $args['mq_compare_aa'],
				'type'    => $args['mq_type_aa'],
			),
			array(
				'key'     => $args['mq_key_ba'],
				'value'   => $args['mq_value_ba'], // This could be an array.
				'compare' => $args['mq_compare_ba'],
				'type'    => $args['mq_type_ba'],
			),
		);
	}
	// We have "Custom field key A1" + "Custom field key A2" + "Custom field key B1".
	elseif ( $args['mq_key_aa'] && $args['mq_key_ab'] && $args['mq_key_ba'] && ! $args['mq_key_bb'] && ! empty( $args['mq_relation'] ) ) {
		$meta_query = array(
			'relation' => $args['mq_relation'],
			array(
				'relation' => $args['mq_relation_a'],
				array(
					'key'     => $args['mq_key_aa'],
					'value'   => $args['mq_value_aa'], // This could be an array.
					'compare' => $args['mq_compare_aa'],
					'type'    => $args['mq_type_aa'],
				),
				array(
					'key'     => $args['mq_key_ab'],
					'value'   => $args['mq_value_ab'], // This could be an array.
					'compare' => $args['mq_compare_ab'],
					'type'    => $args['mq_type_ab'],
				),
			),
			array(
				'key'     => $args['mq_key_ba'],
				'value'   => $args['mq_value_ba'], // This could be an array.
				'compare' => $args['mq_compare_ba'],
				'type'    => $args['mq_type_ba'],
			),
		);
	}
	// We have "Custom field key A1" + "Custom field key B1" + "Custom field key B2".
	elseif ( $args['mq_key_aa'] && ! $args['mq_key_ab'] && $args['mq_key_ba'] && $args['mq_key_bb'] && ! empty( $args['mq_relation'] ) ) {
		$meta_query = array(
			'relation' => $args['mq_relation'],
			array(
				'key'     => $args['mq_key_aa'],
				'value'   => $args['mq_value_aa'], // This could be an array.
				'compare' => $args['mq_compare_aa'],
				'type'    => $args['mq_type_aa'],
			),
			array(
				'relation' => $args['mq_relation_b'],
				array(
					'key'     => $args['mq_key_ba'],
					'value'   => $args['mq_value_ba'], // This could be an array.
					'compare' => $args['mq_compare_ba'],
					'type'    => $args['mq_type_ba'],
				),
				array(
					'key'     => $args['mq_key_bb'],
					'value'   => $args['mq_value_bb'], // This could be an array.
					'compare' => $args['mq_compare_bb'],
					'type'    => $args['mq_type_bb'],
				),
			),
		);
	}
	// We have "Custom field key A1" + "Custom field key A2" + "Custom field key B1" + "Custom field key B2".
	elseif ( $args['mq_key_aa'] && $args['mq_key_ab'] && $args['mq_key_ba'] && $args['mq_key_bb'] && ! empty( $args['mq_relation'] ) ) {
		$meta_query = array(
			'relation' => $args['mq_relation'],
			array(
				'relation' => $args['mq_relation_a'],
				array(
					'key'     => $args['mq_key_aa'],
					'value'   => $args['mq_value_aa'], // This could be an array.
					'compare' => $args['mq_compare_aa'],
					'type'    => $args['mq_type_aa'],
				),
				array(
					'key'     => $args['mq_key_ab'],
					'value'   => $args['mq_value_ab'], // This could be an array.
					'compare' => $args['mq_compare_ab'],
					'type'    => $args['mq_type_ab'],
				),
			),
			array(
				'relation' => $args['mq_relation_b'],
				array(
					'key'     => $args['mq_key_ba'],
					'value'   => $args['mq_value_ba'], // This could be an array.
					'compare' => $args['mq_compare_ba'],
					'type'    => $args['mq_type_ba'],
				),
				array(
					'key'     => $args['mq_key_bb'],
					'value'   => $args['mq_value_bb'], // This could be an array.
					'compare' => $args['mq_compare_bb'],
					'type'    => $args['mq_type_bb'],
				),
			),
		);
	}

	if ( isset( $meta_query ) ) {
		return $meta_query;
	} else {
		return '';
	}
}

/**
 * Get posts by most recent comments.
 *
 * @param  string  $post_type The post type.
 * @param  integer $limit     The number of post IDs to retrieve.
 * @param  string  $order     The order parameter.
 *                            Accepted values: 'desc' (default), 'asc'.
 *
 * @return array   $post_ids  The array with the IDs of the post.
 *
 * @since 4.1
 */
function pis_get_posts_by_recent_comments( $post_type = 'post', $limit = 10, $order = 'desc' ) {
	global $wpdb;

	/*
	 * $wpdb properties for database prefix:
	 *     $wpdb->base_prefix = Get the prefix defined in wp-config.php;
	 *     $wpdb->prefix      = Get the prefix for the current site (useful in a multisite installation).
	 * @see https://codex.wordpress.org/Class_Reference/wpdb#Class_Variables
	 */
	$posts_table = $wpdb->prefix . 'posts'; // Will output, for example, 'wp_posts'.

	$number = (int) apply_filters( 'pis_get_posts_by_recent_comments', $limit );

	$sql = "SELECT $posts_table.*,
	coalesce(
		(
			select max(comment_date)
			from $wpdb->comments wpc
			where wpc.comment_post_id = $posts_table.id
		),
		$posts_table.post_date
	) as mcomment_date
	from $wpdb->posts $posts_table
	where post_type = '$post_type'
	and post_status = 'publish'
	order by mcomment_date $order
	limit $number";

	$post_ids = $wpdb->get_col( $sql );

	return $post_ids;
}
