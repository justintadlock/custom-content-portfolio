<?php
/**
 * Template tags related to portfolio users/authors for theme authors to use in their theme templates.
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013-2015, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

function ccp_is_author( $author = '' ) {

	return apply_filters( 'ccp_is_author', ccp_is_project_archive() && is_author( $author ) );
}
