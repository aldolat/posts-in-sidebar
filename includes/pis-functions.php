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
 * Queries section
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

	if ( '' === $args['mq_key_aa'] || '' === $args['mq_value_aa'] ) {
		$meta_query = '';
	} else {
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

		if ( $args['mq_key_aa'] && ! $args['mq_key_ab'] && ! $args['mq_key_ba'] && ! $args['mq_key_bb'] ) {
			$meta_query = array(
				array(
					'key'     => $args['mq_key_aa'],
					'value'   => $args['mq_value_aa'], // This could be an array.
					'compare' => $args['mq_compare_aa'],
					'type'    => $args['mq_type_aa'],
				),
			);
		} elseif ( $args['mq_key_aa'] && ! $args['mq_key_ab'] && $args['mq_key_ba'] && ! $args['mq_key_bb'] && ! empty( $args['mq_relation'] ) ) {
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
		} elseif ( $args['mq_key_aa'] && $args['mq_key_ab'] && $args['mq_key_ba'] && ! $args['mq_key_bb'] && ! empty( $args['mq_relation'] ) ) {
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
		} elseif ( $args['mq_key_aa'] && ! $args['mq_key_ab'] && $args['mq_key_ba'] && $args['mq_key_bb'] && ! empty( $args['mq_relation'] ) ) {
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
		} elseif ( $args['mq_key_aa'] && $args['mq_key_ab'] && $args['mq_key_ba'] && $args['mq_key_bb'] && ! empty( $args['mq_relation'] ) ) {
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

/*
 * Display section
 ******************************************************************************
 */

/**
 * Returns the title of the post.
 *
 * @param array $args {
 *     The array containing the custom parameters.
 *     @type string  $title_margin      The margin for the title.
 *     @type string  $margin_unit       The measure unit for the margin.
 *                                      Accepted values:
 *                                      px (default), %, em, rem
 *     @type boolean $gravatar_display  If the Gravatar should be displayed.
 *     @type string  $gravatar_position The position of the Gravatar.
 *                                      Accepted values:
 *                                      next_author (default), next_title, next_post
 *     @type string  $gravatar_author   The ID of the post author.
 *     @type integer $gravatar_size     The size of the displayed Gravatar.
 *     @type string  $gravatar_default  The URL of the default Gravatar image.
 *     @type boolean $link_on_title     If the title should be linked to the post.
 *     @type boolean $arrow             If an HTML arrow should be added to the title.
 *     @type integer $title_length      The length of the post title.
 *                                      Accepted values:
 *                                      0 (default, meaning no shortening), any positive integer.
 *     @type boolean $title_hellipsis   If an horizontal ellipsis should be added after the shortened title.
 * }
 *
 * @return The HTML paragraph with the title.
 * @since 3.8.4
 * @since 4.4.0 Added `$title_length` option.
 * @since 4.4.0 Added `$title_hellipsis` option.
 */
function pis_the_title( $args ) {
	$defaults = array(
		'title_margin'      => '',
		'margin_unit'       => '',
		'gravatar_display'  => false,
		'gravatar_position' => '',
		'gravatar_author'   => '',
		'gravatar_size'     => '32',
		'gravatar_default'  => '',
		'link_on_title'     => true,
		'arrow'             => false,
		'title_length'      => 0,
		'title_length_unit' => 'words',
		'title_hellipsis'   => true,
	);

	$args = wp_parse_args( $args, $defaults );

	$output = '<p ' . pis_paragraph( $args['title_margin'], $args['margin_unit'], 'pis-title', 'pis_title_class' ) . '>';

	// The Gravatar.
	if ( $args['gravatar_display'] && 'next_title' === $args['gravatar_position'] ) {
		$output .= pis_get_gravatar( array(
			'author'  => $args['gravatar_author'],
			'size'    => $args['gravatar_size'],
			'default' => $args['gravatar_default'],
		) );
	}

	if ( $args['link_on_title'] ) {
		$output .= '<a ' . pis_class( 'pis-title-link', apply_filters( 'pis_title_link_class', '' ), false ) . ' href="' . get_permalink() . '" rel="bookmark">';
	}

	if ( 0 === $args['title_length'] ) {
		$output .= get_the_title();
	} else {
		$args['title_hellipsis'] ? $title_hellip = '&hellip;' : $title_hellip = '';
		if ( 'words' === $args['title_length_unit'] ) {
			$output .= wp_trim_words( get_the_title(), $args['title_length'], $title_hellip );
		} else {
			if ( strlen( get_the_title() ) <= $args['title_length'] ) {
				$title_hellip = '';
			}
			$output .= rtrim( mb_substr( get_the_title(), 0, $args['title_length'], get_option( 'blog_charset' ) ) ) . $title_hellip;
		}
	}

	if ( $args['arrow'] ) {
		$output .= pis_arrow();
	}

	if ( $args['link_on_title'] ) {
		$output .= '</a>';
	}

	$output .= '</p>';

	return $output;
}

/**
 * Returns the categories of the post.
 *
 * @param array $args {
 *     The array containing the custom parameters.
 *     @type string $post_id           The ID of the post.
 *     @type string $categ_sep         The separator for the categories.
 *     @type string $categories_margin The margin for the categories.
 *     @type string $margin_unit       The measure unit for the margin.
 *                                     Accepted values:
 *                                     px (default), %, em, rem
 *     @type string $categ_text        The leading text for the categories.
 * }
 *
 * @return The HTML paragraph with the categories.
 * @since 3.8.4
 */
function pis_the_categories( $args ) {
	$defaults = array(
		'post_id'           => '',
		'categ_sep'         => ',',
		'categories_margin' => '',
		'margin_unit'       => 'px',
		'categ_text'        => esc_html__( 'Category:', 'posts-in-sidebar' ),
	);

	$args = wp_parse_args( $args, $defaults );

	$output = '';

	$list_of_categories = get_the_term_list( $args['post_id'], 'category', '', $args['categ_sep'] . ' ', '' );

	if ( $list_of_categories ) {
		$output = '<p ' . pis_paragraph( $args['categories_margin'], $args['margin_unit'], 'pis-categories-links', 'pis_categories_class' ) . '>';
		if ( $args['categ_text'] ) {
			$output .= $args['categ_text'] . '&nbsp;';
		}
		$output .= apply_filters( 'pis_categories_list', $list_of_categories );
		$output .= '</p>';
	}

	return $output;
}

/**
 * Return the tags of the post.
 *
 * @param array $args {
 *     The array containing the custom parameters.
 *     @type string $post_id      The ID of the post.
 *     @type string $hashtag      The symbol to be used as hashtag.
 *     @type string $tag_sep      The separator for the tags.
 *     @type string $tags_margin  The margin for the tags.
 *     @type string $margin_unit  The measure unit for the margin.
 *                                Accepted values:
 *                                px (default), %, em, rem
 *     @type string $tags_text    The leading text for the tags.
 * }
 * @return The HTML paragraph with the tags.
 * @uses pis_paragraph()
 * @since 3.8.4
 */
function pis_the_tags( $args ) {
	$defaults = array(
		'post_id'     => '',
		'hashtag'     => '#',
		'tag_sep'     => '',
		'tags_margin' => '',
		'margin_unit' => 'px',
		'tags_text'   => esc_html__( 'Tags:', 'posts-in-sidebar' ),
	);

	$args = wp_parse_args( $args, $defaults );

	$output = '';

	$list_of_tags = get_the_term_list( $args['post_id'], 'post_tag', $args['hashtag'], $args['tag_sep'] . ' ' . $args['hashtag'], '' );

	if ( $list_of_tags ) {
		$output .= '<p ' . pis_paragraph( $args['tags_margin'], $args['margin_unit'], 'pis-tags-links', 'pis_tags_class' ) . '>';
		if ( $args['tags_text'] ) {
			$output .= $args['tags_text'] . '&nbsp;';
		}
		$output .= apply_filters( 'pis_tags_list', $list_of_tags );
		$output .= '</p>';
	}

	return $output;
}

/**
 * Return the custom fields of the post.
 *
 * @param array $args {
 *     The array containing the custom parameters.
 *     @type string  $post_id             The ID of the post.
 *     @type boolean $custom_field_all    If the user want to display all the custom fields of the post.
 *     @type string  $meta                The post meta.
 *     @type string  $custom_field_txt    The leading text for custom fields.
 *     @type boolean $custom_field_key    If the user want to display the custom field key.
 *     @type string  $custom_field_sep    The separator between meta key and value.
 *     @type string  $custom_field_count  The custom field content length (in characters).
 *     @type string  $custom_field_hellip The separator between meta key and value.
 *     @type string  $custom_field_margin The custom field bottom margin.
 *     @type string  $margin_unit         The unit for margin.
 * }
 * @since 3.8.4
 */
function pis_custom_field( $args ) {
	$defaults = array(
		'post_id'             => '',
		'custom_field_all'    => false,
		'meta'                => '',
		'custom_field_txt'    => '',
		'custom_field_key'    => false,
		'custom_field_sep'    => ':',
		'custom_field_count'  => '',
		'custom_field_hellip' => '&hellip;',
		'custom_field_margin' => '',
		'margin_unit'         => 'px',
	);

	$args = wp_parse_args( $args, $defaults );

	$post_id             = $args['post_id'];
	$custom_field_all    = $args['custom_field_all'];
	$meta                = $args['meta'];
	$custom_field_txt    = $args['custom_field_txt'];
	$custom_field_key    = $args['custom_field_key'];
	$custom_field_sep    = $args['custom_field_sep'];
	$custom_field_count  = $args['custom_field_count'];
	$custom_field_hellip = $args['custom_field_hellip'];
	$custom_field_margin = $args['custom_field_margin'];
	$margin_unit         = $args['margin_unit'];

	$output = '';

	// The leading text for the custom fields.
	if ( $custom_field_txt ) {
		$cf_text = '<span class="pis-custom-field-text-before">' . rtrim( $custom_field_txt ) . '</span> ';
	} else {
		$cf_text = '';
	}

	// If the user want to display all the custom fields of the post.
	if ( $custom_field_all ) {
		$the_custom_fields = get_post_custom( $post_id );
		if ( $the_custom_fields ) {
			foreach ( $the_custom_fields as $cf_key => $cf_value ) {
				// Make sure to avoid custom fields starting with _ (an underscore).
				if ( '_' !== substr( $cf_key, 0, 1 ) ) {
					foreach ( $cf_value as $k => $cf_v ) {

						// If we have to display a text before the custom field.
						if ( $custom_field_key ) {
							$key = '<span class="pis-custom-field-key">' . $cf_key . '</span><span class="pis-custom-field-divider">' . $custom_field_sep . '</span>';
						} else {
							$key = '';
						}

						// If we have to reduce the length of the custom field value.
						if ( ! empty( $custom_field_count ) ) {
							if ( $custom_field_count > strlen( $cf_v ) ) {
								$cf_h = '';
							} else {
								$cf_h = $custom_field_hellip;
							}
							$cf_text_value = rtrim( mb_substr( $cf_v, 0, $custom_field_count, get_option( 'blog_charset' ) ) ) . $cf_h;
						} else {
							$cf_text_value = $cf_v;
						}

						// Build the custom field value line.
						$cf_value = '<span class="pis-custom-field-value">' . $cf_text_value . '</span>';

						// Create the class from the key of the custom field key.
						$pis_cf_key_class = ' pis-' . preg_replace( '/[\s]+/', '-', trim( $cf_key, ' -' ) );

						// Build the final output.
						$output .= '<p ' . pis_paragraph( $custom_field_margin, $margin_unit, 'pis-custom-field' . $pis_cf_key_class, 'pis_custom_fields_class' ) . '>';
						$output .= $cf_text . $key . $cf_value;
						$output .= '</p>';
					}
				}
			}
		}
	} else {
		$the_custom_field = get_post_meta( $post_id, $meta, false );
		if ( $the_custom_field ) {
			if ( $custom_field_key ) {
				$key = '<span class="pis-custom-field-key">' . $meta . '</span><span class="pis-custom-field-divider">' . $custom_field_sep . '</span>';
			} else {
				$key = '';
			}
			if ( ! empty( $custom_field_count ) ) {
				if ( $custom_field_count > strlen( $the_custom_field[0] ) ) {
					$custom_field_hellip = '';
				}
				/* It was originally: `$cf_text_value = wp_trim_words( $the_custom_field[0], $custom_field_count, $custom_field_hellip );` */
				$cf_text_value = rtrim( mb_substr( $the_custom_field[0], 0, $custom_field_count, get_option( 'blog_charset' ) ) ) . $custom_field_hellip;
			} else {
				if ( isset( $the_custom_field[0] ) ) {
					$cf_text_value = $the_custom_field[0];
				} else {
					$cf_text_value = '';
				}
			}
			$cf_value = '<span class="pis-custom-field-value">' . $cf_text_value . '</span>';

			$output .= '<p ' . pis_paragraph( $custom_field_margin, $margin_unit, 'pis-custom-field ' . preg_replace( '/[\s]+/', '-', trim( $custom_field_key, ' -' ) ), 'pis_custom_fields_class' ) . '>';
			$output .= $cf_text . $key . $cf_value;
			$output .= '</p>';
		}
	}

	return $output;
}

/**
 * Add the thumbnail of the post.
 *
 * @param array $args {
 *    The array of parameters.
 *
 *    @type string  image_align         Alignment of the image. Accepts 'no_change', 'left', 'right', 'center'. Default 'no_change'.
 *    @type string  side_image_margin   The left/right margin for the image. Default null.
 *    @type string  bottom_image_margin The left/right margin for the image. Default null.
 *    @type string  margin_unit         The margin unit. Accepts 'px', '%', 'em', 'rem'. Default 'px'.
 *    @type string  pis_query           The query containing the post. Default empty.
 *    @type string  image_size          The size of the image. Default 'thumbnail'.
 *    @type boolean thumb_wrap          If the image should be wrapped in a HTML p element. Default false.
 *    @type string  custom_image_url    The URL of the custom thumbnail. Default empty.
 *    @type boolean custom_img_no_thumb If the custom image should be used only if the post has not a featured image. Default true.
 *    @type string  post_type           The post type. Default 'post'.
 *    @type string  image_link          The URL to a custom address. Default empty.
 *    @type boolean image_link_to_post  If the thumbnail should be linked to the post. Default true.
 * }
 * @since 1.18
 * @return The HTML for the thumbnail.
 */
function pis_the_thumbnail( $args ) {
	$defaults = array(
		'image_align'         => 'no_change',
		'side_image_margin'   => null,
		'bottom_image_margin' => null,
		'margin_unit'         => 'px',
		'pis_query'           => '',
		'image_size'          => 'thumbnail',
		'thumb_wrap'          => false,
		'custom_image_url'    => '',
		'custom_img_no_thumb' => true,
		'post_type'           => 'post',
		'image_link'          => '',
		'image_link_to_post'  => true,
	);

	$args = wp_parse_args( $args, $defaults );

	$image_align         = $args['image_align'];
	$side_image_margin   = $args['side_image_margin'];
	$bottom_image_margin = $args['bottom_image_margin'];
	$margin_unit         = $args['margin_unit'];
	$pis_query           = $args['pis_query'];
	$image_size          = $args['image_size'];
	$thumb_wrap          = $args['thumb_wrap'];
	$custom_image_url    = $args['custom_image_url'];
	$custom_img_no_thumb = $args['custom_img_no_thumb'];
	$post_type           = $args['post_type'];
	$image_link          = $args['image_link'];
	$image_link_to_post  = $args['image_link_to_post'];

	if ( $thumb_wrap ) {
		$open_wrap  = '<p class="pis-thumbnail">';
		$close_wrap = '</p>';
	} else {
		$open_wrap  = '';
		$close_wrap = '';
	}

	switch ( $image_align ) {
		case 'left':
			$image_class = 'alignleft ';
			$image_style = '';
			if ( ! is_null( $side_image_margin ) || ! is_null( $bottom_image_margin ) ) {
				$image_style = ' style="display: inline; float: left; margin-right: ' . $side_image_margin . $margin_unit . '; margin-bottom: ' . $bottom_image_margin . $margin_unit . ';"';
				$image_style = str_replace( ' margin-right: px;', '', $image_style );
				$image_style = str_replace( ' margin-bottom: px;', '', $image_style );
			}
			break;
		case 'right':
			$image_class = 'alignright ';
			$image_style = '';
			if ( ! is_null( $side_image_margin ) || ! is_null( $bottom_image_margin ) ) {
				$image_style = ' style="display: inline; float: right; margin-left: ' . $side_image_margin . $margin_unit . '; margin-bottom: ' . $bottom_image_margin . $margin_unit . ';"';
				$image_style = str_replace( ' margin-left: px;', '', $image_style );
				$image_style = str_replace( ' margin-bottom: px;', '', $image_style );
			}
			break;
		case 'center':
			$image_class = 'aligncenter ';
			$image_style = '';
			if ( ! is_null( $bottom_image_margin ) ) {
				$image_style = ' style="margin-bottom: ' . $bottom_image_margin . $margin_unit . ';"';
			}
			break;
		default:
			$image_class = '';
			$image_style = '';
	}

	$output = $open_wrap;

	if ( $image_link_to_post ) {
		// Figure out if a custom link for the featured image has been set.
		if ( $image_link ) {
			$the_image_link = $image_link;
		} else {
			$the_image_link = get_permalink();
		}
		$output .= '<a ' . pis_class( 'pis-thumbnail-link', apply_filters( 'pis_thumbnail_link_class', '' ), false ) . ' href="' . esc_url( wp_strip_all_tags( $the_image_link ) ) . '" rel="bookmark">';
	}

	/**
	 * If the post type is an attachment (an image, or any other attachment),
	 * the construct is different.
	 *
	 * @since 1.28
	 */
	if ( 'attachment' === $post_type ) {
		$final_image_class = rtrim( "attachment-$image_size pis-thumbnail-img " . $image_class . apply_filters( 'pis_thumbnail_class', '' ) );
		$image_html        = wp_get_attachment_image(
			$pis_query->post->ID,
			$image_size,
			false,
			array( 'class' => $final_image_class )
		);
	} else {
		$final_image_class = rtrim( 'pis-thumbnail-img ' . $image_class . apply_filters( 'pis_thumbnail_class', '' ) );
		/**
		 * If the post has not a post-thumbnail AND a custom image URL is defined (in this case the custom image will be used only if the post has not a featured image)
		 * OR
		 * if custom image URL is defined AND the custom image should be used in every case (in this case the custom image will be used for all posts, even those who already have a featured image).
		 */
		if ( ( ! has_post_thumbnail() && $custom_image_url ) || ( $custom_image_url && ! $custom_img_no_thumb ) ) {
			$image_html = '<img src="' . esc_url( $custom_image_url ) . '" alt="" class="' . $final_image_class . '">';
		} else {
			$image_html = get_the_post_thumbnail(
				$pis_query->post->ID,
				$image_size,
				array( 'class' => $final_image_class )
			);
		}
	}

	$output .= str_replace( '<img', '<img' . $image_style, $image_html );

	if ( $image_link_to_post ) {
		$output .= '</a>';
	}

	$output .= $close_wrap;

	return $output;
}

/**
 * Add the text of the post in form of excerpt, full post, and so on.
 *
 * @since 1.18
 * @param array $args The array containing the custom parameters.
 * @return The HTML for the text of the post.
 * @uses pis_break_text()
 * @uses pis_more_arrow()
 */
function pis_the_text( $args ) {
	$defaults = array(
		'excerpt'         => 'excerpt',
		'pis_query'       => '',
		'exc_length'      => 20,
		'exc_length_unit' => 'words',
		'the_more'        => esc_html__( 'Read more&hellip;', 'posts-in-sidebar' ),
		'exc_arrow'       => false,
	);

	$args = wp_parse_args( $args, $defaults );

	$excerpt         = $args['excerpt'];
	$pis_query       = $args['pis_query'];
	$exc_length      = $args['exc_length'];
	$exc_length_unit = $args['exc_length_unit'];
	$the_more        = $args['the_more'];
	$exc_arrow       = $args['exc_arrow'];

	$output = '';

	/*
		"Full content"   = the content of the post as displayed in the page.
		"Rich content"   = the content with inline images, titles and more (shortcodes will be executed).
		"Content"        = the full text of the content, whitout any ornament (shortcodes will be stripped).
		"More excerpt"   = the excerpt up to the point of the "more" tag (inserted by the user, shortcodes will be stripped).
		"Excerpt"        = the excerpt as defined by the user or generated by WordPress (shortcodes will be stripped).
		"Only Read more" = no excerpt, only the Read more link
	*/
	switch ( $excerpt ) :

		case 'full_content':
			/**
			 * Filter the post content. If not filtered, shortcodes (and other things) will not be executed.
			 * See https://codex.wordpress.org/Function_Reference/get_the_content
			 */
			$output = apply_filters( 'the_content', get_the_content() );
			break;

		case 'rich_content':
			$content = $pis_query->post->post_content;
			// Honor any paragraph break.
			$content = pis_break_text( $content );
			$content = do_shortcode( $content );
			$output  = apply_filters( 'pis_rich_content', $content );
			break;

		case 'content':
			// Remove shortcodes.
			$content = strip_shortcodes( $pis_query->post->post_content );
			// remove any HTML tag.
			$content = wp_kses( $content, array() );
			// Honor any paragraph break.
			$content = pis_break_text( $content );
			$output  = apply_filters( 'pis_content', $content );
			break;

		case 'more_excerpt':
			$excerpt_text = strip_shortcodes( $pis_query->post->post_content );
			$testformore  = strpos( $excerpt_text, '<!--more-->' );
			if ( $testformore ) {
				$excerpt_text = substr( $excerpt_text, 0, $testformore );
			} else {
				if ( 'words' === $exc_length_unit ) {
					$excerpt_text = wp_trim_words( $excerpt_text, $exc_length, '&hellip;' );
				} else {
					$excerpt_text = substr( $excerpt_text, 0, $exc_length ) . '&hellip;';
				}
			}
			$output = apply_filters( 'pis_more_excerpt_text', $excerpt_text ) . pis_more_arrow( $the_more, false, $exc_arrow, false, true );
			break;

		case 'excerpt':
			/**
			 * Check if the Relevanssi plugin is active and restore the user-defined excerpt in place of the Relevanssi-generated excerpt.
			 *
			 * @see https://wordpress.org/support/topic/issue-with-excerpts-when-using-relevanssi-search
			 * @since 1.26
			 */
			if ( function_exists( 'relevanssi_do_excerpt' ) && isset( $pis_query->post->original_excerpt ) ) {
				$pis_query->post->post_excerpt = $pis_query->post->original_excerpt;
			}

			// If we have a user-defined excerpt...
			if ( $pis_query->post->post_excerpt ) {
				// Honor any paragraph break.
				$user_excerpt = pis_break_text( $pis_query->post->post_excerpt );
				$output       = apply_filters( 'pis_user_excerpt', $user_excerpt ) . pis_more_arrow( $the_more, false, $exc_arrow, false, true );
				$output       = trim( $output );
			} else { // ... else generate an excerpt.
				$excerpt_text = wp_strip_all_tags( strip_shortcodes( $pis_query->post->post_content ) );
				$no_the_more  = false;
				$hellip       = '&hellip;';
				if ( 'words' === $exc_length_unit ) {
					if ( count( explode( ' ', $excerpt_text ) ) <= $exc_length ) {
						$no_the_more = true;
					}
					$excerpt_text = wp_trim_words( $excerpt_text, $exc_length, $hellip );
				} else {
					if ( strlen( $excerpt_text ) <= $exc_length ) {
						$no_the_more = true;
						$hellip      = '';
					}
					$excerpt_text = rtrim( mb_substr( $excerpt_text, 0, $exc_length, get_option( 'blog_charset' ) ) ) . $hellip;
				}
				$output = apply_filters( 'pis_excerpt_text', $excerpt_text );
				$output = trim( $output );
				if ( $output ) {
					$output .= pis_more_arrow( $the_more, $no_the_more, $exc_arrow, false, true );
				}
			}
			break;

		case 'only_read_more':
			$excerpt_text = '';
			$output       = apply_filters( 'pis_only_read_more', $excerpt_text ) . pis_more_arrow( $the_more, false, $exc_arrow, false, true );
			$output       = trim( $output );

	endswitch; // Close The text.

	return $output;
}

/**
 * Add the utilities section: author, date of the post and comments.
 *
 * @param array $args {
 *    The array of parameters.
 *
 *    @type boolean display_author    If display the post's outhor. Default false.
 *    @type boolean display_date      If display the post's date. Default false.
 *    @type boolean display_mod_date  If display the modification date of the post. Default false.
 *    @type boolean comments          If display comments number. Default false.
 *    @type integer utility_margin    The CSS margin value for the section. Default null value.
 *    @type string  margin_unit       The margin unit for $utility_margin. Accepts 'px', '%', 'em', 'rem'. Default 'px'.
 *    @type string  author_text       The text to be prepended before the author's name. Default 'By'.
 *    @type boolean linkify_author    If link the author name to the posts' archive of the author. Default false.
 *    @type string  utility_sep       The separator between the elements of the section. Default '|'.
 *    @type string  date_text         The text to be prepended before the date. Default 'Published on'.
 *    @type boolean linkify_date      If link the date name to the posts. Default false.
 *    @type string  mod_date_text     The text to be prepended before the modification date. Default 'Modified on'.
 *    @type boolean linkify_mod_date  If link the modification date to the post. Default false.
 *    @type string  comments_text     The text to be prepended before the comments number. Default 'Comments:'.
 *    @type string  pis_post_id       The ID of the post. Default empy.
 *    @type boolean link_to_comments  If link the comments text to the comments form. Default true.
 *    @type boolean gravatar_display  If display the Gravatar. Default false.
 *    @type string  gravatar_position The position for the Gravatar. Accepts 'next_title', 'next_post', 'next_author'. Default empty.
 *    @type string  gravatar_author   The ID of the post's author. Default empty value.
 *    @type integer gravatar_size     The size of the Gravatar. Default 32.
 *    @type string  gravatar_default  The default image for Gravatar when unavailable. Default empty string.
 * }
 * @since 1.18
 * @return The HTML for the section.
 * @uses pis_paragraph()
 * @uses pis_class()
 * @uses pis_get_comments_number()
 */
function pis_utility_section( $args ) {
	$defaults = array(
		'display_author'    => false,
		'display_date'      => false,
		'display_time'      => false,
		'display_mod_date'  => false,
		'display_mod_time'  => false,
		'comments'          => false,
		'utility_margin'    => null,
		'margin_unit'       => 'px',
		'author_text'       => esc_html__( 'By', 'posts-in-sidebar' ),
		'linkify_author'    => false,
		'utility_sep'       => '|',
		'date_text'         => esc_html__( 'Published on', 'posts-in-sidebar' ),
		'linkify_date'      => false,
		'mod_date_text'     => esc_html__( 'Modified on', 'posts-in-sidebar' ),
		'linkify_mod_date'  => false,
		'comments_text'     => esc_html__( 'Comments:', 'posts-in-sidebar' ),
		'pis_post_id'       => '',
		'link_to_comments'  => true,
		'gravatar_display'  => false,
		'gravatar_position' => '',
		'gravatar_author'   => '',
		'gravatar_size'     => 32,
		'gravatar_default'  => '',
	);

	$args = wp_parse_args( $args, $defaults );

	$display_author    = $args['display_author'];
	$display_date      = $args['display_date'];
	$display_time      = $args['display_time'];
	$display_mod_date  = $args['display_mod_date'];
	$display_mod_time  = $args['display_mod_time'];
	$comments          = $args['comments'];
	$utility_margin    = $args['utility_margin'];
	$margin_unit       = $args['margin_unit'];
	$author_text       = $args['author_text'];
	$linkify_author    = $args['linkify_author'];
	$utility_sep       = $args['utility_sep'];
	$date_text         = $args['date_text'];
	$linkify_date      = $args['linkify_date'];
	$mod_date_text     = $args['mod_date_text'];
	$linkify_mod_date  = $args['linkify_mod_date'];
	$comments_text     = $args['comments_text'];
	$pis_post_id       = $args['pis_post_id'];
	$link_to_comments  = $args['link_to_comments'];
	$gravatar_display  = $args['gravatar_display'];
	$gravatar_position = $args['gravatar_position'];
	$gravatar_author   = $args['gravatar_author'];
	$gravatar_size     = $args['gravatar_size'];
	$gravatar_default  = $args['gravatar_default'];

	$output = '';

	if ( $display_author || $display_date || $display_mod_date || $comments ) {
		$output .= '<p ' . pis_paragraph( $utility_margin, $margin_unit, 'pis-utility', 'pis_utility_class' ) . '>';
	}

	/* The Gravatar */
	if ( $gravatar_display && 'next_author' === $gravatar_position ) {
		$output .= pis_get_gravatar( array(
			'author'  => $gravatar_author,
			'size'    => $gravatar_size,
			'default' => $gravatar_default,
		) );
	}

	/* The author */
	if ( $display_author ) {
		$output .= '<span ' . pis_class( 'pis-author', apply_filters( 'pis_author_class', '' ), false ) . '>';
		if ( $author_text ) {
			$output .= $author_text . ' ';
		}
		if ( $linkify_author ) {
			$author_link = get_author_posts_url( get_the_author_meta( 'ID' ) );
			$output     .= '<a ' . pis_class( 'pis-author-link', apply_filters( 'pis_author_link_class', '' ), false ) . ' href="' . $author_link . '" rel="author">';
			$output     .= get_the_author();
			$output     .= '</a>';
		} else {
			$output .= get_the_author();
		}
		$output .= '</span>';
	}

	/* The date */
	if ( $display_date ) {
		if ( $display_author ) {
			$output .= '<span ' . pis_class( 'pis-separator', apply_filters( 'pis_separator_class', '' ), false ) . '> ' . $utility_sep . ' </span>';
		}
		$output .= '<span ' . pis_class( 'pis-date', apply_filters( 'pis_date_class', '' ), false ) . '>';
		if ( $date_text ) {
			$output .= $date_text . ' ';
		}
		if ( $display_time ) {
			// translators: %s is the time of the post.
			$post_time = ' <span class="' . pis_class( 'pis-time', apply_filters( 'pis_time_class', '' ), false ) . '">' . sprintf( esc_html_x( 'at %s', '%s is the time of the post.', 'posts-in-sidebar' ), get_the_time() ) . '</span>';
		} else {
			$post_time = '';
		}
		if ( $linkify_date ) {
			$output .= '<a ' . pis_class( 'pis-date-link', apply_filters( 'pis_date_link_class', '' ), false ) . ' href="' . get_permalink() . '" rel="bookmark">';
			$output .= get_the_date() . $post_time;
			$output .= '</a>';
		} else {
			$output .= get_the_date() . $post_time;
		}
		$output .= '</span>';
	}

	/**
	 * The modification date.
	 * When publishing a new post, WordPress stores two dates:
	 * - the creation date into `post_date` database column;
	 * - the modification date into `post_modified` database column.
	 * and the two dates and times are the same.
	 * In this situation, in order to figure out if a post has been modified
	 * after its publication, we have to compare the times (not simply the dates).
	 */
	if ( $display_mod_date && get_the_modified_time() !== get_the_time() ) {
		if ( $display_author || $display_date ) {
			$output .= '<span ' . pis_class( 'pis-separator', apply_filters( 'pis_separator_class', '' ), false ) . '> ' . $utility_sep . ' </span>';
		}
		$output .= '<span ' . pis_class( 'pis-mod-date', apply_filters( 'pis_mod_date_class', '' ), false ) . '>';
		if ( $mod_date_text ) {
			$output .= $mod_date_text . ' ';
		}
		if ( $display_mod_time ) {
			// translators: %s is the time of the post modified.
			$post_mod_time = ' <span class="' . pis_class( 'pis-mod-time', apply_filters( 'pis_mod_time_class', '' ), false ) . '">' . sprintf( esc_html_x( 'at %s', '%s is the time of the post modified.', 'posts-in-sidebar' ), get_the_modified_time() ) . '</span>';
		} else {
			$post_mod_time = '';
		}
		if ( $linkify_mod_date ) {
			$output .= '<a ' . pis_class( 'pis-mod-date-link', apply_filters( 'pis_mod_date_link_class', '' ), false ) . ' href="' . get_permalink() . '" rel="bookmark">';
			$output .= get_the_modified_date() . $post_mod_time;
			$output .= '</a>';
		} else {
			$output .= get_the_modified_date() . $post_mod_time;
		}
		$output .= '</span>';
	}

	/* The comments */
	if ( ! post_password_required() ) {
		if ( $comments ) {
			if ( $display_author || $display_date || $display_mod_date ) {
				$output .= '<span ' . pis_class( 'pis-separator', apply_filters( 'pis_separator_class', '' ), false ) . '> ' . $utility_sep . ' </span>';
			}
			$output .= '<span ' . pis_class( 'pis-comments', apply_filters( 'pis_comments_class', '' ), false ) . '>';
			if ( $comments_text ) {
				$output .= $comments_text . ' ';
			}
			$output .= pis_get_comments_number( $pis_post_id, $link_to_comments );
			$output .= '</span>';
		}
	}

	if ( $display_author || $display_date || $display_mod_date || $comments ) {
		$output .= '</p>';
	}

	return $output;
}

/**
 * Return the custom taxonomies of the current post.
 *
 * @since 1.29
 * @param array $args The array containing the custom parameters.
 * @see https://codex.wordpress.org/Function_Reference/get_the_terms#Get_terms_for_all_custom_taxonomies
 */
function pis_custom_taxonomies_terms_links( $args ) {
	$defaults = array(
		'post_id'      => '',
		'term_hashtag' => '',
		'term_sep'     => ',',
		'terms_margin' => null,
		'margin_unit'  => 'px',
	);

	$args = wp_parse_args( $args, $defaults );

	$post_id      = $args['post_id'];
	$term_hashtag = $args['term_hashtag'];
	$term_sep     = $args['term_sep'];
	$terms_margin = $args['terms_margin'];
	$margin_unit  = $args['margin_unit'];

	// Get post by post id.
	$post = get_post( $post_id );

	// Get post type by post.
	$post_type = $post->post_type;

	// Get post type taxonomies.
	$taxonomies = get_object_taxonomies( $post_type, 'objects' );

	$output = '';

	foreach ( $taxonomies as $taxonomy_slug => $taxonomy ) {
		// Exclude the standard WordPress 'category' and 'post_tag' taxonomies otherwise we'll have a duplicate in the front-end.
		if ( 'category' !== $taxonomy_slug && 'post_tag' !== $taxonomy_slug ) {
			// Get the terms related to post.
			$list_of_terms = get_the_term_list( $post_id, $taxonomy_slug, $term_hashtag, $term_sep . ' ' . $term_hashtag, '' );
			if ( ! ( is_wp_error( $list_of_terms ) ) && ( $list_of_terms ) ) {
				$output .= '<p ' . pis_paragraph( $terms_margin, $margin_unit, 'pis-terms-links pis-' . $taxonomy_slug, 'pis_terms_class' ) . '>';
				$output .= '<span class="pis-tax-name">' . $taxonomy->label . '</span>: ' . apply_filters( 'pis_terms_list', $list_of_terms );
				$output .= '</p>';
			}
		}
	}

	return $output;
}

/**
 * Returns the HTML for the comments link.
 *
 * @param integer $pis_post_id The ID of the post.
 * @param boolean $link If the output is to be wrapped into a link to comments.
 * @since 3.0
 */
function pis_get_comments_number( $pis_post_id, $link ) {
	$num_comments = get_comments_number( $pis_post_id ); // get_comments_number returns only a numeric value.

	if ( 0 === $num_comments && ! comments_open( $pis_post_id ) ) {
		$output = esc_html__( 'Comments are closed.', 'posts-in-sidebar' );
	} else {
		// Construct the comments string.
		if ( 1 === $num_comments ) {
			$comments = esc_html__( '1 Comment', 'posts-in-sidebar' );
		} elseif ( 1 < $num_comments ) {
			// translators: %d is the number of comments.
			$comments = sprintf( esc_html__( '%d Comments', 'posts-in-sidebar' ), $num_comments );
		} else {
			$comments = esc_html__( 'Leave a comment', 'posts-in-sidebar' );
		}

		// Contruct the HTML string for the comments.
		if ( $link ) {
			$output = '<a ' . pis_class( 'pis-comments-link', apply_filters( 'pis_comments_link_class', '' ), false ) . ' href="' . get_comments_link( $pis_post_id ) . '">' . $comments . '</a>';
		} else {
			$output = $comments;
		}
	}

	return $output;
}

/**
 * Returns the HTML string for the author's Gravatar image.
 *
 * @param array $args The array containing the custom args.
 * @since 3.0
 */
function pis_get_gravatar( $args ) {
	$defaults = array(
		'author'  => '',
		'size'    => 32,
		'default' => '',
	);

	$args = wp_parse_args( $args, $defaults );

	$output = '<span ' . pis_class( 'pis-gravatar', apply_filters( 'pis_gravatar_class', '' ), false ) . '>' . get_avatar( $args['author'], $args['size'], $args['default'] ) . '</span>';

	return $output;
}

/**
 * Returns the HTML string for the archive link.
 *
 * @param array $args The array containing the custom args.
 * @since 3.0
 */
function pis_archive_link( $args ) {
	$defaults = array(
		'link_to'        => 'category',
		'tax_name'       => '',
		'tax_term_name'  => '',
		'archive_text'   => esc_html__( 'Display all posts', 'posts-in-sidebar' ),
		'archive_margin' => null,
		'margin_unit'    => 'px',
	);

	$args = wp_parse_args( $args, $defaults );

	$link_to        = $args['link_to'];
	$tax_name       = $args['tax_name'];
	$tax_term_name  = $args['tax_term_name'];
	$archive_text   = $args['archive_text'];
	$archive_margin = $args['archive_margin'];
	$margin_unit    = $args['margin_unit'];

	switch ( $link_to ) {
		case 'author':
			$term_identity = get_user_by( 'slug', $tax_term_name );
			if ( $term_identity ) {
				$term_link = get_author_posts_url( $term_identity->ID, $tax_term_name );
				$term_name = $term_identity->display_name;
			}
			break;

		case 'category':
			$term_identity = get_term_by( 'slug', $tax_term_name, 'category' );
			if ( $term_identity ) {
				$term_link = get_term_link( $term_identity->term_id, 'category' );
				$term_name = $term_identity->name;
			}
			break;

		case 'tag':
			$term_identity = get_term_by( 'slug', $tax_term_name, 'post_tag' );
			if ( $term_identity ) {
				$term_link = get_term_link( $term_identity->term_id, 'post_tag' );
				$term_name = $term_identity->name;
			}
			break;

		case 'custom_post_type':
			if ( post_type_exists( $tax_term_name ) ) {
				$term_link        = get_post_type_archive_link( $tax_term_name );
				$post_type_object = get_post_type_object( $tax_term_name );
				$term_name        = $post_type_object->labels->name;
			}
			break;

		case 'custom_taxonomy':
			$term_identity = get_term_by( 'slug', $tax_term_name, $tax_name );
			if ( $term_identity ) {
				$term_link = get_term_link( $term_identity->term_id, $tax_name );
				$term_name = $term_identity->name;
			}
			break;

		default: // This is the case of post formats.
			$term_identity = get_term_by( 'slug', $link_to, 'post_format' );
			if ( $term_identity ) {
				$term_link = get_post_format_link( substr( $link_to, 12 ) );
				$term_name = $term_identity->name;
			}
			break;
	}

	if ( isset( $term_link ) ) {
		if ( strpos( $archive_text, '%s' ) ) {
			$archive_text = str_replace( '%s', $term_name, $archive_text );
		}
		$output  = '<p ' . pis_paragraph( $archive_margin, $margin_unit, 'pis-archive-link', 'pis_archive_class' ) . '>';
		$output .= '<a ' . pis_class( 'pis-archive-link-class', apply_filters( 'pis_archive_link_class', '' ), false ) . ' href="' . esc_url( $term_link ) . '" rel="bookmark">';
		$output .= esc_html( $archive_text );
		$output .= '</a>';
		$output .= '</p>';
	}

	if ( isset( $output ) ) {
		return $output;
	} else {
		return '';
	}
}

/**
 * Return the "Generated by..." HTML comment.
 * Includes version of Posts in Sidebar and the status of the cache.
 *
 * @param boolean $cached If the cache is active or not.
 * @since 2.0.3
 */
function pis_generated( $cached ) {
	/* Whether the cache is active */
	if ( $cached ) {
		$pis_cache_active = ' - Cache is active';
	} else {
		$pis_cache_active = '';
	}
	/* Output the HTML comment */
	return '<!-- Generated by Posts in Sidebar v' . PIS_VERSION . $pis_cache_active . ' -->' . "\n";
}

/**
 * Return the debugging informations.
 *
 * @param array $parameters {
 *     The array containing the custom parameters.
 *
 *     @type boolean $admin_only   If the administrators only can view the debugging informations.
 *     @type boolean $debug_query  If display the query used for retrieving posts.
 *     @type boolean $debug_params If display the set of options of the widget.
 *     @type string  $params       The parameters for the query.
 *     @type string  $args         The set of options of the widget.
 *     @type boolean $cached       If the output of the widget has been cached.
 * }
 * @since 2.0.3
 */
function pis_debug( $parameters ) {
	$defaults = array(
		'admin_only'   => true,
		'debug_query'  => false,
		'debug_params' => false,
		'params'       => '',
		'args'         => '',
		'cached'       => false,
		'widget_id'    => '',
	);

	$parameters = wp_parse_args( $parameters, $defaults );

	$admin_only   = $parameters['admin_only'];
	$debug_query  = $parameters['debug_query'];
	$debug_params = $parameters['debug_params'];
	$params       = $parameters['params'];
	$args         = $parameters['args'];
	$cached       = $parameters['cached'];
	$widget_id    = $parameters['widget_id'];

	$output = '';

	if ( $debug_query || $debug_params ) {
		global $wp_version;
		$output .= '<!-- Start PiS Debug -->';
		// translators: %s is the name of the plugin.
		$output .= '<h3 class="pis-debug-title-main">' . sprintf( esc_html__( '%s Debug', 'posts-in-sidebar' ), 'Posts in Sidebar' ) . '</h3>' . "\n";
		$output .= '<p class="pis-debug-title"><strong>' . esc_html__( 'Environment information:', 'posts-in-sidebar' ) . '</strong></p>' . "\n";
		$output .= '<ul class="pis-debug-ul">' . "\n";
		// translators: %s is the site URL.
		$output .= '<li class="pis-debug-li">' . sprintf( esc_html__( 'Site URL: %s', 'posts-in-sidebar' ), site_url() ) . '</li>' . "\n";
		// translators: %s is the WordPress version.
		$output .= '<li class="pis-debug-li">' . sprintf( esc_html__( 'WP version: %s', 'posts-in-sidebar' ), $wp_version ) . '</li>' . "\n";
		// translators: %s is the plugin version.
		$output .= '<li class="pis-debug-li">' . sprintf( esc_html__( 'PiS version: %s', 'posts-in-sidebar' ), PIS_VERSION ) . '</li>' . "\n";
		// translators: %s is the ID of the widget.
		$output .= '<li class="pis-debug-li">' . sprintf( esc_html__( 'Widget ID: %s', 'posts-in-sidebar' ), $widget_id ) . '</li>' . "\n";

		if ( $cached ) {
			$output .= '<li class="pis-debug-li">' . esc_html__( 'Cache: active', 'posts-in-sidebar' ) . '</li>' . "\n";
		} else {
			$output .= '<li class="pis-debug-li">' . esc_html__( 'Cache: not active', 'posts-in-sidebar' ) . '</li>' . "\n";
		}
		// translators: %1$s is the number of queries, %2$s is the number of seconds.
		$output .= '<li class="pis-debug-li">' . sprintf( esc_html__( 'Queries: %1$s queries in %2$s seconds', 'posts-in-sidebar' ), get_num_queries(), timer_stop() ) . '</li>' . "\n";
		$output .= '</ul>';
	}

	if ( $debug_query ) {
		$output .= '<p class="pis-debug-title"><strong>' . esc_html__( 'The parameters for the query:', 'posts-in-sidebar' ) . '</strong></p>' . "\n";
		$output .= '<ul class="pis-debug-ul">' . "\n";
		foreach ( $params as $key => $value ) {
			if ( is_array( $value ) ) {
				$output .= '<li class="pis-debug-li">' . $key . ':</li>' . "\n";
				$output .= '<ul class="pis-debug-ul" style="margin-bottom: 0;">' . pis_array2string( $value ) . '</ul>' . "\n";
			} else {
				$output .= '<li class="pis-debug-li">' . $key . ': <code>' . esc_html( $value ) . '</code></li>' . "\n";
			}
		}
		$output .= '</ul>';
	}

	if ( $debug_params ) {
		$output .= '<p class="pis-debug-title"><strong>' . esc_html__( 'The options of the widget:', 'posts-in-sidebar' ) . '</strong></p>' . "\n";
		$output .= '<ul class="pis-debug-ul">' . "\n";
		foreach ( $args as $key => $value ) {
			if ( is_array( $value ) ) {
				$output .= '<li class="pis-debug-li">' . $key . ': <code>' . implode( ', ', $value ) . '</code></li>' . "\n";
			} else {
				$output .= '<li class="pis-debug-li">' . $key . ': <code>' . esc_html( $value ) . '</code></li>' . "\n";
			}
		}
		$output .= '</ul>' . "\n";
	}

	if ( $debug_query || $debug_params ) {
		$output .= '<!-- End PiS Debug -->' . "\n";
	}

	/**
	 * Display debugging informations to admins only.
	 *
	 * @since 3.8.3
	 */
	if ( $admin_only ) {
		if ( current_user_can( 'create_users' ) ) {
			return $output;
		} else {
			return '';
		}
	} else {
		return $output;
	}
}

/*
 * Posts in Sidebar tools section
 *******************************************************************************
 */

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

	// If $default is not empty, remove any leading and trailing dot, space, and dash,
	// transform it into an array using internal spaces, and merge it with $classes.
	if ( ! empty( $default ) ) {
		if ( ! is_array( $default ) ) {
			$default = preg_split( '/[\s]+/', trim( $default, ' -' ) );
		}
		$classes = array_merge( $classes, $default );
	}

	// If $class is not empty, remove any leading and trailing space,
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

	$sql = "SELECT DISTINCT meta_key
		FROM $wpdb->postmeta
		WHERE meta_key NOT BETWEEN '_' AND '_z'
		HAVING meta_key NOT LIKE %s
		ORDER BY meta_key
		LIMIT %d";

	$keys = $wpdb->get_col( $wpdb->prepare( $sql, $wpdb->esc_like( '_' ) . '%', $limit ) );

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

/*
 * Generic tools section
 *******************************************************************************
 */

/**
 * Remove empty keys from an array recursively.
 *
 * @param array   $array      The array to be checked.
 * @param boolean $make_empty If the output is to return as an empty string.
 * @since 1.29
 * @see http://stackoverflow.com/questions/7696548/php-how-to-remove-empty-entries-of-an-array-recursively
 */
function pis_array_remove_empty_keys( $array, $make_empty = false ) {
	if ( ! is_array( $array ) ) {
		return;
	}

	foreach ( $array as $key => $value ) {
		if ( is_array( $value ) ) {
			$array[ $key ] = pis_array_remove_empty_keys( $array[ $key ] );
		}
		if ( empty( $array[ $key ] ) ) {
			unset( $array[ $key ] );
		}
	}

	if ( empty( $array ) && $make_empty ) {
		$array = '';
	}

	return $array;
}

/**
 * Compare a string and an array and return the common elements as a string.
 *
 * @param string $string The string to be compared.
 * @param array  $array  The array to be compared.
 *
 * @return string $output The string containing the common values.
 *
 * @since 3.8.8
 */
function pis_compare_string_to_array( $string = '', $array = array() ) {
	// Convert the string to lowercase.
	$string = strtolower( $string );
	// Remove any space from the string.
	$string = str_replace( ' ', '', $string );
	// Remove any comma at the beginning and at the end of the string.
	$string = trim( $string, ',' );
	// Convert the string into an array.
	$string = explode( ',', $string );

	// Compare the two arrays and return the intersection (the common values).
	$output = array_intersect( $string, $array );
	// Convert the returned array into a string.
	$output = implode( ', ', $output );

	return $output;
}

/**
 * Remove any leading and trailing dash from a string.
 *
 * @param string $string The string to be trimmed.
 * @since 4.1
 */
function pis_remove_dashes( $string = '' ) {
	$string = trim( $string, '-' );
	return $string;
}

/**
 * Normalize entered values making these checks:
 * 1) transform any comma and space (in any number) into one comma + space (, );
 * 2) if $string contains numbers, the numbers can be converted into positive, non-decimal values.
 *
 * @param string $string The string to be checked.
 * @param bool   $absint If $string contains number to be converted into positive non-decimal values.
 * @since 4.7.0
 */
function pis_normalize_values( $string = '', $absint = false ) {
	$string = preg_replace( '([\s,]+)', ', ', $string );
	$string = trim( $string, ', ' );

	if ( $absint ) {
		$string = explode( ', ', $string );
		foreach ( $string as $key => $value ) {
			$string[ $key ] = absint( $value );
		}
		$string = implode( ', ', $string );
	}

	return $string;
}

/**
 * Returns the title of the main post,
 * changing spaces into a plus and lowering the letters.
 *
 * @since 4.7.0
 * @return string $post_title The title of the main post with pluses and lowercase.
 */
function pis_get_post_title() {
	$post_title = get_the_title();

	/*
	 * Remove punctuation.
	 *
	 * We cannot simply use:
	 * $post_title = preg_replace( '/[^a-zA-Z0-9]+/', '+', $post_title );
	 * or
	 * $post_title = preg_replace( '/[^\w|\s]/', '', $post_title );
	 * because preg_replace() will remove characters like Russian and such.
	 */
	$remove_chars = array(
		',',
		';',
		'.',
		':',
		'\'',
		'*',
		'°',
		'@',
		'#',
		'+',
		'"',
		'!',
		'?',
		'–',
		'—',
		'―',
		'(',
		')',
	);
	$post_title   = str_replace( $remove_chars, '', $post_title );
	$post_title   = strtolower( $post_title );
	return $post_title;
}

/**
 * Check post types entered.
 * The function removes any post type that has not been defined.
 *
 * @param string $post_type The post type.
 * @since 4.7.0
 */
function pis_check_post_types( $post_type ) {
	$post_type_wordpress = get_post_types( array( 'public' => true ), 'names' );
	$post_type           = pis_compare_string_to_array( $post_type, $post_type_wordpress );
	return $post_type;
}

/**
 * Print a multidimensional array.
 *
 * @param array $array The array to be printed.
 * @see https://stackoverflow.com/questions/46343168/how-to-display-values-of-a-multidimensional-associative-array-using-foreach-loop
 * @since 4.7.0
 */
function pis_array2string( $array ) {
	$output = '';
	foreach ( $array as $key => $value ) {
		if ( is_array( $value ) ) {
			$output .= '<ul class="pis-debug-ul" style="margin-bottom: 0;">' . "\n";
			$output .= pis_array2string( $value );
			$output .= '</ul>';
		} else {
			$output .= '<li class="pis-debug-li">' . $key . ': <code>' . esc_html( $value ) . '</code></li>' . "\n";
		}
	}

	return $output;
}
