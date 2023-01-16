// jshint asi: true
// jshint devel: true
// jshint unused:false
Number.prototype.format = function(n) {
    var r = new RegExp('\\d(?=(\\d{3})+' + (n > 0 ? '\\.' : '$') + ')', 'g');
    return this.toFixed(Math.max(0, Math.floor(n))).replace(r, '$&,');
};

function counters(parentDiv){
	parentDiv.find('.count').each(function () {	
	    $(this).prop('counter', 0).animate({
	        counter: $(this).text()
	    }, {
	        duration: 7000,
	        easing: 'easeOutExpo',
	        step: function (step) {
	            $(this).text('' + step.format());
	        }
	    });
	});
}

function isScrolledIntoView(elem) {
	var docViewTop = $(window).scrollTop()
	var docViewBottom = docViewTop + $(window).height()
	if ($(elem).offset()) {
		var elemTop = $(elem).offset().top
		var elemBottom = elemTop + 60
		return (elemBottom <= docViewBottom)
	} else {
		return
	}
}

function doFade(i,div) {
	setTimeout(function() {
		 $(div).addClass('faded')
	}, i*150)
}

function fadeChildren() {

	$('.fade_JS:not(.noFade_JS)').each(function() {
		var children = $(this).children()
		if (isScrolledIntoView(this) === true) {
			$(this).addClass('noFade_JS');
			for (var i = 0; i < children.length; i++) {
				doFade(i+1,children[i])
			}

			if ($(this).find('.count').length){
		   	 counters($(this));
		   	}
		}
	})
}
