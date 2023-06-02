// jshint asi: true
// jshint devel: true
// jshint unused:false

function header() {

	var headerHeight = $('.header_JS').outerHeight()
	var scrollTop    = $(window).scrollTop()
	var headerOffset = $('.header_JS').offset().top
	var distance     = headerOffset - scrollTop

	var distanceStiky = -50;

	if ($('[data-section="home"]').length){
		distanceStiky = ($(window).innerHeight() / 2) * -1;
	}
	
	if (distance < distanceStiky) {
		$('.header_JS').addClass('sticky')
	} else {
		$('.header_JS').removeClass('sticky')
	}


	//console.log('scrollTop', scrollTop);
	//console.log('headerOffset', headerOffset);
	//console.log('distance', distance);
	//console.log('distanceStiky', distanceStiky);

	$('[data-header="white"]').each(function () {
		var sectionHeight = $(this).outerHeight()
		var sectionOffset = $(this).offset().top
		var sectionDistance = sectionOffset - scrollTop - headerHeight
		var sectionVisible = sectionHeight + sectionDistance
		if (sectionDistance <= 0 && sectionVisible >= 0) {
			$('.header_JS').addClass('to-black')
		} else {
			$('.header_JS').removeClass('to-black')
		}
	})

	$('[data-header="black"]').each(function () {
		var sectionHeight = $(this).outerHeight()
		var sectionOffset = $(this).offset().top
		var sectionDistance = sectionOffset - scrollTop - headerHeight
		var sectionVisible = sectionHeight + sectionDistance
		if (sectionDistance <= 0 && sectionVisible >= 0) {
			$('.header_JS').addClass('to-white')
		} else {
			$('.header_JS').removeClass('to-white')
		}
	})

}

$(document).ready(function(){
	header();
});

$(window).scroll(function () {
	header();	
})