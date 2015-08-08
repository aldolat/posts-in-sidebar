/*
 * Posts in Sidebar javascript for admin UI
 */

/*
function pis_slidetoggle() {
	jQuery('.pis-widget-title, .pis-title-center').click(function() {
		jQuery(this).next().slideToggle('fast');
	});
}

jQuery(document).on('widget-added widget-updated', function() { pis_slidetoggle(); });
jQuery(document).ready(function() { pis_slidetoggle(); });
//jQuery(document).on('widget-updated', function() { pis_slidetoggle(); });
*/

jQuery(document).ready(function($){
	jQuery('body').on('click', '.pis-clickable', function( event ){
		jQuery(this).toggleClass('down');
		jQuery(this).next().slideToggle();
	});
               
});