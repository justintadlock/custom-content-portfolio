<?php
/**
 * Project types API.  Project types are a way to distinguish between different types of projects.  The default
 * types are "project/normal", "super", "sticky".  Developers can add new types if they wish to do so.
 *
 */

# Register project types.
add_action( 'init', 'ccp_register_project_types', 95 );

/**
 * Returns the instance of the `CCP_Project_Type_Factory` object.
 *
 * @since  1.0.0
 * @access public
 * @return object
 */
function ccp_project_type_factory() {
	return CCP_Project_Type_Factory::get_instance();
}

/**
 * Returns the "normal" project type.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_normal_project_type() {
	return apply_filters( 'ccp_get_normal_project_type', 'normal' );
}

/**
 * Returns the "sticky" project type.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_sticky_project_type() {
	return apply_filters( 'ccp_get_sticky_project_type', 'sticky' );
}

/**
 * Registers custom project types.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function ccp_register_project_types() {

	$post_type_object = get_post_type_object( ccp_get_project_post_type() );

	// Normal type args.
	$normal_args = array(
		'_builtin'        => true,
		'_internal'       => true,
		'show_in_status_list' => false,
		'show_in_row_actions' => false,
		'show_in_post_states' => false,
		'capability'      => $post_type_object->cap->edit_posts,
		'label'           => __( 'Normal', 'custom-content-portfolio' ),
		'label_count'     => _n_noop( 'Normal <span class="count">(%s)</span>', 'Normal <span class="count">(%s)</span>', 'custom-content-portfolio' ),
	);

	// Sticky type args.
	$sticky_args = array(
		'_builtin'        => true,
		'_internal'       => false,
		'count_callback'  => 'ccp_get_sticky_project_count',
		'capability'      => $post_type_object->cap->publish_posts,
		'label'           => __( 'Sticky', 'custom-content-portfolio' ),
		'label_count'     => _n_noop( 'Sticky <span class="count">(%s)</span>', 'Sticky <span class="count">(%s)</span>', 'custom-content-portfolio' ),
		'label_undo'      => __( 'Unsticky', 'custom-content-portfolio' ),
	);

	// Register project types.
	ccp_register_project_type( ccp_get_normal_project_type(), apply_filters( 'ccp_normal_project_type_args', $normal_args ) );
	ccp_register_project_type( ccp_get_sticky_project_type(), apply_filters( 'ccp_sticky_project_type_args', $sticky_args ) );

	// Hook for registering project types.
	do_action( 'ccp_register_project_types' );
}

/**
 * Registers a new project type.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $name
 * @param  array   $args
 * @return void
 */
function ccp_register_project_type( $name, $args = array() ) {

	ccp_project_type_factory()->register_type( $name, $args );
}

/**
 * Unregister a project type.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $name
 * @return void
 */
function ccp_unregister_project_type( $name ) {

	ccp_project_type_factory()->unregister_type( $name );
}

/**
 * Check if a project type is registered.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $name
 * @return bool
 */
function ccp_project_type_exists( $name ) {

	return ccp_project_type_factory()->type_exists( $name );
}

/**
 * Returns an array of the registered project type objects.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function ccp_get_project_type_objects() {

	return ccp_project_type_factory()->types;
}

/**
 * Returns a single project type object.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $name
 * @return object|bool
 */
function ccp_get_project_type_object( $name ) {

	return ccp_project_type_factory()->get_type( $name );
}

/**
 * Conditional check to see if a project has the "normal" type.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $project_id
 * @return bool
 */
function ccp_is_project_normal( $project_id = 0 ) {
	$project_id = ccp_get_project_id( $project_id );

	return ccp_get_normal_project_type() === ccp_get_project_type( $project_id ) ? true : false;
}

/**
 * Conditional check to see if a project has the "sticky" type.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $project_id
 * @return bool
 */
function ccp_is_project_sticky( $project_id = 0 ) {
	$project_id = ccp_get_project_id( $project_id );

	return ccp_get_sticky_project_type() === ccp_get_project_type( $project_id ) ? true : false;
}

/**
 * Displays the project type for a specific project.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $project_id
 * @return void
 */
function ccp_project_type( $project_id = 0 ) {
	echo ccp_get_project_type( $project_id );
}

/**
 * Returns the project type for a specific project.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $project_id
 * @return string
 */
function ccp_get_project_type( $project_id = 0 ) {
	$project_id = ccp_get_project_id( $project_id );

	//$project_type = $project_id ? ccp_get_project_meta( $project_id, 'project_type' ) : '';

	$project_type = wp_get_post_terms( $project_id, ccp_get_type_taxonomy() );

	if ( $project_type && ! is_wp_error( $project_type ) )
		$project_type = array_shift( $project_type );

	$project_type = $project_type && ! is_wp_error( $project_type ) && ccp_project_type_exists( $project_type->name ) ? $project_type->name : ccp_get_normal_project_type();

	return apply_filters( 'ccp_get_project_type', $project_type, $project_id );
}

/**
 * Sets the project type for a specific project.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $project_id
 * @param  string  $project_type
 * @return bool
 */
function ccp_set_project_type( $project_id, $type ) {

	$type = ccp_project_type_exists( $type ) ? $type : ccp_get_normal_project_type();

	return wp_set_post_terms( $project_id, $type, ccp_get_type_taxonomy(), false );

	return ccp_set_project_meta( $project_id, 'project_type', $type );
}

/**
 * Adds a project to the list of sticky projects.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $project_id
 * @return bool
 */
function ccp_add_sticky_project( $project_id ) {
	$project_id = ccp_get_project_id( $project_id );

	if ( ! ccp_is_project_sticky( $project_id ) )
		return update_option( 'ccp_sticky_projects', array_unique( array_merge( ccp_get_sticky_projects(), array( $project_id ) ) ) );

	return false;
}

/**
 * Removes a project from the list of sticky projects.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $project_id
 * @return bool
 */
function ccp_remove_sticky_project( $project_id ) {
	$project_id = ccp_get_project_id( $project_id );

	if ( ccp_is_project_sticky( $project_id ) ) {
		$stickies = ccp_get_sticky_projects();
		$key      = array_search( $project_id, $stickies );

		if ( isset( $stickies[ $key ] ) ) {
			unset( $stickies[ $key ] );
			return update_option( 'ccp_sticky_projects', array_unique( $stickies ) );
		}
	}

	return false;
}

/**
 * Creates a dropdown `<select>` for selecting the project type in forms.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return string
 */
function ccp_dropdown_project_type( $args = array() ) {

	$defaults = array(
		'name'      => 'ccp_project_type',
		'id'        => 'ccp_project_type',
		'selected'  => ccp_get_project_type(),
		'echo'      => true
	);

	$args = wp_parse_args( $args, $defaults );

	$types = ccp_get_project_type_objects();

	$out = sprintf( '<select name="%s" id="%s">', sanitize_html_class( $args['name'] ), sanitize_html_class( $args['id'] ) );

	if ( ! empty( $args['selected'] ) && ! current_user_can( ccp_get_project_type_object( $args['selected'] )->capability ) ) {

		$type = ccp_get_project_type_object( $args['selected'] );
		$out .= sprintf( '<option value="%s"%s>%s</option>', esc_attr( $type->name ), selected( $type->name, $args['selected'], false ), $type->label );

	} else {
		foreach ( $types as $type ) {

			if ( ! current_user_can( $type->capability ) )
				continue;

			$out .= sprintf( '<option value="%s"%s>%s</option>', esc_attr( $type->name ), selected( $type->name, $args['selected'], false ), $type->label );
		}
	}

	$out .= '</select>';

	if ( ! $args['echo'] )
		return $out;

	echo $out;
}

/**
 * Returns an array of sticky projects.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function ccp_get_sticky_projects() {
	return apply_filters( 'ccp_get_sticky_projects', get_option( 'ccp_sticky_projects', array() ) );
}

/**
 * Returns the sticky project count.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function ccp_get_sticky_project_count() {
	return count( ccp_get_sticky_projects() );
}
