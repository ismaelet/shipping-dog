$(document).ready(function () {
	//open menu category
	$('.navigation .title-box').click(function () {
		const category = $(this).parent()

		if (category.hasClass('active')) {
			category.removeClass('active')
		} else {
			$('.navigation').addClass('open')
			$('.navigation .category').removeClass('active')
			category.addClass('active')
		}
	})

	$('#open-menu').click(function () {
		$('.navigation').toggleClass('open')
	})

	$('#body').click(function () {
		if ($(window).width() <= 1280) {
			$('.navigation').removeClass('open')
			$('.navigation .category').removeClass('active')
		}
	})

	$('.navigation').hover(
		function () {
			if ($(window).width() <= 1280 && $(window).width() >= 1000) {
				$('.navigation').addClass('open')
			}
		},
		function () {
			if ($(window).width() <= 1280 && $(window).width() >= 1000) {
				$('.navigation').removeClass('open')
			}
		}
	)
})
