jQuery(document).ready(function(){
	jQuery('.pis-widget-title').click(function() {
		jQuery(this).siblings('.pis-container').toggleClass('pis-container-open');
	});
	jQuery('.pis-title-center').click(function() {
		jQuery(this).siblings('.pis-container').toggleClass('pis-container-open');
	});
});
