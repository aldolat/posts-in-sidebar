/*
 * Posts in Sidebar javascript for admin UI
 */
jQuery(document).ready(function($){
	jQuery('body').on('click','.pis-widget-title',function(event){
		//jQuery(this).toggleClass('pis-container-open');
		jQuery(this).next().slideToggle();
	});
});

/*
// The old method
function pis_slidetoggle() {
	jQuery('.pis-widget-title, .pis-title-center').click(function() {
		jQuery(this).next().slideToggle('fast');
	});
}

jQuery(document).on('widget-added widget-updated', function() { pis_slidetoggle(); });
jQuery(document).ready(function() { pis_slidetoggle(); });
//jQuery(document).on('widget-updated', function() { pis_slidetoggle(); });
*/