<?php
/**
 * Date control class for the fields manager.
 *
 * @package    CustomContentPortfolio
 * @subpackage Admin
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013-2015, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Date control class.
 *
 * @since  1.0.0
 * @access public
 */
class CCP_Fields_Control_Date extends CCP_Fields_Control {

	/**
	 * Outputs the content template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function content_template( $post_id ) {

		// Get project start/end dates.
		$date = $this->get_value( $post_id );

		// Get the individual years, months, and days.
		$year  = $date ? mysql2date( 'Y', $date, false ) : '';
		$month = $date ? mysql2date( 'm', $date, false ) : '';
		$day   = $date ? mysql2date( 'd', $date, false ) : '';

		// Get the year, month, and day form fields.
		$year_field  = $this->get_year_field( "ccp_{$this->manager->name}_setting_{$this->setting}_year",   $year  );
		$month_field = $this->get_month_field( "ccp_{$this->manager->name}_setting_{$this->setting}_month", $month );
		$day_field   = $this->get_day_field( "ccp_{$this->manager->name}_setting_{$this->setting}_day",     $day   ); ?>

		<label>
			<?php if ( $this->label ) : ?>
				<span class="ccp-fields-label"><?php echo esc_html( $this->label ); ?></span>
				<br />
			<?php endif; ?>

			<?php // Translators: 1: month, 2: day, 3: year.
			printf( __( '%1$s %2$s, %3$s', 'custom-content-portfolio' ), $month_field, $day_field, $year_field ); ?>

			<?php if ( $this->description ) : ?>
				<br />
				<span class="ccp-fields-description description"><?php echo $this->description; ?></span>
			<?php endif; ?>
		</label>
	<?php }

	/**
	 * Returns a year form field text box.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @param  string  $value
	 * @return string
	 */
	public function get_year_field( $name, $value ) {

		return sprintf(
			'<label><span class="screen-reader-text">%s</span><input type="text" name="%s" value="%s" placeholder="%s" size="4" maxlength="4" autocomplete="off" /></label>',
			esc_html__( 'Year', 'custom-content-portfolio' ),
			esc_attr( $name ),
			esc_attr( $value ),
			esc_attr( date( 'Y' ) )
		);
	}

	/**
	 * Returns a month form field select box.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @param  string  $value
	 * @global object  $wp_locale
	 * @return string
	 */
	public function get_month_field( $name, $value ) {
		global $wp_locale;

		$options = '<option value=""></option>';

		for ( $i = 1; $i < 13; $i = $i +1 ) {

			$monthnum  = zeroise( $i, 2 );
			$monthtext = $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) );

			$options .= sprintf(
				'<option value="%s"%s>%s</option>',
				$monthnum,
				selected( $monthnum, $value, false ),
				// Translators: 1: month number (01, 02, etc.), 2: month abbreviation.
				sprintf( esc_html__( '%1$s-%2$s', 'custom-content-portfolio' ), $monthnum, $monthtext )
			);
		}

		return sprintf(
			'<label><span class="screen-reader-text">%s</span><select name="%s">%s</select></label>',
			esc_html__( 'Month', 'custom-content-portfolio' ),
			esc_attr( $name ),
			$options
		);
	}

	/**
	 * Returns a day form field text box.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @param  string  $value
	 * @return string
	 */
	public function get_day_field( $name, $value ) {

		return sprintf(
			'<label><span class="screen-reader-text">%s</span><input type="text" name="%s" value="%s" placeholder="%s" size="2" maxlength="2" autocomplete="off" /></label>',
			esc_html__( 'Day', 'custom-content-portfolio' ),
			esc_attr( $name ),
			esc_attr( $value ),
			esc_attr( date( 'd' ) )
		);
	}
}
