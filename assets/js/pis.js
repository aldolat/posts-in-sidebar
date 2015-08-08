/*
 * Posts in Sidebar javascript for admin UI
 */
jQuery(document).ready(function($){
	jQuery('body').on('click','.pis-widget-title',function(event){
		jQuery(this).next().slideToggle();
	});
});
