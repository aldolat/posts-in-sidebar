/**
 * Posts in Sidebar javascript for admin UI.
 * This file is a modified version of Category Posts Widget's js file from @kometschuh
 * released under GPLv2 or later.
 *
 * @package PostsInSidebar
 * @since 2.0
 * @since 4.0 Now the panels remain open if Save button is clicked.
 */

// The namespace.
var pis_namespace = {
	// Holds an array of open panels per wiget id.
	open_panels : {},
	// Generic click handler on the panel title.
	clickHandler: function( element ) {
		// Open the div "below" the h4 title.
		jQuery( element ).toggleClass( 'open' ).next().stop().slideToggle();
		// Get the data-panel attribute, for example "custom-taxonomy-query".
		var panel = element.getAttribute( 'data-panel' );
		// 1st LEVEL PANELS: Get the id of the widget, for example "widget-32_pis_posts_in_sidebar-8".
		var id = jQuery( element ).parent().parent().parent().parent().parent().attr( 'id' );
		// 2nd LEVEL (CHILD) PANELS: Get the id of the widget, for example "widget-32_pis_posts_in_sidebar-8".
		if ( id === undefined ) {
			var id = jQuery( element ).parent().parent().parent().parent().parent().parent().parent().attr( 'id' );
		}
		// 3rd LEVEL (CHILD) PANELS: Get the id of the widget, for example "widget-32_pis_posts_in_sidebar-8".
		if ( id === undefined ) {
			var id = jQuery( element ).parent().parent().parent().parent().parent().parent().parent().parent().attr( 'id' );
		}
		var o = {};
		if ( this.open_panels.hasOwnProperty( id ) ) {
			o = this.open_panels[id];
		}
		if ( o.hasOwnProperty( panel ) ) {
			delete o[panel];
		} else {
			o[panel] = true;
		}
		this.open_panels[id] = o;
	}
}

jQuery( document ).ready( function() {
	// Open/close the widget panel.
	jQuery( '.pis-widget-title' ).click( function() {
		pis_namespace.clickHandler( this );
	});

	// After saving the widget, we need to reassign click handlers.
	jQuery( document ).on( 'widget-added widget-updated', function( root, element ) {
		jQuery( '.pis-widget-title' ).off( 'click' ).on( 'click', function() {
			pis_namespace.clickHandler( this );
		});
		// Refresh panels to the state before saving.
		var id = jQuery( element ).attr( 'id' );
		if ( pis_namespace.open_panels.hasOwnProperty( id ) ) {
			var o = pis_namespace.open_panels[id];
			for ( var panel in o ) {
				jQuery( element ).find( '[data-panel=' + panel + ']' ).toggleClass( 'open' ).next().stop().show();
			}
		}
	});
});
