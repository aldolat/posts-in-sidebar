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
			'classname' => 'posts-in-sidebar',
			'description' => __( 'Display a list of posts in a widget', 'pis' )
		);

		/* Widget control settings. */
		$control_ops = array(
			'width' => 700,
			'id_base' => 'pis_posts_in_sidebar'
		);

		/* Create the widget. */
		$this->WP_Widget( 'pis_posts_in_sidebar', __( 'Posts in Sidebar', 'pis' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		pis_posts_in_sidebar( array(
			'author'        => $instance['author'],
			'cat'           => $instance['cat'],
			'tag'           => $instance['tag'],
			'number'        => $instance['number'],
			'orderby'       => $instance['orderby'],
			'order'         => $instance['order'],
			'cat_not_in'    => $instance['cat_not_in'],
			'tag_not_in'    => $instance['tag_not_in'],
			'offset_number' => $instance['offset_number'],
			'post_status'   => $instance['post_status'],
			'post_meta_key' => $instance['post_meta_key'],
			'post_meta_val' => $instance['post_meta_val'],
			'ignore_sticky' => $instance['ignore_sticky'],
			'display_title' => $instance['display_title'],
			'link_on_title' => $instance['link_on_title'],
			'display_date'  => $instance['display_date'],
			'display_image' => $instance['display_image'],
			'image_size'    => $instance['image_size'],
			'excerpt'       => $instance['excerpt'],
			'arrow'         => $instance['arrow'],
			'exc_length'    => $instance['exc_length'],
			'exc_arrow'     => $instance['exc_arrow'],
			'comments'      => $instance['comments'],
			'categories'    => $instance['categories'],
			'categ_text'    => $instance['categ_text'],
			'categ_sep'     => $instance['categ_sep'],
			'tags'          => $instance['tags'],
			'tags_text'     => $instance['tags_text'],
			'hashtag'       => $instance['hashtag'],
			'tag_sep'       => $instance['tag_sep'],
			'archive_link'  => $instance['archive_link'],
			'link_to'       => $instance['link_to'],
			'archive_text'  => $instance['archive_text']
		));
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']         = strip_tags( $new_instance['title'] );
		$instance['author']        = $new_instance['author'];
		$instance['cat']           = $new_instance['cat'];
		$instance['tag']           = $new_instance['tag'];
		$instance['number']        = intval( strip_tags( $new_instance['number'] ) );
			if( $instance['number'] == 0 || ! is_numeric( $instance['number'] ) ) $instance['number'] = get_option( 'posts_per_page' );
		$instance['orderby']       = $new_instance['orderby'];
		$instance['order']         = $new_instance['order'];
		$instance['cat_not_in']    = $new_instance['cat_not_in'];
		$instance['tag_not_in']    = $new_instance['tag_not_in'];
		$instance['offset_number'] = absint( strip_tags( $new_instance['offset_number'] ) );
			if( $instance['offset_number'] == 0 || ! is_numeric( $instance['offset_number'] ) ) $instance['offset_number'] = '';
		$instance['post_status']   = $new_instance['post_status'];
		$instance['post_meta_key'] = strip_tags( $new_instance['post_meta_key'] );
		$instance['post_meta_val'] = strip_tags( $new_instance['post_meta_val'] );
		$instance['ignore_sticky'] = $new_instance['ignore_sticky'];
		$instance['display_title'] = $new_instance['display_title'];
		$instance['link_on_title'] = $new_instance['link_on_title'];
		$instance['display_date']  = $new_instance['display_date'];
		$instance['display_image'] = $new_instance['display_image'];
		$instance['image_size']    = $new_instance['image_size'];
		$instance['excerpt']       = $new_instance['excerpt'];
		$instance['exc_length']    = absint( strip_tags( $new_instance['exc_length'] ) );
			if( $instance['exc_length'] == '' || ! is_numeric( $instance['exc_length'] ) ) $instance['exc_length'] = 20;
		$instance['arrow']         = $new_instance['arrow'];
		$instance['exc_arrow']     = strip_tags( $new_instance['exc_arrow'] );
		$instance['comments']      = strip_tags( $new_instance['comments'] );
		$instance['categories']    = $new_instance['categories'];
		$instance['categ_text']    = strip_tags( $new_instance['categ_text'] );
		$instance['categ_sep']     = strip_tags( $new_instance['categ_sep'] );
		$instance['tags']          = $new_instance['tags'];
		$instance['tags_text']     = strip_tags( $new_instance['tags_text'] );
		$instance['hashtag']       = strip_tags( $new_instance['hashtag'] );
		$instance['tag_sep']       = strip_tags( $new_instance['tag_sep'] );
		$instance['archive_link']  = $new_instance['archive_link'];
		$instance['link_to']       = $new_instance['link_to'];
		$instance['archive_text']  = strip_tags( $new_instance['archive_text'] );
		return $instance;
	}

	function form($instance) {
		$defaults = array(
			'title'         => __( 'Posts', 'pis' ),
			'author'        => '',
			'cat'           => '',
			'tag'           => '',
			'number'        => get_option( 'posts_per_page' ),
			'orderby'       => 'date',
			'order'         => 'DESC',
			'cat_not_in'    => '',
			'tag_not_in'    => '',
			'offset_number' => '',
			'post_status'   => 'publish',
			'post_meta_key' => '',
			'post_meta_val' => '',
			'ignore_sticky' => false,
			'display_title' => true,
			'link_on_title' => true,
			'display_date'  => false,
			'display_image' => false,
			'image_size'    => 'thumbnail',
			'excerpt'       => 'excerpt',
			'arrow'         => false,
			'exc_length'    => 20,
			'exc_arrow'     => false,
			'comments'      => false,
			'categories'    => false,
			'categ_text'    => __( 'Category:', 'pis' ),
			'categ_sep'     => ',',
			'tags'          => false,
			'tags_text'     => __( 'Tags:', 'pis' ),
			'hashtag'       => '#',
			'tag_sep'       => '',
			'archive_link'  => false,
			'link_to'       => 'category',
			'archive_text'  => __( 'More posts &rarr;', 'pis' )
		);
		$instance      = wp_parse_args( (array) $instance, $defaults );
		$ignore_sticky = (bool) $instance['ignore_sticky'];
		$display_title = (bool) $instance['display_title'];
		$link_on_title = (bool) $instance['link_on_title'];
		$display_date  = (bool) $instance['display_date'];
		$display_image = (bool) $instance['display_image'];
		$arrow         = (bool) $instance['arrow'];
		$exc_arrow     = (bool) $instance['exc_arrow'];
		$comments      = (bool) $instance['comments'];
		$categories    = (bool) $instance['categories'];
		$tags          = (bool) $instance['tags'];
		$archive_link  = (bool) $instance['archive_link'];
		?>
		<div style="float: left; width: 31%; margin-left: 2%;">

			<h4><?php _e( 'The title of the widget', 'pis' ); ?></h4>

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">
					<?php _e( 'Title', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
			</p>

			<hr />

			<h4><?php _e( 'Get these posts', 'pis' ); ?></h4>

			<p>
				<label for="<?php echo $this->get_field_id('author'); ?>">
					<?php _e( 'Author', 'pis' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name('author'); ?>">
					<?php $my_author = $instance['author']; ?>
					<option <?php selected( 'NULL', $my_author); ?> value="NULL">
						<?php _e( 'None', 'pis' ); ?>
					</option>
					<?php
						$authors = (array) get_users( 'who=authors' ); // If set to 'authors', only authors (user level greater than 0) will be returned. 
						foreach ( $authors as $author ) :
					?>
						<option <?php selected( $author->user_nicename, $my_author); ?> value="<?php echo $author->user_nicename; ?>">
							<?php echo $author->display_name; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('cat'); ?>">
					<?php _e( 'Category', 'pis' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name('cat'); ?>">
					<option <?php selected( 'NULL', $instance['cat']); ?> value="NULL">
						<?php _e( 'None', 'pis' ); ?>
					</option>
					<?php
						$my_cats = get_categories();
						foreach( $my_cats as $my_cat ) :
					?>
						<option <?php selected( $my_cat->slug, $instance['cat']); ?> value="<?php echo $my_cat->slug; ?>">
							<?php echo $my_cat->cat_name; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('tag'); ?>">
					<?php _e( 'Tag', 'pis' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name('tag'); ?>">
					<?php $my_tagx = $instance['tag']; ?>
					<option <?php selected( 'NULL', $my_tagx); ?> value="NULL">
						<?php _e( 'None', 'pis' ); ?>
					</option>
					<?php
						$my_tags = get_tags();
						foreach( $my_tags as $my_tag ) :
					?>
						<option <?php selected( $my_tag->slug, $my_tagx); ?> value="<?php echo $my_tag->slug; ?>">
							<?php echo $my_tag->name; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('number'); ?>">
					<?php _e( 'How many posts to display', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $instance['number']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('orderby'); ?>">
					<?php _e( 'Order by', 'pis' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name('orderby'); ?>">
					<option <?php selected( 'date', $instance['orderby']); ?> value="date">
						<?php _e( 'Date', 'pis' ); ?>
					</option>
					<option <?php selected( 'title', $instance['orderby']); ?> value="title">
						<?php _e( 'Title', 'pis' ); ?>
					</option>
					<option <?php selected( 'id', $instance['orderby']); ?> value="id">
						<?php _e( 'ID', 'pis' ); ?>
					</option>
					<option <?php selected( 'modified', $instance['orderby']); ?> value="modified">
						<?php _e( 'Modified', 'pis' ); ?>
					</option>
					<option <?php selected( 'rand', $instance['orderby']); ?> value="rand">
						<?php _e( 'Random', 'pis' ); ?>
					</option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('order'); ?>">
					<?php _e( 'Order', 'pis' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name('order'); ?>">
					<option <?php selected( 'ASC', $instance['order']); ?> value="ASC">
						<?php _e( 'Ascending', 'pis' ); ?>
					</option>
					<option <?php selected( 'DESC', $instance['order']); ?> value="DESC">
						<?php _e( 'Descending', 'pis' ); ?>
					</option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('offset_number'); ?>">
					<?php _e( 'Number of posts to skip', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id('offset_number'); ?>" name="<?php echo $this->get_field_name('offset_number'); ?>" type="text" value="<?php echo $instance['offset_number']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('post_status'); ?>">
					<?php _e( 'Post status', 'pis' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name('post_status'); ?>">
					<?php $statuses = get_post_stati( '', 'objects' );
						foreach( $statuses as $status ) { ?>
							<option <?php selected( $status->name, $instance['post_status']); ?> value="<?php echo $status->name; ?>">
								<?php _e( $status->label, 'pis' ); ?>
							</option>
						<?php }
					?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('post_meta_key'); ?>">
					<?php _e( 'Post meta key', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id('post_meta_key'); ?>" name="<?php echo $this->get_field_name('post_meta_key'); ?>" type="text" value="<?php echo $instance['post_meta_key']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('post_meta_val'); ?>">
					<?php _e( 'Post meta value', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id('post_meta_val'); ?>" name="<?php echo $this->get_field_name('post_meta_val'); ?>" type="text" value="<?php echo $instance['post_meta_val']; ?>" />
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $ignore_sticky ); ?> value="1" id="<?php echo $this->get_field_id( 'ignore_sticky' ); ?>" name="<?php echo $this->get_field_name( 'ignore_sticky' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'ignore_sticky' ); ?>">
					<?php _e( 'Ignore sticky posts', 'pis' ); ?>
				</label>
				<br /><em><?php _e( 'Sticky posts are automatically ignored if you set up an author or a taxonomy in this widget.', 'pis' ); ?></em>
			</p>

		</div>

		<div style="float: left; width: 31%; margin-left: 2%;">

			<h4><?php _e( 'Exclude posts', 'pis' ); ?></h4>

			<p><em><?php _e( 'Use <code>CTRL+clic</code> to select/deselect multiple items.', 'pis' ); ?></em></p>

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

			<hr />

			<h4><?php _e( 'The title of the post', 'pis' ); ?></h4>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $display_title ); ?> value="1" id="<?php echo $this->get_field_id( 'display_title' ); ?>" name="<?php echo $this->get_field_name( 'display_title' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'display_title' ); ?>">
					<?php _e( 'Display the title of the post', 'pis' ); ?>
				</label>
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $link_on_title ); ?> value="1" id="<?php echo $this->get_field_id( 'link_on_title' ); ?>" name="<?php echo $this->get_field_name( 'link_on_title' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'link_on_title' ); ?>">
					<?php _e( 'Link the title to the post', 'pis' ); ?>
				</label>
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $arrow ); ?> value="1" id="<?php echo $this->get_field_id( 'arrow' ); ?>" name="<?php echo $this->get_field_name( 'arrow' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'arrow' ); ?>">
					<?php _e( 'Show an arrow after the title', 'pis' ); ?>
				</label>
			</p>

			<hr />

			<h4><?php _e( 'The featured image of the post', 'pis' ); ?></h4>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $display_image ); ?> value="1" id="<?php echo $this->get_field_id( 'display_image' ); ?>" name="<?php echo $this->get_field_name( 'display_image' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'display_image' ); ?>">
					<?php _e( 'Display the featured image of the post', 'pis' ); ?>
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('image_size'); ?>">
					<?php _e( 'Size of the thumbnail', 'pis' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name('image_size'); ?>">
					<?php $my_size = $instance['image_size']; ?>
					<?php
						$sizes = (array) get_intermediate_image_sizes();
						foreach ( $sizes as $size ) :
					?>
						<option <?php selected( $size, $my_size); ?> value="<?php echo $size; ?>">
							<?php echo $size; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<em>
					<?php printf( __(
						'Note that in order to use image sizes different from the WordPress standards, add them to your functions.php. See the %1$sCodex%2$s for further information.', 'pis'),
						'<a href="http://codex.wordpress.org/Function_Reference/add_image_size">', '</a>'
					); ?>
					<?php _e( 'You can also use a plugin that could help you in doing it.', 'pis' ); ?>
				</em>
			</p>

			<hr />

			<h4><?php _e( 'The text of the post', 'pis' ); ?></h4>

			<p>
				<label for="<?php echo $this->get_field_id('excerpt'); ?>">
					<?php _e( 'What type of text to display', 'pis' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name('excerpt'); ?>">
					<option <?php selected( 'excerpt', $instance['excerpt']); ?> value="excerpt">
						<?php _e( 'The excerpt', 'pis' ); ?>
					</option>
					<option <?php selected( 'content', $instance['excerpt']); ?> value="content">
						<?php _e( 'The entire content', 'pis' ); ?>
					</option>
					<option <?php selected( 'none', $instance['excerpt']); ?> value="none">
						<?php _e( 'Do not show any text', 'pis' ); ?>
					</option>
				</select>
			</p>
			<p>
				<em>
					<?php _e( 'Shortcodes will be stripped.', 'pis' );?>
				</em>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'exc_length' ); ?>">
					<?php _e( 'Length of the excerpt (in words)', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'exc_length' ); ?>" name="<?php echo $this->get_field_name( 'exc_length' ); ?>" type="text" value="<?php echo $instance['exc_length']; ?>" />
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $exc_arrow ); ?> value="1" id="<?php echo $this->get_field_id( 'exc_arrow' ); ?>" name="<?php echo $this->get_field_name( 'exc_arrow' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'exc_arrow' ); ?>">
					<?php _e( 'Show an arrow after the excerpt', 'pis' ); ?>
				</label>
			</p>

		</div>

		<div style="float: left; width: 31%; margin-left: 2%;">

			<h4><?php _e( 'The date and the comments of the post', 'pis' ); ?></h4>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $display_date ); ?> value="1" id="<?php echo $this->get_field_id( 'display_date' ); ?>" name="<?php echo $this->get_field_name( 'display_date' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'display_date' ); ?>">
					<?php _e( 'Display the date of the post', 'pis' ); ?>
				</label>
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $comments ); ?> value="1" id="<?php echo $this->get_field_id( 'comments' ); ?>" name="<?php echo $this->get_field_name( 'comments' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'comments' ); ?>">
					<?php _e( 'Display the number of comments', 'pis' ); ?>
				</label>
			</p>

			<hr />

			<h4><?php _e( 'The categories of the post', 'pis' ); ?></h4>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $categories ); ?> value="1" id="<?php echo $this->get_field_id( 'categories' ); ?>" name="<?php echo $this->get_field_name( 'categories' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'categories' ); ?>">
					<?php _e( 'Show the categories of the post', 'pis' ); ?>
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('categ_text'); ?>">
					<?php _e( 'Text before categories list', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id('categ_text'); ?>" name="<?php echo $this->get_field_name('categ_text'); ?>" type="text" value="<?php echo $instance['categ_text']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'categ_sep' ); ?>">
					<?php _e( 'Use this separator between categories', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'categ_sep' ); ?>" name="<?php echo $this->get_field_name( 'categ_sep' ); ?>" type="text" value="<?php echo $instance['categ_sep']; ?>" />
				<em><?php _e( 'A space will be added after the separator.', 'pis' ); ?></em>
			</p>

			<hr />

			<h4><?php _e( 'The tags of the post', 'pis' ); ?></h4>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $tags ); ?> value="1" id="<?php echo $this->get_field_id( 'tags' ); ?>" name="<?php echo $this->get_field_name( 'tags' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'tags' ); ?>">
					<?php _e( 'Show the tags of the post', 'pis' ); ?>
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('tags_text'); ?>">
					<?php _e( 'Text before tags list', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id('tags_text'); ?>" name="<?php echo $this->get_field_name('tags_text'); ?>" type="text" value="<?php echo $instance['tags_text']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'hashtag' ); ?>">
					<?php _e( 'Use this hashtag', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'hashtag' ); ?>" name="<?php echo $this->get_field_name( 'hashtag' ); ?>" type="text" value="<?php echo $instance['hashtag']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'tag_sep' ); ?>">
					<?php _e( 'Use this separator between tags', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'tag_sep' ); ?>" name="<?php echo $this->get_field_name( 'tag_sep' ); ?>" type="text" value="<?php echo $instance['tag_sep']; ?>" />
				<br /><em><?php _e( 'A space will be added after the separator.', 'pis' ); ?></em>
			</p>

			<hr />

			<h4><?php _e( 'The link to the archive', 'pis' ); ?></h4>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $archive_link ); ?> value="1" id="<?php echo $this->get_field_id( 'archive_link' ); ?>" name="<?php echo $this->get_field_name( 'archive_link' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'archive_link' ); ?>">
					<?php _e( 'Show the link to the taxonomy archive', 'pis' ); ?>
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('link_to'); ?>">
					<?php _e( 'Link to', 'pis' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name('link_to'); ?>">
					<option <?php selected( 'author', $instance['link_to']); ?> value="author">
						<?php _e( 'Author Archive', 'pis' ); ?>
					</option>
					<option <?php selected( 'category', $instance['link_to']); ?> value="category">
						<?php _e( 'Category Archive', 'pis' ); ?>
					</option>
					<option <?php selected( 'tag', $instance['link_to']); ?> value="tag">
						<?php _e( 'Tag Archive', 'pis' ); ?>
					</option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'archive_text' ); ?>">
					<?php _e( 'Use this text for archive link', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'archive_text' ); ?>" name="<?php echo $this->get_field_name( 'archive_text' ); ?>" type="text" value="<?php echo $instance['archive_text']; ?>" />
			</p>
			<p>
				<em>
					<?php _e( 'Please, note that if you don\'t select any taxonomy, the link won\'t appear.', 'pis' ); ?>
				</em>
			</p>

		</div>

		<div class="clear"></div>

		<?php
	}

}

/***********************************************************************
 *                            CODE IS POETRY
 **********************************************************************/