/**
 * Posts in Sidebar javascript for admin UI
 * @since 2.0
 */
jQuery(document).ready(function($){
	// Animate widget sections
	$('body').on('click','.pis-widget-title',function(event){
		$(this).next().slideToggle();
	});

	// Animate fields for archive link
	/*
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
	*/

	// Animate options for Gravatar settings
	/*
	$('body').on('click','.pis-gravatar',function(event){
		if ( $(this).is(":checked")) {
			$('.pis-gravatar-options').slideDown();
		} else {
			$('.pis-gravatar-options').slideUp();
		}
	});
	*/
});