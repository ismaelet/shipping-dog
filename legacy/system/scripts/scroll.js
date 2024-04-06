$(document).ready(function() {
	/*scroll down*/
	$('.scroll').on('click', function(e) {
	    var target = $(this.getAttribute('href'));

	    if (target.length) {
	        e.preventDefault();

	        $('html, body').stop().animate({
	            scrollTop: target.offset().top
	        }, 500);
	    }
	});
});