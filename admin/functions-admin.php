<?php

/**
 * Help sidebar for all of the help tabs.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_help_sidebar_text() {

	// Get docs and help links.
	$docs_link = sprintf( '<li><a href="https://github.com/justintadlock/custom-content-portfolio/blob/master/readme.md">%s</a></li>', esc_html__( 'Documentation', 'custom-cotent-portfolio' ) );
	$help_link = sprintf( '<li><a href="http://themehybrid.com/board/topics">%s</a></li>', esc_html__( 'Support Forums', 'custom-content-portfolio' ) );

	// Return the text.
	return sprintf(
		'<p><strong>%s</strong></p><ul>%s%s</ul>',
		esc_html__( 'For more information:', 'custom-content-portfolio' ),
		$docs_link,
		$help_link
	);
}
