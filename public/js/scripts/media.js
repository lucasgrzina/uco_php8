// jshint asi: true
// jshint devel: true
// jshint unused:false

function media() {

	var items

	$('.media_btn_JS').click(function () {

		var total = $(this).closest('.media_grid_JS').find('.media_item_JS').length
		var iteration = $(this).closest('.media_grid_JS').data('iteration')

		// Display items
		if ($(window).width() > 1080) {
			items = 3
		} else if ($(window).width() <= 1080) {
			items = 2
		}
		for (var i = items*iteration; i > 0; i--) {
			$($(this).closest('.media_grid_JS').find('.media_item_JS')[(items*iteration - i)]).fadeIn('slow')
		}

		// Scroll function
		var scroll = $('.media_item_JS').height() * 1 + 30
		$('html,body').animate({scrollTop: '+='+scroll+'px'}, 650)

		// Hide button when there are no more items
		if (items*iteration >= total) {
			$(this).closest('.media_grid_JS').find('.media_btn_JS').slideUp()
		}

		iteration++
		$(this).closest('.media_grid_JS').data('iteration',iteration)
		$(this).closest('.media_grid_JS').addClass('iteration-'+iteration)
	})

}