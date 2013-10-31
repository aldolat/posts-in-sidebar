<?php

/**
 * The widget
 *
 * @package PostsInSidebar
 */

/**
 * Register the widget
 *
 * @since 1.0
 */

function pis_load_widgets() {
	register_widget( 'PIS_Posts_In_Sidebar' );
}
add_action( 'widgets_init', 'pis_load_widgets' );


/**
 * Create the widget
 *
 * @since 1.0
 */

class PIS_Posts_In_Sidebar extends WP_Widget {

	function PIS_Posts_In_Sidebar() {
		/* Widget settings. */
		$widget_ops = array(
			'classname'   => 'posts-in-sidebar',
			'description' => __( 'Display a list of posts in a widget', 'pis' ),
		);

		/* Widget control settings. */
		$control_ops = array(
			'width'   => 700,
			'id_base' => 'pis_posts_in_sidebar',
		);

		/* Create the widget. */
		$this->WP_Widget( 'pis_posts_in_sidebar', __( 'Posts in Sidebar', 'pis' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		if ( $title && $instance['title_link'] ) {
			echo $before_title . '<a class="pis-title-link" href="' . $instance['title_link'] . '">' . $title . '</a>' . $after_title;
		} else if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		pis_posts_in_sidebar( array(
			'intro'               => $instance['intro'],
			'post_type'           => $instance['post_type'],
			'author'              => $instance['author'],
			'cat'                 => $instance['cat'],
			'tag'                 => $instance['tag'],
			'post_format'         => $instance['post_format'],
			'number'              => $instance['number'],
			'orderby'             => $instance['orderby'],
			'order'               => $instance['order'],
			'cat_not_in'          => $instance['cat_not_in'],
			'tag_not_in'          => $instance['tag_not_in'],
			'offset_number'       => $instance['offset_number'],
			'post_status'         => $instance['post_status'],
			'post_meta_key'       => $instance['post_meta_key'],
			'post_meta_val'       => $instance['post_meta_val'],
			'ignore_sticky'       => $instance['ignore_sticky'],
			'display_title'       => $instance['display_title'],
			'link_on_title'       => $instance['link_on_title'],
			'display_image'       => $instance['display_image'],
			'image_size'          => $instance['image_size'],
			'image_align'         => $instance['image_align'],
			'excerpt'             => $instance['excerpt'],
			'arrow'               => $instance['arrow'],
			'exc_length'          => $instance['exc_length'],
			'the_more'            => $instance['the_more'],
			'exc_arrow'           => $instance['exc_arrow'],
			'display_author'      => $instance['display_author'],
			'author_text'         => $instance['author_text'],
			'linkify_author'      => $instance['linkify_author'],
			'display_date'        => $instance['display_date'],
			'date_text'           => $instance['date_text'],
			'linkify_date'        => $instance['linkify_date'],
			'comments'            => $instance['comments'],
			'comments_text'       => $instance['comments_text'],
			'utility_sep'         => $instance['utility_sep'],
			'categories'          => $instance['categories'],
			'categ_text'          => $instance['categ_text'],
			'categ_sep'           => $instance['categ_sep'],
			'tags'                => $instance['tags'],
			'tags_text'           => $instance['tags_text'],
			'hashtag'             => $instance['hashtag'],
			'tag_sep'             => $instance['tag_sep'],
			'custom_field'        => $instance['custom_field'],
			'custom_field_txt'    => $instance['custom_field_txt'],
			'meta'                => $instance['meta'],
			'custom_field_key'    => $instance['custom_field_key'],
			'custom_field_sep'    => $instance['custom_field_sep'],
			'archive_link'        => $instance['archive_link'],
			'link_to'             => $instance['link_to'],
			'archive_text'        => $instance['archive_text'],
			'nopost_text'         => $instance['nopost_text'],
			'remove_bullets'      => $instance['remove_bullets'],
			'margin_unit'         => $instance['margin_unit'],
			'intro_margin'        => $instance['intro_margin'],
			'title_margin'        => $instance['title_margin'],
			'side_image_margin'   => $instance['side_image_margin'],
			'bottom_image_margin' => $instance['bottom_image_margin'],
			'excerpt_margin'      => $instance['excerpt_margin'],
			'utility_margin'      => $instance['utility_margin'],
			'categories_margin'   => $instance['categories_margin'],
			'tags_margin'         => $instance['tags_margin'],
			'custom_field_margin' => $instance['custom_field_margin'],
			'archive_margin'      => $instance['archive_margin'],
			'noposts_margin'      => $instance['noposts_margin'],
			'custom_styles'       => $instance['custom_styles'],
		));
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']      = strip_tags( $new_instance['title'] );
		$instance['title_link'] = esc_url( $new_instance['title_link'] );
		$allowed_html = array(
			'a' => array(
				'href'  => array(),
				'title' => array(),
			),
			'em' => array(),
			'strong' => array(),
		);
		$instance['intro']             = wp_kses( $new_instance['intro'], $allowed_html );
		$instance['post_type']         = $new_instance['post_type'];
		$instance['author']            = $new_instance['author'];
		$instance['cat']               = $new_instance['cat'];
		$instance['tag']               = $new_instance['tag'];
		$instance['post_format']       = $new_instance['post_format'];
		$instance['number']            = intval( strip_tags( $new_instance['number'] ) );
			if( $instance['number'] == 0 || ! is_numeric( $instance['number'] ) ) $instance['number'] = get_option( 'posts_per_page' );
		$instance['orderby']           = $new_instance['orderby'];
		$instance['order']             = $new_instance['order'];
		$instance['cat_not_in']        = $new_instance['cat_not_in'];
		$instance['tag_not_in']        = $new_instance['tag_not_in'];
		$instance['offset_number']     = absint( strip_tags( $new_instance['offset_number'] ) );
			if( $instance['offset_number'] == 0 || ! is_numeric( $instance['offset_number'] ) ) $instance['offset_number'] = '';
		$instance['post_status']       = $new_instance['post_status'];
		$instance['post_meta_key']     = strip_tags( $new_instance['post_meta_key'] );
		$instance['post_meta_val']     = strip_tags( $new_instance['post_meta_val'] );
		$instance['ignore_sticky']     = $new_instance['ignore_sticky'];
		$instance['display_title']     = $new_instance['display_title'];
		$instance['link_on_title']     = $new_instance['link_on_title'];
		$instance['arrow']             = $new_instance['arrow'];
		$instance['display_image']     = $new_instance['display_image'];
		$instance['image_size']        = $new_instance['image_size'];
		$instance['image_align']       = $new_instance['image_align'];
		$instance['excerpt']           = $new_instance['excerpt'];
		$instance['exc_length']        = absint( strip_tags( $new_instance['exc_length'] ) );
			if( $instance['exc_length'] == '' || ! is_numeric( $instance['exc_length'] ) ) $instance['exc_length'] = 20;
		$instance['the_more']          = strip_tags( $new_instance['the_more'] );
		$instance['exc_arrow']         = $new_instance['exc_arrow'];
		$instance['display_author']    = $new_instance['display_author'];
		$instance['author_text']       = strip_tags( $new_instance['author_text'] );
		$instance['linkify_author']    = $new_instance['linkify_author'];
		$instance['display_date']      = $new_instance['display_date'];
		$instance['date_text']         = strip_tags( $new_instance['date_text'] );
		$instance['linkify_date']      = $new_instance['linkify_date'];
		$instance['comments']          = $new_instance['comments'];
		$instance['comments_text']     = strip_tags( $new_instance['comments_text'] );
		$instance['utility_sep']       = strip_tags( $new_instance['utility_sep'] );
		$instance['categories']        = $new_instance['categories'];
		$instance['categ_text']        = strip_tags( $new_instance['categ_text'] );
		$instance['categ_sep']         = strip_tags( $new_instance['categ_sep'] );
		$instance['tags']              = $new_instance['tags'];
		$instance['tags_text']         = strip_tags( $new_instance['tags_text'] );
		$instance['hashtag']           = strip_tags( $new_instance['hashtag'] );
		$instance['tag_sep']           = strip_tags( $new_instance['tag_sep'] );
		$instance['custom_field']      = $new_instance['custom_field'];
		$instance['custom_field_txt']  = strip_tags( $new_instance['custom_field_txt'] );
		$instance['meta']              = strip_tags( $new_instance['meta'] );
		$instance['custom_field_key']  = $new_instance['custom_field_key'];
		$instance['custom_field_sep']  = strip_tags( $new_instance['custom_field_sep'] );
		$instance['archive_link']      = $new_instance['archive_link'];
		$instance['link_to']           = $new_instance['link_to'];
		$instance['archive_text']      = strip_tags( $new_instance['archive_text'] );
		$instance['nopost_text']       = strip_tags( $new_instance['nopost_text'] );
		$instance['remove_bullets']    = $new_instance['remove_bullets'];
		$instance['margin_unit']       = $new_instance['margin_unit'];
		$instance['intro_margin']      = strip_tags( $new_instance['intro_margin'] );
			if ( ! is_numeric( $new_instance['intro_margin'] ) ) $instance['intro_margin'] = NULL;
		$instance['title_margin']      = strip_tags( $new_instance['title_margin'] );
			if ( ! is_numeric( $new_instance['title_margin'] ) ) $instance['title_margin'] = NULL;
		$instance['side_image_margin'] = $new_instance['side_image_margin'];
			if ( ! is_numeric( $new_instance['side_image_margin'] ) ) $instance['side_image_margin'] = NULL;
		$instance['bottom_image_margin'] = $new_instance['bottom_image_margin'];
			if ( ! is_numeric( $new_instance['bottom_image_margin'] ) ) $instance['bottom_image_margin'] = NULL;
		$instance['excerpt_margin']    = strip_tags( $new_instance['excerpt_margin'] );
			if ( ! is_numeric( $new_instance['excerpt_margin'] ) ) $instance['excerpt_margin'] = NULL;
		$instance['utility_margin']    = strip_tags( $new_instance['utility_margin'] );
			if ( ! is_numeric( $new_instance['utility_margin'] ) ) $instance['utility_margin'] = NULL;
		$instance['categories_margin'] = strip_tags( $new_instance['categories_margin'] );
			if ( ! is_numeric( $new_instance['categories_margin'] ) ) $instance['categories_margin'] = NULL;
		$instance['tags_margin']       = strip_tags( $new_instance['tags_margin'] );
			if ( ! is_numeric( $new_instance['tags_margin'] ) ) $instance['tags_margin'] = NULL;
		$instance['custom_field_margin'] = strip_tags( $new_instance['custom_field_margin'] );
			if ( ! is_numeric( $new_instance['custom_field_margin'] ) ) $instance['custom_field_margin'] = NULL;
		$instance['archive_margin']    = strip_tags( $new_instance['archive_margin'] );
			if ( ! is_numeric( $new_instance['archive_margin'] ) ) $instance['archive_margin'] = NULL;
		$instance['noposts_margin']    = strip_tags( $new_instance['noposts_margin'] );
			if ( ! is_numeric( $new_instance['noposts_margin'] ) ) $instance['noposts_margin'] = NULL;
		$instance['custom_styles']    = strip_tags( $new_instance['custom_styles'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title'               => __( 'Posts', 'pis' ),
			'title_link'          => '',
			'intro'               => '',
			'post_type'           => 'post',
			'author'              => '',
			'cat'                 => '',
			'tag'                 => '',
			'post_format'         => '',
			'number'              => get_option( 'posts_per_page' ),
			'orderby'             => 'date',
			'order'               => 'DESC',
			'cat_not_in'          => '',
			'tag_not_in'          => '',
			'offset_number'       => '',
			'post_status'         => 'publish',
			'post_meta_key'       => '',
			'post_meta_val'       => '',
			'ignore_sticky'       => false,
			'display_title'       => true,
			'link_on_title'       => true,
			'arrow'               => false,
			'display_image'       => false,
			'image_size'          => 'thumbnail',
			'image_align'         => 'no_change',
			'side_image_margin'   => NULL,
			'bottom_image_margin' => NULL,
			'excerpt'             => 'excerpt',
			'exc_length'          => 20,
			'the_more'            => __( 'Read more&hellip;', 'pis' ),
			'exc_arrow'           => false,
			'display_author'      => false,
			'author_text'         => __( 'By', 'pis' ),
			'linkify_author'      => false,
			'display_date'        => false,
			'date_text'           => __( 'Published on', 'pis' ),
			'linkify_date'        => false,
			'comments'            => false,
			'comments_text'       => __( 'Comments:', 'pis' ),
			'utility_sep'         => '|',
			'categories'          => false,
			'categ_text'          => __( 'Category:', 'pis' ),
			'categ_sep'           => ',',
			'tags'                => false,
			'tags_text'           => __( 'Tags:', 'pis' ),
			'hashtag'             => '#',
			'tag_sep'             => '',
			'custom_field'        => false,
			'custom_field_txt'    => '',
			'meta'                => '',
			'custom_field_key'    => false,
			'custom_field_sep'    => ':',
			'archive_link'        => false,
			'link_to'             => 'category',
			'archive_text'        => __( 'Display all posts', 'pis' ),
			'nopost_text'         => __( 'No posts yet.', 'pis' ),
			'remove_bullets'      => false,
			'margin_unit'         => 'px',
			'intro_margin'        => NULL,
			'title_margin'        => NULL,
			'excerpt_margin'      => NULL,
			'utility_margin'      => NULL,
			'categories_margin'   => NULL,
			'tags_margin'         => NULL,
			'custom_field_margin' => NULL,
			'archive_margin'      => NULL,
			'noposts_margin'      => NULL,
			'custom_styles'       => '',
		);
		$instance         = wp_parse_args( (array) $instance, $defaults );
		$ignore_sticky    = (bool) $instance['ignore_sticky'];
		$display_title    = (bool) $instance['display_title'];
		$link_on_title    = (bool) $instance['link_on_title'];
		$display_image    = (bool) $instance['display_image'];
		$arrow            = (bool) $instance['arrow'];
		$exc_arrow        = (bool) $instance['exc_arrow'];
		$display_author   = (bool) $instance['display_author'];
		$linkify_author   = (bool) $instance['linkify_author'];
		$display_date     = (bool) $instance['display_date'];
		$linkify_date     = (bool) $instance['linkify_date'];
		$comments         = (bool) $instance['comments'];
		$categories       = (bool) $instance['categories'];
		$tags             = (bool) $instance['tags'];
		$custom_field     = (bool) $instance['custom_field'];
		$custom_field_key = (bool) $instance['custom_field_key'];
		$archive_link     = (bool) $instance['archive_link'];
		$remove_bullets   = (bool) $instance['remove_bullets'];
		?>
		<div style="float: left; width: 31%; margin-right: 2%;">

			<h4><?php _e( 'The title of the widget', 'pis' ); ?></h4>

			<?php pis_form_input_text( __( 'Title', 'pis' ), $this->get_field_id('title'), $this->get_field_name('title'), esc_attr( $instance['title'] ) ); ?>

			<?php pis_form_input_text( __( 'Link for the title of the widget', 'pis' ), $this->get_field_id('title_link'), $this->get_field_name('title_link'), esc_url( $instance['title_link'] ) ); ?>

			<?php pis_form_textarea(
				__( 'Introductory text for the widget', 'pis' ),
				$this->get_field_id('intro'),
				$this->get_field_name('intro'),
				$instance['intro'],
				$style = 'resize: vertical; width: 100%; height: 80px;',
				$comment = sprintf( __( 'Allowed HTML: %s. Other tags will be stripped.', 'pis' ), '<code>a</code>, <code>strong</code>, <code>em</code>' )
			); ?>

			<hr />

			<h4><?php _e( 'Get these posts', 'pis' ); ?></h4>

			<?php // ================= Post types
			$options = array(
				array(
					'value' => 'any',
					'desc'  => __( 'Any', 'pis' ),
				)
			);
			$wp_post_types = (array) get_post_types( array( 'exclude_from_search' => false ), 'objects' );
			foreach ( $wp_post_types as $wp_post_type ) {
				$options[] = array(
					'value' => $wp_post_type->name,
					'desc'  => $wp_post_type->labels->singular_name,
				);
			}
			pis_form_select(
				__( 'Post type', 'pis' ),
				$this->get_field_id('post_type'),
				$this->get_field_name('post_type'),
				$options,
				$instance['post_type']
			); ?>

			<?php // ================= Author
			$options = array(
				array(
					'value' => 'NULL',
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
				__( 'Author', 'pis' ),
				$this->get_field_id('author'),
				$this->get_field_name('author'),
				$options,
				$instance['author']
			); ?>

			<?php // ================= Category
			$options = array(
				array(
					'value' => 'NULL',
					'desc'  => __( 'Any', 'pis' )
				)
			);
			$my_cats = get_categories( array( 'hide_empty' => 0 ) );
			foreach( $my_cats as $my_cat ) {
				$options[] = array(
					'value' => $my_cat->slug,
					'desc'  => $my_cat->cat_name,
				);
			}
			pis_form_select(
				__( 'Category', 'pis' ),
				$this->get_field_id('cat'),
				$this->get_field_name('cat'),
				$options,
				$instance['cat']
			); ?>

			<?php // ================= Tag
			$options = array(
				array(
					'value' => 'NULL',
					'desc'  => __( 'Any', 'pis' )
				)
			);
			$my_tags = get_tags( array( 'hide_empty' => 0 ) );
			foreach( $my_tags as $my_tag ) {
				$options[] = array(
					'value' => $my_tag->slug,
					'desc'  => $my_tag->name,
				);
			}
			pis_form_select(
				__( 'Tag', 'pis' ),
				$this->get_field_id('tag'),
				$this->get_field_name('tag'),
				$options,
				$instance['tag']
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
				__( 'Post format', 'pis' ),
				$this->get_field_id('post_format'),
				$this->get_field_name('post_format'),
				$options,
				$instance['post_format']
			); ?>

			<?php // ================= Posts quantity
			pis_form_input_text( __( 'How many posts to display', 'pis' ), $this->get_field_id('number'), $this->get_field_name('number'), esc_attr( $instance['number'] ) ); ?>

			<?php // ================= Post order by
			$options = array(
				'date' => array(
					'value' => 'date',
					'desc'  => __( 'Date', 'pis' )
				),
				'title' => array(
					'value' => 'title',
					'desc'  => __( 'Title', 'pis' )
				),
				'id' => array(
					'value' => 'id',
					'desc'  => __( 'ID', 'pis' )
				),
				'modified' => array(
					'value' => 'modified',
					'desc'  => __( 'Modified', 'pis' )
				),
				'menu_order' => array(
					'value' => 'menu_order',
					'desc'  => __( 'Menu order', 'pis' )
				),
				'comment_count' => array(
					'value' => 'comment_count',
					'desc'  => __( 'Comment count', 'pis' )
				),
				'rand' => array(
					'value' => 'rand',
					'desc'  => __( 'Random', 'pis' )
				),
			);
			pis_form_select(
				__( 'Order by', 'pis' ),
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
				__( 'Order', 'pis' ),
				$this->get_field_id('order'),
				$this->get_field_name('order'),
				$options,
				$instance['order']
			); ?>

			<?php // ================= Number of posts to skip
			pis_form_input_text( __( 'Number of posts to skip', 'pis' ), $this->get_field_id('offset_number'), $this->get_field_name('offset_number'), esc_attr( $instance['offset_number'] ) ); ?>

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
				__( 'Post status', 'pis' ),
				$this->get_field_id('post_status'),
				$this->get_field_name('post_status'),
				$options,
				$instance['post_status']
			); ?>

			<?php // ================= Post meta key
			pis_form_input_text( __( 'Post meta key', 'pis' ), $this->get_field_id('post_meta_key'), $this->get_field_name('post_meta_key'), esc_attr( $instance['post_meta_key'] ) ); ?>

			<?php // ================= Post meta value
			pis_form_input_text( __( 'Post meta value', 'pis' ), $this->get_field_id('post_meta_val'), $this->get_field_name('post_meta_val'), esc_attr( $instance['post_meta_val'] ) ); ?>

			<?php // ================= Ignore sticky post
			pis_form_checkbox( __( 'Ignore sticky posts', 'pis' ), $this->get_field_id( 'ignore_sticky' ), $this->get_field_name( 'ignore_sticky' ), checked( $ignore_sticky, true, false ), __( 'Sticky posts are automatically ignored if you set up an author or a taxonomy in this widget.', 'pis' ) ); ?>

			<hr />

			<h4><?php _e( 'Exclude these posts', 'pis' ); ?></h4>

			<p>
				<em>
					<?php printf( __( 'Use %1$sCTRL+clic%2$s to select/deselect multiple items.', 'pis' ), '<code>', '</code>' ); ?>
				</em>
			</p>

			<?php // ================= Exclude posts from categories ?>
			<p>
				<label for="<?php echo $this->get_field_id('cat_not_in'); ?>">
					<?php _e( 'Exclude posts from these categories', 'pis' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name('cat_not_in'); ?>[]" multiple="multiple" style="width: 100%;">
					<?php foreach( $my_cats as $my_category ) : ?>
						<option <?php selected( in_array( $my_category->term_id, (array)$instance['cat_not_in'] ) ); ?> value="<?php echo $my_category->term_id; ?>">
							<?php echo $my_category->cat_name; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>

			<?php // ================= Exclude posts from tags ?>
			<p>
				<label for="<?php echo $this->get_field_id('tag_not_in'); ?>">
					<?php _e( 'Exclude posts from these tags', 'pis' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name('tag_not_in'); ?>[]" multiple="multiple" style="width: 100%;">
					<?php foreach( $my_tags as $mytag ) : ?>
						<option <?php selected( in_array( $mytag->term_id, (array)$instance['tag_not_in'] ) ); ?> value="<?php echo $mytag->term_id; ?>">
							<?php echo $mytag->name; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>

		</div>

		<div style="float: left; width: 31%; margin-right: 2%;">

			<h4><?php _e( 'The title of the post', 'pis' ); ?></h4>

			<?php // ================= Title of the post
			pis_form_checkbox( __( 'Display the title of the post', 'pis' ), $this->get_field_id( 'display_title' ), $this->get_field_name( 'display_title' ), checked( $display_title, true, false ) ); ?>

			<?php // ================= Link to the title
			pis_form_checkbox( __( 'Link the title to the post', 'pis' ), $this->get_field_id( 'link_on_title' ), $this->get_field_name( 'link_on_title' ), checked( $link_on_title, true, false ) ); ?>

			<?php // ================= Arrow after the title
			pis_form_checkbox( __( 'Show an arrow after the title', 'pis' ), $this->get_field_id( 'arrow' ), $this->get_field_name( 'arrow' ), checked( $arrow, true, false ) ); ?>

			<hr />

			<h4><?php _e( 'The featured image of the post', 'pis' ); ?></h4>

			<?php // ================= Featured image
			pis_form_checkbox( __( 'Display the featured image of the post', 'pis' ), $this->get_field_id( 'display_image' ), $this->get_field_name( 'display_image' ), checked( $display_image, true, false ) ); ?>

			<?php // ================= Image sizes
			$options = array();
			$sizes = (array) get_intermediate_image_sizes();
			foreach ( $sizes as $size ) {
				$options[] = array(
					'value' => $size,
					'desc'  => $size,
				);
			}
			pis_form_select(
				__( 'Size of the thumbnail', 'pis' ),
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
					'desc'  => __( 'Float left', 'pis' )
				),
				'right' => array(
					'value' => 'right',
					'desc'  => __( 'Float right', 'pis' )
				),
				'center' => array(
					'value' => 'center',
					'desc'  => __( 'Align center', 'pis' )
				),

			);
			pis_form_select(
				__( 'Align image', 'pis' ),
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
				'excerpt' => array(
					'value' => 'excerpt',
					'desc'  => __( 'The excerpt', 'pis' )
				),
				'none' => array(
					'value' => 'none',
					'desc'  => __( 'Do not show any text', 'pis' )
				),
			);
			pis_form_select(
				__( 'What type of text to display', 'pis' ),
				$this->get_field_id('excerpt'),
				$this->get_field_name('excerpt'),
				$options,
				$instance['excerpt']
			); ?>

			<?php // ================= Excerpt length
			pis_form_input_text( __( 'Length of the auto-generated excerpt (in words)', 'pis' ), $this->get_field_id( 'exc_length' ), $this->get_field_name( 'exc_length' ), esc_attr( $instance['exc_length'] ) ); ?>

			<?php // ================= More link text
			pis_form_input_text( __( 'Text for More link', 'pis' ), $this->get_field_id( 'the_more' ), $this->get_field_name( 'the_more' ), esc_attr( $instance['the_more'] ) ); ?>

			<?php // ================= Arrow after the excerpt
			pis_form_checkbox( __( 'Show an arrow after the "Read more" link', 'pis' ), $this->get_field_id( 'exc_arrow' ), $this->get_field_name( 'exc_arrow' ), checked( $exc_arrow, true, false ) ); ?>

			<hr />

			<h4><?php _e( 'Author, date and comments', 'pis' ); ?></h4>

			<?php // ================= Author
			pis_form_checkbox( __( 'Display the author of the post', 'pis' ), $this->get_field_id( 'display_author' ), $this->get_field_name( 'display_author' ), checked( $display_author, true, false ) ); ?>

			<?php // ================= Author text
			pis_form_input_text( __( 'Use this text before author\'s name', 'pis' ), $this->get_field_id( 'author_text' ), $this->get_field_name( 'author_text' ), esc_attr( $instance['author_text'] ) ); ?>

			<?php // ================= Author archive
			pis_form_checkbox( __( 'Link the author to his archive', 'pis' ), $this->get_field_id( 'linkify_author' ), $this->get_field_name( 'linkify_author' ), checked( $linkify_author, true, false ) ); ?>

			<?php // ================= Date
			pis_form_checkbox( __( 'Display the date of the post', 'pis' ), $this->get_field_id( 'display_date' ), $this->get_field_name( 'display_date' ), checked( $display_date, true, false ) ); ?>

			<?php // ================= Date text
			pis_form_input_text( __( 'Use this text before date', 'pis' ), $this->get_field_id( 'date_text' ), $this->get_field_name( 'date_text' ), esc_attr( $instance['date_text'] ) ); ?>

			<?php // ================= Date link
			pis_form_checkbox( __( 'Link the date to the post', 'pis' ), $this->get_field_id( 'linkify_date' ), $this->get_field_name( 'linkify_date' ), checked( $linkify_date, true, false ) ); ?>

			<?php // ================= Number of comments
			pis_form_checkbox( __( 'Display the number of comments', 'pis' ), $this->get_field_id( 'comments' ), $this->get_field_name( 'comments' ), checked( $comments, true, false ) ); ?>

			<?php // ================= Comments text
			pis_form_input_text( __( 'Use this text before the comments number', 'pis' ), $this->get_field_id( 'comments_text' ), $this->get_field_name( 'comments_text' ), esc_attr( $instance['comments_text'] ) ); ?>

			<?php // ================= Utility separator
			pis_form_input_text( __( 'Use this separator between author, date and comments', 'pis' ), $this->get_field_id( 'utility_sep' ), $this->get_field_name( 'utility_sep' ), esc_attr( $instance['utility_sep'] ), __( 'A space will be added before and after the separator.', 'pis' ) ); ?>

		</div>

		<div style="float: left; width: 31%; margin-right: 2%;">

			<h4><?php _e( 'The categories of the post', 'pis' ); ?></h4>

			<?php // ================= Post categories
			pis_form_checkbox( __( 'Show the categories of the post', 'pis' ), $this->get_field_id( 'categories' ), $this->get_field_name( 'categories' ), checked( $categories, true, false ) ); ?>

			<?php // ================= Categories text
			pis_form_input_text( __( 'Text before categories list', 'pis' ), $this->get_field_id( 'categ_text' ), $this->get_field_name( 'categ_text' ), esc_attr( $instance['categ_text'] ) ); ?>

			<?php // ================= Categories separator
			pis_form_input_text( __( 'Use this separator between categories', 'pis' ), $this->get_field_id( 'categ_sep' ), $this->get_field_name( 'categ_sep' ), esc_attr( $instance['categ_sep'] ), __( 'A space will be added after the separator.', 'pis' ) ); ?>

			<hr />

			<h4><?php _e( 'The tags of the post', 'pis' ); ?></h4>

			<?php // ================= Post tags
			pis_form_checkbox( __( 'Show the tags of the post', 'pis' ), $this->get_field_id( 'tags' ), $this->get_field_name( 'tags' ), checked( $tags, true, false ) ); ?>

			<?php // ================= Tags text
			pis_form_input_text( __( 'Text before tags list', 'pis' ), $this->get_field_id( 'tags_text' ), $this->get_field_name( 'tags_text' ), esc_attr( $instance['tags_text'] ) ); ?>

			<?php // ================= Hashtag
			pis_form_input_text( __( 'Use this hashtag', 'pis' ), $this->get_field_id( 'hashtag' ), $this->get_field_name( 'hashtag' ), esc_attr( $instance['hashtag'] ) ); ?>

			<?php // ================= Tags separator
			pis_form_input_text( __( 'Use this separator between tags', 'pis' ), $this->get_field_id( 'tag_sep' ), $this->get_field_name( 'tag_sep' ), esc_attr( $instance['tag_sep'] ), __( 'A space will be added after the separator.', 'pis' ) ); ?>

			<hr />

			<h4><?php _e( 'The custom field', 'pis' ); ?></h4>

			<?php // ================= Display custom field
			pis_form_checkbox( __( 'Display the custom field of the post', 'pis' ), $this->get_field_id( 'custom_field' ), $this->get_field_name( 'custom_field' ), checked( $custom_field, true, false ) ); ?>

			<?php // ================= Custom fields text
			pis_form_input_text( __( 'Text before the custom field', 'pis' ), $this->get_field_id( 'custom_field_txt' ), $this->get_field_name( 'custom_field_txt' ), esc_attr( $instance['custom_field_txt'] ) ); ?>

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
			pis_form_input_text( __( 'Use this separator between meta key and value', 'pis' ), $this->get_field_id( 'custom_field_sep' ), $this->get_field_name( 'custom_field_sep' ), esc_attr( $instance['custom_field_sep'] ) ); ?>

			<hr />

			<h4><?php _e( 'The link to the archive', 'pis' ); ?></h4>

			<?php // ================= Taxonomy archive link
			pis_form_checkbox( __( 'Show the link to the taxonomy archive', 'pis' ), $this->get_field_id( 'archive_link' ), $this->get_field_name( 'archive_link' ), checked( $archive_link, true, false ) ); ?>

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
			pis_form_input_text( __( 'Use this text for archive link', 'pis' ), $this->get_field_id( 'archive_text' ), $this->get_field_name( 'archive_text' ), esc_attr( $instance['archive_text'] ), __( 'Please, note that if you don\'t select any taxonomy, the link won\'t appear.', 'pis' ) ); ?>

			<?php // ================= No posts text
			pis_form_input_text( __( 'Use this text when there are no posts', 'pis' ), $this->get_field_id( 'nopost_text' ), $this->get_field_name( 'nopost_text' ), esc_attr( $instance['nopost_text'] ) ); ?>

			<h4><?php _e( 'Extras', 'pis' ); ?></h4>

			<?php // ================= Remove bullets and left space
			pis_form_checkbox(
				__( 'Try to remove the bullets and the extra left space from the list elements', 'pis' ),
				$this->get_field_id( 'remove_bullets' ),
				$this->get_field_name( 'remove_bullets' ),
				checked( $remove_bullets, true, false ),
				sprintf( __( 'If the plugin doesn\'t remove the bullets and/or the extra left space, you have to %1$sedit your CSS file%2$s manually.', 'pis' ), '<a href="' . admin_url( 'theme-editor.php' ) . '" target="_blank">', '</a>' )
			); ?>

		</div>

		<div class="clear"></div>

		<hr />

		<h4><?php _e( 'Elements margins', 'pis' ); ?></h4>

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

		<?php // ================= Margins ?>

		<div style="float: left; width: 31%; margin-right: 2%">
			<?php pis_form_input_text( __( 'Introduction margin', 'pis' ), $this->get_field_id( 'intro_margin' ), $this->get_field_name( 'intro_margin' ), esc_attr( $instance['intro_margin'] ) ); ?>
			<?php pis_form_input_text( __( 'Title margin', 'pis' ), $this->get_field_id( 'title_margin' ), $this->get_field_name( 'title_margin' ), esc_attr( $instance['title_margin'] ) ); ?>
			<?php pis_form_input_text( __( 'Left/Right image margin', 'pis' ), $this->get_field_id( 'side_image_margin' ), $this->get_field_name( 'side_image_margin' ), esc_attr( $instance['side_image_margin'] ) ); ?>
			<?php pis_form_input_text( __( 'Bottom image margin', 'pis' ), $this->get_field_id( 'bottom_image_margin' ), $this->get_field_name( 'bottom_image_margin' ), esc_attr( $instance['bottom_image_margin'] ) ); ?>
		</div>

		<div style="float: left; width: 31%; margin-right: 2%">
			<?php pis_form_input_text( __( 'Excerpt margin', 'pis' ), $this->get_field_id( 'excerpt_margin' ), $this->get_field_name( 'excerpt_margin' ), esc_attr( $instance['excerpt_margin'] ) ); ?>
			<?php pis_form_input_text( __( 'Utility margin', 'pis' ), $this->get_field_id( 'utility_margin' ), $this->get_field_name( 'utility_margin' ), esc_attr( $instance['utility_margin'] ) ); ?>
			<?php pis_form_input_text( __( 'Categories margin', 'pis' ), $this->get_field_id( 'categories_margin' ), $this->get_field_name( 'categories_margin' ), esc_attr( $instance['categories_margin'] ) ); ?>
			<?php pis_form_input_text( __( 'Tags margin', 'pis' ), $this->get_field_id( 'tags_margin' ), $this->get_field_name( 'tags_margin' ), esc_attr( $instance['tags_margin'] ) ); ?>
		</div>

		<div style="float: left; width: 31%;">
			<?php pis_form_input_text( __( 'Custom field margin', 'pis' ), $this->get_field_id( 'custom_field_margin' ), $this->get_field_name( 'custom_field_margin' ), esc_attr( $instance['custom_field_margin'] ) ); ?>
			<?php pis_form_input_text( __( 'Archive margin', 'pis' ), $this->get_field_id( 'archive_margin' ), $this->get_field_name( 'archive_margin' ), esc_attr( $instance['archive_margin'] ) ); ?>
			<?php pis_form_input_text( __( 'No-posts margin', 'pis' ), $this->get_field_id( 'noposts_margin' ), $this->get_field_name( 'noposts_margin' ), esc_attr( $instance['noposts_margin'] ) ); ?>
		</div>

		<div class="clear"></div>

		<hr />

		<h4><?php _e( 'Custom styles', 'pis' ); ?></h4>

		<p><em><?php printf( __( 'In this field you can add your own styles, for example: %s', 'pis' ), '<code>.pis-excerpt { color: green; }</code>' ); ?></em></p>

		<?php // ================= Custom styles
		pis_form_textarea(
			__( 'Custom styles', 'pis' ),
			$this->get_field_id('custom_styles'),
			$this->get_field_name('custom_styles'),
			$instance['custom_styles'],
			$style = 'resize: vertical; width: 100%; height: 80px;'
		); ?>

		<div class="clear"></div>

		<?php
	}

}

/***********************************************************************
 *                            CODE IS POETRY
 **********************************************************************/
