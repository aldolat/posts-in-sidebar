/*
 * Posts in Sidebar javascript for admin UI
 */
function pis_slidetoggle() {
	jQuery('.pis-widget-title').click(function() {
		jQuery(this).next().slideToggle('fast');
	});
	jQuery('.pis-title-center').click(function() {
		jQuery(this).next().slideToggle('fast');
	});
}

jQuery(document).on('widget-added', function() { pis_slidetoggle(); });
jQuery(document).ready(function() { pis_slidetoggle(); });
jQuery(document).on('widget-updated', function() { pis_slidetoggle(); });