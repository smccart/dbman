var animateHeader = (function() {

	var docElem = document.documentElement,
		header = document.querySelector( '.navbar' ),
		didScroll = false,
		changeHeaderOn = 150;

	function init() {
		window.addEventListener( 'scroll', function( event ) {
			if( !didScroll ) {
				didScroll = true;
				setTimeout( scrollPage, 180 );
			}
		}, false );
	}

	function scrollPage() {
		var sy = scrollY();
		if ( sy >= changeHeaderOn ) {
			
			$('.navbar').addClass( "navbar-shrink" );
			$('.navbar-brand').animate({ opacity: 1 });
			//$('.jumbotron').animate({ opacity: .85 }, 200);
			//$('.navbar-brand').fadeIn();
			//$('.jumbotron .container').fadeOut(1000);
		}
		else {
			$('.navbar').removeClass( "navbar-shrink" );
			$('.navbar-brand').animate({ opacity: 0 }, 200);
			//$('.jumbotron').animate({ opacity: 1});
			//$('.navbar-brand').fadeOut();
			//$('.jumbotron .container').fadeIn(1000);
		}
		didScroll = false;
	}

	function scrollY() {
		return window.pageYOffset || docElem.scrollTop;
	}

	init();

})();


//trigger scroll based animations
$(window).scroll(function() {
   var scroll = $(document).scrollTop();
    var parallaxY = (($(document).scrollTop() * .5) / 2) * -1;
    $('.jumbotron').css('background-position', 'center '+parallaxY+'px');
});


// jQuery for page scrolling feature - requires jQuery Easing plugin
$(function() {
    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });
});




//hides all elements that are revealed during scrolling
function hideElements() {
	$('.inc1').css('opacity', '0');
	$('.inc2').css('opacity', '0');
	$('.inc3').css('opacity', '0');
	$('.inc4').css('opacity', '0');
	$('.inc5').css('opacity', '0');
}


//intro animation for skills section
var elementsRevealed = false;
function revealElements() {
	if(!elementsRevealed)
	{
		elementsRevealed = true;
		$('.inc1').animate({'opacity': 1}, {duration: 300, direction: 'down', complete: function() {
			$( ".inc2" ).animate({'opacity': 1}, {duration: 300, direction: 'down', complete: function() {
				$( ".inc3" ).animate({'opacity': 1}, {duration: 300, direction: 'down', complete: function() {
					$( ".inc4" ).animate({'opacity': 1}, {duration: 300, direction: 'down', complete: function() {
						$( ".inc5" ).animate({'opacity': 1}, {duration: 300, direction: 'down', complete: function() {
							//animation complete
						}});
					}});
				}});
			}});
		}});
	}
}

$( document ).ready(function() {
  //do stuff on initial page load
	hideElements();
	revealElements();
});




