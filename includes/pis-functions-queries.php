<?php
/**
 * This file contains the query-related functions of the plugin
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
		return apply_filters( 'pis_meta_query', $meta_query );
	} else {
		return '';
	}
}

/**
 * Get posts by most recent comments.
 *
 * This function will retrieve the defined number of posts based on the most
 * recent comments. Since the function operates on comments and since a post can
 * have multiple comments, first we must get all comments. Then for each
 * comment, we store the relevant post IDs. Finally, we remove duplicated IDs of
 * the returned posts and, only for an aesthetic reason, we reindex the elements
 * of the array.
 *
 * A note. We can't slice here the array containing the post IDs (i.e., we can't
 * return only the number of IDs defined by the user in the widget) because at
 * this moment we don't know if the user wants to add other params to the query,
 * for example posts by category, tag, and so on.
 *
 * @param  string  $post_type Post type or array of post types to retrieve
 *                            affiliated comments for. Pass 'any' to match any value.
 *                            Default: 'post'.
 * @param  integer $limit     The number of post IDs to retrieve.
 *                            Default: 10.
 * @param  string  $order     How to order retrieved comments.
 *                            Accepts: 'ASC', 'DESC'.
 *                            Default: 'DESC'.
 *
 * @return array   $post_ids  The array with the IDs of the post or an empty array.
 *
 * @since 4.1
 * @since 4.16.0 Function rewritten from scratch.
 */
function pis_get_posts_by_recent_comments( $post_type = 'post', $order = 'DESC' ) {
	$post_ids = array();

	$args = array(
		'post_type'   => $post_type,
		'order'       => $order,
		'status'      => 'approve',
		'post_status' => 'publish',
	);

	$recent_comments = get_comments( $args );

	if ( $recent_comments ) {
		foreach ( $recent_comments as $comment ) {
			$post_ids[] = $comment->comment_post_ID;
		}
	}

	$post_ids = array_unique( $post_ids );
	$post_ids = array_values( $post_ids );

	return $post_ids;
}
