/**
 * Posts in Sidebar javascript for admin UI
 * @since 2.0
 */
jQuery(document).ready(function($){
	jQuery('body').on('click','.pis-widget-title',function(event){
		jQuery(this).next().slideToggle();
	});
});
