<?php
/**
 * Project type factory class.  This is a singleton factory class for handling the registering and
 * storing of `CCP_Project_Type` objects.  Developers should utilize the API functions found
 * in `inc/functions-types.php`.
 *
 */

/**
 * Project Type Factory class. This is the backbone of the Types API.  Theme authors should
 * utilize the appropriate functions for accessing the `Hybrid_Type_Factory` object.
 *
 * @since  1.0.0
 * @access public
 */
class CCP_Project_Type_Factory {

	/**
	 * Array of project type objects.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array
	 */
	public $types = array();

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Register a new project type object
	 *
	 * @see    CCP_Project_Type::__construct()
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @param  array   $args
	 * @return void
	 */
	public function register_type( $name, $args = array() ) {

		if ( ! $this->type_exists( $name ) ) {

			$type = new CCP_Project_Type( $name, $args );

			$this->types[ $type->name ] = $type;
		}
	}

	/**
	 * Unregisters a project type object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @return void
	 */
	public function unregister_type( $name ) {

		if ( $this->type_exists( $name ) && false === $this->get_type( $name )->_internal )
			unset( $this->types[ $name ] );
	}

	/**
	 * Checks if a type exists.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @return bool
	 */
	public function type_exists( $name ) {

		return isset( $this->types[ $name ] );
	}

	/**
	 * Gets a type object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @return object|bool
	 */
	public function get_type( $name ) {

		return $this->type_exists( $name ) ? $this->types[ $name ] : false;
	}

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) )
			$instance = new CCP_Project_Type_Factory;

		return $instance;
	}
}
