<?php

final class CCP_Meta_Box_Project_Details {

	/**
	 * Sets up the appropriate actions.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected function __construct() {

		add_action( 'load-post.php',     array( $this, 'load' ) );
		add_action( 'load-post-new.php', array( $this, 'load' ) );
	}

	/**
	 * Fires on the page load hook to add actions specifically for the post and
	 * new post screens.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function load() {

		$project_type = ccp_get_project_post_type();

		// Add custom meta boxes.
		add_action( "add_meta_boxes_{$project_type}", array( $this, 'add_meta_boxes' ) );

		// Save metadata on post save.
		add_action( 'save_post', array( $this, 'update' ) );
	}

	/**
	 * Adds the meta box.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function add_meta_boxes() {

		add_meta_box( 'ccp-project-details', esc_html__( 'Project Details', 'custom-content-portfolio' ), array( $this, 'meta_box' ), ccp_get_project_post_type(), 'side', 'core' );
	}

	/**
	 * Outputs the meta box HTML.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $post
	 * @global object  $wp_roles
	 * @return void
	 */
	public function meta_box( $post ) {

		// Get project start/end dates.
		$start_date = ccp_get_project_meta( $post->ID, 'start_date' );
		$end_date   = ccp_get_project_meta( $post->ID, 'end_date'   );

		// Get the individual years, months, and days.
		$start_year  = $start_date ? mysql2date( 'Y', $start_date, false ) : '';
		$start_month = $start_date ? mysql2date( 'm', $start_date, false ) : '';
		$start_day   = $start_date ? mysql2date( 'd', $start_date, false ) : '';
		$end_year    = $end_date   ? mysql2date( 'Y', $end_date,   false ) : '';
		$end_month   = $end_date   ? mysql2date( 'm', $end_date,   false ) : '';
		$end_day     = $end_date   ? mysql2date( 'd', $end_date,   false ) : '';

		// Get the year, month, and day form fields.
		$s_year  = $this->get_year_field( 'ccp_project_start_year',   $start_year  );
		$s_month = $this->get_month_field( 'ccp_project_start_month', $start_month );
		$s_day   = $this->get_day_field( 'ccp_project_start_day',     $start_day   );
		$e_year  = $this->get_year_field( 'ccp_project_end_year',     $end_year    );
		$e_month = $this->get_month_field( 'ccp_project_end_month',   $end_month   );
		$e_day   = $this->get_day_field( 'ccp_project_end_day',       $end_day     );

		// Nonce field to validate on save.
		wp_nonce_field( 'ccp_project_details_nonce', 'ccp_project_details' ); ?>

		<p>
			<label>
				<strong><?php esc_html_e( 'URL', 'custom-content-portfolio' ); ?></strong>
				<input type="text" class="widefat code" name="ccp_project_url" value="<?php echo esc_url( ccp_get_project_meta( $post->ID, 'url' ) ); ?>" placeholder="http://example.com" />
				<span class="howto"><?php esc_html_e( 'Enter the URL of the project Web page.', 'custom-content-portfolio' ); ?></span>
			</label>
		</p>

		<p>
			<label>
				<strong><?php esc_html_e( 'Client', 'custom-content-portfolio' ); ?></strong>
				<input type="text" class="widefat" name="ccp_project_client" value="<?php echo esc_attr( ccp_get_project_meta( $post->ID, 'client' ) ); ?>" placeholder="<?php esc_attr_e( 'John Doe', 'custom-content-portfolio' ); ?>" />
				<span class="howto"><?php esc_html_e( 'Enter the name of the client for the project.', 'custom-content-portfolio' ); ?></span>
			</label>
		</p>

		<p>
			<strong><?php esc_html_e( 'Start Date', 'custom-content-portfolio' ); ?></strong>
			<br />
			<?php // Translators: 1: month, 2: day, 3: year.
			printf( __( '%1$s %2$s, %3$s', 'custom-content-portfolio' ), $s_month, $s_day, $s_year ); ?>
		</p>

		<p>
			<strong><?php esc_html_e( 'End Date', 'custom-content-portfolio' ); ?></strong>
			<br />
			<?php // Translators: 1: month, 2: day, 3: year.
			printf( __( '%1$s %2$s, %3$s', 'custom-content-portfolio' ), $e_month, $e_day, $e_year ); ?>
		</p>
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

	/**
	 * Saves the post meta.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int     $post_id
	 * @return void
	 */
	public function update( $post_id ) {

		// Verify the nonce.
		if ( ! isset( $_POST['ccp_project_details'] ) || ! wp_verify_nonce( $_POST['ccp_project_details'], 'ccp_project_details_nonce' ) )
			return;

		/* === Project URL === */

		// Get the old url.
		$old_url = ccp_get_project_meta( $post_id, 'url' );

		// Get the new url.
		$new_url = isset( $_POST['ccp_project_url'] ) ? esc_url_raw( $_POST['ccp_project_url'] ) : '';

		// If we have don't have a new url but do have an old one, delete it.
		if ( '' == $new_url && $old_url )
			ccp_delete_project_meta( $post_id, 'url' );

		// If the new url doesn't match the old url, set it.
		else if ( $new_url !== $old_url )
			ccp_set_project_meta( $post_id, 'url', $new_url );

		/* === Project Client === */

		// Get the old client.
		$old_client = ccp_get_project_meta( $post_id, 'client' );

		// Get the new client.
		$new_client = isset( $_POST['ccp_project_client'] ) ? strip_tags( $_POST['ccp_project_client'] ) : '';

		// If we have don't have a new client but do have an old one, delete it.
		if ( '' == $new_client && $old_client )
			ccp_delete_project_meta( $post_id, 'client' );

		// If the new client doesn't match the old client, set it.
		else if ( $new_client !== $old_client )
			ccp_set_project_meta( $post_id, 'client', $new_client );

		/* === Project Start Date === */

		// Get the old date.
		$old_start_date = ccp_get_project_meta( $post_id, 'start_date' );

		// Get the posted year, month, and day.
		$s_year  = isset( $_POST['ccp_project_start_year'] )  ? zeroise( absint( $_POST['ccp_project_start_year']  ), 4 ) : '';
		$s_month = isset( $_POST['ccp_project_start_month'] ) ? zeroise( absint( $_POST['ccp_project_start_month'] ), 2 ) : '';
		$s_day   = isset( $_POST['ccp_project_start_day'] )   ? zeroise( absint( $_POST['ccp_project_start_day']   ), 2 ) : '';

		// If we have a year, month, and day, get the new date.
		$new_start_date = $s_year && $s_month && $s_day ? "{$s_year}-{$s_month}-{$s_day} 00:00:00" : '';

		// If we have don't have a new date but do have an old one, delete it.
		if ( '' == $new_start_date && $old_start_date )
			ccp_delete_project_meta( $post_id, 'start_date' );

		// If the new date doesn't match the old date, set it.
		else if ( $new_start_date !== $old_start_date )
			ccp_set_project_meta( $post_id, 'start_date', $new_start_date );

		/* === Project End Date === */

		// Get the old date.
		$old_end_date = ccp_get_project_meta( $post_id, 'end_date' );

		// Get the posted year, month, and day.
		$e_year  = isset( $_POST['ccp_project_end_year'] )  ? zeroise( absint( $_POST['ccp_project_end_year']  ), 4 ) : '';
		$e_month = isset( $_POST['ccp_project_end_month'] ) ? zeroise( absint( $_POST['ccp_project_end_month'] ), 2 ) : '';
		$e_day   = isset( $_POST['ccp_project_end_day'] )   ? zeroise( absint( $_POST['ccp_project_end_day']   ), 2 ) : '';

		// If we have a year, month, and day, get the new date.
		$new_end_date = $e_year && $e_month && $e_day ? "{$e_year}-{$e_month}-{$e_day} 00:00:00" : '';

		// If we have don't have a new date but do have an old one, delete it.
		if ( '' == $new_end_date && $old_end_date )
			ccp_delete_project_meta( $post_id, 'end_date' );

		// If the new date doesn't match the old date, set it.
		else if ( $new_end_date !== $old_end_date )
			ccp_set_project_meta( $post_id, 'end_date', $new_end_date );
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
			$instance = new CCP_Meta_Box_Project_Details;

		return $instance;
	}
}

CCP_Meta_Box_Project_Details::get_instance();
