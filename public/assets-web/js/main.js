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

	$($navList).superfish({ delay: 100, speed: 'fast', disableHI: true });

	// Mobile menu trigger
	$('#nav-toggle').click(function(el) {
		el.preventDefault();
		$(this).find('i.fa').toggleClass('fa-bars fa-times');
		$($navList).slideToggle();
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
			if ($($navList).is(':hidden')) {
				$($navList).show();
			}
		}
	});

	// Social updates slider + home slider (see home #social-slider, .home-slider)
	$('.simple-slider').owlCarousel({
		nav: true,
		autoPlay: 7000,
		stopOnHover: true,
		slideSpeed: 200,
		paginationSpeed: 800,
		rewindSpeed: 800,
		singleItem: true
	});

	// Tabs (previous-members page)
	$('.tabs-hor').easyResponsiveTabs({ type: 'default', width: 'auto', fit: true, closed: false });
	$('.tabs-vert').easyResponsiveTabs({ type: 'vertical', width: 'auto', fit: true, closed: false });
	$('.accordion').easyResponsiveTabs({ type: 'accordion', width: 'auto', fit: true, closed: false });

	// Gallery filtering / lightbox
	$('.gallery-items').mixitup({
		targetSelector: '.gallery-item',
		filterSelector: '.gallery-filter',
		effects: ['fade'],
		easing: 'snap'
	});
	$('.gallery-item').each(function() {
		$(this).magnificPopup({
			delegate: 'a.zoom',
			type: 'image',
			gallery: { enabled: true }
		});
	});

	// Responsive video embeds
	$('body').fitVids();

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
