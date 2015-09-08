jQuery(document).ready(function($){
	// Animate widget sections
	$('body').on('click','.pis-widget-title',function(event){
		$(this).next().slideToggle();
	});

	// Animate fields for archive link
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

	var pisGravatarChecked = sessionStorage.getItem( "pis-gravatar-checked" );
	if( pisGravatarChecked !== null ) {
		var pisGravatarCheckedVal = parseInt( pisGravatarChecked, 10 );
		if( $( ".pis-gravatar-options" ).length ) {
			if( pisGravatarCheckedVal == 1 ) {
				$( ".pis-gravatar-options" ).slideDown();
			} else {
				$( ".pis-gravatar-options" ).slideUp();
			}
		}
	}

	var gravEvent = ( $( ".pis-gravatar" ).is( ":checkbox" ) ) ? "change" : "click";

	// Animate options for Gravatar settings
	$('body').on( gravEvent,'.pis-gravatar',function(event){
		if ( $( this ).prop( "checked" ) ) {
			sessionStorage.setItem( "pis-gravatar-checked", 1 );
			$('.pis-gravatar-options').slideDown();
		} else {
			$('.pis-gravatar-options').slideUp();
			sessionStorage.setItem( "pis-gravatar-checked", 0 );
		}
	});
});
