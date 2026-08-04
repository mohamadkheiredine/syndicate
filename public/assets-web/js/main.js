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
