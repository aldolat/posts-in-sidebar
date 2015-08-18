/**
 * Posts in Sidebar javascript for admin UI
 * @since 2.0
 */
jQuery(document).ready(function($){
	// Animate widget sections
	jQuery('body').on('click','.pis-widget-title',function(event){
		jQuery(this).next().slideToggle();
	});

	// Animate fields for archive link
	jQuery(".pis-linkto-form").change(function(){
		if ( jQuery(this).val() == "author" ) {
			jQuery('.pis-linkto-tax-name').slideUp();
			jQuery('.pis-linkto-term-name').slideDown();
		} else if ( jQuery(this).val() == "category" ) {
			jQuery('.pis-linkto-tax-name').slideUp();
			jQuery('.pis-linkto-term-name').slideDown();
		} else if ( jQuery(this).val() == "tag" ) {
			jQuery('.pis-linkto-tax-name').slideUp();
			jQuery('.pis-linkto-term-name').slideDown();
		} else if ( jQuery(this).val() == "custom_post_type" ) {
			jQuery('.pis-linkto-tax-name').slideUp();
			jQuery('.pis-linkto-term-name').slideDown();
		} else if ( jQuery(this).val() == "custom_taxonomy" ) {
			jQuery('.pis-linkto-tax-name').slideDown();
			jQuery('.pis-linkto-term-name').slideDown();
		} else { // This is the case of post formats
			jQuery('.pis-linkto-tax-name').slideUp();
			jQuery('.pis-linkto-term-name').slideUp();
		}
	}).change();
});

