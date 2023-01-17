var edadRecordarme = false;
$(document).ready(function(){
	var myModal = new bootstrap.Modal(document.getElementById('edadModal'), {
		keyboard: false
	});

	if(typeof Cookies.get('ag_uco') == 'undefined' && Cookies.get('ag_uco') != 1) {
		myModal.show();
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
			Cookies.set('ag_uco' , '1', { expires: 365 });
		} else {
			Cookies.set('ag_uco' , '1', { expires: 1 });
		}
		console.log(document.getElementById('recordarme-mayor').checked);
		myModal.hide();
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

});
$( window ).scroll(function() {
	fadeChildren();
});
$( window ).resize(function() {
	fadeChildren();
});

$(window).load(function() {

});

