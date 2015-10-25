<?php

/**
 * Date setting class.
 *
 * @since  1.0.0
 * @access public
 */
class CCP_Fields_Setting_Date extends CCP_Fields_Setting {

	/**
	 * Gets the posted value of the setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed
	 */
	public function get_posted_value() {

		// Get the posted year, month, and day.
		$year  = isset( $_POST[ "ccp_{$this->manager->name}_setting_{$this->name}_year" ] )  ? zeroise( absint( $_POST[ "ccp_{$this->manager->name}_setting_{$this->name}_year" ]  ), 4 ) : '';
		$month = isset( $_POST[ "ccp_{$this->manager->name}_setting_{$this->name}_month" ] ) ? zeroise( absint( $_POST[ "ccp_{$this->manager->name}_setting_{$this->name}_month" ] ), 2 ) : '';
		$day   = isset( $_POST[ "ccp_{$this->manager->name}_setting_{$this->name}_day" ] )   ? zeroise( absint( $_POST[ "ccp_{$this->manager->name}_setting_{$this->name}_day" ]   ), 2 ) : '';

		$new_date = $year && $month && $day ? "{$year}-{$month}-{$day} 00:00:00" : '';

		return $new_date;
	}
}
