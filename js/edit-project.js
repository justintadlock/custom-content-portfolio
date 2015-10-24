jQuery( document ).ready( function() {

	/* ====== Tabs ====== */

	// Hides the tab content.
	jQuery( '.ccp-project-section' ).hide();

	// Shows the first tab's content.
	jQuery( '.ccp-project-section:first-child' ).show();

	// Makes the 'aria-selected' attribute true for the first tab nav item.
	jQuery( '.ccp-project-sections-nav :first-child' ).attr( 'aria-selected', 'true' );

	// Copies the current tab item title to the box header.
	jQuery( '.ccp-which-tab' ).text( jQuery( '.ccp-project-sections-nav :first-child a' ).text() );

	// When a tab nav item is clicked.
	jQuery( '.ccp-project-sections-nav li a' ).click(
		function( j ) {

			// Prevent the default browser action when a link is clicked.
			j.preventDefault();

			// Get the `href` attribute of the item.
			var href = jQuery( this ).attr( 'href' );

			// Hide all tab content.
			jQuery( this ).parents( '.ccp-project-manager' ).find( '.ccp-project-section' ).hide();

			// Find the tab content that matches the tab nav item and show it.
			jQuery( this ).parents( '.ccp-project-manager' ).find( href ).show();

			// Set the `aria-selected` attribute to false for all tab nav items.
			jQuery( this ).parents( '.ccp-project-manager' ).find( '.ccp-project-sections-nav li' ).attr( 'aria-selected', 'false' );

			// Set the `aria-selected` attribute to true for this tab nav item.
			jQuery( this ).parent().attr( 'aria-selected', 'true' );

			// Copy the current tab item title to the box header.
			jQuery( '.ccp-which-tab' ).text( jQuery( this ).text() );
		}
	); // click()

} ); // ready()
