<?php
/**
 * Various functions, filters, and actions used by the plugin.
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013-2015, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Add sticky posts to the front of the line.
add_filter( 'the_posts', 'ccp_posts_sticky_filter', 10, 2 );

# Filter the document title.
add_filter( 'document_title_parts', 'ccp_document_title_parts', 5 );

# Filter the post type archive title.
add_filter( 'post_type_archive_title', 'ccp_post_type_archive_title' );

# Filter the archive title and description.
add_filter( 'get_the_archive_title',       'ccp_get_the_archive_title',       5 );
add_filter( 'get_the_archive_description', 'ccp_get_the_archive_description', 5 );

# Filter the post type permalink.
add_filter( 'post_type_link', 'ccp_post_type_link', 10, 2 );

# Filter the post author link.
add_filter( 'author_link', 'ccp_author_link_filter', 10, 3 );

# Force taxonomy term selection.
add_action( 'save_post', 'ccp_force_term_selection' );

# Filter the Breadcrumb Trail plugin args.
add_filter( 'breadcrumb_trail_args', 'ccp_breadcrumb_trail_args', 15 );

/**
 * Filter on `the_posts` for the project archive. Moves sticky posts to the top of
 * the project archive list.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $posts
 * @param  object $query
 * @return array
 */
function ccp_posts_sticky_filter( $posts, $query ) {

	if ( $query->is_main_query() && ! is_admin() && ccp_is_project_archive() ) {

		remove_filter( 'the_posts', 'ccp_posts_sticky_filter' );

		$posts = ccp_add_stickies( $posts, ccp_get_sticky_projects() );
	}

	return $posts;
}

/**
 * Adds sticky posts to the front of the line with any given set of posts and stickies.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $posts         Array of post objects.
 * @param  array  $sticky_posts  Array of post IDs.
 * @return array
 */
function ccp_add_stickies( $posts, $sticky_posts ) {

	// Only do this if on the first page and we indeed have stickies.
	if ( ! is_paged() && ! empty( $sticky_posts ) ) {

		$num_posts     = count( $posts );
		$sticky_offset = 0;

		// Loop over posts and relocate stickies to the front.
		for ( $i = 0; $i < $num_posts; $i++ ) {

			if ( in_array( $posts[ $i ]->ID, $sticky_posts ) ) {

				$sticky_post = $posts[ $i ];

				// Remove sticky from current position.
				array_splice( $posts, $i, 1);

				// Move to front, after other stickies.
				array_splice( $posts, $sticky_offset, 0, array( $sticky_post ) );

				// Increment the sticky offset. The next sticky will be placed at this offset.
				$sticky_offset++;

				// Remove post from sticky posts array.
				$offset = array_search( $sticky_post->ID, $sticky_posts );

				unset( $sticky_posts[ $offset ] );
			}
		}

		// Fetch sticky posts that weren't in the query results.
		if ( ! empty( $sticky_posts ) ) {

			$args = array(
					'post__in'    => $sticky_posts,
					'post_type'   => ccp_get_project_post_type(),
					'post_status' => 'publish',
					'nopaging'    => true
			);

			$stickies = get_posts( $args );

			foreach ( $stickies as $sticky_post ) {
				array_splice( $posts, $sticky_offset, 0, array( $sticky_post ) );
				$sticky_offset++;
			}
		}
	}

	return $posts;
}

/**
 * Filter on `document_title_parts` (WP 4.4.0).
 *
 * @since  1.0.0
 * @access public
 * @param  array  $title
 * @return array
 */
function ccp_document_title_parts( $title ) {

	if ( ccp_is_author() )
		$title['title'] = ccp_get_single_author_title();

	return $title;
}

/**
 * Filter on 'post_type_archive_title' to allow for the use of the 'archive_title' label that isn't supported
 * by WordPress.  That's okay since we can roll our own labels.
 *
 * @since  0.1.0
 * @access public
 * @param  string $title
 * @return string
 */
function ccp_post_type_archive_title( $title ) {

	return ccp_is_project_archive() ? get_post_type_object( ccp_get_project_post_type() )->labels->archive_title : $title;
}

/**
 * Filters the archive title. Note that we need this additional filter because core WP does
 * things like add "Archives:" in front of the archive title.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $title
 * @return string
 */
function ccp_get_the_archive_title( $title ) {

	if ( ccp_is_author() )
		$title = ccp_get_single_author_title();

	else if ( ccp_is_project_archive() )
		$title = post_type_archive_title( '', false );

	return $title;
}

/**
 * Filters the archive description.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $desc
 * @return string
 */
function ccp_get_the_archive_description( $desc ) {

	if ( ccp_is_author() )
		$desc = get_the_author_meta( 'description', get_query_var( 'author' ) );

	else if ( cp_is_project_archive() && ! $desc )
		$desc = ccp_get_portfolio_description();

	return $desc;
}

/**
 * Filter on `post_type_link` to make sure that single portfolio projects have the correct
 * permalink.
 *
 * @since  0.1.0
 * @access public
 * @param  string  $post_link
 * @param  object  $post
 * @return string
 */
function ccp_post_type_link( $post_link, $post ) {

	// Bail if this isn't a portfolio project.
	if ( ccp_get_project_post_type() !== $post->post_type )
		return $post_link;

	$cat_taxonomy = ccp_get_category_taxonomy();
	$tag_taxonomy = ccp_get_tag_taxonomy();

	$author = $category = $tag = '';

	// Check for the category.
	if ( false !== strpos( $post_link, "%{$cat_taxonomy}%" ) ) {

		// Get the terms.
		$terms = get_the_terms( $post, $cat_taxonomy );

		// Check that terms were returned.
		if ( $terms ) {

			usort( $terms, '_usort_terms_by_ID' );

			$category = $terms[0]->slug;
		}
	}

	// Check for the tag.
	if ( false !== strpos( $post_link, "%{$tag_taxonomy}%" ) ) {

		// Get the terms.
		$terms = get_the_terms( $post, $tag_taxonomy );

		// Check that terms were returned.
		if ( $terms ) {

			usort( $terms, '_usort_terms_by_ID' );

			$tag = $terms[0]->slug;
		}
	}

	// Check for the author.
	if ( false !== strpos( $post_link, '%author%' ) ) {

		$authordata = get_userdata( $post->post_author );
		$author     = $authordata->user_nicename;
	}

	$rewrite_tags = array(
		'%portfolio_category%',
		'%portfolio_tag%',
		'%author%'
	);

	$map_tags = array(
		$category,
		$tag,
		$author
	);

	return str_replace( $rewrite_tags, $map_tags, $post_link );
}

/**
 * Filter on `author_link` to change the URL when viewing a portfolio project. The new link
 * should point to the portfolio author archive.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $url
 * @param  int     $author_id
 * @param  string  $nicename
 * @return string
 */
function ccp_author_link_filter( $url, $author_id, $nicename ) {
	global $wp_rewrite;

	if ( $nicename && ccp_is_project() ) {

		if ( $wp_rewrite->using_permalinks() )
			$url = home_url( user_trailingslashit( trailingslashit( ccp_get_author_rewrite_slug() ) . $nicename ) );

		else
			$url = add_query_arg( array( 'post_type' => ccp_get_project_post_type(), 'author_name' => $nicename ), home_url( '/' ) );
	}

	return $url;
}

/**
 * If a project has `%portfolio_category%` or `%portfolio_tag%` in its permalink structure,
 * it must have a term set for the taxonomy.  This function is a callback on `save_post`
 * that checks if a term is set.  If not, it forces the first term of the taxonomy to be
 * the selected term.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $post_id
 * @return void
 */
function ccp_force_term_selection( $post_id ) {

	if ( ccp_is_project( $post_id ) ) {

		$project_base = ccp_get_project_rewrite_base();
		$cat_tax      = ccp_get_category_taxonomy();
		$tag_tax      = ccp_get_tag_taxonomy();

		if ( false !== strpos( $project_base, "%{$cat_tax}%" ) )
			ccp_set_term_if_none( $post_id, $cat_tax, ccp_get_default_category() );

		if ( false !== strpos( $project_base, "%{$tag_tax}%" ) )
			ccp_set_term_if_none( $post_id, $tag_tax, ccp_get_default_tag() );
	}
}

/**
 * Checks if a post has a term of the given taxonomy.  If not, set it with the first
 * term available from the taxonomy.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $post_id
 * @param  string  $taxonomy
 * @param  int     $default
 * @return void
 */
function ccp_set_term_if_none( $post_id, $taxonomy, $default = 0 ) {

	// Get the current post terms.
	$terms = wp_get_post_terms( $post_id, $taxonomy );

	// If no terms are set, let's roll.
	if ( ! $terms ) {

		$new_term = false;

		// Get the default term if set.
		if ( $default )
			$new_term = get_term( $default, $taxonomy );

		// If no default term or if there's an error, get the first term.
		if ( ! $new_term || is_wp_error( $new_term ) ) {
			$available = get_terms( $taxonomy, array( 'number' => 1 ) );

			// Get the first term.
			$new_term = $available ? array_shift( $available ) : false;
		}

		// Only run if there are taxonomy terms.
		if ( $new_term ) {
			$tax_object = get_taxonomy( $taxonomy );

			// Use the ID for hierarchical taxonomies. Use the slug for non-hierarchical.
			$slug_or_id = $tax_object->hierarchical ? $new_term->term_id : $new_term->slug;

			// Set the new post term.
			wp_set_post_terms( $post_id, $slug_or_id, $taxonomy, true );
		}
	}
}

/**
 * Filters the Breadcrumb Trail plugin arguments.  We're basically just telling it to show the
 * `portfolio_category` taxonomy when viewing single portfolio projects.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return array
 */
function ccp_breadcrumb_trail_args( $args ) {

	$project_type = ccp_get_project_post_type();
	$project_base = ccp_get_project_rewrite_base();

	if ( false === strpos( $project_base, '%' ) && ! isset( $args['post_taxonomy'][ $project_type ] ) )
		$args['post_taxonomy'][ $project_type ] = ccp_get_category_taxonomy();

	return $args;
}
