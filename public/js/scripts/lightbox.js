// jshint asi: true
// jshint devel: true
// jshint unused:false

function lightbox() {

	$('.video_play_JS').click(function(e) {
		var iframe = $(this).closest('.video_item_JS').find('.video_iframe_JS').val()
		$('.lightbox_JS').fadeIn('fast')
		$('.lightbox_holder_JS').append(iframe)
	})
	$('.lightbox_close_JS').click(function () {
		$('.lightbox_JS').fadeOut()
		$('.lightbox_holder_JS').find('iframe').remove()
	})
	$('.lightbox_holder_JS').click(function(e) {
		 if (e.target === this) {
		 	$('.lightbox_JS').fadeOut('fast')
			$('.lightbox_holder_JS').find('iframe').remove()
		 }
	})

}