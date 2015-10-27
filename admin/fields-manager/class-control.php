<?php
/**
 * Base control class for the fields manager.
 *
 * @package    CustomContentPortfolio
 * @subpackage Admin
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013-2015, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Base control class.
 *
 * @since  1.0.0
 * @access public
 */
class CCP_Fields_Control {

	/**
	 * Stores the manager object.
	 *
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
	 * The type of setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'text';

	/**
	 * Form field attributes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array
	 */
	public $attr = '';

	/**
	 * Choices for fields with multiple choices.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array
	 */
	public $choices = '';

	/**
	 * Creates a new control object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @param  string  $name
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
	 * Gets the attributes for the control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_attr() {

		$defaults = array( 'name' => "ccp_{$this->manager->name}_setting_{$this->setting}" );

		return wp_parse_args( $this->attr, $defaults );
	}

	/**
	 * Prints the attributes for the control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function attr() {

		foreach ( $this->get_attr() as $attr => $value )
			printf( '%s="%s" ', esc_html( $attr ), esc_attr( $value ) );
	}

	/**
	 * Outputs the HTML for the control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int    $post_id
	 * @return void
	 */
	public function content_template( $post_id ) {

		if ( 'textarea' === $this->type )
			$this->template_textarea( $post_id );

		else if ( 'select' === $this->type )
			$this->template_select( $post_id );

		else if ( 'radio' === $this->type )
			$this->template_radio( $post_id );

		else if ( 'checkbox' === $this->type )
			$this->template_checkbox( $post_id );

		else
			$this->template_text( $post_id );
	}

	/**
	 * Outputs the HTML for a text input control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int     $post_id
	 * @return void
	 */
	public function template_text( $post_id ) { ?>

		<label>
			<?php if ( $this->label ) : ?>
				<span class="ccp-fields-label"><?php echo esc_html( $this->label ); ?></span>
				<br />
			<?php endif; ?>

			<input type="<?php echo esc_attr( $this->type ); ?>" value="<?php echo esc_attr( $this->get_value( $post_id ) ); ?>" <?php $this->attr(); ?> />

			<?php if ( $this->description ) : ?>
				<br />
				<span class="ccp-fields-description description"><?php echo $this->description; ?></span>
			<?php endif; ?>
		</label>
	<?php }

	/**
	 * Outputs the HTML for a textarea control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int     $post_id
	 * @return void
	 */
	public function template_textarea( $post_id ) { ?>

		<label>
			<?php if ( $this->label ) : ?>
				<span class="ccp-fields-label"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>

			<textarea <?php $this->attr(); ?>><?php echo esc_textarea( $this->get_value( $post_id ) ); ?></textarea>

			<?php if ( $this->description ) : ?>
				<span class="ccp-fields-description description"><?php echo $this->description; ?></span>
			<?php endif; ?>
		</label>
	<?php }

	/**
	 * Outputs the HTML for a drop-down select control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int     $post_id
	 * @return void
	 */
	public function template_select( $post_id ) { ?>

		<label>
			<?php if ( $this->label ) : ?>
				<span class="ccp-fields-label"><?php echo esc_html( $this->label ); ?></span>
				<br />
			<?php endif; ?>

			<?php if ( $this->description ) : ?>
				<span class="ccp-fields-description description"><?php echo $this->description; ?></span>
				<br />
			<?php endif; ?>

			<select <?php $this->attr(); ?>>

				<?php foreach ( $this->choices as $choice => $label ) : ?>

					<option value="<?php echo esc_attr( $choice ); ?>" <?php selected( $this->get_value( $post_id ), $choice ); ?>><?php echo esc_html( $label ); ?></option>

				<?php endforeach; ?>

			</select>
		</label>
	<?php }

	/**
	 * Outputs the HTML for a radio input control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int     $post_id
	 * @return void
	 */
	public function template_radio( $post_id ) { ?>

		<?php if ( $this->label ) : ?>
			<span class="ccp-fields-label"><?php echo esc_html( $this->label ); ?></span>
			<br />
		<?php endif; ?>

		<?php if ( $this->description ) : ?>
			<span class="ccp-fields-description description"><?php echo $this->description; ?></span>
			<br />
		<?php endif; ?>

		<?php foreach ( $this->choices as $choice => $label ) : ?>

			<label>
				<input type="radio" value="<?php echo esc_attr( $choice ); ?>" <?php checked( $this->get_value( $post_id ), $choice ); ?> <?php $this->attr(); ?> />
				<?php echo esc_html( $label ); ?><br />
			</label>

		<?php endforeach; ?>
	<?php }

	/**
	 * Outputs the HTML for a checkbox input control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int     $post_id
	 * @return void
	 */
	public function template_checkbox( $post_id ) { ?>

		<label>
			<input type="checkbox" value="<?php echo esc_attr( $this->get_value( $post_id ) ); ?>" <?php $this->attr(); ?><?php selected( $this->get_value( $post_id ) ); ?> />

			<?php if ( $this->label ) : ?>
				<span class="ccp-fields-label"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>

			<?php if ( $this->description ) : ?>
				<br />
				<span class="ccp-fields-description description"><?php echo $this->description; ?></span>
			<?php endif; ?>
		</label>
	<?php }
}
