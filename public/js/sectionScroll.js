function initScroll(){
	var moveAvailable = true;
	var wheelPosition = 0;
	var wheelvariation = 50;
	 
	$('section').on('wheel', (function(e) {

		
		e.preventDefault();
  		wheelPosition = e.originalEvent.deltaY;
        var active = $('section.active');

        console.log('on scroll 2 '+moveAvailable);
        console.log('on scroll 2 '+$(this).attr('class'));
        console.log('on scroll 2- '+$(this).next().attr('class'));

        if (wheelPosition > wheelvariation || wheelPosition < (wheelvariation*-1)){
  			if (moveAvailable){
  				moveAvailable = false;
  				next = $(this).next();

  				
                    $('body, html').animate({
                        scrollTop: next.offset().top
                    }, 300, 'swing');
                    
                    // move the indicator 'active' class
                    next.addClass('active')
                        .siblings().removeClass('active');
                    
                    

  				setTimeout(function(){ 
					moveAvailable = true;
					console.log('on scroll 3 '+moveAvailable);
				}, 1000);
  			}
  		}

  		/* 
		 $('body, html').stop().animate({
            scrollTop: $('section:eq(1)').offset().top - 0
        }, 'slow');

  		*/
      
	}));

	
}


function checkSectioninView(){
	let sectionsNum = $('section').length;

	for (var i = 0; i < sectionsNum; i++) {
		var result = isScrolledIntoView($('section:eq('+i+')'));

		if (result){
			console.log('sec '+ i);
		}
	}

}

function isScrolledIntoView(elem)
{
    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();

    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}

$(document).ready(function(){
//	initScroll();
});