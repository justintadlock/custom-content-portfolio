jQuery( document ).ready( function() {

	/* === Edit sticky status in the "Publish" meta box. === */

	var sticky_checkbox = jQuery( 'input[name=ccp_project_sticky]' );
	var is_sticky       = jQuery( sticky_checkbox ).prop( 'checked' );

	// When user clicks the "Edit" sticky link.
	jQuery( 'a.ccp-edit-sticky' ).click(
		function( j ) {
			j.preventDefault();

			// Grab the original status again in case user clicks "OK" or "Cancel" more than once.
			is_sticky = jQuery( sticky_checkbox ).prop( 'checked' );

			// Hide this link.
			jQuery( this ).hide();

			// Open the sticky edit.
			jQuery( '#ccp-sticky-edit' ).slideToggle();
		}
	);

	/* When the user clicks the "OK" post status button. */
	jQuery( 'a.ccp-save-sticky' ).click(
		function( j ) {
			j.preventDefault();

			// Close the sticky edit.
			jQuery( '#ccp-sticky-edit' ).slideToggle();

			// Show the hidden "Edit" link.
			jQuery( 'a.ccp-edit-sticky' ).show();
		}
	);

	// When the user clicks the "Cancel" edit sticky link.
	jQuery( 'a.ccp-cancel-sticky' ).click(
		function( j ) {
			j.preventDefault();

			// Close the sticky edit.
			jQuery( '#ccp-sticky-edit' ).slideToggle();

			// Show the hidden "Edit" link.
			jQuery( 'a.ccp-edit-sticky' ).show();

			// Set the original checked/not-checked since we're canceling.
			jQuery( sticky_checkbox ).prop( 'checked', is_sticky ).trigger( 'change' );
		}
	);

	// When the sticky status changes.
	jQuery( sticky_checkbox ).change(
		function() {
			jQuery( 'strong.ccp-sticky-status' ).text(
				jQuery( sticky_checkbox ).prop( 'checked' ) ? ccp_i18n.label_sticky : ccp_i18n.label_not_sticky
			);
		}
	);

	/* ====== Tabs ====== */

	// Hides the tab content.
	jQuery( '.ccp-fields-section' ).hide();

	// Shows the first tab's content.
	jQuery( '.ccp-fields-section:first-child' ).show();

	// Makes the 'aria-selected' attribute true for the first tab nav item.
	jQuery( '.ccp-fields-nav :first-child' ).attr( 'aria-selected', 'true' );

	// Copies the current tab item title to the box header.
	jQuery( '.ccp-which-tab' ).text( jQuery( '.ccp-fields-nav :first-child a' ).text() );

	// When a tab nav item is clicked.
	jQuery( '.ccp-fields-nav li a' ).click(
		function( j ) {

			// Prevent the default browser action when a link is clicked.
			j.preventDefault();

			// Get the `href` attribute of the item.
			var href = jQuery( this ).attr( 'href' );

			// Hide all tab content.
			jQuery( this ).parents( '.ccp-fields-manager' ).find( '.ccp-fields-section' ).hide();

			// Find the tab content that matches the tab nav item and show it.
			jQuery( this ).parents( '.ccp-fields-manager' ).find( href ).show();

			// Set the `aria-selected` attribute to false for all tab nav items.
			jQuery( this ).parents( '.ccp-fields-manager' ).find( '.ccp-fields-nav li' ).attr( 'aria-selected', 'false' );

			// Set the `aria-selected` attribute to true for this tab nav item.
			jQuery( this ).parent().attr( 'aria-selected', 'true' );

			// Copy the current tab item title to the box header.
			jQuery( '.ccp-which-tab' ).text( jQuery( this ).text() );
		}
	); // click()

} ); // ready()
