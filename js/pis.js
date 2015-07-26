/*
 * Posts in Sidebar javascript for admin UI
 */
jQuery(document).ready(function(){
	jQuery('.pis-widget-title').click(function() {
		jQuery(this).next().slideToggle('fast');
	});
	jQuery('.pis-title-center').click(function() {
		jQuery(this).next().slideToggle('fast');
	});
});
