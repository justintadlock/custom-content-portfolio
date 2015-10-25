<?php

/**
 * Base section class.
 *
 * @since  1.0.0
 * @access public
 */
class CCP_Fields_Section {

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
	 * Name/ID of the section.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $name = '';

	/**
	 * Dashicons icon for the section.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $icon = 'dashicons-admin-generic';

	/**
	 * Label for the section.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $label = '';

	/**
	 * Description for the section.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $description = '';

	/**
	 * Creates a new section object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @param  string  $section
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
	}
}
