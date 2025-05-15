jQuery(document).ready(function($){

	// GLOBAL VARIABLES
	var $window = $(window);
	var vw = $(window).width();
	$(window).resize(function(){
		vw = $(window).width();
	});


    // fill the height of the divs
	$window.scroll(function() {
		if( ($('#become_a_sponsor_impact').visible() && vw > 768) || ($('#become_a_sponsor_impact').visible(true) && vw < 768 ) ){
            $('#become_a_sponsor_impact .col .fill').each(function(){
                var $this = $(this);
                var fillHeight = $this.attr('value');
                $this.css('height', fillHeight + '%');
            });
		}
		else{
            $('#become_a_sponsor_impact .col .fill').each(function(){
                var $this = $(this);
                $this.css('height', 0 + '%');
            });
		}
	});


});