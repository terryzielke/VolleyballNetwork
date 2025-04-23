jQuery(document).ready(function($){


	// DISABLE SCROLL
	function disableScroll() {
		var scrollDiv = document.createElement("div");
		scrollDiv.className = "b-scrollbar";
		document.body.appendChild(scrollDiv);
		var scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth;
		document.body.removeChild(scrollDiv);

		$('body').css({'overflow': 'hidden'});
		$('.b-page').css({'border-right-width': scrollbarWidth + 'px'});
		$('.b-page-head, .b-nav, .b-modal').css({'right': scrollbarWidth + 'px'});
	}


	// ENABLE SCROLL
	function enableScroll() {
		$('body').removeAttr('style');
		$('.b-page').removeAttr('style');
		$('.b-page-head, .b-nav, .b-modal').removeAttr('style');
	}


    // UPDATE CURRENT LEVEL
    // This function will update the current level of the menu
    function updateCurrentLevel(){
        var $deepestOnItem = $('li.menu-item-has-children.on').filter(function () {
            return $(this).find('li.menu-item-has-children.on').length === 0;
        });
        var currentLevel = $deepestOnItem.find('a').first().html();
        if(currentLevel == undefined){
            $('#nav-current-level').html('Menu');
        }
        else{
            $('#nav-current-level').html(currentLevel);
        }
    }


	// OPEN MOBLE NAV
	// This function will open the mobile navigation
	function openMobileNav(){
		$('body').addClass('menu-open');
		$('.menu-item-has-children').removeClass('on');
		disableScroll();
	}


	// CLOSE MOBILE NAV
	// This function will close the mobile navigation
	function closeMobileNav(){
		$('body').removeClass('menu-open');
		$('.menu-item-has-children').removeClass('on');
		enableScroll();
	}


	// GLOBAL VARIABLES
	var $window = $(window);
	var vw = $(window).width();
	$(window).resize(function(){
		vw = $(window).width();
	});


	// SCROLLING
	$window.scroll(function() {
		var distance = $('#page').offset().top;
		if ( $window.scrollTop() > distance ) {
			$('body').addClass('scrolling');
		}else{
			$('body').removeClass('scrolling');
		}
	});
	var lastScrollTop = 0, delta = 5;

	$(window).scroll(function(event){
		var st = $(this).scrollTop();
		
		if(Math.abs(lastScrollTop - st) <= delta)
		return;
		
		if (st > lastScrollTop){
			// downscroll code
			$('body').removeClass('scrolling_up');
		} else {
			// upscroll code
			$('body').addClass('scrolling_up');
		}
		lastScrollTop = st;
	});


	// SMOOTH SCROLL ON ACHORS
	$('a[href*="#"]').on('click', function(event) {     
		if (
			location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') &&
			location.hostname == this.hostname &&
			$(this.hash).length
		) {
			event.preventDefault();
			$('html, body').animate({ scrollTop: $(this.hash).offset().top }, 500, function() {
				// end scroll to function
			});
		}
	});


    // ADD B ELEMENT TO LI
    $('#primary-menu > .menu-item-has-children').each(function(){
        var $this = $(this);
        $this.prepend('<b></b>');
    });


    // MEGA NAV TOGGLE
    $(document).on('click', '#primary-menu .menu-item-has-children b', function(e) {
		//e.preventDefault();
        var $this = $(this).closest('.menu-item-has-children');
		if ($this.hasClass('on')) {
			setTimeout(function(){
				$('.menu-item-has-children').removeClass('on');
			}, 200);
		} else {
			$('.menu-item-has-children').removeClass('on');
			$this.addClass('on');
		}
    });


	// MOBILE NAV TOGGLE
    $(document).on('click', '#primary-menu .menu-item-has-children', function(e) {
		//e.preventDefault();
		var $this = $(this);
		var currentLevel = $this.find('a').first().html();
		if($this.hasClass('on')){
			// do nothing
		}
		else{
			$this.addClass('on');
			$('#nav-current-level').html(currentLevel);
		}
    });


	// TOGGLE NAV
	$(document).on('click', '#menu-button', function() {
		if($('body').hasClass('menu-open')){
			closeMobileNav();
		}
		else{
			openMobileNav();
		}
        updateCurrentLevel();
	});


    // MENU BACK
    $(document).on('click', '#nav-back', function() {
        var $deepestOnItem = $('li.menu-item-has-children.on').filter(function () {
            return $(this).find('li.menu-item-has-children.on').length === 0;
        });
		if($deepestOnItem.length == 0){
			closeMobileNav();
		}
		else{
			$deepestOnItem.removeClass('on');
			updateCurrentLevel();
		}
    });


    // CLICK OFF TO CLOSE
    $(document).on('click', function(e) {
        if (vw > 768) {
            if (!$(e.target).closest('#primary-menu').length)  {
            $('.menu-item-has-children.on').removeClass('on');
            }
        }
      });


	// TOGGLE FILTERS ON TAXONOMY PAGES
	$(document).on('click', '#more-filters', function() {
		if($('body').hasClass('filters-open')){
			$('body').removeClass('filters-open');
		}
		else{
			$('body').addClass('filters-open');
		}
	});


});