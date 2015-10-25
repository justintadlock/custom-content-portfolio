<?php

/**
 * Excerpt control class.
 *
 * @since  1.0.0
 * @access public
 */
class CCP_Fields_Control_Excerpt extends CCP_Fields_Control {

	public function get_value( $post_id ) {
		return get_post( $post_id )->post_excerpt;
	}
}
