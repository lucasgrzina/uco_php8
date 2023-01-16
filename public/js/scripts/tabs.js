// jshint asi: true
// jshint devel: true
// jshint unused:false

function tabs() {
	
	$('.tabs_ttl_JS').click(function () {
		if ($(window).width() >= 720) {
			if (!$(this).closest('.tabs_item_JS').hasClass('active')) {
				$('.tabs_txt_JS').slideUp('fast')
				$('.tabs_item_JS').removeClass('active')
				$(this).closest('.tabs_item_JS').find('.tabs_txt_JS').slideDown('fast')
				$(this).closest('.tabs_item_JS').addClass('active')
			} else {
				$(this).closest('.tabs_item_JS').find('.tabs_txt_JS').slideUp('fast')
				$(this).closest('.tabs_item_JS').removeClass('active')			
			}
		}
	})

}