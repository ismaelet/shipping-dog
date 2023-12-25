$(document).ready(function() {

	//open
	$('.lbox-open').click(function() {

		const tar = $($(this).data('tar'));

		if ($('.lbox.open').length) {

			$('.lbox').removeClass('open');

		} else {

			$('body').addClass('fixed');

		}

		tar.addClass('open');

	});

	//close
	$('.lbox').click(function(e) {

		if (e.target != this) return;

		close();

	});

	$('.lbox-close').click(function() {close();});
	$('.lbox .close-icon').click(function() {close();});

	function close() {

		$('.lbox').removeClass('open');
		$('body').removeClass('fixed');

	}

});