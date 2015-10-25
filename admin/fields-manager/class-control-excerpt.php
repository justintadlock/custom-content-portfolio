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

	/**
	 * Adds custom data to the json array. This data is passed to the Underscore template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function content_template( $post_id ) { ?>

		<?php if ( $this->label ) : ?>
			<label for="excerpt"><span class="ccp-fields-label"><?php echo esc_html( $this->label ); ?></span></label>
		<?php endif; ?>

		<textarea class="widefat" name="excerpt" id="excerpt" rows="15" cols="40"><?php echo esc_textarea( $this->get_value( $post_id ) ); ?></textarea>

		<?php if ( $this->description ) : ?>
			<span class="ccp-fields-description description"><?php echo $this->description; ?></span>
		<?php endif; ?>
	<?php }
}
