<?php
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

	if ( true === $echo )
		echo apply_filters( 'pis_classes', $classes );
	else
		return apply_filters( 'pis_classes', $classes );
}


/**
 * Return the paragraph class with inline style
 *
 * @since 1.12
 *
 * @param string $margin The margin of the paragraph.
 * @param string $unit The unit measure to be used.
 * @param string $class The default class defined by the plugin's developer.
 * @param string $class_filter The name of the class filter.
 * @param boolean $class_echo If the pis_class() function should echo or not the output.
 * @return string $output The class and the inline style.
 * @uses pis_class()
 */
function pis_paragraph( $margin, $unit, $class, $class_filter ) {
	( ! is_null( $margin ) ) ? $style = ' style="margin-bottom: ' . $margin . $unit . ';"' : $style = '';
	$output = pis_class( $class, apply_filters( $class_filter, '' ) ) . $style;
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
 * @since 1.15
 * @return string $output The HTML arrow.
 * @uses pis_class()
 */
function pis_arrow() {
	$the_arrow = '&rarr;';
	if ( is_rtl() ) $the_arrow = '&larr;';

	$output = '&nbsp;<span ' . pis_class( 'pis-arrow', apply_filters( 'pis_arrow_class', '' ), false ) . '>' . $the_arrow . '</span>';

	return $output;
}


/**
 * Generate the output for the more and/or the HTML arrow.
 *
 * @since 1.15
 * @uses pis_arrow()
 * @param string $the_more The text to be displayed for "Continue reading". Default empty.
 * @param boolean $exc_arrow If the arrow must be displayed or not. Default false.
 * @return string The HTML arrow linked to the post.
 */
function pis_more_arrow( $the_more = '', $exc_arrow = false ) {
	if ( $the_more || $exc_arrow ) {
		if ( $exc_arrow ) {
			$the_arrow = pis_arrow();
		} else {
			$the_arrow = '';
		} ?>
		<span <?php pis_class( 'pis-more', apply_filters( 'pis_more_class', '' ) ); ?>>
			<a href="<?php echo the_permalink(); ?>" title="<?php esc_attr_e( 'Read the full post', 'pis' ); ?>" rel="bookmark">
				<?php echo $the_more . '&nbsp;' . $the_arrow; ?>
			</a>
		</span>
	<?php }
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
	if ( $styles ) echo '<style type="text/css">' . $styles . '</style>';
}
add_action( 'wp_head', 'pis_add_styles_to_head' );


/**
 * Add the utilities section: author, date of the post and comments.
 *
 * @since 1.18
 * @return The HTML for the section.
 * @uses pis_paragraph()
 * @uses pis_class()
 */
function pis_utility_section( $display_author, $display_date, $comments, $utility_margin, $margin_unit, $author_text, $linkify_author, $utility_sep, $date_text, $linkify_date, $comments_text ) { ?>
	<?php if ( $display_author || $display_date || $comments ) { ?>
		<p <?php echo pis_paragraph( $utility_margin, $margin_unit, 'pis-utility', 'pis_utility_class' ); ?>>
	<?php } ?>

		<?php /* The author */ ?>
		<?php if ( $display_author ) { ?>
			<span <?php pis_class( 'pis-author', apply_filters( 'pis_author_class', '' ) ); ?>>
				<?php if ( $author_text ) echo $author_text . '&nbsp;'; ?><?php
				if ( $linkify_author ) { ?>
					<?php
					$author_title = sprintf( __( 'View all posts by %s', 'pis' ), get_the_author() );
					$author_link  = get_author_posts_url( get_the_author_meta( 'ID' ) );
					?>
					<a <?php pis_class( 'pis-author-link', apply_filters( 'pis_author_link_class', '' ) ); ?> href="<?php echo $author_link; ?>" title="<?php echo esc_attr( $author_title ); ?>" rel="author">
						<?php echo get_the_author(); ?></a>
				<?php } else {
					echo get_the_author();
				} ?>
			</span>
		<?php } ?>

		<?php /* The date */ ?>
		<?php if ( $display_date ) : ?>
			<?php if ( $display_author ) { ?>
				<span <?php pis_class( 'pis-separator', apply_filters( 'pis_separator_class', '' ) ); ?>>&nbsp;<?php echo $utility_sep; ?>&nbsp;</span>
			<?php } ?>
			<span <?php pis_class( 'pis-date', apply_filters( 'pis_date_class', '' ) ); ?>>
				<?php if ( $date_text ) echo $date_text . '&nbsp;'; ?><?php
				if ( $linkify_date ) { ?>
					<?php $date_title = sprintf( __( 'Permalink to %s', 'pis' ), the_title_attribute( 'echo=0' ) ); ?>
					<a <?php pis_class( 'pis-date-link', apply_filters( 'pis_date_link_class', '' ) ); ?> href="<?php the_permalink(); ?>" title="<?php echo esc_attr( $date_title ); ?>" rel="bookmark">
						<?php echo get_the_date(); ?></a>
				<?php } else {
					echo get_the_date();
				} ?>
			</span>

		<?php endif; ?>

		<?php /* The comments */ ?>
		<?php if ( ! post_password_required() ) : ?>
			<?php if ( $comments ) { ?>
				<?php if ( $display_author || $display_date ) { ?>
					<span <?php pis_class( 'pis-separator', apply_filters( 'pis_separator_class', '' ) ); ?>>&nbsp;<?php echo $utility_sep; ?>&nbsp;</span>
				<?php } ?>
				<span <?php pis_class( 'pis-comments', apply_filters( 'pis_comments_class', '' ) ); ?>>
					<?php if ( $comments_text ) echo $comments_text . '&nbsp;'; ?><?php
					comments_popup_link( '<span class="pis-reply">' . __( 'Leave a comment', 'pis' ) . '</span>', __( '1 Comment', 'pis' ), __( '% Comments', 'pis' ) ); ?>
				</span>
			<?php } ?>
		<?php endif; ?>

	<?php if ( $display_author || $display_date || $comments ) : ?>
		</p>
	<?php endif; ?>
<?php }


/**
 * Add the thumbnail of the post.
 *
 * @since 1.18
 * @return The HTML for the thumbnail.
 */
function pis_the_thumbnail( $display_image, $image_align, $side_image_margin, $bottom_image_margin, $margin_unit, $title_link, $pis_query, $image_size, $thumb_wrap = false, $custom_image_url = '', $custom_img_no_thumb, $post_type ) {
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
	} ?>
	<?php echo $open_wrap; ?>
	<a <?php pis_class( 'pis-thumbnail-link', apply_filters( 'pis_thumbnail_link_class', '' ) ); ?> href="<?php the_permalink(); ?>" title="<?php echo esc_attr( $title_link ); ?>" rel="bookmark">
		<?php
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
		$image_html = str_replace( '<img', '<img' . $image_style, $image_html );
		echo $image_html;
		?></a>		
	<?php echo $close_wrap;
}


/**
 * Add the text of the post in form of excerpt, full post, and so on.
 *
 * @since 1.18
 * @return The HTML for the text of the post.
 * @uses pis_break_text()
 * @uses pis_more_arrow()
 */
function pis_the_text( $excerpt, $pis_query, $exc_length, $the_more, $exc_arrow ) {
	/*
		"Full content"   = the content of the post as displayed in the page.
		"Rich content"   = the content with inline images, titles and more (shortcodes will be executed).
		"Content"        = the full text of the content, whitout any ornament (shortcodes will be stripped).
		"More excerpt"   = the excerpt up to the point of the "more" tag (inserted by the user).
		"Excerpt"        = the excerpt as defined by the user or generated by WordPress.
		"Only Read more" = no excerpt, only the Read more link
	*/
	switch ( $excerpt ) :

		case 'full_content':
			the_content();
		break;

		case 'rich_content':
			$content = $pis_query->post->post_content;
			// Honor any paragraph break
			$content = pis_break_text( $content );
			echo apply_filters( 'pis_rich_content', $content );
		break;

		case 'content':
			// Remove shortcodes
			$content = strip_shortcodes( $pis_query->post->post_content );
			// remove any HTML tag
			$content = wp_kses( $content, array() );
			// Honor any paragraph break
			$content = pis_break_text( $content );
			echo apply_filters( 'pis_content', $content );
		break;

		case 'more_excerpt':
			$excerpt_text = strip_shortcodes( $pis_query->post->post_content );
			$testformore = strpos( $excerpt_text, '<!--more-->' );
			if ( $testformore ) {
				$excerpt_text = substr( $excerpt_text, 0, $testformore );
			} else {
				$excerpt_text = wp_trim_words( $excerpt_text, $exc_length, '&hellip;' );
			}
			echo apply_filters( 'pis_more_excerpt_text', $excerpt_text );
			pis_more_arrow( $the_more, $exc_arrow );
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
				echo apply_filters( 'pis_user_excerpt', $user_excerpt );
			} else {
			// ... else generate an excerpt
				$excerpt_text = strip_shortcodes( $pis_query->post->post_content );
				$excerpt_text = wp_trim_words( $excerpt_text, $exc_length, '&hellip;' );
				echo apply_filters( 'pis_excerpt_text', $excerpt_text );
			}
			pis_more_arrow( $the_more, $exc_arrow );
		break;

		case 'only_read_more':
			$excerpt_text = '';
			echo apply_filters( 'pis_only_read_more', $excerpt_text );
			pis_more_arrow( $the_more, $exc_arrow );
		break;

	endswitch;
	// Close The text
}


/**
 * Display the custom taxonomies of the current post.
 * 
 * @since 1.29
 * @see https://codex.wordpress.org/Function_Reference/get_the_terms#Get_terms_for_all_custom_taxonomies
 */
// get taxonomies terms links
function pis_custom_taxonomies_terms_links( $postID, $term_hashtag, $term_sep, $terms_margin, $margin_unit ) {
	// get post by post id
	$post = get_post( $postID );

	// get post type by post
	$post_type = $post->post_type;

	// get post type taxonomies
	$taxonomies = get_object_taxonomies( $post_type, 'objects' );

	foreach ( $taxonomies as $taxonomy_slug => $taxonomy ) {
		// Exclude the standard WordPress 'category' and 'post_tag' taxonomies otherwise we'll have a duplicate in the front-end.
		if ( 'category' != $taxonomy_slug && 'post_tag' != $taxonomy_slug ) {
			// get the terms related to post
			$list_of_terms = get_the_term_list( $postID, $taxonomy_slug, $term_hashtag, $term_sep . ' ' . $term_hashtag, '' );
			if ( $list_of_terms ) { ?>
				<p <?php echo pis_paragraph( $terms_margin, $margin_unit, 'pis-terms-links pis-' . $taxonomy_slug, 'pis_terms_class' ); ?>>
					<span class="pis-tax-name"><?php echo $taxonomy->label; ?></span>: <?php echo apply_filters( 'pis_terms_list', $list_of_terms ); ?>
				</p>
			<?php }
		}
	}
}


/**
 * Build the query based on taxonomies.
 * 
 * @since 1.29
 */
function pis_tax_query( $relation, $taxonomy_aa, $field_aa, $terms_aa, $operator_aa, $relation_a, $taxonomy_ab, $field_ab, $terms_ab, $operator_ab, $taxonomy_ba, $field_ba, $terms_ba, $operator_ba, $relation_b, $taxonomy_bb, $field_bb, $terms_bb, $operator_bb ) {
	if ( '' == $taxonomy_aa && '' == $terms_aa ) {
		$tax_query = '';
	} else {
		// Convert terms into arrays
		$terms_aa = explode( ',', $terms_aa );
		if ( $terms_ab ) $terms_ab = explode( ',', $terms_ab );
		if ( $terms_ba ) $terms_ba = explode( ',', $terms_ba );
		if ( $terms_bb ) $terms_bb = explode( ',', $terms_bb );

		// Let's figure out the tax_query to build
		if ( $taxonomy_aa && !$taxonomy_ab && !$taxonomy_ba && !$taxonomy_bb ) {
			$tax_query = array(
				array(
					'taxonomy' => $taxonomy_aa,
					'field'    => $field_aa,
					'terms'    => $terms_aa, // This must be an array
					'operator' => $operator_aa,
				)
			);
		} else if ( $taxonomy_aa && $taxonomy_ab && !$taxonomy_ba && !$taxonomy_bb ) {
			$tax_query = array(
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
				)
			);
		} else if ( $taxonomy_aa && $taxonomy_ab && $taxonomy_ba && !$taxonomy_bb && !empty( $relation ) ) {
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
		} else if ( $taxonomy_aa && !$taxonomy_ab && $taxonomy_ba && !$taxonomy_bb && !empty( $relation ) ) {
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
		} else if ( $taxonomy_aa && !$taxonomy_ab && $taxonomy_ba && $taxonomy_bb && !empty( $relation ) ) {
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
		} else if ( $taxonomy_aa && $taxonomy_ab && $taxonomy_ba && $taxonomy_bb && !empty( $relation ) ) {
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
	if ( isset( $tax_query ) )
		return $tax_query;
	else
		return '';
}


/**
 * Remove empty keys from an array recursively.
 * 
 * @since 1.29
 * @see http://stackoverflow.com/questions/7696548/php-how-to-remove-empty-entries-of-an-array-recursively
 */
function pis_array_remove_empty_keys( $array ) {
	foreach ( $array as $key => $value ) {
		if ( is_array( $value ) ) {
			$array[$key] = pis_array_remove_empty_keys( $array[$key] );
		}
		if ( empty( $array[$key] ) ) {
			unset( $array[$key] );
		}
	}
	return $array;
}
