jQuery( document ).ready( function() {


	/* Get the original post status in the case the user clicks "Cancel". */
	var orig_status = jQuery( 'select[name=ccp_project_type] option:selected' ).val();

	/* When user clicks the "Edit" post status link. */
	jQuery( 'a.ccp-edit-project-type' ).click(
		function( j ) {
			j.preventDefault();

			/* Grab the original status again in case user clicks "OK" or "Cancel" more than once. */
			orig_status = jQuery( 'select[name=ccp_project_type] option:selected' ).val();

			/* Hide this link. */
			jQuery( this ).hide();

			/* Open the post status select section. */
			jQuery( '#ccp-project-type-select' ).slideToggle();
		}
	);

	/* When the user clicks the "OK" post status button. */
	jQuery( 'a.ccp-save-project-type' ).click(
		function( j ) {
			j.preventDefault();

			/* Close the post status select section. */
			jQuery( '#ccp-project-type-select' ).slideToggle();

			/* Show the hidden "Edit" link. */
			jQuery( 'a.ccp-edit-project-type' ).show();
		}
	);

	/* When the user clicks the "Cancel" post status link. */
	jQuery( 'a.ccp-cancel-project-type' ).click(
		function( j ) {
			j.preventDefault();

			/* Close the post status select section. */
			jQuery( '#ccp-project-type-select' ).slideToggle();

			/* Show the hidden "Edit" link. */
			jQuery( 'a.ccp-edit-project-type' ).show();

			/* Check the original status radio since we're canceling. */
			jQuery( 'option[value="' + orig_status + '"]' ).prop( 'selected', true ).trigger( 'change' );

			/* Change the post status text. */
			/*jQuery( 'strong.ccp-current-project-type' ).text(
				jQuery( 'option[value="' + orig_status + '"]' ).text()
			);*/
		}
	);

	/* When a new status is selected, change the post status text to match the selected status. */
	jQuery( 'select[name=ccp_project_type]' ).change(
		function() {
			jQuery( 'strong.ccp-current-project-type' ).text(
				jQuery( 'option:selected', this ).text()
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
