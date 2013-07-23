<?php
/**
 * Plugin Name: Posts in Sidebar
 * Description:  Publish a list of posts in your sidebar
 * Plugin URI: http://dev.aldolat.it/projects/posts-in-sidebar/
 * Author: Aldo Latino
 * Author URI: http://www.aldolat.it/
 * Version: 1.10
 * License: GPLv3 or later
 * Text Domain: pis
 * Domain Path: /languages/
 */

/*
 * Copyright (C) 2009, 2013  Aldo Latino  (email : aldolat@gmail.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

define( 'PIS_VERSION', '1.10' );

/**
 * The core function
 *
 * @since 1.0
 */
function pis_posts_in_sidebar( $args ) {
	$defaults = array(
		'intro'             => '',
		'post_type'         => 'post', // post, page, media, or any custom post type
		'author'            => NULL,   // Author nicename, NOT name
		'cat'               => NULL,   // Category slugs, comma separated
		'tag'               => NULL,   // Tag slugs, comma separated
		'post_format'       => '',
		'number'            => get_option( 'posts_per_page' ),
		'orderby'           => 'date',
		'order'             => 'DESC',
		'cat_not_in'        => '',
		'tag_not_in'        => '',
		'offset_number'     => '',
		'post_status'       => 'publish',
		'post_meta_key'     => '',
		'post_meta_val'     => '',
		'ignore_sticky'     => false,
		'display_title'     => true,
		'link_on_title'     => true,
		'arrow'             => false,
		'display_image'     => false,
		'image_size'        => 'thumbnail',
		'image_align'       => 'no_change',
		'excerpt'           => 'excerpt', // can be "full_content", "content", "excerpt", "none"
		'exc_length'        => 20,      // In words
		'the_more'          => __( 'Read more&hellip;', 'pis' ),
		'exc_arrow'         => false,
		'display_author'    => false,
		'author_text'       => __( 'By', 'pis' ),
		'linkify_author'    => false,
		'display_date'      => false,
		'date_text'         => __( 'Published on', 'pis' ),
		'linkify_date'      => false,
		'comments'          => false,
		'comments_text'     => __( 'Comments:', 'pis' ),
		'utility_sep'       => '|',
		'categories'        => false,
		'categ_text'        => __( 'Category:', 'pis' ),
		'categ_sep'         => ',',
		'tags'              => false,
		'tags_text'         => __( 'Tags:', 'pis' ),
		'hashtag'           => '#',
		'tag_sep'           => '',
		'archive_link'      => false,
		'link_to'           => 'category',
		'archive_text'      => '',
		'nopost_text'       => __( 'No posts yet.', 'pis' ),
		'margin_unit'       => 'px',
		'intro_margin'      => NULL,
		'title_margin'      => NULL,
		'excerpt_margin'    => NULL,
		'utility_margin'    => NULL,
		'categories_margin' => NULL,
		'tags_margin'       => NULL,
		'archive_margin'    => NULL,
		'noposts_margin'    => NULL,
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	$author == 'NULL' ? $author = '' : $author = $author;
	$cat    == 'NULL' ? $cat = ''    : $cat = $cat;
	$tag    == 'NULL' ? $tag = ''    : $tag = $tag;

	// Build the array to get posts
	$params = array(
		'post_type'           => $post_type,
		'author_name'         => $author, // Use nicenames.
		'category_name'       => $cat,
		'tag'                 => $tag,
		'post_format'         => $post_format,
		'posts_per_page'      => $number,
		'orderby'             => $orderby,
		'order'               => $order,
		'category__not_in'    => $cat_not_in,
		'tag__not_in'         => $tag_not_in,
		'offset'              => $offset_number,
		'post_status'         => $post_status,
		'meta_key'            => $post_meta_key,
		'meta_value'          => $post_meta_val,
		'ignore_sticky_posts' => $ignore_sticky
	);
	$linked_posts = new WP_Query( $params ); ?>

		<?php // If in a single post, get the ID of the post of the main loop ?>
		<?php if ( is_single() ) {
			global $post;
			$single_post_id = $post->ID;
		} ?>

		<?php /* The Loop */ ?>
		<?php if ( $linked_posts->have_posts() ) : ?>

			<?php if ( $intro ) { ?>
				<?php if ( ! is_null( $intro_margin ) ) $intro_style = ' style="margin-bottom: ' . $intro_margin . $margin_unit . ';"'; ?>
				<p <?php echo pis_class( 'pis-intro', apply_filters( 'pis_intro_class', $class ), false ) . $intro_style; ?>>
					<?php echo $intro; ?>
				</p>
			<?php } ?>

			<ul <?php pis_class( 'pis-ul', apply_filters( 'pis_ul_class', $class ) ); ?>>

				<?php while( $linked_posts->have_posts() ) : $linked_posts->the_post(); ?>

					<?php // Assign the class 'current-post' if this is the post of the main loop ?>
					<?php if ( is_single() && $single_post_id == $linked_posts->post->ID ) {
						$postclass = 'current-post pis-li';
					} else {
						$postclass = 'pis-li';
					} ?>

					<li <?php pis_class( $postclass, apply_filters( 'pis_li_class', $class ) ); ?>>

						<?php /* The title */ ?>
						<?php if ( $display_title ) { ?>
							<?php if ( ! is_null( $title_margin ) ) $title_style = ' style="margin-bottom: ' . $title_margin . $margin_unit . ';"'; ?>
							<p <?php echo pis_class( 'pis-title', apply_filters( 'pis_title_class', $class ), false ) . $title_style; ?>>
								<?php if ( $link_on_title ) { ?>
									<?php $title_link = sprintf( __( 'Permalink to %s', 'pis' ), the_title_attribute( 'echo=0' ) ); ?>
									<a <?php pis_class( 'pis-title-link', apply_filters( 'pis_title_link_class', $class ) ); ?> href="<?php the_permalink(); ?>" title="<?php echo esc_attr( $title_link ); ?>" rel="bookmark">
								<?php } ?>
										<?php the_title(); ?>
										<?php if ( $arrow ) { ?>
											&nbsp;<span <?php pis_class( 'pis-arrow', apply_filters( 'pis_arrow_class', $class ) ); ?>>&rarr;</span>
										<?php } ?>
								<?php if ( $link_on_title ) { ?>
									</a>
								<?php } ?>
							</p>
						<?php } // Close Display title ?>

						<?php /* The post content */ ?>
						<?php if ( ( $display_image && has_post_thumbnail() ) || 'none' != $excerpt ) { ?>

							<?php if ( ! is_null( $excerpt_margin ) ) $excerpt_style = ' style="margin-bottom: ' . $excerpt_margin . $margin_unit . ';"'; ?>
							<p <?php echo pis_class( 'pis-excerpt', apply_filters( 'pis_excerpt_class', $class ), false ) . $excerpt_style; ?>>

								<?php /* The thumbnail */ ?>
								<?php if ( $display_image ) {
									if ( has_post_thumbnail() ) { ?>
										<?php
										switch ( $image_align ) {
											case 'left' :
												$image_style = ' alignleft';
												break;
											case 'right':
												$image_style = ' alignright';
												break;
											case 'center':
												$image_style = ' aligncenter';
												break;
											default:
												$image_style = '';
												break;
										} ?>
										<a <?php pis_class( 'pis-thumbnail-link', apply_filters( 'pis_thumbnail_link_class', $class ) ); ?> href="<?php the_permalink(); ?>" title="<?php echo esc_attr( $title_link ); ?>" rel="bookmark">
											<?php the_post_thumbnail(
												$image_size,
												array(
													'class' => 'pis-thumbnail-img' . ' ' . apply_filters( 'pis_thumbnail_class', $thumb_class ),
												)
											); ?></a>
									<?php } // Close if ( has_post_thumbnail )  */
								} // Close if ( $display_image ) ?>

								<?php /* The text */ ?>
								<?php /*
									"Full content" = the content of the post as displayed in the page.
									"Content"      = the full text of the content, whitout any ornament.
									"Excerpt"      = the excerpt as defined by the user or generated by WordPress.
								*/ ?>
								<?php switch ( $excerpt ) {
									case 'full_content':
										the_content();
										break;
									case 'content':
										if ( post_password_required() ) {
											echo get_the_password_form();
										} else {
											// Remove shortcodes
											$content = strip_shortcodes( $linked_posts->post->post_content );
											// remove any HTML tag
											$content = wp_kses( $content, array() );
											echo apply_filters( 'pis_content', $content );
										}
										break;
									case 'excerpt':
										# code...
										if ( post_password_required( $post_id ) ) {
											echo get_the_password_form();
										} else {
											// If we have a user-defined excerpt...
											if ( $linked_posts->post->post_excerpt ) {
												the_excerpt();
											} else {
											// ... else generate an excerpt
												$excerpt_text = strip_shortcodes( $linked_posts->post->post_content );
												$excerpt_text = wp_trim_words( $excerpt_text, $exc_length, '&hellip;' );
												echo $excerpt_text;
											}

											/* The 'Read more' and the Arrow */ ?>
											<?php if ( $the_more || $exc_arrow ) {
												if ( $exc_arrow ) $the_arrow = '<span ' . pis_class( 'pis-arrow', apply_filters( 'pis_arrow_class', $class ), false ) . '>&rarr;</span>'; ?>
												<span <?php pis_class( 'pis-more', apply_filters( 'pis_more_class', $class ) ); ?>>
													<a href="<?php echo the_permalink(); ?>" title="<?php esc_attr_e( 'Read the full post', 'pis' ); ?>" rel="bookmark">
														<?php echo $the_more . '&nbsp;' . $the_arrow; ?>
													</a>
												</span>
											<?php }
										}
								} ?>
								<?php // Close The text ?>

							</p>

						<?php }	// Close The content ?>

						<?php /* The author, the date and the comments */ ?>
						<?php if ( $display_author || $display_date || $comments ) { ?>
							<?php if ( ! is_null( $utility_margin ) ) $utility_style = ' style="margin-bottom: ' . $utility_margin . $margin_unit . ';"'; ?>
							<p <?php echo pis_class( 'pis-utility', apply_filters( 'pis_utility_class', $class ), false ) . $utility_style; ?>>
						<?php } ?>

							<?php /* The author */ ?>
							<?php if ( $display_author ) { ?>
								<span <?php pis_class( 'pis-author', apply_filters( 'pis_author_class', $class ) ); ?>>
									<?php if ( $author_text ) echo $author_text . '&nbsp;'; ?><?php
									if ( $linkify_author ) { ?>
										<?php
										$author_title = sprintf( __( 'View all posts by %s', 'pis' ), get_the_author() );
										$author_link  = get_author_posts_url( get_the_author_meta( 'ID' ) );
										?>
										<a <?php pis_class( 'pis-author-link', apply_filters( 'pis_author_link_class', $class ) ); ?> href="<?php echo $author_link; ?>" title="<?php echo esc_attr( $author_title ); ?>" rel="author">
											<?php echo get_the_author(); ?></a>
									<?php } else {
										echo get_the_author();
									} ?>
								</span>
							<?php } ?>

							<?php /* The date */ ?>
							<?php if ( $display_date ) { ?>
								<?php if ( $display_author ) { ?>
									<span <?php pis_class( 'pis-separator', apply_filters( 'pis_separator_class', $class ) ); ?>>&nbsp;<?php echo $utility_sep; ?>&nbsp;</span>
								<?php } ?>
								<span <?php pis_class( 'pis-date', apply_filters( 'pis_date_class', $class ) ); ?>>
									<?php if ( $date_text ) echo $date_text . '&nbsp;'; ?><?php
									if ( $linkify_date ) { ?>
										<?php $date_title = sprintf( __( 'Permalink to %s', 'pis' ), the_title_attribute( 'echo=0' ) ); ?>
										<a <?php pis_class( 'pis-date-link', apply_filters( 'pis_date_link_class', $class ) ); ?> href="<?php the_permalink(); ?>" title="<?php echo esc_attr( $date_title ); ?>" rel="bookmark">
											<?php echo get_the_date(); ?></a>
									<?php } else {
										echo get_the_date();
									} ?>
								</span>

							<?php } ?>

							<?php /* The comments */ ?>
							<?php if ( $comments ) { ?>
								<?php if ( $display_author || $display_date ) { ?>
									<span <?php pis_class( 'pis-separator', apply_filters( 'pis_separator_class', $class ) ); ?>>&nbsp;<?php echo $utility_sep; ?>&nbsp;</span>
								<?php } ?>
								<span <?php pis_class( 'pis-comments', apply_filters( 'pis_comments_class', $class ) ); ?>>
									<?php if ( $comments_text ) echo $comments_text . '&nbsp;'; ?><?php
									comments_popup_link( '<span class="pis-reply">' . __( 'Leave a comment', 'pis' ) . '</span>', __( '1 Comment', 'pis' ), __( '% Comments', 'pis' ) ); ?>
								</span>
							<?php } ?>

						<?php if ( $display_author || $display_date || $comments ) { ?>
							</p>
						<?php } ?>

						<?php /* The categories */ ?>
						<?php if ( $categories ) {
							$list_of_categories = get_the_category_list( $categ_sep . ' ', '', $linked_posts->post->ID );
							if ( $list_of_categories ) { ?>
								<?php if ( ! is_null( $categories_margin ) ) $categories_style = ' style="margin-bottom: ' . $categories_margin . $margin_unit . ';"'; ?>
								<p <?php echo pis_class( 'pis-categories-links', apply_filters( 'pis_categories_class', $class ), false ) . $categories_style; ?>>
									<?php if ( $categ_text ) $categ_text .= '&nbsp';
									echo $categ_text . apply_filters(  'pis_categories_list', $list_of_categories );
									?>
								</p>
							<?php }
						} ?>

						<?php /* The tags */ ?>
						<?php if ( $tags ) {
							$list_of_tags = get_the_term_list( $linked_posts->post->ID, 'post_tag', $hashtag, $tag_sep . ' ' . $hashtag, '' );
							if ( $list_of_tags ) { ?>
								<?php if ( ! is_null( $tags_margin ) ) $tags_style = ' style="margin-bottom: ' . $tags_margin . $margin_unit . ';"'; ?>
								<p <?php echo pis_class( 'pis-tags-links', apply_filters( 'pis_tags_class', $class ), false ) . $tags_style; ?>>
									<?php if ( $tags_text ) $tags_text .= '&nbsp;';
									echo $tags_text . apply_filters( 'pis_tags_list', $list_of_tags );
									?>
								</p>
							<?php }
						} ?>

					</li>

				<?php endwhile; ?>

			</ul>
			<!-- / ul#pis-ul -->

			<?php /* The link to the entire archive */ ?>
			<?php if ( $archive_link ) {

				$wp_post_type = array( 'post', 'page', 'media', 'any' );

				if ( $link_to == 'author' && isset( $author ) ) {
					$author_infos = get_user_by( 'slug', $author );
					if ( $author_infos ) {
						$term_link = get_author_posts_url( $author_infos->ID, $author );
						$title_text = sprintf( __( 'Display all posts by %s', 'pis' ), $author_infos->display_name );
					}
				} elseif ( $link_to == 'category' && isset( $cat ) ) {
					$term_identity = get_term_by( 'slug', $cat, 'category' );
					if ( $term_identity ) {
						$term_link = get_category_link( $term_identity->term_id );
						$title_text = sprintf( __( 'Display all posts archived as %s', 'pis' ), $term_identity->name );
					}
				} elseif ( $link_to == 'tag' && isset( $tag ) ) {
					$term_identity = get_term_by( 'slug', $tag, 'post_tag' );
					if ( $term_identity ) {
						$term_link = get_tag_link( $term_identity->term_id );
						$title_text = sprintf( __( 'Display all posts archived as %s', 'pis' ), $term_identity->name );
					}
				} elseif ( ! in_array( $post_type, $wp_post_type ) ) {
					$term_link = get_post_type_archive_link( $link_to );
					$post_type_object = get_post_type_object( $link_to );
					$title_text = sprintf( __( 'Display all posts archived as %s', 'pis' ), $post_type_object->labels->name );
				} elseif ( term_exists( $link_to, 'post_format' ) && $link_to == $post_format ) {
					$term_link = get_post_format_link( substr( $link_to, 12 ) );
					$term_object = get_term_by( 'slug', $link_to, 'post_format' );
					$title_text = sprintf( __( 'Display all posts with post format %s', 'pis' ), $term_object->name );
				}

				// If the user has choosen to display the archive link but the text has not been setup, show this text
				if ( $archive_text == '' ) {
					$archive_text = $title_text;
				}

				if ( isset( $term_link ) ) { ?>
					<?php if ( ! is_null( $archive_margin ) ) $archive_style = ' style="margin-bottom: ' . $archive_margin . $margin_unit . ';"'; ?>
					<p <?php echo pis_class( 'pis-archive-link', apply_filters( 'pis_archive_class', $class ), false ) . $archive_style; ?>>
						<a <?php pis_class( 'pis-archive-link-class', apply_filters( 'pis_archive_link_class', $class ) ); ?> href="<?php echo $term_link; ?>" title="<?php echo esc_attr( $title_text ); ?>" rel="bookmark">
							<?php echo $archive_text; ?>
						</a>
					</p>
				<?php }
			} ?>

		<?php /* If we have no posts yet */ ?>
		<?php else : ?>

			<?php if ( $nopost_text ) { ?>
				<ul <?php pis_class( 'pis-ul', apply_filters( 'pis_ul_class', $class ) ); ?>>
					<li <?php pis_class( 'pis-li pis-noposts', apply_filters( 'pis_nopost_class', $class ) ); ?>>
						<?php if ( ! is_null( $noposts_margin ) ) $noposts_style = ' style="margin-bottom: ' . $noposts_margin . $margin_unit . ';"'; ?>
						<p <?php echo pis_class( 'noposts', apply_filters( 'pis_noposts_class', $class ), false ) . $noposts_style; ?>>
							<?php echo $nopost_text; ?>
						</p>
					</li>
				</ul>
			<?php } ?>

		<?php endif; ?>

		<?php /* Reset this custom query */ ?>
		<?php wp_reset_postdata(); ?>

		<?php echo '<!-- Generated by Posts in Sidebar v' . PIS_VERSION . ' -->'; ?>

<?php }


/**
 * Return the class for the HTML element
 *
 * @since 1.9
 *
 * @param string $default One or more classes, defined by plugin's developer, to add to the class list.
 * @param string|array $class One or more classes, defined by the user, to add to the class list.
 * @param boolean $echo If the function should echo or not the output.
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
 * Include the widget
 *
 * @since 1.1
 */
include_once( plugin_dir_path( __FILE__ ) . 'posts-in-sidebar-widget.php' );


/**
 * Make plugin available for i18n
 *
 * Translations must be archived in the /languages/ directory
 * The name of each translation file must be:
 *
 * ITALIAN:
 * pis-it_IT.po
 * pis-it_IT.mo
 *
 * GERMAN:
 * pis-de_DE.po
 * pis-de_DE.po
 *
 * and so on.
 *
 * @since 0.1
 */
function pis_load_languages() {
	load_plugin_textdomain( 'pis', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
}
add_action( 'plugins_loaded', 'pis_load_languages' );

/***********************************************************************
 *                            CODE IS POETRY
 **********************************************************************/
