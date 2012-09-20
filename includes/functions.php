<?php

add_filter( 'post_type_archive_title', 'cpt_portfolio_post_type_archive_title' );

add_filter( 'post_type_link', 'cpt_portfolio_post_type_link', 10, 2 );

/**
 * @since 0.1.0
 * @access public
 * @return array
 */
function cpt_portfolio_get_default_settings() {

	$settings = array(
		'portfolio_root'      => 'portfolio',
		'portfolio_base'      => '',          // defaults to 'portfolio_root'
		'portfolio_item_base' => 'item'
	);

	return $settings;
}

/**
 * @since 0.1.0
 * @access public
 * @param string $title
 * @return string
 */
function cpt_portfolio_post_type_archive_title( $title ) {

	if ( is_post_type_archive( 'portfolio_item' ) ) {
		$post_type = get_post_type_object( 'portfolio_item' );
		$title = isset( $post_type->labels->archive_title ) ? $post_type->labels->archive_title : $title;
	}

	return $title;
}

/**
 * @since 0.1.0
 * @access public
 * @param string $post_link
 * @param object $post
 * @return string
 */
function cpt_portfolio_post_type_link( $post_link, $post ) {

	// @todo apply filters for post type name.
	if ( 'portfolio_item' !== $post->post_type )
		return $post_link;

	/* Allow %portfolio% in the custom post type permalink. */
	if ( strpos( $post_link, '%portfolio%' ) ) {
	
		/* Get the terms. */
		$terms = get_the_terms( $post, 'portfolio' ); // @todo apply filters to tax name.

		/* Check that terms were returned. */
		if ( $terms ) {

			usort( $terms, '_usort_terms_by_ID' );

			$post_link = str_replace( '%portfolio%', $terms[0]->slug, $post_link );

		} else {
			$post_link = str_replace( '%portfolio%', 'item', $post_link );
		}
	}

	return $post_link;
}

?>