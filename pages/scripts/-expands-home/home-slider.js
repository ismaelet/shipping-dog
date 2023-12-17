$(window).on('load', function() {

	const count = $('#slider').data('count');

	var i = 0

	loop();
	
	function loop() {

		i++

		if (i > count) {
			i = 1
		}

		$('#slider .slide').removeClass('active')
		$('#slider .slide[data-id="' + i + '"]').addClass('active')

		$('#slider .icons img').removeClass('active')
		$('#slider .icons img[data-id="' + i + '"]').addClass('active')

		$('#slider .name').removeClass('active')
		$('#slider .name[data-id="' + i + '"]').addClass('active')

		setTimeout(loop, 6000)

	};

});