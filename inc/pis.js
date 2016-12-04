/**
 * Posts in Sidebar javascript for admin UI
 *
 * This file contains the jQuery instructions to animate fields:
 * - the first animate the panels of the entire widget.
 * - the second animate (hiding and showing) the necessary field(s)
 *   for the archive link section.
 *
 * @since 2.0
 */

 /*
  * Animate widget sections
  */
jQuery(document).ready(function($){
	// Animate widget sections
	$('body').on('click','.pis-widget-title',function(event){
		$(this).next().slideToggle();
	});
});

/*
 * Animate fields for archive link.
 * TODO
 */
/*
jQuery(document).ready(function($){
	$('body').on('click','.pis-linkto-form',function(event){
		var select = $(this).val();
		if ( select == "author" || select == "category" || select == "tag" || select == "custom_post_type" ) {
			$('.pis-linkto-tax-name').slideUp();
			$('.pis-linkto-term-name').slideDown();
		} else if ( select == "custom_taxonomy" ) {
			$('.pis-linkto-tax-name').slideDown();
			$('.pis-linkto-term-name').slideDown();
		} else { // This is the case of post formats
			$('.pis-linkto-tax-name').slideUp();
			$('.pis-linkto-term-name').slideUp();
		}
	});
});
*/
