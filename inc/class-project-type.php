<?php
/**
 * Project type class.  This class is for creating new layout objects.  Type registration is handled via
 * the `Hybrid_Type_Factory` class in `inc/class-project-type-factory.php`.  Developers should utilize
 * the API functions in `inc/functions-project-types.php`.
 *
 */

/**
 * Creates new project type objects.
 *
 * @since  1.0.0
 * @access public
 */
class CCP_Project_Type {

	/**
	 * Arguments for creating the project type object.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array
	 */
	protected $args = array();

	/* ====== Magic Methods ====== */

	/**
	 * Magic method for getting layout object properties.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $property
	 * @return mixed
	 */
	public function __get( $property ) {

		return isset( $this->$property ) ? $this->args[ $property ] : null;
	}

	/**
	 * Magic method for setting layout object properties.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $property
	 * @param  mixed   $value
	 * @return void
	 */
	public function __set( $property, $value ) {

		if ( isset( $this->$property ) )
			$this->args[ $property ] = $value;
	}

	/**
	 * Magic method for checking if a layout property is set.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $property
	 * @return bool
	 */
	public function __isset( $property ) {

		return isset( $this->args[ $property ] );
	}

	/**
	 * Don't allow properties to be unset.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $property
	 * @return void
	 */
	public function __unset( $property ) {}

	/**
	 * Magic method to use in case someone tries to output the layout object as a string.
	 * We'll just return the layout name.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function __toString() {
		return $this->name;
	}

	/**
	 * Register a new layout object
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @param  array   $args  {
	 *     @type string  $label
	 *     @type string  $label_count
	 *     @type string  $capability
	 *     @type string  $label
	 *     @type string  $image
	 *     @type bool    $_builtin
	 *     @type bool    $_internal
	 * }
	 * @return void
	 */
	public function __construct( $name, $args = array() ) {

		$post_type_object = get_post_type_object( ccp_get_project_post_type() );

		$name = sanitize_key( $name );

		$defaults = array(
			'label'               => $name,
			'label_count'         => '',
			'label_undo'          => '',
			'show_in_status_list' => true,
			'show_in_row_actions' => true,
			'show_in_post_states' => true,
			'count_callback'      => '',
			'capability'          => $post_type_object->cap->publish_posts,
			'_builtin'            => false, // Internal use only! Whether the type is built in.
			'_internal'           => false, // Internal use only! Whether the type is internal (cannot be unregistered).
		);

		$this->args = wp_parse_args( $args, $defaults );

		$this->args['name'] = $name;
	}
}
