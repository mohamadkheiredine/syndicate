/*
|--------------------------------------------------------------------------
| General functions
|--------------------------------------------------------------------------
|
*/

$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
})

// Disable Image Draging
window.ondragstart = function() { return false; }

// Disable Right Click On Images
$("img").bind('contextmenu', function(e) { return false; });

/*
|--------------------------------------------------------------------------
| Document ready function
|--------------------------------------------------------------------------
|
*/

$(document).ready(function(){

	/*
	|--------------------------------------------------------------------------
	| Initialize Libraries
	|--------------------------------------------------------------------------
	|
	*/

	// Custom Slick
	// $(".custom-slick-js").each(function(){
	// 	$(this).slick({
	// 		arrows: true,
	// 		dots: true,
	// 		infinite: false,
	// 		autoplay: true,
	// 		autoplaySpeed: 3000,
	// 		pauseOnHover: false,
	// 		responsive: [
	// 		{
	// 			breakpoint: 992,
	// 			settings: {
	// 				slidesToShow: 3,
	// 				slidesToScroll: 3
	// 			}
	// 		},
	// 		{
	// 			breakpoint: 768,
	// 			settings: {
	// 				slidesToShow: 2,
	// 				slidesToScroll: 2
	// 			}
	// 		},
	// 		{
	// 			breakpoint: 576,
	// 			settings: {
	// 				slidesToShow: 1,
	// 				slidesToScroll: 1
	// 			}
	// 		}
	// 		]
	// 	});

	// 	var stHeight = $(this).find('.slick-track').height();
	// 	$(this).find('.slick-slide').css('height', stHeight + 'px' );
	// });

	/*
	|--------------------------------------------------------------------------
	| End Initialize Libraries
	|--------------------------------------------------------------------------
	*/



});

/*
|--------------------------------------------------------------------------
| Syndicate site (ported from the old public site, custom.electoral.js)
|--------------------------------------------------------------------------
| Runs against whichever jQuery is loaded last on the page, matching the
| old site's own layering of a modern jQuery early and the legacy 1.11.1
| build + plugins right before this file.
|
*/
(function($) {
	"use strict";

	if (!$('#nav').length) {
		return;
	}

	// Navigation menu
	var $navList = $('#nav > ul');
	var $navBreakPoint = 768;

	// Plugin guards: superfish, owl-carousel, easy-responsive-tabs, mixitup,
	// magnific-popup and fitVids are only bundled on the pages that use
	// them. Without an $.fn check a missing plugin throws here and kills
	// everything below it - which is what stopped the mobile menu toggle
	// from ever binding on any page but the homepage.
	if ($.fn.superfish) {
		$($navList).superfish({ delay: 100, speed: 'fast', disableHI: true });
	}

	// Tag nav links that have a submenu so the theme's mobile CSS shows a
	// caret on them and the tap handler below knows which links to catch.
	$navList.find('li > a').each(function() {
		if ($(this).next('ul').length) {
			$(this).addClass('sf-with-ul');
		}
	});

	// Close every open submenu and drop the inline styles slideUp/slideDown
	// leave behind (else a submenu opened on mobile stays visible after a
	// resize back to desktop, where it should be hover-controlled).
	function resetSubmenus() {
		$navList.find('a.sub-open').removeClass('sub-open');
		$navList.find('ul ul').removeAttr('style');
	}

	// Mobile menu trigger
	$('#nav-toggle').click(function(el) {
		el.preventDefault();
		$(this).find('i.fa').toggleClass('fa-bars fa-times');
		resetSubmenus();
		$($navList).slideToggle();
	});

	// Mobile submenu tap-to-expand: the submenus are hover-only in CSS,
	// which touch can't trigger. While the burger is showing, the first tap
	// on a parent opens its submenu (closing any open sibling); a second
	// tap follows the parent's own link, or - for a label-only parent like
	// "Members Previous Years" - closes it again.
	$navList.on('click', 'a.sf-with-ul', function(e) {
		if (!$('#nav-toggle').is(':visible')) {
			return; // desktop - CSS hover handles it
		}
		var $a = $(this);
		var href = $a.attr('href');
		var hasRealHref = href && href !== '#';
		if ($a.hasClass('sub-open')) {
			if (hasRealHref) {
				return; // second tap on a real link - let it navigate
			}
			e.preventDefault();
			$a.removeClass('sub-open').next('ul').slideUp(150);
			return;
		}
		e.preventDefault();
		$a.closest('ul').children('li').find('> a.sub-open')
			.removeClass('sub-open').next('ul').slideUp(150);
		$a.addClass('sub-open').next('ul').slideDown(150);
	});

	if (typeof Modernizr !== 'undefined' && Modernizr.mq('(max-width: ' + $navBreakPoint + 'px)')) {
		$($navList).addClass('nav-mobile');
	} else {
		$($navList).removeClass('nav-mobile');
	}

	$(window).resize(function() {
		if (typeof Modernizr !== 'undefined' && Modernizr.mq('(max-width: ' + $navBreakPoint + 'px)')) {
			if (!$($navList).hasClass('nav-mobile')) {
				$($navList).addClass('nav-mobile').hide();
			}
		} else {
			$($navList).removeClass('nav-mobile');
			resetSubmenus();
			if ($($navList).is(':hidden')) {
				$($navList).show();
			}
		}
	});

	// Social updates slider + home slider (see home #social-slider, .home-slider)
	if ($.fn.owlCarousel) {
		$('.simple-slider').owlCarousel({
			nav: true,
			autoPlay: 7000,
			stopOnHover: true,
			slideSpeed: 200,
			paginationSpeed: 800,
			rewindSpeed: 800,
			singleItem: true
		});
	}

	// Tabs (terms page, previous-members page)
	if ($.fn.easyResponsiveTabs) {
		$('.tabs-hor').easyResponsiveTabs({ type: 'default', width: 'auto', fit: true, closed: false });
		$('.tabs-vert').easyResponsiveTabs({ type: 'vertical', width: 'auto', fit: true, closed: false });
		$('.accordion').easyResponsiveTabs({ type: 'accordion', width: 'auto', fit: true, closed: false });
	}

	// Gallery filtering / lightbox
	if ($.fn.mixitup) {
		$('.gallery-items').mixitup({
			targetSelector: '.gallery-item',
			filterSelector: '.gallery-filter',
			effects: ['fade'],
			easing: 'snap'
		});
	}
	if ($.fn.magnificPopup) {
		$('.gallery-item').each(function() {
			$(this).magnificPopup({
				delegate: 'a.zoom',
				type: 'image',
				gallery: { enabled: true }
			});
		});
	}

	// Responsive video embeds
	if ($.fn.fitVids) {
		$('body').fitVids();
	}

	// Scroll-in animations
	if (typeof WOW !== 'undefined') {
		var wow = new WOW({
			boxClass: 'wow',
			animateClass: 'animated',
			offset: 0,
			mobile: false,
			live: false
		});
		wow.init();
	}
})(jQuery);
