<?php
/**
 * Plugin Name: Posts in Sidebar
 * Plugin URI: http://dev.aldolat.it/projects/posts-in-sidebar/
 * Description: Publish a list of posts in your sidebar
 * Version: 1.27
 * Author: Aldo Latino
 * Author URI: http://www.aldolat.it/
 * Text Domain: pis
 * Domain Path: /languages/
 * License: GPLv3 or later
 */

/* Copyright (C) 2009, 2015  Aldo Latino  (email : aldolat@gmail.com)

   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.
   
   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.
   
   You should have received a copy of the GNU General Public License
   along with this program. If not, see <http://www.gnu.org/licenses/>.
*/


/**
 * Launch Posts in Sidebar.
 * 
 * @since 1.27
 */
add_action( 'plugins_loaded', 'pis_setup' );


/**
 * Setup Posts in Sidebar.
 * 
 * @since 1.27
 */
function pis_setup() {

	/* Define the version of the plugin. */
	define( 'PIS_VERSION', '1.27' );

	/**
	 * Make plugin available for i18n.
	 * Translations must be archived in the /languages/ directory.
	 * The name of each translation file must be, for example:
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
	load_plugin_textdomain( 'pis', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');

	/**
	 * Load the plugin's functions.
	 *
	 * @since 1.23
	 */
	require_once( plugin_dir_path( __FILE__ ) . 'inc/pis-functions.php' );

	/* Load Posts in Sidebar's widgets. */
	add_action( 'widgets_init', 'pis_load_widgets' );
}


/**
 * Register the widget
 *
 * @since 1.0
 */
function pis_load_widgets() {

	/**
	 * Load the widget's form functions.
	 *
	 * @since 1.12
	 */
	require_once( plugin_dir_path( __FILE__ ) . 'inc/widget-form-functions.php' );

	/**
	 * Load the widget's PHP file.
	 *
	 * @since 1.1
	 */
	require_once( plugin_dir_path( __FILE__ ) . 'inc/posts-in-sidebar-widget.php' );

	register_widget( 'PIS_Posts_In_Sidebar' );
}


/**
 * The core function.
 *
 * @since 1.0
 * @param mixed $args The options for the main function.
 */
function pis_posts_in_sidebar( $args ) {
	$defaults = array(
		// The title of the widget
		'intro'               => '',

		// Posts retrieving
		'post_type'           => 'post',    // post, page, media, or any custom post type
		'posts_id'            => '',        // Post/Pages IDs, comma separated
		'author'              => '',        // Author nicename
		'cat'                 => '',        // Category slugs, comma separated
		'tag'                 => '',        // Tag slugs, comma separated
		'post_format'         => '',
		'number'              => get_option( 'posts_per_page' ),
		'orderby'             => 'date',
		'order'               => 'DESC',
		'offset_number'       => '',
		'post_status'         => 'publish',
		'post_meta_key'       => '',
		'post_meta_val'       => '',
		'ignore_sticky'       => false,

		// Posts exclusion
		'exclude_current_post'=> false,
		'post_not_in'         => '',
		'cat_not_in'          => '',        // Category ID, comma separated
		'tag_not_in'          => '',        // Tag ID, comma separated

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
		'excerpt'             => 'excerpt', // can be "full_content", "rich_content", "content", "more_excerpt", "excerpt", "none"
		'exc_length'          => 20,        // In words
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

		// When no posts found
		'nopost_text'         => __( 'No posts yet.', 'pis' ),
		'hide_widget'         => false,

		// Extras
		'list_element'        => 'ul',
		'remove_bullets'      => false,

		// Cache
		'cached'              => false,
		'cache_time'          => 3600,
		'widget_id'           => '',

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
		'archive_margin'      => NULL,
		'noposts_margin'      => NULL,

		// Debug
		'debug_query'         => false,
		'debug_params'        => false,
		'debug_query_number'  => false,

	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	// Some params accept only an array
	if ( $posts_id    && ! is_array( $posts_id ) )    $posts_id    = explode( ',', $posts_id );    else $posts_id    = '';
	if ( $post_not_in && ! is_array( $post_not_in ) ) $post_not_in = explode( ',', $post_not_in ); else $post_not_in = '';
	if ( $cat_not_in  && ! is_array( $cat_not_in ) )  $cat_not_in  = explode( ',', $cat_not_in );  else $cat_not_in  = '';
	if ( $tag_not_in  && ! is_array( $tag_not_in ) )  $tag_not_in  = explode( ',', $tag_not_in );  else $tag_not_in  = '';

	// Get the ID of the current post.
	// This will be used in case the user do not want to display the same post in the main body and in the sidebar.
	if ( ( is_single() || is_page() ) && $exclude_current_post ) {
		$post_not_in[] = get_the_id();
	}

	// Build the array to get posts
	$params = array(
		'post_type'           => $post_type,
		'post__in'            => $posts_id,    // Uses ids
		'author_name'         => $author,      // Uses nicenames
		'category_name'       => $cat,         // Uses category slugs
		'tag'                 => $tag,         // Uses tag slugs 
		'post_format'         => $post_format,
		'posts_per_page'      => $number,
		'orderby'             => $orderby,
		'order'               => $order,
		'post__not_in'        => $post_not_in, // Uses ids
		'category__not_in'    => $cat_not_in,  // Uses ids
		'tag__not_in'         => $tag_not_in,  // uses ids
		'offset'              => $offset_number,
		'post_status'         => $post_status,
		'meta_key'            => $post_meta_key,
		'meta_value'          => $post_meta_val,
		'ignore_sticky_posts' => $ignore_sticky
	);

	// If the user has chosen a cached version of the widget output...
	if ( $cached ) {

		// Get the cached query
		$pis_query = get_transient( $widget_id . '_query_cache' );

		// If it does not exist, create a new query and cache it for future uses
		if ( ! $pis_query ) {
			$pis_query = new WP_Query( $params );
			set_transient( $widget_id . '_query_cache', $pis_query, $cache_time );
		}

	// ... otherwise serve a not-cached version of the output.
	} else {

		$pis_query = new WP_Query( $params );

	} ?>

	<?php // If in a single post, get the ID of the post of the main loop. This will be used to add the "current-post" CSS class. ?>
	<?php if ( is_single() ) {
		global $post;
		$single_post_id = $post->ID;
	} ?>

	<?php /* The Loop */ ?>
	<?php if ( $pis_query->have_posts() ) : ?>

		<?php if ( $intro ) { ?>
			<p <?php echo pis_paragraph( $intro_margin, $margin_unit, 'pis-intro', 'pis_intro_class' ); ?>>
				<?php echo pis_break_text( $intro ); ?>
			</p>
		<?php } ?>

		<?php
			// When updating from 1.14, the $list_element variable is empty.
			if ( ! $list_element ) $list_element = 'ul';
		?>
		<?php if ( $remove_bullets && $list_element == 'ul' ) $bullets_style = ' style="list-style-type:none; margin-left:0; padding-left:0;"'; else $bullets_style = ''; ?>
		<<?php echo $list_element; ?> <?php pis_class( 'pis-ul', apply_filters( 'pis_ul_class', '' ) ); echo $bullets_style; ?>>

			<?php while( $pis_query->have_posts() ) : $pis_query->the_post(); ?>

				<?php
				/**
				 * Assign the class 'current-post' if this is the post of the main loop.
				 *
				 * @since 1.6
				 */
				$current_post_class = ''; 
				if ( is_single() && $single_post_id == $pis_query->post->ID ) {
					$current_post_class = ' current-post';
				} 

				/**
				 * Assign the class 'sticky' if the post is sticky.
				 *
				 * @since 1.25
				 */
				 $sticky_class = '';
				 if ( is_sticky() ) {
				 	$sticky_class = ' sticky';
				 } ?>

				<li <?php pis_class( 'pis-li' . $current_post_class . $sticky_class, apply_filters( 'pis_li_class', '' ) ); ?>>

					<?php /* The thumbnail before the title */ ?>
					<?php if ( $image_before_title ) : ?>

						<?php if ( $display_image && ( has_post_thumbnail() || $custom_image_url ) ) {
							$title_link = sprintf( __( 'Permalink to %s', 'pis' ), the_title_attribute( 'echo=0' ) );
							pis_the_thumbnail( $display_image, $image_align, $side_image_margin, $bottom_image_margin, $margin_unit, $title_link, $pis_query, $image_size, $thumb_wrap = true, $custom_image_url, $custom_img_no_thumb );
						} ?>

					<?php endif; // Close if $image_before_title ?>

					<?php /* The title */ ?>
					<?php if ( $display_title ) { ?>
						<p <?php echo pis_paragraph( $title_margin, $margin_unit, 'pis-title', 'pis_title_class' ); ?>>
							<?php if ( $link_on_title ) { ?>
								<?php $title_link = sprintf( __( 'Permalink to %s', 'pis' ), the_title_attribute( 'echo=0' ) ); ?>
								<a <?php pis_class( 'pis-title-link', apply_filters( 'pis_title_link_class', '' ) ); ?> href="<?php the_permalink(); ?>" title="<?php echo esc_attr( $title_link ); ?>" rel="bookmark">
							<?php } ?>
									<?php the_title(); ?>
									<?php if ( $arrow ) { ?>
										<?php echo pis_arrow(); ?>
									<?php } ?>
							<?php if ( $link_on_title ) { ?>
								</a>
							<?php } ?>
						</p>
					<?php } // Close Display title ?>

					<?php /* The author, the date and the comments */ ?>
					<?php if ( $utility_after_title ) {
						pis_utility_section( $display_author, $display_date, $comments, $utility_margin, $margin_unit, $author_text, $linkify_author, $utility_sep, $date_text, $linkify_date, $comments_text );
					} ?>

					<?php /* The post content */ ?>
					<?php if ( ! post_password_required() ) : ?>
						
						<?php if ( ( $display_image && has_post_thumbnail() ) || 'none' != $excerpt ) : ?>

							<p <?php echo pis_paragraph( $excerpt_margin, $margin_unit, 'pis-excerpt', 'pis_excerpt_class' ); ?>>

								<?php if ( ! $image_before_title ) : ?>

									<?php /* The thumbnail */ ?>
									<?php if ( $display_image && ( has_post_thumbnail() || $custom_image_url ) ) {
										$title_link = sprintf( __( 'Permalink to %s', 'pis' ), the_title_attribute( 'echo=0' ) );
										pis_the_thumbnail( $display_image, $image_align, $side_image_margin, $bottom_image_margin, $margin_unit, $title_link, $pis_query, $image_size, $thumb_wrap = false, $custom_image_url, $custom_img_no_thumb );
									} // Close if ( $display_image && has_post_thumbnail ) ?>

								<?php endif; // Close if $image_before_title ?>

								<?php /* The text */ ?>
								<?php pis_the_text( $excerpt, $pis_query, $exc_length, $the_more, $exc_arrow ); ?>

							</p>

						<?php endif; // Close if $display_image ?>

					<?php endif; // Close if post password required ?>

					<?php /* The author, the date and the comments */ ?>
					<?php if ( ! $utility_after_title ) {
						pis_utility_section( $display_author, $display_date, $comments, $utility_margin, $margin_unit, $author_text, $linkify_author, $utility_sep, $date_text, $linkify_date, $comments_text );
					} ?>

					<?php /* The categories */ ?>
					<?php if ( $categories ) {
						$list_of_categories = get_the_term_list( $pis_query->post->ID, 'category', '', $categ_sep . ' ', '' );
						if ( $list_of_categories ) { ?>
							<p <?php echo pis_paragraph( $categories_margin, $margin_unit, 'pis-categories-links', 'pis_categories_class' ); ?>>
								<?php if ( $categ_text ) echo $categ_text . '&nbsp';
								echo apply_filters(  'pis_categories_list', $list_of_categories ); ?>
							</p>
						<?php }
					} ?>

					<?php /* The tags */ ?>
					<?php if ( $tags ) {
						$list_of_tags = get_the_term_list( $pis_query->post->ID, 'post_tag', $hashtag, $tag_sep . ' ' . $hashtag, '' );
						if ( $list_of_tags ) { ?>
							<p <?php echo pis_paragraph( $tags_margin, $margin_unit, 'pis-tags-links', 'pis_tags_class' ); ?>>
								<?php if ( $tags_text ) echo $tags_text . '&nbsp;';
								echo apply_filters( 'pis_tags_list', $list_of_tags );
								?>
							</p>
						<?php }
					} ?>

					<?php /* The post meta */ ?>
					<?php if ( $custom_field ) {
						$the_custom_field = get_post_meta( $pis_query->post->ID, $meta, false );
						if ( $the_custom_field ) {
							if ( $custom_field_txt )
								$cf_text = '<span class="pis-custom-field-text-before">' . $custom_field_txt . '</span>';
							else
								$cf_text = '';
							if ( $custom_field_key )
								$key = '<span class="pis-custom-field-key">' . $meta . '</span>' . '<span class="pis-custom-field-divider">' . $custom_field_sep . '</span> ';
							else
								$key = '';
							$cf_value = '<span class="pis-custom-field-value">' . $the_custom_field[0] . '</span>'; ?>
							<p <?php echo pis_paragraph( $custom_field_margin, $margin_unit, 'pis-custom-field', 'pis_custom_fields_class' ); ?>>
								<?php echo $cf_text . $key . $cf_value; ?>
							</p>
						<?php }
					} ?>

				</li>

			<?php endwhile; ?>

		</<?php echo $list_element; ?>>
		<!-- / ul#pis-ul -->

		<?php /* The link to the entire archive */ ?>
		<?php if ( $archive_link ) {

			$wp_post_type = array( 'post', 'page', 'media', 'any' );

			if ( 'author' == $link_to && isset( $author ) ) {
				$author_infos = get_user_by( 'slug', $author );
				if ( $author_infos ) {
					$term_link = get_author_posts_url( $author_infos->ID, $author );
					$term_name = $author_infos->display_name;
					$title_text = sprintf( __( 'Display all posts by %s', 'pis' ), $term_name );
				}
			} elseif ( 'category' == $link_to && isset( $cat ) ) {
				$term_identity = get_term_by( 'slug', $cat, 'category' );
				if ( $term_identity ) {
					$term_link = get_category_link( $term_identity->term_id );
					$term_name = $term_identity->name;
					$title_text = sprintf( __( 'Display all posts archived as %s', 'pis' ), $term_name );
				}
			} elseif ( 'tag' == $link_to && isset( $tag ) ) {
				$term_identity = get_term_by( 'slug', $tag, 'post_tag' );
				if ( $term_identity ) {
					$term_link = get_tag_link( $term_identity->term_id );
					$term_name = $term_identity->name;
					$title_text = sprintf( __( 'Display all posts archived as %s', 'pis' ), $term_identity->name );
				}
			} elseif ( ! in_array( $post_type, $wp_post_type ) ) {
				$term_link = get_post_type_archive_link( $link_to );
				$post_type_object = get_post_type_object( $link_to );
				$term_name = $post_type_object->labels->name;
				$title_text = sprintf( __( 'Display all posts archived as %s', 'pis' ), $term_name );
			} elseif ( term_exists( $link_to, 'post_format' ) && $link_to == $post_format ) {
				$term_link = get_post_format_link( substr( $link_to, 12 ) );
				$term_object = get_term_by( 'slug', $link_to, 'post_format' );
				$term_name = $term_object->name;
				$title_text = sprintf( __( 'Display all posts with post format %s', 'pis' ), $term_name );
			}

			if ( isset( $term_link ) ) {
				if ( strpos( $archive_text, '%s' ) ) {
					$archive_text = str_replace( '%s', $term_name, $archive_text );
				}
			?>
				<p <?php echo pis_paragraph( $archive_margin, $margin_unit, 'pis-archive-link', 'pis_archive_class' ); ?>>
					<a <?php pis_class( 'pis-archive-link-class', apply_filters( 'pis_archive_link_class', '' ) ); ?> href="<?php echo $term_link; ?>" title="<?php echo esc_attr( $title_text ); ?>" rel="bookmark">
						<?php echo $archive_text; ?>
					</a>
				</p>
			<?php }
		} ?>

	<?php /* If we have no posts yet */ ?>
	<?php else : ?>

		<?php if ( $nopost_text ) { ?>
			<p <?php echo pis_paragraph( $noposts_margin, $margin_unit, 'pis-noposts noposts', 'pis_noposts_class' ); ?>>
				<?php echo $nopost_text; ?>
			</p>
		<?php } ?>
		<?php if ( $hide_widget ) {
			echo '<style type="text/css">#' . $widget_id . ' { display: none; }</style>';
		} ?>

	<?php endif; ?>

	<?php /* Reset this custom query */ ?>
	<?php wp_reset_postdata(); ?>

	<?php /* Debugging */ ?>

	<?php if ( $debug_query || $debug_params || $debug_query_number ) { ?>
		<hr />
		<h3>Debugging</h3>
	<?php } ?>

	<?php if ( $debug_query ) { ?>
		<p><strong><?php _e( 'The parameters for the query:', 'pis' ); ?></strong></p>
		<pre><?php print_r($params); ?></pre>
		<hr />
	<?php } ?>

	<?php if ( $debug_params ) { ?>
		<p><strong><?php _e( 'The complete set of parameters of the widget:', 'pis' ); ?></strong></p>
		<pre><?php print_r($args); ?></pre>
		<hr />
	<?php } ?>

	<?php if ( $debug_query_number ) { ?>
		<p><strong><?php _e( 'The total number of queries of this WordPress installation:', 'pis' ); ?></strong></p>
		<pre><?php printf( __( '%1$s queries in %2$s seconds', 'pis' ), get_num_queries(), timer_stop() ); ?></pre>
		<hr />
	<?php } ?>

	<?php /* Whether the cache is active */ ?>
	<?php if ( $cached ) {
		$pis_cache_active = ' - Cache is active';
	} else {
		$pis_cache_active = '';
	} ?>

	<?php /* Prints the version of Posts in Sidebar and if the cache is active. */ ?>
	<?php echo '<!-- Generated by Posts in Sidebar v' . PIS_VERSION . $pis_cache_active . ' -->'; ?>

<?php }


/***********************************************************************
 *                            CODE IS POETRY
 **********************************************************************/
