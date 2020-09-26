<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * User: Salemi
 * Date: 22/07/2016
 * Time: 01:39 AM
 */
class WPP_Widget_JArchive extends WP_Widget
{

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'wpp_jarchive', // Base ID
			__( 'Jalali Archive', 'wp-persian' ), // Name
			array( 'description' => __( 'A jalali monthly archive of your site’s posts', 'wp-persian' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		//extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Archives' ) : $instance['title'] );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$type            = "monthly";
		$show_post_count = ! empty( $instance['show_post_count'] ) ? "1" : "0";
		$dropdown        = ! empty( $instance['dropdown'] ) ? "1" : "0";


		if ( $dropdown ) {
			?>
			<label class="screen-reader-text"><?php echo $title ?></label>
			<select name="jarchive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'>
				<?php
				wpp_get_jarchives("type=$type&format=option&show_post_count=$show_post_count" );
				?>
			</select>
			<?php
		} else {
			?>
			<ul>
				<?php
				wpp_get_jarchives( "type=$type&show_post_count=$show_post_count" );
				?>
			</ul>
			<?php
		}

		echo $args['after_widget'];
	}



	public function update( $new_instance, $old_instance ) {
		$instance                    = $old_instance;
		$new_instance                = wp_parse_args( (array) $new_instance, array(
			'title'           => '',
			'show_post_count' => 0,
			'dropdown'        => '',
			'type'            => 'monthly'
		) );
		$instance['title']           = sanitize_text_field( $new_instance['title'] );
		$instance['type']            = 'monthly';
		$instance['show_post_count'] = $new_instance['show_post_count'] ? 1 : 0;
		$instance['dropdown']        = $new_instance['dropdown'] ? 1 : 0;
		return $instance;
	}

	public function form( $instance ) {
		$instance        = wp_parse_args( (array) $instance, array( 'title' => '', 'show_post_count' => 0, 'dropdown' => '' ) );
		$title           = sanitize_text_field( $instance['title'] );
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'wp-persian' ); ?></label>
            <input
					class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
					name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
					value="<?php echo esc_attr( $title ); ?>"/></p>
		<p><input name="<?php echo $this->get_field_name( 'dropdown' ); ?>" type="checkbox" value="1"
		          id="<?php echo $this->get_field_id( 'dropdown' ); ?>" <?php checked( $instance['dropdown'] ); ?> />
			<label
				for="<?php echo $this->get_field_id( 'dropdown' ); ?>"><?php _e( 'Display as dropdown', 'wp-persian' ); ?></label><br/>
			<input name="<?php echo $this->get_field_name( 'show_post_count' ); ?>" type="checkbox" value="1"
			       id="<?php echo $this->get_field_id( 'show_post_count' ); ?>" <?php checked( $instance['show_post_count'] ); ?> />
			<label
				for="<?php echo $this->get_field_id( 'show_post_count' ); ?>"><?php _e( 'Show post counts', 'wp-persian' ); ?></label>
		</p>

		<?php
	}

}

