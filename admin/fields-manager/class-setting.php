<?php
/**
 * Base setting class for the fields manager.
 *
 * @package    CustomContentPortfolio
 * @subpackage Admin
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013-2015, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Base setting class.
 *
 * @since  1.0.0
 * @access public
 */
class CCP_Fields_Setting {

	/**
	 * Stores the manager object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    object
	 */
	public $manager;

	/**
	 * Name/ID of the setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $name = '';

	/**
	 * Value of the setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $value = '';

	/**
	 * Default value of the setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $default = '';

	/**
	 * Sanitization/Validation callback function.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $sanitize_callback = 'wp_strip_all_tags';

	/**
	 * Creates a new setting object.
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

		if ( $this->sanitize_callback )
			add_filter( "ccp_{$this->manager->name}_sanitize_{$this->name}", $this->sanitize_callback, 10, 2 );
	}

	/**
	 * Gets the value of the setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed
	 */
	public function get_value( $post_id ) {

		return ccp_get_project_meta( $post_id, $this->name );
	}

	/**
	 * Gets the posted value of the setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed
	 */
	public function get_posted_value() {

		$value = '';

		if ( isset( $_POST[ "ccp_{$this->manager->name}_setting_{$this->name}" ] ) )
			$value = $_POST[ "ccp_{$this->manager->name}_setting_{$this->name}" ];

		return $this->sanitize( $value );
	}

	/**
	 * Sanitizes the value of the setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed
	 */
	public function sanitize( $value ) {

		return apply_filters( "ccp_{$this->manager->name}_sanitize_{$this->name}", $value, $this );
	}

	/**
	 * Saves the value of the setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function save( $post_id ) {

		$old_value = $this->get_value( $post_id );
		$new_value = $this->get_posted_value();

		// If we have don't have a new value but do have an old one, delete it.
		if ( '' == $new_value && $old_value )
			ccp_delete_project_meta( $post_id, $this->name );

		// If the new value doesn't match the old value, set it.
		else if ( $new_value !== $old_value )
			ccp_set_project_meta( $post_id, $this->name, $new_value );
	}
}
