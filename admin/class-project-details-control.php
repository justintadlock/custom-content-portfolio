<?php

/**
 * Base control class.
 *
 * @since  1.0.0
 * @access public
 */
class CCP_Project_Details_Control {

	/**
	 * Stores the project details manager object.
	 *
	 * @see    CCP_Project_Details_Manager
	 * @since  1.0.0
	 * @access public
	 * @var    object
	 */
	public $manager;

	/**
	 * Name/ID of the control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $name = '';

	/**
	 * Label for the control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $label = '';

	/**
	 * Description for the control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $description = '';

	/**
	 * ID of the section the control is for.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $section = '';

	/**
	 * ID of the setting the control is for.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $setting = '';

	/**
	 * Creates a new control object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @param  string  $cap
	 * @param  array   $args
	 * @return void
	 */
	public function __construct( $manager, $name, $args = array() ) {

		foreach ( array_keys( get_object_vars( $this ) ) as $key ) {

			if ( isset( $args[ $key ] ) )
				$this->$key = $args[ $key ];
		}

		$this->manager = $manager;
		$this->name    = $name;

		if ( ! isset( $args['setting'] ) )
			$this->setting = $name;
	}

	/**
	 * Get the value for the setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed
	 */
	public function get_value( $post_id ) {

		$setting = $this->manager->get_setting( $this->setting );

		return $setting ? $setting->get_value( $post_id ) : false;
	}

	/**
	 * Adds custom data to the json array. This data is passed to the Underscore template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function content_template( $post_id ) { ?>

		<label>
			<?php if ( $this->label ) : ?>
				<span class="ccp-label"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>

			<input type="text" class="widefat" name="<?php echo esc_attr( "ccp_setting_{$this->setting}" ); ?>" value="<?php echo esc_attr( $this->get_value( $post_id ) ); ?>" />

			<?php if ( $this->description ) : ?>
				<span class="ccp-description description"><?php echo $this->description; ?></span>
			<?php endif; ?>
		</label>
	<?php }
}
