$(document).ready(function() {
	
// Show our site navigation bar
	$("#toggle-mobile-nav").click(function( event ) {
	    event.preventDefault();
	    $("body").toggleClass('navigationShown');
	});
		
	$(document).keyup(function(e) {
    	if (e.key === "Escape" && $("body").hasClass('navigationShown') ) { // escape key maps to keycode `27`
        	 $("body").toggleClass('navigationShown');
    	}
	});

});