// jshint asi: true
// jshint devel: true
// jshint unused:false

function map() {

	$('.map_icon_JS').hover(function () {
		$(this).fadeOut()
	})
	
	if ($(window).width() <= 720) {
		$('.map_wrap_JS').width($('.map_img_JS').width())
	} else {
		$('.map_wrap_JS').width('100%')
	}
	
	if ($(window).width() <= 720) {
		$('[data-item').click(function () {
			$(this).toggleClass('hover')
		})
	}

}