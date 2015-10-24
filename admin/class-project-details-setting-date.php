<?php

/**
 * Cap control class.
 *
 * @since  1.0.0
 * @access public
 */
class CCP_Project_Details_Setting_Date extends CCP_Project_Details_Setting {

	public function get_posted_value() {

		// Get the posted year, month, and day.
		$year  = isset( $_POST[ "ccp_setting_{$this->name}_year" ] )  ? zeroise( absint( $_POST[ "ccp_setting_{$this->name}_year" ]  ), 4 ) : '';
		$month = isset( $_POST[ "ccp_setting_{$this->name}_month" ] ) ? zeroise( absint( $_POST[ "ccp_setting_{$this->name}_month" ] ), 2 ) : '';
		$day   = isset( $_POST[ "ccp_setting_{$this->name}_day" ] )   ? zeroise( absint( $_POST[ "ccp_setting_{$this->name}_day" ]   ), 2 ) : '';

		$new_date = $year && $month && $day ? "{$year}-{$month}-{$day} 00:00:00" : '';

		return $new_date;
	}
}
