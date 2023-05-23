// jshint asi: true
// jshint devel: true
// jshint unused:false

function clickTransition() {
	// $('a[href*="http"]').click(function(e) {
	// 	if (e.metaKey !== true) {
	// 		if ( $(this).attr('target') !== '_blank' && !this.hasAttribute('download')) {
	// 			if ( !$(this).hasClass('slider__slide') && !$(this).hasClass('tag_JS') ) { 
	// 				$('#pre').fadeIn('fast')
	// 			}
	// 		}
	// 	}
	// })
}

$(window).on('ready', function() {
	if ($(window).scrollTop() === 0) {
		setTimeout(function () {
			$('#pre').fadeOut('slow')
		},900)
	} else {
		$('#pre').fadeOut('slow')
	}
})