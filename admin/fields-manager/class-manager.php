<?php
/**
 * Custom fields manager.  The purpose of this class is to offer a mini framework for putting
 * together a "manager box" that can be used for handling custom fields.  This box contains
 * sections and controls.  These can be tabbed or have any type of interface.
 *
 * @package    CustomContentPortfolio
 * @subpackage Admin
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013-2015, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Handles building the fields manager.
 *
 * @since  1.0.0
 * @access public
 */
class CCP_Fields_Manager {

	/**
	 * Name of this instance of the manager. Used for hooks, classes, etc.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $name = '';

	/**
	 * Array of sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array
	 */
	public $sections = array();

	/**
	 * Array of controls.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array
	 */
	public $controls = array();

	/**
	 * Array of settings.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array
	 */
	public $settings = array();

	/**
	 * Sets up the manager.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @return void
	 */
	public function __construct( $name ) {

		// Set the manager name.
		$this->name = sanitize_key( $name );

		// Load the base section, control, and setting classes.
		require_once( 'class-section.php' );
		require_once( 'class-control.php' );
		require_once( 'class-setting.php' );

		// Load sub section, control, and setting classes.
		require_once( 'class-control-date.php'    );
		require_once( 'class-control-excerpt.php' );
		require_once( 'class-setting-date.php'    );

		// Add sections and controls.
		$this->register();
	}

	/**
	 * Executes a hook for registering sections, controls, and settings.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function register() {

		// Hook before registering.
		do_action( "ccp_{$this->name}_manager_register", $this );
	}

	/**
	 * Register a section.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object|string  $section
	 * @param  array          $args
	 * @return void
	 */
	public function register_section( $section, $args = array() ) {

		if ( ! is_object( $section ) )
			$section = new CCP_Fields_Section( $this, $section, $args );

		if ( ! $this->section_exists( $section->name ) )
			$this->sections[ $section->name ] = $section;
	}

	/**
	 * Register a control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object|string  $control
	 * @param  array          $args
	 * @return void
	 */
	public function register_control( $control, $args = array() ) {

		if ( ! is_object( $control ) )
			$control = new CCP_Fields_Control( $this, $control, $args );

		if ( ! $this->control_exists( $control->name ) )
			$this->controls[ $control->name ] = $control;
	}

	/**
	 * Register a setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object|string  $setting
	 * @param  array          $args
	 * @return void
	 */
	public function register_setting( $setting, $args = array() ) {

		if ( ! is_object( $setting ) )
			$setting = new CCP_Fields_Setting( $this, $setting, $args );

		if ( ! $this->setting_exists( $setting->name ) )
			$this->settings[ $setting->name ] = $setting;
	}

	/**
	 * Unregisters a section object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @return void
	 */
	public function unregister_section( $name ) {

		if ( $this->section_exists( $name ) )
			unset( $this->sections[ $name ] );
	}

	/**
	 * Unregisters a control object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @return void
	 */
	public function unregister_control( $name ) {

		if ( $this->control_exists( $name ) )
			unset( $this->controls[ $name ] );
	}

	/**
	 * Unregisters a setting object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @return void
	 */
	public function unregister_setting( $name ) {

		if ( $this->setting_exists( $name ) )
			unset( $this->settings[ $name ] );
	}

	/**
	 * Returns a section object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @return object|bool
	 */
	public function get_section( $name ) {

		return $this->section_exists( $name ) ? $this->sections[ $name ] : false;
	}

	/**
	 * Returns a control object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @return object|bool
	 */
	public function get_control( $name ) {

		return $this->control_exists( $name ) ? $this->controls[ $name ] : false;
	}

	/**
	 * Returns a setting object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @return object|bool
	 */
	public function get_setting( $name ) {

		return $this->setting_exists( $name ) ? $this->settings[ $name ] : false;
	}

	/**
	 * Checks if a section exists.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @return bool
	 */
	public function section_exists( $name ) {

		return isset( $this->sections[ $name ] );
	}

	/**
	 * Checks if a control exists.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @return bool
	 */
	public function control_exists( $name ) {

		return isset( $this->controls[ $name ] );
	}

	/**
	 * Checks if a setting exists.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @return bool
	 */
	public function setting_exists( $name ) {

		return isset( $this->settings[ $name ] );
	}

	/**
	 * Outputs the manager HTML.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function display( $post_id ) {

		$id    = sanitize_html_class( "ccp-fields-manager-{$this->name}" );
		$class = 'ccp-fields-manager ' . sanitize_html_class( "ccp-fields-manager-{$this->name}" ); ?>

		<div id="<?php echo $id; ?>" class="<?php echo $class; ?>">
			<?php $this->nav( $post_id ); ?>
			<?php $this->content( $post_id ); ?>
		</div><!-- .ccp-fields-manager -->
	<?php }

	/**
	 * Outputs the nav.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function nav() { ?>

		<ul class="ccp-fields-nav">

		<?php foreach ( $this->sections as $section ) : ?>

			<?php $icon = preg_match( '/dashicons-/', $section->icon ) ? sprintf( 'dashicons %s', sanitize_html_class( $section->icon ) ) : esc_attr( $section->icon ); ?>

			<li>
				<a href="<?php echo esc_attr( "#ccp-fields-section-{$section->name}" ); ?>"><i class="<?php echo $icon; ?>"></i> <span class="label"><?php echo esc_html( $section->label ); ?></span></a>
			</li>

		<?php endforeach; ?>

		</ul><!-- .ccp-fields-nav -->
	<?php }

	/**
	 * Outputs the content.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function content( $post_id ) {

		// Nonce field to validate on save.
		wp_nonce_field( "ccp_{$this->name}_nonce", "ccp_{$this->name}" ); ?>

		<div class="ccp-fields-content">

		<?php foreach ( $this->sections as $section ) : ?>

			<div id="<?php echo esc_attr( "ccp-fields-section-{$section->name}" ); ?>" class="ccp-fields-section <?php echo esc_attr( "ccp-fields-section-{$section->name}" ); ?>">

				<?php if ( $section->description ) : ?>
					<p class="ccp-fields-description description"><?php echo $section->description; ?></p>
				<?php endif; ?>

				<?php foreach ( $this->controls as $control ) : ?>

					<?php if ( $section->name === $control->section ) : ?>

						<div id="<?php echo esc_attr( "ccp-fields-control-{$control->name}" ); ?>" class="ccp-fields-control <?php echo esc_attr( "ccp-fields-control-type-{$control->type}" ); ?>">
							<?php $control->content_template( $post_id ); ?>
						</div><!-- .ccp-fields-control -->

					<?php endif; ?>

				<?php endforeach; ?>

			</div><!-- .ccp-fields-section -->

		<?php endforeach; ?>

		</div><!-- .ccp-fields-content -->
	<?php }

	/**
	 * Saves the settings.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function update( $post_id ) {

		// Verify the nonce.
		if ( ! isset( $_POST["ccp_{$this->name}"] ) || ! wp_verify_nonce( $_POST["ccp_{$this->name}"], "ccp_{$this->name}_nonce" ) )
			return;

		foreach ( $this->settings as $setting )
			$setting->save( $post_id );
	}
}
