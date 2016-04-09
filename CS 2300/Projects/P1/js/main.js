// Sticky nav bar
var nav = $( ".main-nav" );
var nscr = ".nav-scrolled";
var head = 50;

$(window).scroll(function() {
	if ( $( this ).scrollTop() > head ) {
		nav.removeClass(".main-nav");
		nav.addClass(nscr);
	}
	else {
		nav.removeClass(nscr);
	}
});

//Simple button animation
$("button").click(function() {
  $(".image").animate({
    left: '250px',
    opacity: '0.5',
    height: '950px',
    width: '750px'
  });
  $("button").animate({
    width: '75%'
  });
  
});