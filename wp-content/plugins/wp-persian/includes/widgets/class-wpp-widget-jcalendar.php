<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Adds JCalendar widget.
 */
class WPP_Widget_JCalendar extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'wpp_jcalendar', // Base ID
			__( 'Jalali Calendar', 'wp-persian' ), // Name
			array( 'description' => __( 'A jalali calendar of your site’s posts', 'wp-persian' ), ) // Args
		);
	}

	private static function _calendar() {
		global $m, $year;
		$j_year = 0;
		if ( $m != '' ) {
			$m      = preg_replace( '|[^0-9]|', '', $m );
			$j_year = (int)substr( $m, 0, 4 );
		} else if ( $year != '' ) {
			$j_year = (int)$year;
		}
		if ( $j_year > 1700 ) {
			get_calendar();
		} else {
			get_jcalendar();
		}
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		global $m, $year;

		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		echo '<div id="calendar_wrap" class="calendar_wrap">';

		$j_year = 0;
		if ( $m != '' ) {
			$m      = preg_replace( '|[^0-9]|', '', $m );
			$j_year = (int)substr( $m, 0, 4 );
		} else if ( $year != '' ) {
			$j_year = (int)$year;
		}
		if ( $j_year > 1700 ) {
			get_calendar();
		} else {
			get_jcalendar();
		}


		echo '</div>';
		echo $args['after_widget'];
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		return $instance;
	}


	public function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
        $title = sanitize_text_field( $instance['title'] );
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"/></p>
		<?php
	}


}

