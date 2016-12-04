<?php
/**
 * This file contains the functions of the plugin
 *
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


/**
 * Return the class for the HTML element.
 *
 * @since 1.9
 *
 * @param string $default One or more classes, defined by plugin's developer, to add to the class list.
 * @param string|array $class One or more classes, defined by the user, to add to the class list.
 * @param boolean $echo If the function should echo or not the output. Default true.
 * @return string $output List of classes.
 */
function pis_class( $default = '', $class = '', $echo = true ) {

	// Define $classes as array
	$classes = array();

	// If $default is not empy, add the value ad an element of the array
	if( ! empty( $default ) )
		$classes[] = $default;

	// If $class is not empty, transform it into an array and add the elements to the array
	if ( ! empty( $class ) ) {
		if ( ! is_array( $class ) ) $class = preg_split( '#\s+#', $class );
		$classes = array_merge( $classes, $class );
	}

	// Escape evil chars in $classes
	$classes = array_map( 'esc_attr', $classes );

	// Remove null or empty or space-only-filled elements from the array
	foreach ( $classes as $key => $value ) {
		if ( is_null( $value ) || $value == '' || $value == ' ' ) {
			unset( $classes[ $key ] );
		}
	}

	// Convert the array into string
	$classes = implode( ' ', $classes );

	// Complete the final output
	$classes = 'class="' . $classes . '"';

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
 * @param string $margin The margin of the paragraph.
 * @param string $unit The unit measure to be used.
 * @param string $class The default class defined by the plugin's developer.
 * @param string $class_filter The name of the class filter.
 * @return string $output The class and the inline style.
 * @uses pis_class()
 */
function pis_paragraph( $margin, $unit, $class, $class_filter ) {
	( ! is_null( $margin ) ) ? $style = ' style="margin-bottom: ' . $margin . $unit . ';"' : $style = '';
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
	// Convert cross-platform newlines into HTML '<br />'
	$text = str_replace( array( "\r\n", "\n", "\r" ), "<br />", $text );
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
	$keys = $wpdb->get_col( "
		SELECT meta_key
		FROM $wpdb->postmeta
		GROUP BY meta_key
		HAVING meta_key NOT LIKE '\_%'
		ORDER BY meta_key
		LIMIT $limit" );
	if ( $keys )
		natcasesort($keys);
	return $keys;
}


/**
 * Generate an HTML arrow.
 *
 * @param boolean $pre_space If a space must be prepended before the arrow.
 * @return string $output The HTML arrow.
 * @uses pis_class()
 * @since 1.15
 */
function pis_arrow( $pre_space = true ) {
	$the_arrow = '&rarr;';
	if ( is_rtl() ) $the_arrow = '&larr;';

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
 * @since 1.15
 * @uses pis_arrow()
 * @param string $the_more The text to be displayed for "Continue reading". Default empty.
 * @param boolean $no_the_more If the text for "Continue reading" must be hidden. Default false.
 * @param boolean $exc_arrow If the arrow must be displayed or not. Default false.
 * @param boolean $echo If echo the output or return.
 * @return string The HTML arrow linked to the post.
 */
function pis_more_arrow( $the_more = '', $no_the_more = false, $exc_arrow = false, $echo = true ) {
	$output = '';
	// If we do not want any "Read more" nor any arrow
	// or the user doesn't want any "Read more" nor any arrow.
	if ( ( true == $no_the_more && false == $exc_arrow ) || ( '' == $the_more && false == $exc_arrow ) ) {
		$output = '';
	} else {
		// Else if we do not want any "Read more" but the user wants an arrow
		// or the user doesn't want the "Read more" but only the arrow.
		if ( ( true == $no_the_more && true == $exc_arrow ) || ( ! $the_more && $exc_arrow ) ) {
			$the_more = '';
			$the_arrow = pis_arrow( false );
		}
		// The user wants the "Read more" and the arrow.
		elseif ( $the_more && $exc_arrow ) {
			$the_arrow = pis_arrow();
		}
		// The user wants the "Read more" but not the arrow
		else {
			$the_arrow = '';
		}
		$output = '<span ' . pis_class( 'pis-more', apply_filters( 'pis_more_class', '' ), false ) . '>';
			$output .= '<a href="' . get_permalink() . '" rel="bookmark">';
				$output .= $the_more . $the_arrow;
			$output .= '</a>';
		$output .= '</span>';
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
 * @return The HTML for custom styles in the HEAD section.
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

	// Remove any empty elements from the array
	$styles = array_filter( $styles );

	// Make the array as string.
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


/**
 * Add the utilities section: author, date of the post and comments.
 *
 * @since 1.18
 * @param array The array of parameters.
 * @return The HTML for the section.
 * @uses pis_paragraph()
 * @uses pis_class()
 * @uses pis_get_comments_number()
 */
function pis_utility_section( $args ) {
	$defaults = array(
		'display_author'    => false,
		'display_date'      => false,
		'display_mod_date'  => false,
		'comments'          => false,
		'utility_margin'    => NULL,
		'margin_unit'       => 'px',
		'author_text'       => __( 'By', 'posts-in-sidebar' ),
		'linkify_author'    => false,
		'utility_sep'       => '|',
		'date_text'         => __( 'Published on', 'posts-in-sidebar' ),
		'linkify_date'      => false,
		'mod_date_text'     => __( 'Modified on', 'posts-in-sidebar' ),
		'linkify_mod_date'  => false,
		'comments_text'     => __( 'Comments:', 'posts-in-sidebar' ),
		'pis_post_id'       => '',
		'link_to_comments'  => true,
		'gravatar_display'  => false,
		'gravatar_position' => '',
		'gravatar_author'   => '',
		'gravatar_size'     => 32,
		'gravatar_default'  => '',
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	$output = '';

	if ( $display_author || $display_date || $display_mod_date || $comments ) {
		$output .= '<p ' . pis_paragraph( $utility_margin, $margin_unit, 'pis-utility', 'pis_utility_class' ) . '>';
	}

		/* The Gravatar */
		if ( $gravatar_display && 'next_author' == $gravatar_position ) {
			$output .= pis_get_gravatar( array(
				'author'  => $gravatar_author,
				'size'    => $gravatar_size,
				'default' => $gravatar_default,
			) );
		}

		/* The author */
		if ( $display_author ) {
			$output .= '<span ' . pis_class( 'pis-author', apply_filters( 'pis_author_class', '' ), false ) . '>';
				if ( $author_text ) $output .= $author_text . ' ';
				if ( $linkify_author ) {
					$author_link  = get_author_posts_url( get_the_author_meta( 'ID' ) );
					$output .= '<a ' . pis_class( 'pis-author-link', apply_filters( 'pis_author_link_class', '' ), false ) . ' href="' . $author_link . '" rel="author">';
						$output .= get_the_author();
					$output .= '</a>';
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
				if ( $date_text ) $output .= $date_text . ' ';
				if ( $linkify_date ) {
					$output .= '<a ' . pis_class( 'pis-date-link', apply_filters( 'pis_date_link_class', '' ), false ) . ' href="' . get_permalink() . '" rel="bookmark">';
						$output .= get_the_date();
					$output .= '</a>';
				} else {
					$output .= get_the_date();
				}
			$output .= '</span>';
		}

		/* The modification date */
		if ( $display_mod_date ) {
			/**
			 * The modification date is displayed under these two conditions:
			 * 1. if the creation date is not displayed OR
			 * 2. if the creation date is displayed AND the modification date is different from the creation date.
			 */
			if ( ( ! $display_date ) || ( $display_date && get_the_modified_date() != get_the_date() ) ) {
				if ( $display_author || $display_date ) {
					$output .= '<span ' . pis_class( 'pis-separator', apply_filters( 'pis_separator_class', '' ), false ) . '> ' . $utility_sep . ' </span>';
				}
				$output .= '<span ' . pis_class( 'pis-mod-date', apply_filters( 'pis_mod_date_class', '' ), false ) . '>';
					if ( $mod_date_text ) $output .= $mod_date_text . ' ';
					if ( $linkify_mod_date ) {
						$output .= '<a ' . pis_class( 'pis-mod-date-link', apply_filters( 'pis_mod_date_link_class', '' ), false ) . ' href="' . get_permalink() . '" rel="bookmark">';
							$output .= get_the_modified_date();
						$output .= '</a>';
					} else {
						$output .= get_the_modified_date();
					}
				$output .= '</span>';
			}
		}

		/* The comments */
		if ( ! post_password_required() ) {
			if ( $comments ) {
				if ( $display_author || $display_date || $display_mod_date ) {
					$output .= '<span ' . pis_class( 'pis-separator', apply_filters( 'pis_separator_class', '' ), false ) . '> ' . $utility_sep . ' </span>';
				}
				$output .= '<span ' . pis_class( 'pis-comments', apply_filters( 'pis_comments_class', '' ), false ) . '>';
					if ( $comments_text ) $output .= $comments_text . ' ';
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
 * Add the thumbnail of the post.
 *
 * @since 1.18
 * @param array The array of parameters.
 * @return The HTML for the thumbnail.
 */
function pis_the_thumbnail( $args ) {
	$defaults = array(
		'display_image'       => false,
		'image_align'         => 'no_change',
		'side_image_margin'   => NULL,
		'bottom_image_margin' => NULL,
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
	extract( $args, EXTR_SKIP );

	if ( $thumb_wrap ) {
		$open_wrap = '<p class="pis-thumbnail">';
		$close_wrap = '</p>';
	} else {
		$open_wrap = '';
		$close_wrap = '';
	}

	switch ( $image_align ) {
		case 'left' :
			$image_class = ' alignleft';
			$image_style = '';
			if ( ! is_null( $side_image_margin ) || ! is_null( $bottom_image_margin ) ) {
				$image_style = ' style="display: inline; float: left; margin-right: ' . $side_image_margin . $margin_unit . '; margin-bottom: ' . $bottom_image_margin . $margin_unit . ';"';
				$image_style = str_replace( ' margin-right: px;', '', $image_style);
				$image_style = str_replace( ' margin-bottom: px;', '', $image_style);
			}
		break;
		case 'right':
			$image_class = ' alignright';
			$image_style = '';
			if ( ! is_null( $side_image_margin ) || ! is_null( $bottom_image_margin ) ) {
				$image_style = ' style="display: inline; float: right; margin-left: ' . $side_image_margin . $margin_unit . '; margin-bottom: ' . $bottom_image_margin . $margin_unit . ';"';
				$image_style = str_replace( ' margin-left: px;', '', $image_style);
				$image_style = str_replace( ' margin-bottom: px;', '', $image_style);
			}
		break;
		case 'center':
			$image_class = ' aligncenter';
			$image_style = '';
			if ( ! is_null( $bottom_image_margin ) )
				$image_style = ' style="margin-bottom: ' . $bottom_image_margin . $margin_unit . ';"';
		break;
		default:
			$image_class = '';
			$image_style = '';
		break;
	}

	$output = $open_wrap;

		if ( $image_link_to_post ) {

		// Figure out if a custom link for the featured image has been set.
		if ( $image_link ) {
			$the_image_link = $image_link;
		} else {
			$the_image_link = get_permalink();
		}
		$output .= '<a ' . pis_class( 'pis-thumbnail-link', apply_filters( 'pis_thumbnail_link_class', '' ), false ) . 'href="' . esc_url( strip_tags( $the_image_link ) ) . '" rel="bookmark">';
		}

			/**
			 * If the post type is an attachment (an image, or any other attachment),
			 * the construct is different.
			 *
			 * @since 1.28
			 */
			if ( 'attachment' == $post_type ) {
				$image_html = wp_get_attachment_image(
					$pis_query->post->ID,
					$image_size,
					false,
					array(
						'class' => "attachment-$image_size pis-thumbnail-img" . ' ' . apply_filters( 'pis_thumbnail_class', '' ) . $image_class,
					)
				);
			} else {
				/**
				 * If the post has not a post-thumbnail AND a custom image URL is defined (in this case the custom image will be used only if the post has not a featured image)
				 * OR
				 * if custom image URL is defined AND the custom image should be used in every case (in this case the custom image will be used for all posts, even those who already have a featured image).
				 */
				if ( ( ! has_post_thumbnail() && $custom_image_url ) || ( $custom_image_url && ! $custom_img_no_thumb ) ) {
					$image_html = '<img src="' . esc_url( $custom_image_url ) . '" alt="" class="pis-thumbnail-img' . ' ' . apply_filters( 'pis_thumbnail_class', '' ) . $image_class . '">';
				} else {
					$image_html = get_the_post_thumbnail(
						$pis_query->post->ID,
						$image_size,
						array(
							'class' => 'pis-thumbnail-img' . ' ' . apply_filters( 'pis_thumbnail_class', '' ) . $image_class,
						)
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
		'excerpt'    => 'excerpt',
		'pis_query'  => '',
		'exc_length' => 20,
		'the_more'   => __( 'Read more&hellip;', 'posts-in-sidebar' ),
		'exc_arrow'  => false,
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

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
			/* Filter the post content. If not filtered, shortcodes (and other things) will not be executed.
			 * See https://codex.wordpress.org/Function_Reference/get_the_content
			 */
			$output .= apply_filters( 'the_content', get_the_content() );
		break;

		case 'rich_content':
			$content = $pis_query->post->post_content;
			// Honor any paragraph break
			$content = pis_break_text( $content );
			$content = do_shortcode( $content );
			$output .= apply_filters( 'pis_rich_content', $content );
		break;

		case 'content':
			// Remove shortcodes
			$content = strip_shortcodes( $pis_query->post->post_content );
			// remove any HTML tag
			$content = wp_kses( $content, array() );
			// Honor any paragraph break
			$content = pis_break_text( $content );
			$output .= apply_filters( 'pis_content', $content );
		break;

		case 'more_excerpt':
			$excerpt_text = strip_shortcodes( $pis_query->post->post_content );
			$testformore = strpos( $excerpt_text, '<!--more-->' );
			if ( $testformore ) {
				$excerpt_text = substr( $excerpt_text, 0, $testformore );
			} else {
				$excerpt_text = wp_trim_words( $excerpt_text, $exc_length, '&hellip;' );
			}
			$output .= apply_filters( 'pis_more_excerpt_text', $excerpt_text ) . ' ' . pis_more_arrow( $the_more, false, $exc_arrow, false );
		break;

		case 'excerpt':
			/**
			 * Check if the Relevanssi plugin is active and restore the user-defined excerpt in place of the Relevanssi-generated excerpt.
			 * @see https://wordpress.org/support/topic/issue-with-excerpts-when-using-relevanssi-search
			 *
			 * @since 1.26
			 */
			if ( function_exists( 'relevanssi_do_excerpt' ) && isset( $pis_query->post->original_excerpt ) ) {
				$pis_query->post->post_excerpt = $pis_query->post->original_excerpt;
			}

			// If we have a user-defined excerpt...
			if ( $pis_query->post->post_excerpt ) {
				// Honor any paragraph break
				$user_excerpt = pis_break_text( $pis_query->post->post_excerpt );
				$output .= apply_filters( 'pis_user_excerpt', $user_excerpt ) . ' ' . pis_more_arrow( $the_more, false, $exc_arrow, false );
			} else {
			// ... else generate an excerpt
				$excerpt_text = strip_shortcodes( $pis_query->post->post_content );
				$no_the_more = false;
				if ( count( explode( ' ', wp_strip_all_tags( $excerpt_text ) ) ) <= $exc_length ) $no_the_more = true;
				$excerpt_text = wp_trim_words( $excerpt_text, $exc_length, '&hellip;' );
				$output .= apply_filters( 'pis_excerpt_text', $excerpt_text ) . ' ' . pis_more_arrow( $the_more, $no_the_more, $exc_arrow, false );
			}
		break;

		case 'only_read_more':
			$excerpt_text = '';
			$output .= apply_filters( 'pis_only_read_more', $excerpt_text ) . ' ' . pis_more_arrow( $the_more, false, $exc_arrow, false );
		break;

	endswitch;
	// Close The text

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
		'postID'       => '',
		'term_hashtag' => '',
		'term_sep'     => ',',
		'terms_margin' => NULL,
		'margin_unit'  => 'px',
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	// get post by post id
	$post = get_post( $postID );

	// get post type by post
	$post_type = $post->post_type;

	// get post type taxonomies
	$taxonomies = get_object_taxonomies( $post_type, 'objects' );

	$output = '';

	foreach ( $taxonomies as $taxonomy_slug => $taxonomy ) {
		// Exclude the standard WordPress 'category' and 'post_tag' taxonomies otherwise we'll have a duplicate in the front-end.
		if ( 'category' != $taxonomy_slug && 'post_tag' != $taxonomy_slug ) {
			// get the terms related to post
			$list_of_terms = get_the_term_list( $postID, $taxonomy_slug, $term_hashtag, $term_sep . ' ' . $term_hashtag, '' );
			if ( $list_of_terms ) {
				$output .= '<p ' . pis_paragraph( $terms_margin, $margin_unit, 'pis-terms-links pis-' . $taxonomy_slug, 'pis_terms_class' ) . '>';
					$output .= '<span class="pis-tax-name">' . $taxonomy->label . '</span>: ' . apply_filters( 'pis_terms_list', $list_of_terms );
				$output .= '</p>';
			}
		}
	}

	return $output;
}


/**
 * Build the query based on taxonomies.
 *
 * @param array $args The array containing the custom parameters.
 * @return array An array of array of parameters.
 * @since 1.29
 */
function pis_tax_query( $args ) {
	$defaults = array (
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
	extract( $args, EXTR_SKIP );

	if ( '' == $taxonomy_aa && '' == $terms_aa ) {
		$tax_query = '';
	} else {
		// Convert terms into arrays
		$terms_aa = explode( ',', $terms_aa );
		if ( $terms_ab ) $terms_ab = explode( ',', $terms_ab );
		if ( $terms_ba ) $terms_ba = explode( ',', $terms_ba );
		if ( $terms_bb ) $terms_bb = explode( ',', $terms_bb );

		// Let's figure out the tax_query to build
		if ( $taxonomy_aa && ! $taxonomy_ab && ! $taxonomy_ba && ! $taxonomy_bb ) {
			$tax_query = array(
				array(
					'taxonomy' => $taxonomy_aa,
					'field'    => $field_aa,
					'terms'    => $terms_aa, // This must be an array
					'operator' => $operator_aa,
				)
			);
		} elseif ( $taxonomy_aa && ! $taxonomy_ab && $taxonomy_ba && ! $taxonomy_bb && ! empty( $relation ) ) {
			$tax_query = array(
				'relation' => $relation,
				array(
					'taxonomy' => $taxonomy_aa,
					'field'    => $field_aa,
					'terms'    => $terms_aa, // This must be an array
					'operator' => $operator_aa,
				),
				array(
					'taxonomy' => $taxonomy_ba,
					'field'    => $field_ba,
					'terms'    => $terms_ba, // This must be an array
					'operator' => $operator_ba,
				)
			);
		} elseif ( $taxonomy_aa && $taxonomy_ab && $taxonomy_ba && ! $taxonomy_bb && ! empty( $relation ) ) {
			$tax_query = array(
				'relation' => $relation,
				array(
					'relation_a' => $relation_a,
					array (
						'taxonomy' => $taxonomy_aa,
						'field'    => $field_aa,
						'terms'    => $terms_aa, // This must be an array
						'operator' => $operator_aa,
					),
					array (
						'taxonomy' => $taxonomy_ab,
						'field'    => $field_ab,
						'terms'    => $terms_ab, // This must be an array
						'operator' => $operator_ab,
					)
				),
				array(
					'taxonomy' => $taxonomy_ba,
					'field'    => $field_ba,
					'terms'    => $terms_ba, // This must be an array
					'operator' => $operator_ba,
				)
			);
		} elseif ( $taxonomy_aa && ! $taxonomy_ab && $taxonomy_ba && $taxonomy_bb && ! empty( $relation ) ) {
			$tax_query = array(
				'relation' => $relation,
				array(
					'taxonomy' => $taxonomy_aa,
					'field'    => $field_aa,
					'terms'    => $terms_aa, // This must be an array
					'operator' => $operator_aa,
				),
				array(
					'relation_b' => $relation_b,
					array (
						'taxonomy' => $taxonomy_ba,
						'field'    => $field_ba,
						'terms'    => $terms_ba, // This must be an array
						'operator' => $operator_ba,
					),
					array (
						'taxonomy' => $taxonomy_bb,
						'field'    => $field_bb,
						'terms'    => $terms_bb, // This must be an array
						'operator' => $operator_bb,
					)
				)
			);
		} elseif ( $taxonomy_aa && $taxonomy_ab && $taxonomy_ba && $taxonomy_bb && ! empty( $relation ) ) {
			$tax_query = array(
				'relation' => $relation,
				array(
					'relation_a' => $relation_a,
					array (
						'taxonomy' => $taxonomy_aa,
						'field'    => $field_aa,
						'terms'    => $terms_aa, // This must be an array
						'operator' => $operator_aa,
					),
					array (
						'taxonomy' => $taxonomy_ab,
						'field'    => $field_ab,
						'terms'    => $terms_ab, // This must be an array
						'operator' => $operator_ab,
					)
				),
				array(
					'relation_b' => $relation_b,
					array (
						'taxonomy' => $taxonomy_ba,
						'field'    => $field_ba,
						'terms'    => $terms_ba, // This must be an array
						'operator' => $operator_ba,
					),
					array (
						'taxonomy' => $taxonomy_bb,
						'field'    => $field_bb,
						'terms'    => $terms_bb, // This must be an array
						'operator' => $operator_bb,
					)
				)
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
 * Remove empty keys from an array recursively.
 *
 * @param array $array The array to be checked.
 * @param boolean $make_empty If the output is to return as an empty string.
 * @since 1.29
 * @see http://stackoverflow.com/questions/7696548/php-how-to-remove-empty-entries-of-an-array-recursively
 */
function pis_array_remove_empty_keys( $array, $make_empty = false ) {
	foreach ( $array as $key => $value ) {
		if ( is_array( $value ) ) {
			$array[$key] = pis_array_remove_empty_keys( $array[$key] );
		}
		if ( empty( $array[$key] ) ) {
			unset( $array[$key] );
		}
	}

	if ( empty( $array ) && $make_empty ) {
		$array = '';
	}

	return $array;
}


/**
 * Return the debugging informations.
 *
 * @param array $parameters The array containing the custom parameters.
 * @since 2.0.3
 */
function pis_debug( $parameters ) {
	$defaults = array (
		'debug_query'        => false,
		'debug_params'       => false,
		'debug_query_number' => false,
		'params'             => '',
		'args'               => '',
		'cached'             => false,
	);
	$parameters = wp_parse_args( $parameters, $defaults );
	extract( $parameters, EXTR_SKIP );

	$output = '';

	if ( $debug_query || $debug_params || $debug_query_number ) {
		global $wp_version;
		$output .= '<h3>' . sprintf( __( '%s Debug', 'posts-in-sidebar' ), 'Posts in Sidebar' ) . '</h3>';
		$output .= '<p><strong>Environment informations:</strong><br>';
			$output .= '&bull;&ensp;' . sprintf( __( 'Site URL: %s', 'posts-in-sidebar' ), site_url() . '<br>' );
			$output .= '&bull;&ensp;' . sprintf( __( 'WP version: %s', 'posts-in-sidebar' ), $wp_version . '<br>' );
			$output .= '&bull;&ensp;' . sprintf( __( 'PiS version: %s', 'posts-in-sidebar' ), PIS_VERSION . '<br>' );
			if ( $cached ) {
				$output .= '&bull;&ensp;' . __( 'Cache: active', 'posts-in-sidebar' );
			} else {
				$output .= '&bull;&ensp;' . __( 'Cache: not active', 'posts-in-sidebar' );
			}
		$output .= '</p>';
	}

	if ( $debug_query ) {
		$output .= '<p><strong>' . __( 'The parameters for the query:', 'posts-in-sidebar' ) . '</strong></p>';
		$output .= '<pre><code>$pis_query = ' . print_r( $params, true ) . '</code></pre>';
	}

	if ( $debug_params ) {
		$output .= '<p><strong>' . __( 'The complete set of parameters of the widget:', 'posts-in-sidebar' ) . '</strong></p>';
		$output .= '<pre><code>$args = ' . print_r( $args, true ) . '</code></pre>';
	}

	if ( $debug_query_number ) {
		$output .= '<p><strong>' . __( 'The total number of queries so far:', 'posts-in-sidebar' ) . '</strong><br>';
		$output .= sprintf( __( '%1$s queries in %2$s seconds', 'posts-in-sidebar' ), get_num_queries(), timer_stop() );
		$output .= '</p>';
	}

	return $output;
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
	return '<!-- Generated by Posts in Sidebar v' . PIS_VERSION . $pis_cache_active . ' -->';
}


/**
 * Returns the HTML for the comments link.
 *
 * @param integer $pis_post_id The ID of the post.
 * @param boolean $link If the output is to be wrapped into a link to comments.
 * @since 3.0
 */
function pis_get_comments_number( $pis_post_id, $link ) {
	$num_comments = get_comments_number( $pis_post_id ); // get_comments_number returns only a numeric value

	if ( 0 == $num_comments && ! comments_open( $pis_post_id ) ) {
		$output = __( 'Comments are closed.', 'posts-in-sidebar' );
	} else {
		// Construct the comments string.
		if ( 1 == $num_comments ) {
			$comments = __( '1 Comment', 'posts-in-sidebar' );
		} elseif ( 1 < $num_comments ) {
			$comments = sprintf( __( '%d Comments', 'posts-in-sidebar' ), $num_comments );
		} else {
			$comments = __( 'Leave a comment', 'posts-in-sidebar' );
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
		'archive_text'   => __( 'Display all posts', 'posts-in-sidebar' ),
		'archive_margin' => NULL,
		'margin_unit'    => 'px'
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

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
				$term_link = get_post_type_archive_link( $tax_term_name );
				$post_type_object = get_post_type_object( $tax_term_name );
				$term_name = $post_type_object->labels->name;
			}
			break;

		case 'custom_taxonomy':
			$term_identity = get_term_by( 'slug', $tax_term_name, $tax_name );
			if ( $term_identity ) {
				$term_link = get_term_link( $term_identity->term_id, $tax_name );
				$term_name = $term_identity->name;
			}
			break;

		default : // This is the case of post formats
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
		$output = '<p ' . pis_paragraph( $archive_margin, $margin_unit, 'pis-archive-link', 'pis_archive_class' ) . '>';
			$output .= '<a ' . pis_class( 'pis-archive-link-class', apply_filters( 'pis_archive_link_class', '' ), false ) . ' href="' . esc_url( $term_link ) . '" rel="bookmark">';
				$output .= $archive_text;
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
 * Returns the HTML string for the author's Gravatar image.
 *
 * @param array $args The array containing the custom args.
 * @since 3.0
 */
function pis_get_gravatar( $args ) {
	$defaults = array(
		'author'    => '',
		'size'      => 32,
		'default'   => '',
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	$output = '<span ' . pis_class( 'pis-gravatar', apply_filters( 'pis_gravatar_class', '' ), false ) . '>' . get_avatar( $author, $size, $default ) . '</span>';

	return $output;
}


/**
 * Returns the tooltip text for the link to the post.
 *
 * @param $tooltip_text The text to be displayed in the tooltip.
 * @since 3.9
 */
/*function pis_tooltip( $tooltip_text ) {
	$tooltip_text = rtrim( $tooltip_text ) . ' ';
	return $tooltip_text;
}*/
