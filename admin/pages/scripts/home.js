$(window).ready(function() {

	//slider
	resize();

	$(window).on('resize', function() { resize(); });

	function resize() {

		const width = $(window).width();
		const height = $(window).height();

		if (width > height) { $('#slider.full-height').css('height', height + 'px'); }

	}

	//smooth scrolling
	$('html').addClass('smooth-scrolling');

	//svg logo loading
	setTimeout(function() { $('#loading svg').addClass('active'); }, 400);
	
});

//svg logo loading
$(window).on('load', function() {

	const time = $('#loading input').data('time');

	setTimeout(function(){
		$('#loading').fadeOut(400);
	}, time);

});