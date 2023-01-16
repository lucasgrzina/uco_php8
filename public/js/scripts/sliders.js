// jshint asi: true
// jshint devel: true
// jshint unused:false

function slick_md(slider,settings){
	$(window).on('ready load resize', function() {
		if ($(window).width() > 1080) {
			if (slider.hasClass('slick-initialized')) {
				slider.slick('unslick')
			}
			return
		}
		if (!slider.hasClass('slick-initialized')) {
			return slider.on('init reInit afterChange', function(event, slick, currentSlide, nextSlide) {
				var i = (currentSlide ? currentSlide : 0) + 1
			}).slick(settings)
		}
	})
}

// Sliders on Mobile
var features_settings = {
	dots: true,
	fade: false,
	speed: 600,
	slide: '.features_slide_JS',
	arrows: false,
	infinite: true,
	autoplay: true,
	pauseOnHover: false,
	autoplaySpeed: 3000,
	slidesToShow: 1,
	slidesToScroll: 1,
}
var features = $('.features_JS')
slick_md(features,features_settings)

function sliders() {

	$('.home_lines_JS').slick({
		dots: true,
		fade: false,
		speed: 600,
		arrows: false,
		infinite: true,
		autoplay: true,
		pauseOnHover: false,
		autoplaySpeed: 3000,
		slidesToShow: 3,
		slidesToScroll: 1,
		responsive: [
			{
				breakpoint: 1080,
				settings: {
					slidesToShow: 1,
				}
			},
		]
	})

	$('.people_intro_JS').slick({
		dots: false,
		fade: true,
		speed: 450,
		arrows: false,
		infinite: true,
		autoplay: true,
		draggable: false,
		pauseOnHover: false,
		autoplaySpeed: 4000,
	})

	$('.people_gallery_JS').slick({
		dots: false,
		fade: true,
		speed: 450,
		arrows: false,
		infinite: true,
		autoplay: true,
		draggable: false,
		pauseOnHover: false,
		autoplaySpeed: 4500,
	})

	$('.people_teams_JS').slick({
		dots: false,
		fade: true,
		speed: 450,
		arrows: false,
		infinite: true,
		autoplay: true,
		draggable: false,
		pauseOnHover: false,
		autoplaySpeed: 4500,
	})

	$('.profile_gallery_JS').each(function () {
		$(this).slick({
			dots: false,
			fade: true,
			speed: 450,
			arrows: false,
			infinite: true,
			autoplay: true,
			draggable: false,
			pauseOnHover: false,
			autoplaySpeed: 4500,
		})
	})


}