$(document).ready(function() {

	//open menu category
	$('#menu .title-box').click(function() {

		const category = $(this).parent()

		if (category.hasClass('active')) {

			category.removeClass('active')

		} else {
		
			$('#menu').addClass('open')
			$('#menu .category').removeClass('active')
			category.addClass('active')
		
		}

	})

	$('#open-menu').click(function() {

		$('#menu').toggleClass('open')

	})


	$('#body').click(function() {

		if ($(window).width() <= 1280) {
			$('#menu').removeClass('open')
			$('#menu .category').removeClass('active')
		}

	})

	$('#menu').hover(function() {

		if ($(window).width() <= 1280 && $(window).width() >= 1000) {

			$('#menu').addClass('open')

		}

	}, function() {

		if ($(window).width() <= 1280 && $(window).width() >= 1000) {

			$('#menu').removeClass('open')

		}

	})

})