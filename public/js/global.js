var edadRecordarme = false;
var modalEdad = null;
var nombreCookieAG = _prefCookie + 'ag_uco';
$(document).ready(function(){
	modalEdad = new bootstrap.Modal(document.getElementById('edadModal'), {
		keyboard: false
	});

	if(typeof Cookies.get(nombreCookieAG) == 'undefined' && Cookies.get(nombreCookieAG) != 1 && !hideAgeGate) {
		setTimeout(function() {
            modalEdad.show();
        },1000);

	}

	$('.module-full-slider .slider').slick({
		arrows: false,
		centerMode: true,
		centerPadding: '0',
		autoplaySpeed: 3000,
		autoplay: true,
		slidesToShow: 1,
		fade: true,
		pauseOnHover: false,
		speed: 800,
		cssEase: 'linear',
		responsive: [
		{
			breakpoint: 992,
			settings: {
				autoplay: true,
				autoplaySpeed: 3000
			}
		}

		]
	});

	$('.section-almacenamiento .slider').slick({
		dots: true,
		arrows: true,
		centerMode: true,
		centerPadding: '0',
		autoplaySpeed: 3000,
		autoplay: false,
		slidesToShow: 1,
		fade: true,
		pauseOnHover: false,
		speed: 800,
		cssEase: 'linear',
		responsive: [
		{
			breakpoint: 992,
			settings: {
				autoplay: true,
				autoplaySpeed: 3000
			}
		}

		]
	});

	$('.section-almacenamiento .slider-gente').slick({
		dots: true,
		arrows: false,
		centerMode: true,
		centerPadding: '0',
		autoplaySpeed: 3000,
		autoplay: true,
		slidesToShow: 1,
		fade: true,
		pauseOnHover: false,
		speed: 800,
		cssEase: 'linear',
		responsive: [
		{
			breakpoint: 992,
			settings: {
				autoplay: true,
				autoplaySpeed: 3000
			}
		}

		]
	});

	$('.section-gallery .slider').slick({
		arrows: true,
		appendArrows: $('#content_arrows'),
		autoplaySpeed: 12000,
		autoplay: true,
		slidesToScroll: 1,
		centerPadding: '0',
		variableWidth: true,
		slidesToShow: 3,
		centerMode: false,
		pauseOnHover: false,
		speed: 800,
		responsive: [
		{
			breakpoint: 992,
			settings: {
				slidesToShow: 2,
				autoplay: true,
				variableWidth: true,
				autoplaySpeed: 3000
			}
		},
		{
			breakpoint: 600,
			settings: {
				slidesToShow: 1,
				autoplay: true,
				variableWidth: false,
				autoplaySpeed: 3000
			}
		}

		]
	});

	$('.slider-fullscreen-section .slider').slick({
		arrows: true,
		appendArrows: $('#content_arrows'),
		autoplaySpeed: 3000,
		autoplay: false,
		slidesToShow: 1,
		fade: true,
		pauseOnHover: false,
		speed: 800,
		cssEase: 'linear',
		responsive: [
		{
			breakpoint: 992,
			settings: {
				autoplay: true,
				autoplaySpeed: 3000
			}
		}

		]
	});




	fadeChildren();

	$('#checkmarkEdad').trigger('click');

	$('#btnSiEdad').click(function() {
		if (document.getElementById('recordarme-mayor').checked) {
			Cookies.set(nombreCookieAG , '1', { expires: 365 });
		} else {
			Cookies.set(nombreCookieAG , '1');
		}
		console.log(document.getElementById('recordarme-mayor').checked);
		modalEdad.hide();
	});

	$(".producto-item .dropdown-menu li a, .table .dropdown-menu li a").click(function(e){
		e.preventDefault();
		var selText = $(this).text();
		console.log( selText);
		$(this).parents('.dropdown').find('.dropdown-toggle span').html(selText);
	});

	$('#slider_noticias').slick({
		arrows: true,
		autoplaySpeed: 1500,

		autoplay: true,
		slidesToShow: 4,
		fade: false,
		pauseOnHover: true,
		speed: 600,
		cssEase: 'ease-out',
		responsive: [
		{
			breakpoint: 1180,
			settings: {
				slidesToShow: 3,
				autoplay: true,
				autoplaySpeed: 1500
			}
		},
		{
			breakpoint: 992,
			settings: {
				slidesToShow: 2,
				autoplay: true,
				autoplaySpeed: 1500
			}
		},
		{
			breakpoint: 768,
			settings: {
				slidesToShow: 1,
				autoplay: true,
				autoplaySpeed: 1500
			}
		}

		]
	});
	setTimeout(function() {
		sliderProduct();
	},500);
	//sliderProduct();

	initSliderCertificaciones();
});

function initSliderCertificaciones() {
    if ($(window).width() < 769) {
        if (!$('.slide-only-mobile').hasClass('slick-initialized')) {
            $('.slide-only-mobile').slick({
            	dots: true,
                autoplay: true,
                arrows: true,
                autoplaySpeed: 3000,
                slidesToShow: 1,
                fade: true,
                pauseOnHover: false,
                speed: 800,
                cssEase: 'linear'
            });
        }
    } else {
        if ($('.slide-only-mobile').hasClass('slick-initialized')) {
            $('.slide-only-mobile').slick('unslick');
        }
    }
}

function sliderProduct(){
	$('.slider-product').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: true,
		fade: false
	});
	$('.slider-product-nav').slick({
		slidesToShow: 5,
		slidesToScroll: 1,
		asNavFor: '.slider-product',
		focusOnSelect: true,
		centerMode: false
	});
}

$( window ).scroll(function() {
	fadeChildren();
});
$( window ).resize(function() {
	fadeChildren();
	initSliderCertificaciones();
});

const renderModal = (content, id) => {
	modalcontent =
	'<div class="modal modal-uco  modal-slim to-contact-modal " data-bs-backdrop="static" id="js_alert_modal" tabindex="-1" role="dialog" aria-labelledby="alert_modalLabel" aria-hidden="true">\
	<div class="modal-dialog modal-dialog-centered" role="document">\
	<div  class="modal-content">\
	<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">\
	<span aria-hidden="true">&times;</span>\
	</button>\
	<div class="forms-overflow" >\
	' +
	content +
	"\
	</div>\
	</div>\
	</div>\
	</div>";
	return modalcontent;
};

const showAlert = (title , message = null, btn1 = null, btn2 = null) => {
	modal = $(
		renderModal(
			'<div class="modal-header">\
			' +(title?('<h5 class="modal-title ">' +title + '</h5>'):'') +'\
			</div>\
			<div class="modal-body">\
			<div class="modal-icon-title"><i class="icon icon icon-complete-multicolor"></i></div>\
			'+ (message?('<p class="message ">' +message + '</p>'):'') + '</div>\
			<div class="modal-footer">\
			' + (btn1?('<button type="button" class="btn-primary faded" '+(btn1['fun']?('onclick="'+btn1['fun']+'"'):'data-bs-dismiss="modal"')+'" >'+btn1['text']+'</button>'):'') +'\
			' + (btn2?('<button type="button" class="btn-primary faded" '+(btn2['fun']?('onclick="'+btn2['fun']+'"'):'data-bs-dismiss="modal"')+'" >'+btn2['text']+'</button>'):'') +'\
			</div>'
			)
		);
	$("body").append(modal);

	modal.modal("show").on("hide.bs.modal", function () {
		$(this).remove();
	});

};
