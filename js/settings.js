jQuery( document ).ready( function() {

	var page_select  = jQuery( '#ccp_portfolio_page_id' );
	var base_input   = jQuery( 'input[name="ccp_settings[portfolio_rewrite_base]"]' );
	var base_default = base_input.data( 'default' );
	var base_labels  = page_select.closest( '.form-table' ).find( 'td label code span' );

	base_input.keyup( function() {
		base_labels.text( jQuery( this ).val() );
	} );

	page_select.on( 'change', function() {
		if ( ! jQuery( this ).val() ) {
			base_input.val( base_default ).prop( 'disabled', false );
			base_labels.text( base_default );
		} else {
			var page_slug = jQuery( this ).find( ':selected' ).data( 'slug' );

			base_input.val( page_slug ).prop( 'disabled', true );
			base_labels.text( page_slug );
		}
	} );

} ); // ready()
